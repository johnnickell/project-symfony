<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Tests\Fixture\BootedTestKernel;
use App\Tests\Fixture\Templating\TestTemplateHelper;
use Fight\Common\Application\Templating\TemplateEngine;
use PHPUnit\Framework\TestCase;

final class TemplateHelperJourneyTest extends TestCase
{
    use BootedTestKernel;

    public function testBootedTemplateEngineReceivesTheTestHelperFromTheCompilerPass(): void
    {
        [$kernel, $container] = $this->bootTestKernel();

        try {
            $templates = $container->get('test.contract.'.TemplateEngine::class);
            self::assertTrue($templates->hasHelper(new TestTemplateHelper()));
        } finally {
            $kernel->shutdown();
        }
    }
}
