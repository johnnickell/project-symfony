<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Messaging;

use Fight\Common\Domain\Messaging\Event\Event;

/**
 * Carries the test event payload
 */
final readonly class TestEvent implements Event
{
    /**
     * Constructs TestEvent
     */
    public function __construct(private string $name)
    {
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $data): static
    {
        return new self($data['name']);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return ['name' => $this->name];
    }
}
