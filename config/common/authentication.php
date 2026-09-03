<?php

declare(strict_types=1);

use Fight\Common\Adapter\Auth\Hmac\HmacAuthenticator;
use Fight\Common\Adapter\Auth\Hmac\HmacRequestService;
use Fight\Common\Application\Auth\Authenticator;
use Fight\Common\Application\Auth\RequestService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->set(HmacAuthenticator::class)
        ->arg('$public', '%env(FIGHT_COMMON_HMAC_PUBLIC)%')
        ->arg('$private', '%env(FIGHT_COMMON_HMAC_PRIVATE)%')
        ->arg('$timeTolerance', 300);
    $services->alias(Authenticator::class, HmacAuthenticator::class);
    $services->set(HmacRequestService::class)
        ->arg('$public', '%env(FIGHT_COMMON_HMAC_PUBLIC)%')
        ->arg('$private', '%env(FIGHT_COMMON_HMAC_PRIVATE)%');
    $services->alias(RequestService::class, HmacRequestService::class);
};
