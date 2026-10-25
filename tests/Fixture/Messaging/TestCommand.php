<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Messaging;

use Fight\Common\Domain\Messaging\Command\Command;

/**
 * Carries the test command payload
 */
final readonly class TestCommand implements Command
{
    /**
     * Constructs TestCommand
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
