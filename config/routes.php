<?php

declare(strict_types=1);

namespace Symfony\Component\Routing\Loader\Configurator;

use App\Adapter\Homepage\HomeAction;

return static function (RoutingConfigurator $routes): void {
    $routes->add('homepage', '/')
        ->controller(HomeAction::class.'::handle')
        ->methods(['GET']);

    if ($routes->env() === 'test') {
        $routes->import('../tests/Fixture/Http/routes.php');
    }
};
