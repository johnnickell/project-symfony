<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Messaging;

use Fight\Common\Application\Messaging\Event\EventSubscriber;
use Fight\Common\Domain\Messaging\Event\EventMessage;

/**
 * Records event delivery for journey assertions
 */
final class TestEventSubscriber implements EventSubscriber
{
    public ?EventMessage $handled = null;

    /**
     * @inheritDoc
     */
    public static function eventRegistration(): array
    {
        return [TestEvent::class => 'onTestEvent'];
    }

    /**
     * Records the observed test event
     */
    public function onTestEvent(EventMessage $message): void
    {
        $this->handled = $message;
    }
}
