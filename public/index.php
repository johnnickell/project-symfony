<?php

declare(strict_types=1);

use App\Adapter\Kernel;
use Fight\Common\Adapter\Middleware\Symfony\JsonRequestMiddleware;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return static function (array $context): JsonRequestMiddleware {
    return new JsonRequestMiddleware(new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']));
};
