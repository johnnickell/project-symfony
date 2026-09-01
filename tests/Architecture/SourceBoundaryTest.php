<?php

declare(strict_types=1);

namespace App\Tests\Architecture;

use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

final class SourceBoundaryTest extends TestCase
{
    public function testFightLibrariesAreConsumedOnlyThroughComposer(): void
    {
        $projectRoot = dirname(__DIR__, 2);
        $manifest = json_decode((string) file_get_contents($projectRoot.'/composer.json'), true, flags: JSON_THROW_ON_ERROR);

        self::assertSame('dev-develop as 1.1.9999999-dev', $manifest['require']['johnnickell/fight-common']);
        self::assertContains(
            'https://github.com/johnnickell/fight-common',
            array_column($manifest['repositories'], 'url'),
        );
        self::assertArrayNotHasKey('Fight\\Common\\', $manifest['autoload']['psr-4']);
        self::assertSame('dev-develop', $manifest['require']['johnnickell/fight-access-control']);
        self::assertContains(
            'https://github.com/johnnickell/fight-access-control',
            array_column($manifest['repositories'], 'url'),
        );
        self::assertArrayNotHasKey('fight/symfony-bundle', $manifest['require']);

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($projectRoot.'/src'));

        /** @var SplFileInfo $file */
        foreach ($iterator as $file) {
            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $source = (string) file_get_contents($file->getPathname());
            self::assertDoesNotMatchRegularExpression(
                '/namespace\\s+Fight\\\\(?:Common|AccessControl)\\b/',
                $source,
                sprintf('Copied Fight source is forbidden: %s', $file->getPathname()),
            );
        }

        self::assertDirectoryDoesNotExist($projectRoot.'/src/Fight');
    }

    public function testTheRepositoryOwnsItsCompositionAndAuthority(): void
    {
        $projectRoot = dirname(__DIR__, 2);

        foreach ([
            'AGENTS.md',
            'CONTEXT.md',
            'LICENSE',
            'SECURITY.md',
            'CONTRIBUTING.md',
            'bin/composer',
            'bin/console',
            'bin/down',
            'bin/exec',
            'scripts/console.php',
            'bin/phpunit',
            'bin/planning-check',
            'scripts/planning-check.php',
            'scripts/production-autoload-check.php',
            'bin/up',
            'bin/build',
            'compose.yaml',
            'config/bundles.php',
            'config/packages/framework.php',
            'config/packages/doctrine.php',
            'config/common/services.php',
            'config/routes.php',
            'public/index.php',
            'src/Controller/HomeController.php',
            'planning/README.md',
            'planning/ROADMAP.md',
            'planning/specs/00001-PRD.md',
            'planning/specs/README.md',
            'planning/tickets/00001-TICKET.md',
            'planning/tickets/00002-TICKET.md',
            'planning/tickets/README.md',
            'planning/tickets/BOARD.md',
            'planning/agents/issue-tracker.md',
            'planning/agents/triage-labels.md',
            'planning/agents/domain.md',
            'config/services.php',
            'src/Composition/Compiler/ProjectServicePass.php',
        ] as $path) {
            self::assertFileExists($projectRoot.'/'.$path, sprintf('Repository authority is missing: %s', $path));
        }

        $agents = (string) file_get_contents($projectRoot.'/AGENTS.md');
        self::assertStringContainsString('planning/tickets/', $agents);
        self::assertStringContainsString('no Fight bundle', $agents);

        $prd = (string) file_get_contents($projectRoot.'/planning/specs/00001-PRD.md');
        self::assertStringNotContainsString('source_commit:', $prd);
        self::assertStringContainsString('hosted build', $prd);

        $board = (string) file_get_contents($projectRoot.'/planning/tickets/BOARD.md');
        self::assertStringContainsString('Ready Frontier', $board);
        self::assertStringContainsString('00001-TICKET.md', $board);

        $kernel = (string) file_get_contents($projectRoot.'/src/Kernel.php');
        self::assertStringContainsString('ProjectServicePass', $kernel);
        self::assertStringContainsString("config/common/*.php", $kernel);
        self::assertStringContainsString("config/services.php", $kernel);
    }

    public function testGeneratedReferenceConfigurationIsNotCommitted(): void
    {
        $projectRoot = dirname(__DIR__, 2);

        self::assertFileExists($projectRoot.'/config/services.php');
        $gitignore = (string) file_get_contents($projectRoot.'/.gitignore');
        self::assertStringContainsString('/config/reference.php', $gitignore);
    }

    public function testToolCachesUseTheRuntimeCacheDirectory(): void
    {
        $projectRoot = dirname(__DIR__, 2);

        $phpunitConfiguration = (string) file_get_contents($projectRoot.'/phpunit.xml.dist');
        self::assertStringContainsString('cacheDirectory="var/cache/phpunit"', $phpunitConfiguration);

        $gitignore = (string) file_get_contents($projectRoot.'/.gitignore');
        self::assertStringContainsString('/var/', $gitignore);

        foreach ([
            '/.phpunit.result.cache',
            '/.phpunit.cache/',
            '/.phpstan.cache',
            '/.phpstan.result.cache',
            '/.php-cs-fixer.cache',
            '/.rector.cache',
        ] as $rootCachePath) {
            self::assertStringNotContainsString($rootCachePath, $gitignore);
        }
    }

    public function testGeneratedGraphArtifactsAreIgnoredAtAnyDepth(): void
    {
        $projectRoot = dirname(__DIR__, 2);
        $gitignore = (string) file_get_contents($projectRoot.'/.gitignore');

        self::assertStringContainsString('graphify-out/', $gitignore);
        self::assertStringNotContainsString('/graphify-out/', $gitignore);
    }

}
