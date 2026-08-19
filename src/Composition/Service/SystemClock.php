<?php

declare(strict_types=1);

namespace App\Composition\Service;

final class SystemClock implements ProjectClock
{
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}
