<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Http;

use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Jwt\TokenFactoryInterface;
use Symfony\Component\Mercure\Update;

final class RecordingMercureHub implements HubInterface
{
    /** @var list<Update> */
    public array $updates = [];

    public function getPublicUrl(): string
    {
        return 'https://mercure.example.test/.well-known/mercure';
    }

    public function getFactory(): ?TokenFactoryInterface
    {
        return null;
    }

    public function publish(Update $update): string
    {
        $this->updates[] = $update;

        return 'test-update-id';
    }
}
