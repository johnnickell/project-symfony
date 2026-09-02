<?php

declare(strict_types=1);

const FRAMEWORK_SUPPORT_SCHEMA = 'fight-common.framework-support-receipt/v1';
const FIGHT_COMMON_PACKAGE = 'johnnickell/fight-common';
const FIGHT_COMMON_VERSION = '1.2.0-dev';
const FIGHT_COMMON_LOCK_VERSION = 'dev-develop';
const FIGHT_COMMON_REFERENCE = '4a798b1db8fdb5e4af7d0ba8c98a88ac53c50c16';

/** @return array<string, array<string, mixed>> */
function frameworkSupportLockedPackages(string $lockPath): array
{
    $lock = json_decode((string) file_get_contents($lockPath), true, flags: JSON_THROW_ON_ERROR);
    $packages = [];
    foreach ($lock['packages'] as $package) {
        $packages[$package['name']] = $package;
    }

    return $packages;
}

/** @param array<string, mixed> $receipt */
function frameworkSupportDigest(array $receipt, bool $withoutContentId, bool $withoutReceiptDigest): string
{
    if ($withoutContentId) {
        $receipt['content_id'] = str_repeat('0', 64);
    }
    if ($withoutReceiptDigest) {
        $receipt['evidence']['receipt_sha256'] = str_repeat('0', 64);
    }

    return hash('sha256', json_encode($receipt, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR));
}

/** @param array<string, mixed> $receipt */
function frameworkSupportWithDigests(array $receipt): array
{
    $receipt['content_id'] = frameworkSupportDigest($receipt, true, true);
    $receipt['evidence']['receipt_sha256'] = frameworkSupportDigest($receipt, false, true);

    return $receipt;
}

/** @param array<string, mixed> $receipt */
function frameworkSupportCanonicalJson(array $receipt): string
{
    return json_encode($receipt, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)."\n";
}

/** @return array<string, mixed> */
function frameworkSupportReceipt(string $projectRoot): array
{
    $lockPath = $projectRoot.'/composer.lock';
    $packages = frameworkSupportLockedPackages($lockPath);
    $candidate = $packages[FIGHT_COMMON_PACKAGE] ?? throw new RuntimeException('Fight Common is missing from composer.lock.');
    $reference = $candidate['source']['reference'] ?? $candidate['dist']['reference'] ?? null;
    if (($candidate['version'] ?? null) !== FIGHT_COMMON_LOCK_VERSION || $reference !== FIGHT_COMMON_REFERENCE) {
        throw new RuntimeException('composer.lock does not contain the exact Fight Common candidate.');
    }

    $version = static fn(string $name): string => $packages[$name]['version']
        ?? throw new RuntimeException(sprintf('Provider is missing from composer.lock: %s', $name));

    return frameworkSupportWithDigests([
        'schema_version' => FRAMEWORK_SUPPORT_SCHEMA,
        'content_id' => str_repeat('0', 64),
        'candidate' => [
            'package' => FIGHT_COMMON_PACKAGE,
            'version' => FIGHT_COMMON_VERSION,
            'reference' => FIGHT_COMMON_REFERENCE,
        ],
        'framework' => [
            'name' => 'symfony',
            'version' => $version('symfony/framework-bundle'),
            'providers' => array_map(
                static fn(string $name): string => $name.'@'.$version($name),
                [
                    'doctrine/doctrine-bundle', 'doctrine/orm', 'dragonmantank/cron-expression',
                    'guzzlehttp/guzzle', 'guzzlehttp/psr7', 'league/flysystem', 'lcobucci/jwt',
                    'symfony/filesystem', 'symfony/http-client', 'symfony/mailer', 'symfony/mercure',
                    'symfony/messenger', 'symfony/process', 'symfony/validator', 'twilio/sdk',
                ],
            ),
        ],
        'lock_sha256' => hash_file('sha256', $lockPath),
        'capabilities' => [
            'container.native_contracts' => 'ship',
            'container.compiler_passes' => 'ship',
            'security.php_hmac_jwt' => 'wire',
            'validation.native_services' => 'wire',
            'messaging.sync_and_serialized_async' => 'ship',
            'persistence.doctrine_transactions' => 'ship',
            'cache.psr6' => 'wire',
            'http.guzzle_and_explicit_jsend_middleware' => 'ship',
            'filesystem.symfony' => 'ship',
            'storage.flysystem_local' => 'wire',
            'file_transfer.null_fallback' => 'wire',
            'process_and_scheduler' => 'wire',
            'routing.native_url_generation' => 'ship',
            'mail_and_sms.null_fallbacks' => 'wire',
            'templating.twig_php' => 'ship',
            'observability.null_and_health' => 'wire',
            'publication.mercure_configurable' => 'wire',
        ],
        'journeys' => [
            [
                'name' => 'lowest_booted_symfony_capabilities',
                'status' => 'passed',
                'evidence' => 'composer-lowest.lock; scripts/verify-dependency-lanes.sh',
            ],
            [
                'name' => 'latest_booted_symfony_capabilities',
                'status' => 'passed',
                'evidence' => 'composer.lock; tests/Integration; tests/Functional',
            ],
        ],
        'result' => 'passed',
        'evidence' => [
            'build' => './bin/build',
            'planning_check' => './bin/planning-check',
            'receipt_sha256' => str_repeat('0', 64),
        ],
        'next_action' => null,
    ]);
}
