<?php

declare(strict_types=1);

namespace App\Composition\Service;

interface ProjectClock
{
    public function now(): \DateTimeImmutable;
}
