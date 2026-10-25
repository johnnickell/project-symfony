<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Homepage;

use App\Adapter\Homepage\HomeResponder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Loader\ArrayLoader;

/**
 * Tests homepage response ownership
 */
#[CoversClass(HomeResponder::class)]
final class HomeResponderTest extends TestCase
{
    /**
     * Tests rendered HTML with explicit successful status and headers
     */
    public function testRespondRendersSafePresentationContext(): void
    {
        $twig = new Environment(new ArrayLoader([
            'home/index.html.twig' => '<title>{{ applicationName }}</title><h1>Fixed greeting</h1>'
        ]));
        $responder = new HomeResponder($twig);

        $response = $responder->respond('Configured <Starter> & "Friends"');

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('text/html; charset=UTF-8', $response->headers->get('Content-Type'));
        self::assertSame(
            '<title>Configured &lt;Starter&gt; &amp; &quot;Friends&quot;</title><h1>Fixed greeting</h1>',
            $response->getContent()
        );
    }

    /**
     * Tests unchanged propagation of renderer failures
     */
    public function testRespondDoesNotHideRenderingFailure(): void
    {
        $failure = new LoaderError('Homepage template unavailable');
        $twig = $this->createStub(Environment::class);
        $twig->method('render')->willThrowException($failure);
        $responder = new HomeResponder($twig);

        $this->expectExceptionObject($failure);

        $responder->respond('Configured Starter');
    }
}
