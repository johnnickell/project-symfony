<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Messaging;

use Fight\Common\Application\Messaging\Event\EventSubscriber;
use Fight\Common\Domain\Messaging\Event\EventMessage;

final class TestEventSubscriber implements EventSubscriber
{
    public ?EventMessage $handled = null;

    public static function eventRegistration(): array
    {
        return [TestEvent::class => 'onTestEvent'];
    }

    public function onTestEvent(EventMessage $message): void
    {
        $this->handled = $message;
    }
}
