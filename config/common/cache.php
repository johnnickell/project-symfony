<?php

declare(strict_types=1);

use Fight\Common\Adapter\Cache\Psr6\Psr6Cache;
use Fight\Common\Application\Cache\Cache;
use Fight\Common\Application\Cache\MutableCache;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->set(Psr6Cache::class);
    $services->alias(Cache::class, Psr6Cache::class);
    $services->alias(MutableCache::class, Psr6Cache::class);
    $services->set(CacheItemPoolInterface::class, FilesystemAdapter::class)
        ->arg('$namespace', 'fight-common')
        ->arg('$defaultLifetime', 0)
        ->arg('$directory', '%kernel.cache_dir%/fight-common');
};
