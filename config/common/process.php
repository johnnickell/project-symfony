<?php

declare(strict_types=1);

use Fight\Common\Adapter\Process\Symfony\SymfonyProcessRunner;
use Fight\Common\Application\Process\ProcessRunner;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->set(SymfonyProcessRunner::class);
    $services->alias(ProcessRunner::class, SymfonyProcessRunner::class);
};
