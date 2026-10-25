<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Messaging;

use Fight\Common\Application\Messaging\Query\QueryHandler;
use Fight\Common\Domain\Messaging\Query\QueryMessage;

/**
 * Records query delivery and returns its test result
 */
final class TestQueryHandler implements QueryHandler
{
    public ?QueryMessage $handled = null;

    /**
     * @inheritDoc
     */
    public static function queryRegistration(): string
    {
        return TestQuery::class;
    }

    /**
     * @inheritDoc
     */
    public function handle(QueryMessage $queryMessage): mixed
    {
        $this->handled = $queryMessage;

        return ['name' => $queryMessage->payload()->toArray()['name']];
    }
}
