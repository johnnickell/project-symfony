<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Messaging;

use Fight\Common\Application\Messaging\Query\QueryFilter;
use Fight\Common\Domain\Messaging\Query\QueryMessage;

final class TestQueryFilter implements QueryFilter
{
    public ?QueryMessage $filtered = null;

    public function process(QueryMessage $queryMessage, callable $next): void
    {
        $this->filtered = $queryMessage;
        $next($queryMessage);
    }
}
