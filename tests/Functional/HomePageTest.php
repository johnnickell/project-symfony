<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Adapter\Kernel;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tests the homepage HTTP journey
 */
#[CoversNothing]
final class HomePageTest extends TestCase
{
    /**
     * Tests preserved routing and safe configured HTML rendering
     */
    #[DataProvider('applicationNames')]
    public function testTheHomePagePreservesItsPresentation(?string $name, string $expectedTitle): void
    {
        $previousEnvironment = getenv('APP_NAME');
        $previousEnv = $_ENV;
        $previousServer = $_SERVER;
        unset($_ENV['APP_NAME'], $_SERVER['APP_NAME']);
        putenv($name === null ? 'APP_NAME' : 'APP_NAME='.$name);
        $kernel = new Kernel('test', true);
        $request = Request::create('/');

        try {
            $response = $kernel->handle($request);

            self::assertSame('homepage', $request->attributes->get('_route'));
            self::assertSame(200, $response->getStatusCode());
            self::assertSame('text/html; charset=UTF-8', $response->headers->get('Content-Type'));
            self::assertStringContainsString('<title>'.$expectedTitle.'</title>', (string) $response->getContent());
            self::assertStringContainsString('<h1>Hello, Fight Symfony Starter</h1>', (string) $response->getContent());
            self::assertStringContainsString(
                '<p>The Symfony full-stack foundation is running.</p>',
                (string) $response->getContent()
            );
        } finally {
            if (isset($response)) {
                $kernel->terminate($request, $response);
            }

            $kernel->shutdown();
            $_ENV = $previousEnv;
            $_SERVER = $previousServer;
            putenv($previousEnvironment === false ? 'APP_NAME' : 'APP_NAME='.$previousEnvironment);
        }
    }

    /**
     * Supplies baseline title expectations independent of the presentation implementation
     *
     * @return iterable<string, array{?string, string}>
     */
    public static function applicationNames(): iterable
    {
        yield 'default name' => [null, 'Fight Symfony Starter'];
        yield 'configured name' => ['Configured Starter', 'Configured Starter'];
        yield 'escaped name' => [
            'Configured <Starter> & "Friends"',
            'Configured &lt;Starter&gt; &amp; &quot;Friends&quot;'
        ];
    }
}
