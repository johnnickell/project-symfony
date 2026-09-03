<?php

declare(strict_types=1);

use App\Adapter\Kernel;

require dirname(__DIR__).'/vendor/autoload.php';

/** @var array{versions: array<string, array<string, mixed>>} $installed */
$installed = require dirname(__DIR__).'/vendor/composer/installed.php';

foreach (['johnnickell/fight-common', 'johnnickell/fight-access-control'] as $package) {
    if (!isset($installed['versions'][$package])) {
        throw new RuntimeException(sprintf('Production dependency %s is not installed.', $package));
    }
}

$kernel = new Kernel('prod', false);
$kernel->boot();
$kernel->shutdown();

fwrite(STDOUT, "Production autoload and kernel boot passed.\n");
