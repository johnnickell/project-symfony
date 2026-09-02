<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Templating;

use Fight\Common\Application\Templating\TemplateHelper;

final class TestTemplateHelper implements TemplateHelper
{
    public function getName(): string
    {
        return 'test_helper';
    }
}
