<?php

declare(strict_types=1);

use Fight\Common\Adapter\Routing\Symfony\SymfonyUrlGenerator;
use Fight\Common\Application\Routing\UrlGenerator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->set(SymfonyUrlGenerator::class)
        ->arg('$urlGenerator', service(UrlGeneratorInterface::class));
    $services->alias(UrlGenerator::class, SymfonyUrlGenerator::class);
};
