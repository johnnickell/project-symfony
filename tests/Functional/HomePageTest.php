<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Adapter\Kernel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class HomePageTest extends TestCase
{
    public function testTheHomePageRendersTheFullStackFoundation(): void
    {
        $kernel = new Kernel('test', true);

        try {
            $response = $kernel->handle(Request::create('/'));

            self::assertSame(200, $response->getStatusCode());
            self::assertStringContainsString('Hello, Fight Symfony Starter', (string) $response->getContent());
        } finally {
            $kernel->terminate(Request::create('/'), $response ?? throw new \LogicException('Response was not created.'));
            $kernel->shutdown();
        }
    }
}
