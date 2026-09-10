<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Adapter\Kernel;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;

#[CoversNothing]
final class KernelBootTest extends TestCase
{
    public function testTheProjectOwnedKernelBoots(): void
    {
        $kernel = new Kernel('test', false);

        try {
            $kernel->boot();

            self::assertSame('test', $kernel->getContainer()->getParameter('kernel.environment'));
        } finally {
            $kernel->shutdown();
        }
    }
}
