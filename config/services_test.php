<?php

declare(strict_types=1);

use App\Tests\Fixture\Messaging\TestCommandHandler;
use App\Tests\Fixture\Messaging\TestEventMappingProvider;
use App\Tests\Fixture\Messaging\TestEventSubscriber;
use App\Tests\Fixture\Http\JsonJourneyController;
use App\Tests\Fixture\Http\RecordingMercureHub;
use Fight\Common\Adapter\Auth\Hmac\HmacAuthenticator;
use Fight\Common\Adapter\Auth\Hmac\HmacRequestService;
use Fight\Common\Adapter\Auth\Security\JwtDecoder;
use Fight\Common\Adapter\Auth\Security\JwtEncoder;
use Fight\Common\Adapter\Auth\Security\PhpPasswordHasher;
use Fight\Common\Adapter\Auth\Security\PhpPasswordValidator;
use Fight\Common\Adapter\Cache\Psr6\Psr6Cache;
use Fight\Common\Adapter\EventSourcing\Dbal\DbalEventStore;
use Fight\Common\Adapter\FileStorage\FlysystemStorage;
use Fight\Common\Adapter\Filesystem\Symfony\SymfonyFilesystem;
use Fight\Common\Adapter\FileTransfer\Null\NullFileTransport;
use Fight\Common\Adapter\HttpClient\Guzzle\GuzzleClient;
use Fight\Common\Adapter\HttpClient\Guzzle\GuzzleMessageFactory;
use Fight\Common\Adapter\HttpClient\Guzzle\GuzzleStreamFactory;
use Fight\Common\Adapter\HttpClient\Guzzle\GuzzleUriFactory;
use Fight\Common\Adapter\Mail\Null\NullMailTransport;
use Fight\Common\Adapter\Mail\Symfony\SymfonyMailFactory;
use Fight\Common\Adapter\Messaging\Command\Sync\CommandPipeline;
use Fight\Common\Adapter\Messaging\Event\Sync\ServiceAwareEventDispatcher;
use Fight\Common\Adapter\Messaging\Query\QueryPipeline;
use Fight\Common\Adapter\Messaging\Symfony\MessengerCommandBus;
use Fight\Common\Adapter\Messaging\Symfony\MessengerEventDispatcher;
use Fight\Common\Adapter\Observability\Audit\NullAuditLog;
use Fight\Common\Adapter\Observability\Health\HealthReporter;
use Fight\Common\Adapter\Observability\Metrics\NullMetricsCollector;
use Fight\Common\Adapter\Persistence\Doctrine\DoctrineTransactionalUnitOfWork;
use Fight\Common\Adapter\Process\Symfony\SymfonyProcessRunner;
use Fight\Common\Adapter\Routing\Symfony\SymfonyUrlGenerator;
use Fight\Common\Adapter\Sms\Null\NullSmsTransport;
use Fight\Common\Adapter\Templating\TwigEngine;
use Fight\Common\Application\Auth\Authenticator;
use Fight\Common\Application\Auth\RequestService;
use Fight\Common\Application\Auth\Security\PasswordHasher;
use Fight\Common\Application\Auth\Security\PasswordValidator;
use Fight\Common\Application\Auth\Security\TokenDecoder;
use Fight\Common\Application\Auth\Security\TokenEncoder;
use Fight\Common\Application\Cache\Cache;
use Fight\Common\Application\Cache\MutableCache;
use Fight\Common\Application\FileStorage\FileStorage;
use Fight\Common\Application\Filesystem\Filesystem;
use Fight\Common\Application\FileTransfer\Transport\FileTransport;
use Fight\Common\Application\HttpClient\Message\MessageFactory;
use Fight\Common\Application\HttpClient\Message\StreamFactory;
use Fight\Common\Application\HttpClient\Message\UriFactory;
use Fight\Common\Application\HttpClient\Transport\HttpClient;
use Fight\Common\Application\Mail\Message\MailFactory;
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
use Fight\Common\Application\Sms\Message\SmsFactory;
use Fight\Common\Application\Sms\Transport\SmsTransport;
use Fight\Common\Application\Socket\PrivatePublisher;
use Fight\Common\Application\Socket\Publisher;
use Fight\Common\Application\Templating\TemplateEngine;
use Fight\Common\Domain\EventSourcing\EventStore;
use Fight\Common\Domain\Serialization\Serializer;
use Fight\Common\Application\Serialization\JsonSerializer;
use Fight\Common\Application\Sms\SmsService;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure()->public();
    $services->set(JsonJourneyController::class);
    $services->set(TestCommandHandler::class)->tag('common.command_handler');
    $services->set(TestEventSubscriber::class)->tag('common.event_subscriber');
    $services->set(TestEventMappingProvider::class)->tag('common.event_mapping_provider');
    $services->set(RecordingMercureHub::class);
    $services->alias(HubInterface::class, RecordingMercureHub::class)->public();

    foreach ([
        Authenticator::class => HmacAuthenticator::class,
        RequestService::class => HmacRequestService::class,
        PasswordHasher::class => PhpPasswordHasher::class,
        PasswordValidator::class => PhpPasswordValidator::class,
        TokenEncoder::class => JwtEncoder::class,
        TokenDecoder::class => JwtDecoder::class,
        Cache::class => Psr6Cache::class,
        MutableCache::class => Psr6Cache::class,
        FileStorage::class => FlysystemStorage::class,
        Filesystem::class => SymfonyFilesystem::class,
        FileTransport::class => NullFileTransport::class,
        HttpClient::class => GuzzleClient::class,
        MessageFactory::class => GuzzleMessageFactory::class,
        StreamFactory::class => GuzzleStreamFactory::class,
        UriFactory::class => GuzzleUriFactory::class,
        MailFactory::class => SymfonyMailFactory::class,
        MailTransport::class => NullMailTransport::class,
        CommandBus::class => CommandPipeline::class,
        SynchronousCommandBus::class => CommandPipeline::class,
        AsynchronousCommandBus::class => MessengerCommandBus::class,
        EventDispatcher::class => ServiceAwareEventDispatcher::class,
        SynchronousEventDispatcher::class => ServiceAwareEventDispatcher::class,
        AsynchronousEventDispatcher::class => MessengerEventDispatcher::class,
        QueryBus::class => QueryPipeline::class,
        AuditLog::class => NullAuditLog::class,
        HealthAggregator::class => HealthReporter::class,
        MetricsCollector::class => NullMetricsCollector::class,
        ProcessRunner::class => SymfonyProcessRunner::class,
        TransactionalUnitOfWork::class => DoctrineTransactionalUnitOfWork::class,
        UrlGenerator::class => SymfonyUrlGenerator::class,
        SmsFactory::class => SmsService::class,
        SmsTransport::class => NullSmsTransport::class,
        Publisher::class => \Fight\Common\Adapter\Socket\MercureHubPublisher::class,
        PrivatePublisher::class => \Fight\Common\Adapter\Socket\PrivateMercureHubPublisher::class,
        TemplateEngine::class => TwigEngine::class,
        EventStore::class => DbalEventStore::class,
        Serializer::class => JsonSerializer::class,
    ] as $alias => $target) {
        $services->alias($alias, $target)->public();
    }

    foreach ([
        \Fight\Common\Application\FileStorage\StorageService::class,
        \Fight\Common\Application\FileTransfer\FileTransferService::class,
        \Fight\Common\Application\HttpClient\HttpService::class,
        \Fight\Common\Application\Mail\MailService::class,
        \Fight\Common\Application\Scheduler\Scheduler::class,
        SmsService::class,
        \Fight\Common\Application\Validation\ValidationService::class,
        \Fight\Common\Domain\EventSourcing\EventMapper::class,
    ] as $service) {
        $services->alias('test.retain.'.$service, $service)->public();
    }
};
