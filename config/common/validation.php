<?php

declare(strict_types=1);

use Fight\Common\Application\Validation\ValidationService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $container->extension('framework', [
        'validation' => [
            'email_validation_mode' => 'html5',
        ],
    ]);

    $container->services()->set(ValidationService::class);
};
