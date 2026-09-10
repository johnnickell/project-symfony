<?php

declare(strict_types=1);

namespace App\Tests\Tooling;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;

#[CoversNothing]
final class RuntimeContainerConfigurationTest extends TestCase
{
    private const string PROJECT_ROOT = __DIR__.'/../..';

    public function testTheFpmRuntimeUsesTheEstablishedNonRootLogAndPoolContracts(): void
    {
        $dockerfile = self::readProjectFile('etc/docker/fpm/Dockerfile');
        $entrypoint = self::readProjectFile('etc/docker/fpm/entrypoint.sh');
        $pool = self::readProjectFile('etc/docker/fpm/www.conf');

        self::assertStringContainsString('USER systemuser', $dockerfile);
        self::assertStringContainsString(
            'COPY ./www.conf /usr/local/etc/php-fpm.d/www.conf',
            $dockerfile,
        );
        self::assertStringContainsString('mkfifo /tmp/stdout', $entrypoint);
        self::assertStringContainsString('php-fpm --pid /tmp/php-fpm.pid -d error_log=/tmp/stdout &', $entrypoint);
        self::assertStringContainsString('exec tail -f /tmp/stdout', $entrypoint);
        self::assertStringContainsString('user = systemuser', $pool);
        self::assertStringContainsString('group = systemuser', $pool);
        self::assertStringContainsString('pm = ondemand', $pool);
        self::assertStringContainsString('pm.max_children = 6', $pool);
        self::assertStringContainsString('pm.process_idle_timeout = 10s', $pool);
        self::assertStringContainsString('pm.max_requests = 500', $pool);
        self::assertStringContainsString('request_terminate_timeout = 60s', $pool);
    }

    public function testComposeKeepsOnlyTheMinimalRuntimeServices(): void
    {
        $compose = self::readProjectFile('compose.yaml');

        self::assertSame(1, preg_match('/^services:\n(?<services>.*?)^networks:/ms', $compose, $serviceBlock));
        preg_match_all('/^  ([a-z][a-z0-9-]*):$/m', $serviceBlock['services'], $matches);

        self::assertSame(['api', 'server'], $matches[1]);
        self::assertStringContainsString('build: ./etc/docker/fpm', $compose);
        self::assertStringContainsString('build: ./etc/docker/nginx', $compose);
    }

    private static function readProjectFile(string $path): string
    {
        $contents = file_get_contents(self::PROJECT_ROOT.'/'.$path);

        self::assertNotFalse($contents);

        return $contents;
    }
}
