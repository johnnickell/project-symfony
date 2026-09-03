<?php

declare(strict_types=1);

use Fight\Common\Adapter\Messaging\Query\QueryPipeline;
use Fight\Common\Adapter\Messaging\Query\Routing\QueryRouter;
use Fight\Common\Adapter\Messaging\Query\Routing\ServiceAwareQueryRouter;
use Fight\Common\Adapter\Messaging\Query\RoutingQueryBus;
use Fight\Common\Application\Messaging\Query\QueryBus;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->set(ServiceAwareQueryRouter::class)
        ->arg('$container', service('service_container'));
    $services->alias(QueryRouter::class, ServiceAwareQueryRouter::class);
    $services->set(RoutingQueryBus::class);
    $services->set(QueryPipeline::class)
        ->arg('$queryBus', service(RoutingQueryBus::class));
    $services->alias(QueryBus::class, QueryPipeline::class);
};
