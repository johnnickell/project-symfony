<?php

declare(strict_types=1);

namespace Symfony\Component\Routing\Loader\Configurator;

use App\Controller\HomeController;

return static function (RoutingConfigurator $routes): void {
    $routes->add('homepage', '/')
        ->controller(HomeController::class.'::index')
        ->methods(['GET']);

    if ($routes->env() === 'test') {
        $routes->import('../tests/Fixture/Http/routes.php');
    }
};
