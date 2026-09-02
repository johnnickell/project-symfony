<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Adapter\Kernel;
use Fight\Common\Adapter\Middleware\Symfony\JsonRequestMiddleware;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use Symfony\Component\HttpFoundation\Request;

final class JsonMiddlewareTest extends TestCase
{
    public function testTheFrontControllerComposesJsonMiddlewareAndReturnsANativeJSendResponse(): void
    {
        $factory = require dirname(__DIR__, 2).'/public/index.php';
        self::assertIsCallable($factory);

        $kernel = $factory(['APP_ENV' => 'test', 'APP_DEBUG' => true]);
        self::assertInstanceOf(JsonRequestMiddleware::class, $kernel);
        $innerKernel = (new ReflectionProperty(JsonRequestMiddleware::class, 'kernel'))->getValue($kernel);
        self::assertInstanceOf(Kernel::class, $innerKernel);

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
            $innerKernel->shutdown();
        }
    }
}
