<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Messaging;

use Fight\Common\Domain\Messaging\Command\Command;

final readonly class TestCommand implements Command
{
    public function __construct(private string $name)
    {
    }

    public static function fromArray(array $data): static
    {
        return new self($data['name']);
    }

    public function toArray(): array
    {
        return ['name' => $this->name];
    }
}
