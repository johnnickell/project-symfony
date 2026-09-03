<?php

declare(strict_types=1);

use Fight\Common\Adapter\FileStorage\FlysystemStorage;
use Fight\Common\Application\FileStorage\FileStorage;
use Fight\Common\Application\FileStorage\StorageService;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->set(LocalFilesystemAdapter::class)
        ->arg('$location', '%kernel.project_dir%/var/storage');
    $services->set(FilesystemOperator::class, Flysystem::class)
        ->arg('$adapter', service(LocalFilesystemAdapter::class));
    $services->set(FlysystemStorage::class);
    $services->alias(FileStorage::class, FlysystemStorage::class);
    $services->set(StorageService::class)
        ->call('addStorage', ['default', service(FlysystemStorage::class)]);
};
