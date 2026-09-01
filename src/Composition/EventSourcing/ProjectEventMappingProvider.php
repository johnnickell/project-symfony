<?php

declare(strict_types=1);

namespace App\Composition\EventSourcing;

use Fight\Common\Domain\EventSourcing\EventMapping;
use Fight\Common\Domain\EventSourcing\EventMappingProvider;

final class ProjectEventMappingProvider implements EventMappingProvider
{
    public function namespace(): string
    {
        return 'project';
    }

    public function mappings(): iterable
    {
        return [new EventMapping('capability-activated', ProjectCapabilityActivated::class, 1)];
    }
}
