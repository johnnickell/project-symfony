<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Adapter\Kernel;
use PHPUnit\Framework\TestCase;

final class KernelBootTest extends TestCase
{
    public function testTheProjectOwnedKernelBoots(): void
    {
        $kernel = new Kernel('test', false);

        try {
            $kernel->boot();

            self::assertNotNull($kernel->getContainer());
        } finally {
            $kernel->shutdown();
        }
    }
}
