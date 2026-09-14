<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Tests\Fixture\BootedTestKernel;
use App\Tests\Fixture\Messaging\TestCommand;
use App\Tests\Fixture\Messaging\TestCommandFilter;
use App\Tests\Fixture\Messaging\TestCommandHandler;
use App\Tests\Fixture\Messaging\TestEvent;
use App\Tests\Fixture\Messaging\TestEventSubscriber;
use App\Tests\Fixture\Messaging\TestQuery;
use App\Tests\Fixture\Messaging\TestQueryFilter;
use App\Tests\Fixture\Messaging\TestQueryHandler;
use Fight\Common\Domain\Messaging\Command\CommandMessage;
use Fight\Common\Domain\Messaging\Event\EventMessage;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Transport\InMemory\InMemoryTransport;

/**
 * Tests messaging boundaries.
 */
#[CoversNothing]
final class MessagingJourneyTest extends TestCase
{
    use BootedTestKernel;

    /**
     * Tests compiler-pass collection of test messaging services.
     */
    public function testCompilerPassesCollectTestOnlyHandlersAndSubscribers(): void
    {
        [$kernel, $container] = $this->bootTestKernel();

        try {
            $command = new TestCommand('handled');
            $container->get('test.messaging.synchronous_command_bus')->execute($command);
            self::assertSame($command, $container->get(TestCommandHandler::class)->handled?->payload());
            self::assertSame(
                $command,
                $container->get('test.fixture.'.TestCommandFilter::class)->filtered?->payload()
            );

            $query = new TestQuery('fetched');
            self::assertSame(['name' => 'fetched'], $container->get('test.messaging.query_bus')->fetch($query));
            self::assertSame($query, $container->get(TestQueryHandler::class)->handled?->payload());
            self::assertSame(
                $query,
                $container->get('test.fixture.'.TestQueryFilter::class)->filtered?->payload()
            );

            $event = new TestEvent('observed');
            $container->get('test.messaging.synchronous_event_dispatcher')->trigger($event);
            self::assertSame($event, $container->get(TestEventSubscriber::class)->handled?->payload());
        } finally {
            $kernel->shutdown();
        }
    }

    /**
     * Tests dispatch through the configured serialized transport.
     */
    public function testMessengerDispatchUsesTheConfiguredSerializedTransport(): void
    {
        [$kernel, $container] = $this->bootTestKernel();

        try {
            $transport = $container->get('messenger.transport.fight_common_async');
            self::assertInstanceOf(InMemoryTransport::class, $transport);
            $transport->reset();

            $container->get('test.messaging.asynchronous_command_bus')->execute(new TestCommand('async-command'));
            $container->get('test.messaging.asynchronous_event_dispatcher')->trigger(new TestEvent('async-event'));

            $sent = $transport->getSent();
            self::assertCount(2, $sent);
            self::assertInstanceOf(CommandMessage::class, $sent[0]->getMessage());
            self::assertSame(['name' => 'async-command'], $sent[0]->getMessage()->payload()->toArray());
            self::assertInstanceOf(EventMessage::class, $sent[1]->getMessage());
            self::assertSame(['name' => 'async-event'], $sent[1]->getMessage()->payload()->toArray());
        } finally {
            $kernel->shutdown();
        }
    }
}
