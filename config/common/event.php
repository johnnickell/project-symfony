<?php

declare(strict_types=1);

use Fight\Common\Adapter\Messaging\Event\Sync\ServiceAwareEventDispatcher;
use Fight\Common\Application\Messaging\Event\EventDispatcher;
use Fight\Common\Application\Messaging\Event\SynchronousEventDispatcher;
use Fight\Common\Domain\EventSourcing\EventMapper;
use Fight\Common\Domain\EventSourcing\EventMappingProvider;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->instanceof(EventMappingProvider::class)
        ->tag('common.event_mapping_provider');
    $services->set(EventMapper::class)
        ->arg('$providers', [])
        ->public();
    $services->set(ServiceAwareEventDispatcher::class)
        ->arg('$container', service('service_container'))
        ->public();
    $services->alias(SynchronousEventDispatcher::class, ServiceAwareEventDispatcher::class);
    $services->alias(EventDispatcher::class, SynchronousEventDispatcher::class);
};
