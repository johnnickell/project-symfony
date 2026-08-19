<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $container->extension('framework', [
        'secret' => '%env(APP_SECRET)%',
        'router' => [
            'resource' => '%kernel.project_dir%/config/routes.php',
            'type' => 'php',
            'utf8' => true,
        ],
    ]);
};
