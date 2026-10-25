<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Templating;

use Fight\Common\Application\Templating\TemplateHelper;

/**
 * Identifies the test template helper
 */
final class TestTemplateHelper implements TemplateHelper
{
    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'test_helper';
    }
}
