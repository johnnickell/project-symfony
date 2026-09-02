<?php

declare(strict_types=1);

use Fight\Common\Adapter\Observability\Metrics\NullMetricsCollector;
use Fight\Common\Application\Observability\MetricsCollector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->set(NullMetricsCollector::class);
    $services->alias(MetricsCollector::class, NullMetricsCollector::class);
};
