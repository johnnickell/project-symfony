<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $parameters = $container->parameters();
    $parameters->set('project.application_name', '%env(default:project.application_name.fallback:APP_NAME)%');
    $parameters->set('project.application_name.fallback', 'Fight Symfony Starter');

    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->load('App\\', '../src/')
        ->exclude([
            '../src/Adapter/DependencyInjection/',
            '../src/Adapter/Kernel.php',
        ]);
};
