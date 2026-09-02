<?php

declare(strict_types=1);

use Fight\Common\Adapter\Messaging\Symfony\MessengerCommandBus;
use Fight\Common\Adapter\Messaging\Symfony\MessengerEventDispatcher;
use Fight\Common\Adapter\Messaging\Symfony\Serializer\SymfonyMessageSerializer;
use Fight\Common\Application\Messaging\Command\AsynchronousCommandBus;
use Fight\Common\Application\Messaging\Event\AsynchronousEventDispatcher;
use Fight\Common\Domain\Messaging\Command\CommandMessage;
use Fight\Common\Domain\Messaging\Event\EventMessage;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $container->extension('framework', [
        'messenger' => [
            'default_bus' => 'messenger.bus.default',
            'transports' => [
                'fight_common_async' => [
                    'dsn' => 'in-memory://?serialize=true',
                    'serializer' => SymfonyMessageSerializer::class,
                ],
            ],
            'routing' => [
                CommandMessage::class => 'fight_common_async',
                EventMessage::class => 'fight_common_async',
            ],
        ],
    ]);

    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->set(SymfonyMessageSerializer::class);
    $services->set(MessengerCommandBus::class)
        ->arg('$sender', service('messenger.transport.fight_common_async'));
    $services->alias(AsynchronousCommandBus::class, MessengerCommandBus::class);
    $services->set(MessengerEventDispatcher::class)
        ->arg('$sender', service('messenger.transport.fight_common_async'));
    $services->alias(AsynchronousEventDispatcher::class, MessengerEventDispatcher::class);
    $services->alias(SenderInterface::class, 'messenger.transport.fight_common_async');
};
