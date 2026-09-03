<?php

declare(strict_types=1);

use Fight\Common\Adapter\Templating\PhpEngine;
use Fight\Common\Adapter\Templating\TwigEngine;
use Fight\Common\Application\Templating\TemplateEngine;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->set(TwigEngine::class);
    $services->set(PhpEngine::class)
        ->arg('$paths', ['%kernel.project_dir%/templates']);
    $services->alias(TemplateEngine::class, TwigEngine::class);
};
