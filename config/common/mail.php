<?php

declare(strict_types=1);

use Fight\Common\Adapter\Mail\Null\NullMailTransport;
use Fight\Common\Adapter\Mail\Symfony\SymfonyMailFactory;
use Fight\Common\Adapter\Mail\Symfony\SymfonyMailTransport;
use Fight\Common\Application\Mail\MailService;
use Fight\Common\Application\Mail\Message\MailFactory;
use Fight\Common\Application\Mail\Transport\MailTransport;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $container->extension('framework', [
        'mailer' => [
            'dsn' => 'null://null',
        ],
    ]);

    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->set(SymfonyMailFactory::class);
    $services->set(SymfonyMailTransport::class);
    $services->set(NullMailTransport::class);
    $services->set(MailService::class)
        ->arg('$transport', service(NullMailTransport::class))
        ->arg('$factory', service(SymfonyMailFactory::class));
    $services->alias(MailFactory::class, SymfonyMailFactory::class);
    $services->alias(MailTransport::class, NullMailTransport::class);
};
