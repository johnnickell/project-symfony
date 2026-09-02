<?php

declare(strict_types=1);

use Fight\Common\Adapter\FileTransfer\Null\NullFileTransport;
use Fight\Common\Application\FileTransfer\FileTransferService;
use Fight\Common\Application\FileTransfer\Transport\FileTransport;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->set(NullFileTransport::class);
    $services->alias(FileTransport::class, NullFileTransport::class);
    $services->set(FileTransferService::class)
        ->call('addTransport', ['default', service(NullFileTransport::class)]);
};
