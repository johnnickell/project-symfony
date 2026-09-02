<?php

declare(strict_types=1);

use Fight\Common\Adapter\Messaging\Command\Sync\CommandPipeline;
use Fight\Common\Adapter\Messaging\Command\Sync\Routing\CommandRouter;
use Fight\Common\Adapter\Messaging\Command\Sync\Routing\ServiceAwareCommandRouter;
use Fight\Common\Adapter\Messaging\Command\Sync\RoutingCommandBus;
use Fight\Common\Application\Messaging\Command\CommandBus;
use Fight\Common\Application\Messaging\Command\SynchronousCommandBus;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->set(ServiceAwareCommandRouter::class)
        ->arg('$container', service('service_container'))
        ->public();
    $services->alias(CommandRouter::class, ServiceAwareCommandRouter::class);
    $services->set(RoutingCommandBus::class);
    $services->set(CommandPipeline::class)
        ->arg('$commandBus', service(RoutingCommandBus::class));
    $services->alias(SynchronousCommandBus::class, CommandPipeline::class);
    $services->alias(CommandBus::class, SynchronousCommandBus::class);
};
