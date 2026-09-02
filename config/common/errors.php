<?php

declare(strict_types=1);

use Fight\Common\Adapter\Http\Symfony\Controller\ErrorController;
use Fight\Common\Adapter\Http\Symfony\EventSubscriber\SymfonyExceptionSubscriber;
use Fight\Common\Adapter\Http\Symfony\EventSubscriber\SymfonyValidationSubscriber;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->set(ErrorController::class);
    $services->set(SymfonyValidationSubscriber::class);
    $services->set(SymfonyExceptionSubscriber::class);
};
