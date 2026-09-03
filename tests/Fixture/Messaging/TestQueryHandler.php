<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Messaging;

use Fight\Common\Application\Messaging\Query\QueryHandler;
use Fight\Common\Domain\Messaging\Query\QueryMessage;

final class TestQueryHandler implements QueryHandler
{
    public ?QueryMessage $handled = null;

    public static function queryRegistration(): string
    {
        return TestQuery::class;
    }

    public function handle(QueryMessage $queryMessage): mixed
    {
        $this->handled = $queryMessage;

        return ['name' => $queryMessage->payload()->toArray()['name']];
    }
}
