<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Messaging;

use Fight\Common\Application\Messaging\Command\CommandHandler;
use Fight\Common\Domain\Messaging\Command\CommandMessage;

/**
 * Records command delivery for journey assertions
 */
final class TestCommandHandler implements CommandHandler
{
    public ?CommandMessage $handled = null;

    /**
     * @inheritDoc
     */
    public static function commandRegistration(): string
    {
        return TestCommand::class;
    }

    /**
     * @inheritDoc
     */
    public function handle(CommandMessage $commandMessage): void
    {
        $this->handled = $commandMessage;
    }
}
