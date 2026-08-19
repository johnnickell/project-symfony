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

        self::assertSame('^1.1', $manifest['require']['johnnickell/fight-common']);
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
            'planning/PRD-00018.md',
            'planning/BOARD.md',
            'planning/ROADMAP.md',
            'planning/agents/issue-tracker.md',
            'planning/agents/triage-labels.md',
            'planning/agents/domain.md',
            'config/services.php',
            'src/Composition/Compiler/ProjectServicePass.php',
        ] as $path) {
            self::assertFileExists($projectRoot.'/'.$path, sprintf('Repository authority is missing: %s', $path));
        }

        $agents = (string) file_get_contents($projectRoot.'/AGENTS.md');
        self::assertStringContainsString('Future detailed tickets and status live in this repository.', $agents);
        self::assertStringContainsString('no Fight bundle', $agents);

        $prd = (string) file_get_contents($projectRoot.'/planning/PRD-00018.md');
        self::assertStringNotContainsString('source_commit:', $prd);
        self::assertStringContainsString('hosted build', $prd);

        $kernel = (string) file_get_contents($projectRoot.'/src/Kernel.php');
        self::assertStringContainsString('ProjectServicePass', $kernel);
        self::assertStringContainsString("config/services.php", $kernel);
    }

    public function testGeneratedReferenceConfigurationIsNotCommitted(): void
    {
        $projectRoot = dirname(__DIR__, 2);

        self::assertFileExists($projectRoot.'/config/services.php');
        self::assertFileDoesNotExist($projectRoot.'/config/reference.php');
    }

}
