<?php

declare(strict_types=1);

namespace App\Tests\Adapter\DependencyInjection;

use App\Adapter\DependencyInjection\LazyMessagingServiceVisibilityCompilerPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class LazyMessagingServiceVisibilityCompilerPassTest extends TestCase
{
    public function testMakesOnlyLazyMessagingServicesPublic(): void
    {
        $container = new ContainerBuilder();

        foreach ([
            'command_handler' => 'common.command_handler',
            'event_subscriber' => 'common.event_subscriber',
            'query_handler' => 'common.query_handler',
            'command_filter' => 'common.command_filter',
            'query_filter' => 'common.query_filter',
            'template_helper' => 'common.template_helper',
        ] as $id => $tag) {
            $container->register($id, \stdClass::class)->addTag($tag);
        }
        $container->register('unrelated', \stdClass::class);

        (new LazyMessagingServiceVisibilityCompilerPass())->process($container);

        self::assertTrue($container->getDefinition('command_handler')->isPublic());
        self::assertTrue($container->getDefinition('event_subscriber')->isPublic());
        self::assertTrue($container->getDefinition('query_handler')->isPublic());
        self::assertFalse($container->getDefinition('command_filter')->isPublic());
        self::assertFalse($container->getDefinition('query_filter')->isPublic());
        self::assertFalse($container->getDefinition('template_helper')->isPublic());
        self::assertFalse($container->getDefinition('unrelated')->isPublic());
    }
}
