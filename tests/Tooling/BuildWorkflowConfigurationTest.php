<?php

declare(strict_types=1);

namespace App\Tests\Tooling;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;

#[CoversNothing]
final class BuildWorkflowConfigurationTest extends TestCase
{
    private const string PROJECT_ROOT = __DIR__.'/../..';

    public function testBuildOwnsOneFailFastOrderedVerdict(): void
    {
        $build = self::readProjectFile('bin/build');
        self::assertStringContainsString("set -eu\n", $build);
        self::assertSame(1, substr_count($build, './bin/planning-check'));

        $commands = [
            './bin/planning-check',
            'docker build',
            'php scripts/validate-composer-candidate.php',
            'composer install --no-interaction --prefer-dist --no-progress',
            'php scripts/generate-framework-support-receipt.php',
            'scripts/verify-dependency-lanes.sh',
            'php scripts/verify-framework-support-receipt.php',
            'php vendor/bin/phpcs',
            'php vendor/bin/phpstan analyse --no-progress',
            'php vendor/bin/deptrac --fail-on-uncovered --report-uncovered --report-skipped',
            'php vendor/bin/deptrac debug:unassigned --no-cache',
            'php vendor/bin/rector process src tests --dry-run --no-progress-bar',
            'php vendor/bin/phpunit --fail-on-skipped --fail-on-notice --fail-on-warning',
            'bash bin/coverage',
            'composer install --no-dev --no-interaction --prefer-dist --no-progress',
            'php scripts/production-autoload-check.php',
        ];

        $previousPosition = -1;
        foreach ($commands as $command) {
            $position = strpos($build, $command);

            self::assertNotFalse($position, sprintf('Missing canonical build command: %s', $command));
            self::assertGreaterThan($previousPosition, $position, sprintf('Build command is out of order: %s', $command));
            $previousPosition = $position;
        }
    }

    public function testHostedWorkflowDelegatesToTheCanonicalBuild(): void
    {
        $workflow = self::readProjectFile('.github/workflows/build.yml');

        self::assertSame(1, substr_count($workflow, 'run: sh bin/build'));
        self::assertStringNotContainsString('vendor/bin/phpunit', $workflow);
        self::assertStringNotContainsString('vendor/bin/phpstan', $workflow);
        self::assertStringNotContainsString('vendor/bin/phpcs', $workflow);
    }

    private static function readProjectFile(string $path): string
    {
        $contents = file_get_contents(self::PROJECT_ROOT.'/'.$path);

        self::assertIsString($contents);

        return $contents;
    }
}
