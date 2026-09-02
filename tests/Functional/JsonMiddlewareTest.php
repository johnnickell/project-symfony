<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Kernel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class JsonMiddlewareTest extends TestCase
{
    public function testTheDecoratedKernelParsesJsonAndReturnsANativeJSendResponse(): void
    {
        $kernel = new Kernel('test', true);
        $request = Request::create(
            '/_test/json-journey',
            'POST',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: '{"capability":"messenger"}',
        );

        try {
            $response = $kernel->handle($request);

            self::assertSame(200, $response->getStatusCode());
            self::assertSame('application/json', $response->headers->get('Content-Type'));
            self::assertSame(
                ['status' => 'success', 'data' => ['accepted' => ['capability' => 'messenger']]],
                json_decode((string) $response->getContent(), true, flags: JSON_THROW_ON_ERROR),
            );
        } finally {
            if (isset($response)) {
                $kernel->terminate($request, $response);
            }
            $kernel->shutdown();
        }
    }
}
