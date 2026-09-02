<?php

declare(strict_types=1);

namespace Symfony\Component\Routing\Loader\Configurator;

use App\Tests\Fixture\Http\JsonJourneyController;

return static function (RoutingConfigurator $routes): void {
    $routes->add('test_json_journey', '/_test/json-journey')
        ->controller(JsonJourneyController::class)
        ->methods(['POST']);
};
