<?php

declare(strict_types=1);

/**
 * Reports actual prepared CLI/FPM capabilities without installing dependencies
 */
function failPreparation(string $message): never
{
    fwrite(STDERR, $message . " Run ./bin/prepare; inspect its first failure.\n");
    exit(1);
}

if (PHP_VERSION !== '8.5.12') {
    failPreparation('Expected patched CLI PHP 8.5.12; got ' . PHP_VERSION . '.');
}

if (ini_get('session.upload_progress.enabled') !== '0') {
    failPreparation('CLI session.upload_progress.enabled must be Off.');
}

foreach (['ctype', 'dom', 'fileinfo', 'intl', 'mbstring', 'session', 'simplexml', 'tokenizer', 'xml', 'xmlwriter', 'zip', 'xdebug'] as $extension) {
    if (!extension_loaded($extension)) {
        failPreparation('Missing PHP extension: ' . $extension . '.');
    }
}

if (!in_array('coverage', xdebug_info('mode'), true)) {
    failPreparation('Xdebug coverage mode is disabled.');
}

if (!is_file(dirname(__DIR__) . '/vendor/autoload.php')) {
    failPreparation('Locked dependencies are missing.');
}

try {
    $root = dirname(__DIR__);
    $lock = json_decode(file_get_contents($root . '/composer.lock'), true, 512, JSON_THROW_ON_ERROR);
    $installed = json_decode(file_get_contents($root . '/vendor/composer/installed.json'), true, 512, JSON_THROW_ON_ERROR);
    if (($installed['dev'] ?? false) !== true) {
        failPreparation('Locked development dependencies are required.');
    }
    $identities = static function (array $packages): array {
        $identities = [];
        foreach ($packages as $package) {
            $identities[$package['name']] = [
                $package['version'], $package['source'] ?? null, $package['dist'] ?? null,
            ];
        }
        ksort($identities);

        return $identities;
    };
    if ($identities(array_merge($lock['packages'], $lock['packages-dev'])) !== $identities($installed['packages'])) {
        failPreparation('Installed package versions/provenance do not match composer.lock.');
    }
} catch (Throwable $error) {
    failPreparation('Unable to inspect locked dependencies: ' . $error->getMessage());
}

$fpm = [];
exec('php-fpm -i 2>&1', $fpm, $exit);
$fpmInfo = implode("\n", $fpm);

if ($exit !== 0 || !preg_match('/^PHP Version => 8\.5\.12$/m', $fpmInfo)) {
    failPreparation('Expected patched FPM PHP 8.5.12.');
}

if (!preg_match('/^session\.upload_progress\.enabled => Off => Off$/m', $fpmInfo)) {
    failPreparation('FPM session.upload_progress.enabled must be Off before request parsing.');
}

printf("CLI PHP %s; FPM PHP 8.5.12; session upload progress Off in both\n", PHP_VERSION);
printf("Xdebug %s; active coverage mode; required extensions loaded\n", phpversion('xdebug'));
printf("Dependencies: %s/vendor; Composer platform requirements checked next\n", dirname(__DIR__));
