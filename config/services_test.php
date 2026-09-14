<?php

declare(strict_types=1);

use App\Tests\Fixture\Messaging\TestCommandHandler;
use App\Tests\Fixture\Messaging\TestCommandFilter;
use App\Tests\Fixture\Messaging\TestEventSubscriber;
use App\Tests\Fixture\Messaging\TestQueryFilter;
use App\Tests\Fixture\Messaging\TestQueryHandler;
use App\Tests\Fixture\Http\JsonJourneyController;
use App\Tests\Fixture\Templating\TestTemplateHelper;
use Fight\Common\Application\Messaging\Command\AsynchronousCommandBus;
use Fight\Common\Application\Messaging\Command\SynchronousCommandBus;
use Fight\Common\Application\Messaging\Event\AsynchronousEventDispatcher;
use Fight\Common\Application\Messaging\Event\SynchronousEventDispatcher;
use Fight\Common\Application\Messaging\Query\QueryBus;
use Fight\Common\Application\Templating\TemplateEngine;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->set(JsonJourneyController::class)->public();
    $services->set(TestCommandFilter::class);
    $services->set(TestCommandHandler::class);
    $services->set(TestEventSubscriber::class);
    $services->set(TestQueryFilter::class);
    $services->set(TestQueryHandler::class);
    $services->set(TestTemplateHelper::class);
    $services->alias('test.fixture.'.TestCommandFilter::class, TestCommandFilter::class)->public();
    $services->alias('test.fixture.'.TestQueryFilter::class, TestQueryFilter::class)->public();
    $services->alias('test.messaging.asynchronous_command_bus', AsynchronousCommandBus::class)->public();
    $services->alias('test.messaging.asynchronous_event_dispatcher', AsynchronousEventDispatcher::class)->public();
    $services->alias('test.messaging.query_bus', QueryBus::class)->public();
    $services->alias('test.messaging.synchronous_command_bus', SynchronousCommandBus::class)->public();
    $services->alias('test.messaging.synchronous_event_dispatcher', SynchronousEventDispatcher::class)->public();
    $services->alias('test.template.engine', TemplateEngine::class)->public();
};
