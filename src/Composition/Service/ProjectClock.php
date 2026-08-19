<?php

declare(strict_types=1);

namespace App\Composition\Service;

use DateTimeImmutable;

interface ProjectClock
{
    public function now(): DateTimeImmutable;
}
