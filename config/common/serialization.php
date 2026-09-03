<?php

declare(strict_types=1);

use Fight\Common\Application\Serialization\JsonSerializer;
use Fight\Common\Domain\Serialization\Serializer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->set(JsonSerializer::class);
    $services->alias(Serializer::class, JsonSerializer::class);
};
