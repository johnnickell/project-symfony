<?php

declare(strict_types=1);

namespace App\Composition\Compiler;

use App\Composition\Service\SystemClock;
use LogicException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ProjectServicePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(SystemClock::class)) {
            throw new LogicException('The project-owned service configuration must register the native clock.');
        }
    }
}
