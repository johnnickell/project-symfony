<?php

declare(strict_types=1);

namespace App\Tests\Adapter;

use App\Adapter\Kernel;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

#[CoversClass(Kernel::class)]
final class KernelTest extends TestCase
{
    public function testKernelBootsTheProjectComposition(): void
    {
        $kernel = new Kernel('test', true);
        new Filesystem()->remove($kernel->getCacheDir());

        try {
            $kernel->boot();

            self::assertSame('test', $kernel->getContainer()->getParameter('kernel.environment'));
        } finally {
            $kernel->shutdown();
        }
    }
}
