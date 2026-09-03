<?php

declare(strict_types=1);

use Fight\Common\Adapter\Observability\Health\HealthReporter;
use Fight\Common\Application\Observability\HealthAggregator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->set(HealthReporter::class);
    $services->alias(HealthAggregator::class, HealthReporter::class);
};
