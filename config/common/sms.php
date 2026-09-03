<?php

declare(strict_types=1);

use Fight\Common\Adapter\Sms\Null\NullSmsTransport;
use Fight\Common\Application\Sms\Message\SmsFactory;
use Fight\Common\Application\Sms\SmsService;
use Fight\Common\Application\Sms\Transport\SmsTransport;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->set(NullSmsTransport::class);
    $services->set(SmsService::class)
        ->arg('$transport', service(NullSmsTransport::class));
    $services->alias(SmsFactory::class, SmsService::class);
    $services->alias(SmsTransport::class, NullSmsTransport::class);
};
