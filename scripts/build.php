<?php

declare(strict_types=1);

chdir(dirname(__DIR__));
putenv('APP_SECRET=build-only-secret');

/**
 * Runs a command without hiding its output or exit status
 */
function runBuildCommand(array $command): void
{
    $process = proc_open($command, [STDIN, STDOUT, STDERR], $pipes);
    if (!is_resource($process)) {
        throw new RuntimeException('Unable to start ' . $command[0], 1);
    }

    $exit = proc_close($process);
    if ($exit !== 0) {
        throw new RuntimeException('Command failed: ' . implode(' ', $command), $exit > 0 ? $exit : 1);
    }
}

/**
 * Names a phase and stops immediately on failure
 */
function runBuildPhase(string $name, Closure $phase): void
{
    printf("\n[build] START %s\n", $name);
    try {
        $phase();
    } catch (Throwable $error) {
        fprintf(STDERR, "[build] FAIL %s: %s\n", $name, $error->getMessage());
        $code = $error->getCode();
        exit(is_int($code) && $code > 0 && $code < 256 ? $code : 1);
    }
    printf("[build] PASS %s\n", $name);
}

runBuildPhase('Prepared runtime and locked dependencies', static function (): void {
    runBuildCommand([PHP_BINARY, 'scripts/check-preparation.php']);
});
runBuildPhase('Composer validation', static function (): void {
    runBuildCommand(['composer', 'validate', '--strict', '--no-interaction']);
});
runBuildPhase('Composer platform requirements', static function (): void {
    runBuildCommand(['composer', 'check-platform-reqs', '--no-interaction']);
});
runBuildPhase('PHP syntax', static function (): void {
    $files = [];
    foreach (['config', 'public', 'scripts', 'src'] as $directory) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }
    }
    sort($files, SORT_STRING);
    foreach ($files as $file) {
        runBuildCommand([PHP_BINARY, '-l', $file]);
    }
});
runBuildPhase('PHPCS', static function (): void {
    runBuildCommand([PHP_BINARY, 'vendor/bin/phpcs']);
});
runBuildPhase('PHPStan', static function (): void {
    runBuildCommand([PHP_BINARY, 'vendor/bin/phpstan', 'analyse', '--no-progress']);
});
runBuildPhase('Deptrac', static function (): void {
    runBuildCommand([PHP_BINARY, 'vendor/bin/deptrac', '--fail-on-uncovered', '--report-uncovered', '--report-skipped']);
});
runBuildPhase('Deptrac unassigned', static function (): void {
    runBuildCommand([PHP_BINARY, 'vendor/bin/deptrac', 'debug:unassigned', '--no-cache']);
});
runBuildPhase('Rector', static function (): void {
    runBuildCommand([PHP_BINARY, 'vendor/bin/rector', 'process', 'src', 'tests', '--dry-run', '--no-progress-bar']);
});
runBuildPhase('Unit', static function (): void {
    $report = 'var/reports/coverage/unit/clover.xml';
    if (file_exists($report) && !unlink($report)) {
        throw new RuntimeException('Unable to remove stale Unit coverage: ' . $report);
    }
    runBuildCommand([
        PHP_BINARY, 'vendor/bin/phpunit', '--testsuite', 'Unit',
        '--fail-on-skipped', '--fail-on-notice', '--fail-on-warning', '--coverage-clover', $report,
    ]);
});
runBuildPhase('Unit coverage', static function (): void {
    runBuildCommand([PHP_BINARY, 'scripts/check-coverage.php']);
});
foreach (['Integration', 'Functional'] as $suite) {
    runBuildPhase($suite, static function () use ($suite): void {
        runBuildCommand([
            PHP_BINARY, 'vendor/bin/phpunit', '--testsuite', $suite,
            '--fail-on-skipped', '--fail-on-notice', '--fail-on-warning', '--no-coverage',
        ]);
    });
}
printf("\n[build] PASS all phases; Unit artifact: var/reports/coverage/unit/clover.xml\n");
