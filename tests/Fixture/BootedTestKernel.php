<?php

declare(strict_types=1);

namespace App\Tests\Fixture;

use App\Adapter\Kernel;
use Psr\Container\ContainerInterface;

trait BootedTestKernel
{
    /** @return array{Kernel, ContainerInterface} */
    private function bootTestKernel(): array
    {
        $kernel = new Kernel('test', true);
        $kernel->boot();

        return [$kernel, $kernel->getContainer()->get('test.service_container')];
    }
}
