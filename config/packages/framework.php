<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Fight\Common\Domain\Messaging\Command\CommandMessage;
use Fight\Common\Domain\Messaging\Event\EventMessage;

return static function (ContainerConfigurator $container): void {
    $container->extension('framework', [
        'secret' => '%env(APP_SECRET)%',
        'router' => [
            'resource' => '%kernel.project_dir%/config/routes.php',
            'type' => 'php',
            'utf8' => true,
        ],
        'messenger' => [
            'default_bus' => 'messenger.bus.default',
            'transports' => [
                'fight_common_async' => 'in-memory://',
            ],
            'routing' => [
                CommandMessage::class => 'fight_common_async',
                EventMessage::class => 'fight_common_async',
            ],
        ],
        'mailer' => [
            'dsn' => 'null://null',
        ],
        'validation' => [
            'email_validation_mode' => 'html5',
        ],
    ]);
};
