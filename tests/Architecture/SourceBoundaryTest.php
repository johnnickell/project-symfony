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

        self::assertSame(
            'dev-develop#4a798b1db8fdb5e4af7d0ba8c98a88ac53c50c16 as 1.2.0-dev',
            $manifest['require']['johnnickell/fight-common'],
        );
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

    public function testSymfonyOwnsCompositionWithoutProductionProfilesFixturesOrReceiptAuthority(): void
    {
        $projectRoot = dirname(__DIR__, 2);

        $kernel = (string) file_get_contents($projectRoot.'/src/Kernel.php');
        self::assertStringContainsString('ProjectServicePass', $kernel);
        self::assertStringContainsString("config/common/*.php", $kernel);
        self::assertStringContainsString("config/services.php", $kernel);

        foreach (['PlatformProfile.php', 'SecurityProfile.php', 'ReceiptCanonicalizer.php'] as $file) {
            self::assertFileDoesNotExist($projectRoot.'/src/Composition/FrameworkSupport/'.$file);
        }
        self::assertDirectoryDoesNotExist($projectRoot.'/src/Fixture');
        self::assertStringNotContainsString('ProjectEventMappingProvider', (string) file_get_contents($projectRoot.'/config/common/services.php'));
        self::assertStringContainsString("->decorate('http_kernel')", (string) file_get_contents($projectRoot.'/config/common/services.php'));
    }
}
