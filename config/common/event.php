<?php

declare(strict_types=1);

use Fight\Common\Adapter\Messaging\Event\Sync\ServiceAwareEventDispatcher;
use Fight\Common\Application\Messaging\Event\EventDispatcher;
use Fight\Common\Application\Messaging\Event\SynchronousEventDispatcher;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->set(ServiceAwareEventDispatcher::class)
        ->arg('$container', service('service_container'));
    $services->alias(SynchronousEventDispatcher::class, ServiceAwareEventDispatcher::class);
    $services->alias(EventDispatcher::class, SynchronousEventDispatcher::class);
};
