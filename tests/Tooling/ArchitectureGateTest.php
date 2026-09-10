<?php

declare(strict_types=1);

namespace App\Tests\Tooling;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

#[CoversNothing]
final class ArchitectureGateTest extends TestCase
{
    private string $directory;

    protected function setUp(): void
    {
        $this->directory = sys_get_temp_dir().'/fight-symfony-architecture-'.bin2hex(random_bytes(8));
        mkdir($this->directory.'/src/Misc', 0777, true);

        $configuration = file_get_contents(dirname(__DIR__, 2).'/deptrac.php');
        self::assertIsString($configuration);
        $configuration = str_replace(
            "->paths(__DIR__.'/src')",
            '->paths('.var_export($this->directory.'/src', true).')',
            $configuration,
            $replacementCount,
        );
        self::assertSame(1, $replacementCount);
        file_put_contents($this->directory.'/deptrac.php', $configuration);
    }

    protected function tearDown(): void
    {
        $this->removeDirectory($this->directory);
    }

    public function testArchitectureGateRejectsUnassignedProductionClasses(): void
    {
        file_put_contents(
            $this->directory.'/src/Misc/UnassignedClass.php',
            "<?php\n\ndeclare(strict_types=1);\n\nnamespace App\\Misc;\n\nfinal class UnassignedClass {}\n",
        );

        $process = new Process([
            PHP_BINARY,
            dirname(__DIR__, 2).'/vendor/bin/deptrac',
            'debug:unassigned',
            '--config-file='.$this->directory.'/deptrac.php',
            '--no-cache',
        ], dirname(__DIR__, 2), timeout: 20);
        $process->run();

        self::assertNotSame(0, $process->getExitCode());
        self::assertStringContainsString('App\\Misc\\UnassignedClass', $process->getOutput());
    }

    private function removeDirectory(string $directory): void
    {
        foreach (scandir($directory) ?: [] as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $path = $directory.'/'.$entry;
            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                unlink($path);
            }
        }

        rmdir($directory);
    }
}
