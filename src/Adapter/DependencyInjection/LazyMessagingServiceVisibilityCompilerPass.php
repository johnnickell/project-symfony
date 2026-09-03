<?php

declare(strict_types=1);

namespace App\Adapter\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class LazyMessagingServiceVisibilityCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        foreach ([
            'common.command_handler',
            'common.event_subscriber',
            'common.query_handler',
        ] as $tag) {
            foreach (array_keys($container->findTaggedServiceIds($tag)) as $id) {
                $container->getDefinition($id)->setPublic(true);
            }
        }
    }
}
