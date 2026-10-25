<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Messaging;

use Fight\Common\Application\Messaging\Command\CommandFilter;
use Fight\Common\Domain\Messaging\Command\CommandMessage;

/**
 * Records command filtering before continuing dispatch
 */
final class TestCommandFilter implements CommandFilter
{
    public ?CommandMessage $filtered = null;

    /**
     * @inheritDoc
     */
    public function process(CommandMessage $commandMessage, callable $next): void
    {
        $this->filtered = $commandMessage;
        $next($commandMessage);
    }
}
