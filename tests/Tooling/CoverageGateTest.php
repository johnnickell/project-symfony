<?php

declare(strict_types=1);

namespace App\Tests\Tooling;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

#[CoversNothing]
final class CoverageGateTest extends TestCase
{
    private string $directory;

    protected function setUp(): void
    {
        $this->directory = sys_get_temp_dir().'/fight-symfony-coverage-'.bin2hex(random_bytes(8));
        mkdir($this->directory.'/src', 0777, true);
        mkdir($this->directory.'/var/reports/coverage', 0777, true);
    }

    protected function tearDown(): void
    {
        $this->removeDirectory($this->directory);
    }

    public function testCoverageGateRejectsProductionIgnoreDirectives(): void
    {
        file_put_contents($this->directory.'/src/Example.php', "<?php\n// @codeCoverageIgnore\n");
        $this->writeClover(4, 4);

        $process = $this->runCoverageGate();

        self::assertSame(1, $process->getExitCode());
        self::assertSame(
            "Coverage-ignore directive found in production PHP: src/Example.php\n",
            $process->getErrorOutput(),
        );
    }

    public function testCoverageGateRejectsIncompleteStatementCounts(): void
    {
        file_put_contents($this->directory.'/src/Example.php', "<?php\n");
        $this->writeClover(4, 3);

        $process = $this->runCoverageGate();

        self::assertSame(1, $process->getExitCode());
        self::assertSame("Statement coverage is incomplete: 3/4 statements covered\n", $process->getErrorOutput());
    }

    public function testCoverageGateAcceptsExactStatementCounts(): void
    {
        file_put_contents($this->directory.'/src/Example.php', "<?php\n");
        $this->writeClover(4, 4);

        $process = $this->runCoverageGate();

        self::assertSame(0, $process->getExitCode());
        self::assertSame("Statement coverage is exact: 4/4 statements covered\n", $process->getOutput());
    }

    private function runCoverageGate(): Process
    {
        $process = new Process(
            [PHP_BINARY, dirname(__DIR__, 2).'/scripts/check-coverage.php'],
            $this->directory,
            timeout: 20,
        );
        $process->run();

        return $process;
    }

    private function writeClover(int $statements, int $coveredStatements): void
    {
        file_put_contents(
            $this->directory.'/var/reports/coverage/clover.xml',
            sprintf(
                '<?xml version="1.0"?><coverage><project><metrics statements="%d" coveredstatements="%d" /></project></coverage>',
                $statements,
                $coveredStatements,
            ),
        );
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
