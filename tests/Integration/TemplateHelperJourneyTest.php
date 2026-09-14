<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Tests\Fixture\BootedTestKernel;
use App\Tests\Fixture\Templating\TestTemplateHelper;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;

/**
 * Tests template-helper integration.
 */
#[CoversNothing]
final class TemplateHelperJourneyTest extends TestCase
{
    use BootedTestKernel;

    /**
     * Tests compiler-pass registration of the template helper.
     */
    public function testBootedTemplateEngineReceivesTheTestHelperFromTheCompilerPass(): void
    {
        [$kernel, $container] = $this->bootTestKernel();

        try {
            $templates = $container->get('test.template.engine');
            self::assertTrue($templates->hasHelper(new TestTemplateHelper()));
        } finally {
            $kernel->shutdown();
        }
    }
}
