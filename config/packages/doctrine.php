<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $container->extension('doctrine', [
        'dbal' => [
            'url' => 'sqlite:///:memory:',
        ],
        'orm' => [
            'auto_mapping' => false,
        ],
    ]);
};
