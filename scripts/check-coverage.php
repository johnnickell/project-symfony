<?php

declare(strict_types=1);

$fail = static function (string $message): never {
    fwrite(STDERR, $message.PHP_EOL);
    exit(1);
};

$coverageIgnoreDirectiveFile = static function (string $sourceDirectory): ?string {
    if (!is_dir($sourceDirectory)) {
        throw new RuntimeException(sprintf('Unable to scan production PHP: %s', $sourceDirectory));
    }

    $files = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sourceDirectory));
    foreach ($iterator as $file) {
        if (!$file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }

        $files[] = $file->getPathname();
    }
    sort($files, SORT_STRING);

    foreach ($files as $file) {
        $contents = file_get_contents($file);
        if (false === $contents) {
            throw new RuntimeException(sprintf('Unable to scan production PHP: %s', $sourceDirectory));
        }
        if (str_contains($contents, '@codeCoverageIgnore')) {
            return $file;
        }
    }

    return null;
};

$coverageMetrics = static function (string $report): array {
    if (!is_file($report)) {
        throw new RuntimeException(sprintf('Clover report not found: %s', $report));
    }

    $previous = libxml_use_internal_errors(true);
    $xml = simplexml_load_file($report, SimpleXMLElement::class, LIBXML_NONET);
    libxml_clear_errors();
    libxml_use_internal_errors($previous);

    if (false === $xml) {
        throw new RuntimeException(sprintf('Clover report is malformed: %s', $report));
    }

    $metrics = $xml->xpath('/coverage/project/metrics');
    if (!is_array($metrics) || count($metrics) !== 1) {
        throw new RuntimeException(sprintf('Clover project statement metrics are missing: %s', $report));
    }

    $attributes = $metrics[0]->attributes();
    if (!isset($attributes['statements'], $attributes['coveredstatements'])) {
        throw new RuntimeException(sprintf('Clover project statement metrics are missing: %s', $report));
    }

    $statements = (string) $attributes['statements'];
    $coveredStatements = (string) $attributes['coveredstatements'];
    if (!ctype_digit($statements) || !ctype_digit($coveredStatements)) {
        throw new RuntimeException(
            sprintf('Clover project statement metrics must be non-negative integers: %s', $report),
        );
    }

    $statements = ltrim($statements, '0') ?: '0';
    $coveredStatements = ltrim($coveredStatements, '0') ?: '0';

    return ['covered' => $coveredStatements, 'statements' => $statements];
};

try {
    $directiveFile = $coverageIgnoreDirectiveFile('src');
    if (null !== $directiveFile) {
        throw new RuntimeException(sprintf('Coverage-ignore directive found in production PHP: %s', $directiveFile));
    }

    $report = 'var/reports/coverage/clover.xml';
    $metrics = $coverageMetrics($report);
    $statements = $metrics['statements'];
    $coveredStatements = $metrics['covered'];
    $coveredExceedsStatements = strlen($coveredStatements) > strlen($statements)
        || (strlen($coveredStatements) === strlen($statements) && strcmp($coveredStatements, $statements) > 0);

    if ($coveredExceedsStatements) {
        throw new RuntimeException(
            sprintf(
                'Clover project statement metrics are contradictory: %s covered exceeds %s statements',
                $coveredStatements,
                $statements,
            ),
        );
    }

    if ($coveredStatements !== $statements) {
        throw new RuntimeException(
            sprintf(
                'Statement coverage is incomplete: %s/%s statements covered',
                $coveredStatements,
                $statements,
            ),
        );
    }
} catch (RuntimeException $exception) {
    $fail($exception->getMessage());
}

printf("Statement coverage is exact: %s/%s statements covered\n", $coveredStatements, $statements);
