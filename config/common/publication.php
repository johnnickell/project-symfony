<?php

declare(strict_types=1);

use Fight\Common\Adapter\Socket\MercureHubPublisher;
use Fight\Common\Adapter\Socket\PrivateMercureHubPublisher;
use Fight\Common\Application\Socket\PrivatePublisher;
use Fight\Common\Application\Socket\Publisher;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Mercure\Hub;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Jwt\StaticTokenProvider;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->set(StaticTokenProvider::class)
        ->arg('$token', '%env(MERCURE_JWT_TOKEN)%');
    $services->set(Hub::class)
        ->arg('$url', '%env(MERCURE_URL)%')
        ->arg('$jwtProvider', service(StaticTokenProvider::class))
        ->arg('$publicUrl', '%env(MERCURE_PUBLIC_URL)%');
    $services->alias(HubInterface::class, Hub::class);
    $services->set(MercureHubPublisher::class);
    $services->alias(Publisher::class, MercureHubPublisher::class);
    $services->set(PrivateMercureHubPublisher::class);
    $services->alias(PrivatePublisher::class, PrivateMercureHubPublisher::class);
};
