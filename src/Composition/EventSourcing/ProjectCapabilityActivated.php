<?php

declare(strict_types=1);

namespace App\Composition\EventSourcing;

use Fight\Common\Domain\Messaging\Event\Event;

final readonly class ProjectCapabilityActivated implements Event
{
    public function __construct(private string $capability)
    {
    }

    public static function fromArray(array $data): static
    {
        return new self($data['capability']);
    }

    public function capability(): string
    {
        return $this->capability;
    }

    public function toArray(): array
    {
        return ['capability' => $this->capability];
    }
}
