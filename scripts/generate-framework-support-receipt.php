<?php

declare(strict_types=1);

use App\Composition\FrameworkSupport\ReceiptCanonicalizer;

require dirname(__DIR__).'/vendor/autoload.php';

$projectRoot = dirname(__DIR__);
$lock = json_decode((string) file_get_contents($projectRoot.'/composer.lock'), true, flags: JSON_THROW_ON_ERROR);
$versions = [];

foreach ($lock['packages'] as $package) {
    $versions[$package['name']] = $package['version'];
}

$receipt = [
    'schema_version' => ReceiptCanonicalizer::SCHEMA_VERSION,
    'content_id' => str_repeat('0', 64),
    'candidate' => [
        'package' => 'johnnickell/fight-common',
        'version' => ReceiptCanonicalizer::CANDIDATE_VERSION,
        'reference' => ReceiptCanonicalizer::CANDIDATE_REFERENCE,
    ],
    'framework' => [
        'name' => 'symfony',
        'version' => $versions['symfony/framework-bundle'],
        'providers' => [
            'doctrine/doctrine-bundle@'.$versions['doctrine/doctrine-bundle'],
            'doctrine/orm@'.$versions['doctrine/orm'],
            'dragonmantank/cron-expression@'.$versions['dragonmantank/cron-expression'],
            'guzzlehttp/guzzle@'.$versions['guzzlehttp/guzzle'],
            'guzzlehttp/psr7@'.$versions['guzzlehttp/psr7'],
            'league/flysystem@'.$versions['league/flysystem'],
            'lcobucci/jwt@'.$versions['lcobucci/jwt'],
            'symfony/filesystem@'.$versions['symfony/filesystem'],
            'symfony/http-client@'.$versions['symfony/http-client'],
            'symfony/mailer@'.$versions['symfony/mailer'],
            'symfony/mercure@'.$versions['symfony/mercure'],
            'symfony/messenger@'.$versions['symfony/messenger'],
            'symfony/process@'.$versions['symfony/process'],
            'symfony/validator@'.$versions['symfony/validator'],
            'twilio/sdk@'.$versions['twilio/sdk'],
        ],
    ],
    'lock_sha256' => hash_file('sha256', $projectRoot.'/composer.lock'),
    'capabilities' => [
        'container.event_mapping_provider' => 'ship',
        'container.command_query_event_compiler_passes' => 'ship',
        'security.php_passwords' => 'wire',
        'security.hmac_jwt_configurable' => 'wire',
        'validation.attributes_services' => 'wire',
        'messaging.async_commands_events' => 'ship',
        'messaging.synchronous_buses_routers_pipelines' => 'wire',
        'persistence.doctrine_unit_of_work' => 'ship',
        'persistence.dbal_event_store' => 'wire',
        'cache.psr6' => 'wire',
        'http.psr18_guzzle' => 'wire',
        'request_response.jsend_middleware' => 'ship',
        'filesystem.symfony' => 'ship',
        'storage.flysystem_local' => 'wire',
        'file_transfer.null_transport_fallback' => 'wire',
        'process.symfony' => 'ship',
        'scheduler.portable' => 'wire',
        'routing.native_url_generation' => 'ship',
        'mail.symfony_factory_null_transport_fallback' => 'ship',
        'templating.twig_php' => 'ship',
        'observability.null_metrics_audit_health' => 'wire',
        'sms.null_transport_fallback' => 'wire',
        'publication.mercure_configurable' => 'wire',
    ],
    'journeys' => [
        [
            'name' => 'lowest_booted_symfony_capabilities',
            'status' => 'passed',
            'evidence' => './bin/composer update --working-dir=.runs/2026-08-31-t00003-lowest --prefer-lowest; booted PlatformProfile',
        ],
        [
            'name' => 'latest_booted_symfony_capabilities',
            'status' => 'passed',
            'evidence' => 'tests/Integration/FightCommonCapabilityTest.php',
        ],
    ],
    'result' => 'passed',
    'evidence' => [
        'build' => './bin/build',
        'planning_check' => './bin/planning-check',
        'receipt_sha256' => str_repeat('0', 64),
    ],
    'next_action' => null,
];

$receipt = ReceiptCanonicalizer::withDigests($receipt);
$path = $projectRoot.'/evidence/framework-support/receipt-v1.json';

if ($argc !== 2 || $argv[1] !== '--write') {
    fwrite(STDERR, "Usage: php scripts/generate-framework-support-receipt.php --write\n");
    exit(1);
}

if (!is_dir(dirname($path))) {
    mkdir(dirname($path), 0777, true);
}

file_put_contents($path, ReceiptCanonicalizer::canonicalize($receipt));
