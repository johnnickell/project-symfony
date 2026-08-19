<?php

declare(strict_types=1);

use App\Composition\Service\ProjectClock;
use App\Composition\Service\SystemClock;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $parameters = $container->parameters();
    $parameters->set('project.application_name', '%env(default:project.application_name.fallback:APP_NAME)%');
    $parameters->set('project.application_name.fallback', 'Fight Symfony Starter');

    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->load('App\\', '../src/')
        ->exclude('../src/Kernel.php');
    $services->alias(ProjectClock::class, SystemClock::class);
};
