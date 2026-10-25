<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Homepage;

use App\Adapter\Homepage\HomeAction;
use App\Adapter\Homepage\HomeResponder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Loader\ArrayLoader;

/**
 * Tests homepage interaction delegation
 */
#[CoversClass(HomeAction::class)]
#[UsesClass(HomeResponder::class)]
final class HomeActionTest extends TestCase
{
    /**
     * Tests presentation of the injected application name
     */
    public function testHandlePresentsTheConfiguredName(): void
    {
        $twig = new Environment(new ArrayLoader(['home/index.html.twig' => '<title>{{ applicationName }}</title>']));
        $action = new HomeAction(new HomeResponder($twig), 'Configured <Starter>');

        $response = $action->handle();

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('text/html; charset=UTF-8', $response->headers->get('Content-Type'));
        self::assertSame('<title>Configured &lt;Starter&gt;</title>', $response->getContent());
    }

    /**
     * Tests propagation of presentation failures without a false success response
     */
    public function testHandlePropagatesRenderingFailure(): void
    {
        $action = new HomeAction(new HomeResponder(new Environment(new ArrayLoader([]))), 'Configured Starter');

        $this->expectException(LoaderError::class);
        $this->expectExceptionMessage('home/index.html.twig');

        $action->handle();
    }
}
