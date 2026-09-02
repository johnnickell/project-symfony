<?php

declare(strict_types=1);

use Fight\Common\Adapter\Process\Symfony\SymfonyProcessRunner;
use Fight\Common\Application\Mail\MailService;
use Fight\Common\Application\Scheduler\Scheduler;
use Fight\Common\Domain\Value\DateTime\Timezone;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->set(Timezone::class)->arg('$timezone', 'UTC');
    $services->set(Scheduler::class)
        ->factory([Scheduler::class, 'withProcessRunner'])
        ->arg('$timezone', service(Timezone::class))
        ->arg('$tempDirectory', '%kernel.cache_dir%/scheduler')
        ->arg('$processRunner', service(SymfonyProcessRunner::class))
        ->arg('$mailService', service(MailService::class));
};
