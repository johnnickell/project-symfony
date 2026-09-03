<?php

declare(strict_types=1);

use App\Tests\Fixture\Messaging\TestCommandHandler;
use App\Tests\Fixture\Messaging\TestCommandFilter;
use App\Tests\Fixture\Messaging\TestEventSubscriber;
use App\Tests\Fixture\Messaging\TestQueryFilter;
use App\Tests\Fixture\Messaging\TestQueryHandler;
use App\Tests\Fixture\Http\JsonJourneyController;
use App\Tests\Fixture\Http\RecordingMercureHub;
use App\Tests\Fixture\Templating\TestTemplateHelper;
use Fight\Common\Application\Auth\Authenticator;
use Fight\Common\Application\Auth\RequestService;
use Fight\Common\Application\Auth\Security\PasswordHasher;
use Fight\Common\Application\Auth\Security\PasswordValidator;
use Fight\Common\Application\Auth\Security\TokenDecoder;
use Fight\Common\Application\Auth\Security\TokenEncoder;
use Fight\Common\Application\Cache\Cache;
use Fight\Common\Application\Cache\MutableCache;
use Fight\Common\Application\FileStorage\FileStorage;
use Fight\Common\Application\FileStorage\StorageService;
use Fight\Common\Application\Filesystem\Filesystem;
use Fight\Common\Application\FileTransfer\FileTransferService;
use Fight\Common\Application\FileTransfer\Transport\FileTransport;
use Fight\Common\Application\HttpClient\HttpService;
use Fight\Common\Application\HttpClient\Message\MessageFactory;
use Fight\Common\Application\HttpClient\Message\StreamFactory;
use Fight\Common\Application\HttpClient\Message\UriFactory;
use Fight\Common\Application\HttpClient\Transport\HttpClient;
use Fight\Common\Application\Mail\Message\MailFactory;
use Fight\Common\Application\Mail\MailService;
use Fight\Common\Application\Mail\Transport\MailTransport;
use Fight\Common\Application\Messaging\Command\AsynchronousCommandBus;
use Fight\Common\Application\Messaging\Command\CommandBus;
use Fight\Common\Application\Messaging\Command\SynchronousCommandBus;
use Fight\Common\Application\Messaging\Event\AsynchronousEventDispatcher;
use Fight\Common\Application\Messaging\Event\EventDispatcher;
use Fight\Common\Application\Messaging\Event\SynchronousEventDispatcher;
use Fight\Common\Application\Messaging\Query\QueryBus;
use Fight\Common\Application\Observability\AuditLog;
use Fight\Common\Application\Observability\HealthAggregator;
use Fight\Common\Application\Observability\MetricsCollector;
use Fight\Common\Application\Process\ProcessRunner;
use Fight\Common\Application\Repository\TransactionalUnitOfWork;
use Fight\Common\Application\Routing\UrlGenerator;
use Fight\Common\Application\Scheduler\Scheduler;
use Fight\Common\Application\Sms\Message\SmsFactory;
use Fight\Common\Application\Sms\Transport\SmsTransport;
use Fight\Common\Application\Socket\PrivatePublisher;
use Fight\Common\Application\Socket\Publisher;
use Fight\Common\Application\Templating\TemplateEngine;
use Fight\Common\Application\Validation\ValidationService;
use Fight\Common\Domain\Serialization\Serializer;
use Fight\Common\Application\Sms\SmsService;
use Symfony\Component\Mercure\HubInterface;
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
    $services->set(RecordingMercureHub::class)->public();
    $services->set(TestTemplateHelper::class);
    $services->alias(HubInterface::class, RecordingMercureHub::class)->public();
    $services->alias('test.fixture.'.TestCommandFilter::class, TestCommandFilter::class)->public();
    $services->alias('test.fixture.'.TestQueryFilter::class, TestQueryFilter::class)->public();

    foreach ([
        Authenticator::class, RequestService::class, PasswordHasher::class, PasswordValidator::class,
        TokenEncoder::class, TokenDecoder::class, Cache::class, MutableCache::class, FileStorage::class,
        Filesystem::class, FileTransport::class, HttpClient::class, MessageFactory::class, StreamFactory::class,
        UriFactory::class, MailFactory::class, MailTransport::class, CommandBus::class,
        SynchronousCommandBus::class, AsynchronousCommandBus::class, EventDispatcher::class,
        SynchronousEventDispatcher::class, AsynchronousEventDispatcher::class, QueryBus::class,
        AuditLog::class, HealthAggregator::class, MetricsCollector::class, ProcessRunner::class,
        TransactionalUnitOfWork::class, UrlGenerator::class, SmsFactory::class, SmsTransport::class,
        Publisher::class, PrivatePublisher::class, TemplateEngine::class, Serializer::class,
    ] as $contract) {
        $services->alias('test.contract.'.$contract, $contract)->public();
    }

    foreach ([
        StorageService::class,
        FileTransferService::class,
        HttpService::class,
        MailService::class,
        Scheduler::class,
        SmsService::class,
        ValidationService::class,
    ] as $service) {
        $services->alias('test.service.'.$service, $service)->public();
    }
};
