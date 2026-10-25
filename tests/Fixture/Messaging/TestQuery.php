<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Messaging;

use Fight\Common\Domain\Messaging\Query\Query;

/**
 * Carries the test query payload
 */
final readonly class TestQuery implements Query
{
    /**
     * Constructs TestQuery
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
