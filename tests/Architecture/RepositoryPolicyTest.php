<?php

declare(strict_types=1);

namespace App\Tests\Architecture;

use PHPUnit\Framework\TestCase;

final class RepositoryPolicyTest extends TestCase
{
    public function testRequiredProjectScaffoldAndPlanningAuthorityExist(): void
    {
        $root = dirname(__DIR__, 2);
        foreach ([
            'AGENTS.md', 'CONTEXT.md', 'LICENSE', 'SECURITY.md', 'CONTRIBUTING.md', 'compose.yaml',
            'bin/build', 'bin/composer', 'bin/console', 'bin/phpunit', 'bin/planning-check',
            'config/bundles.php', 'config/services.php', 'config/common/services.php', 'config/routes.php',
            'public/index.php', 'src/Kernel.php', 'planning/README.md', 'planning/CONVENTIONS.md',
            'planning/ROADMAP.md', 'planning/tickets/BOARD.md',
        ] as $path) {
            self::assertFileExists($root.'/'.$path, sprintf('Required project file is missing: %s', $path));
        }

        self::assertStringContainsString('no Fight bundle', (string) file_get_contents($root.'/AGENTS.md'));
        self::assertStringContainsString('Ready Frontier', (string) file_get_contents($root.'/planning/tickets/BOARD.md'));
    }

    public function testGeneratedAndRuntimeArtifactsRemainIgnored(): void
    {
        $root = dirname(__DIR__, 2);
        $gitignore = (string) file_get_contents($root.'/.gitignore');
        self::assertStringContainsString('/config/reference.php', $gitignore);
        self::assertStringContainsString('/var/', $gitignore);
        self::assertStringContainsString('graphify-out/', $gitignore);
        self::assertStringNotContainsString('/graphify-out/', $gitignore);
        self::assertStringContainsString('cacheDirectory="var/cache/phpunit"', (string) file_get_contents($root.'/phpunit.xml.dist'));
    }
}
