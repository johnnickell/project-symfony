<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Messaging;

use Fight\Common\Domain\EventSourcing\EventMapping;
use Fight\Common\Domain\EventSourcing\EventMappingProvider;

final class TestEventMappingProvider implements EventMappingProvider
{
    public function namespace(): string
    {
        return 'test';
    }

    public function mappings(): iterable
    {
        return [new EventMapping('event', TestEvent::class, 1)];
    }
}
