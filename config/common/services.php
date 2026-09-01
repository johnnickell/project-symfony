<?php

declare(strict_types=1);

use Fight\Common\Domain\EventSourcing\EventMapper;
use Fight\Common\Domain\EventSourcing\EventMappingProvider;
use App\Composition\FrameworkSupport\PlatformProfile;
use Fight\Common\Application\Repository\TransactionalUnitOfWork;
use Fight\Common\Application\Routing\UrlGenerator;
use Fight\Common\Application\Serialization\JsonSerializer;
use Fight\Common\Adapter\Messaging\Symfony\Serializer\SymfonyMessageSerializer;
use Fight\Common\Adapter\Persistence\Doctrine\DoctrineTransactionalUnitOfWork;
use Fight\Common\Adapter\Routing\Symfony\SymfonyUrlGenerator;
use Fight\Common\Domain\Serialization\Serializer;
use App\Composition\EventSourcing\ProjectEventMappingProvider;
use Doctrine\DBAL\Connection;
use Fight\Common\Adapter\Cache\Psr6\Psr6Cache;
use Fight\Common\Adapter\FileTransfer\Null\NullFileTransport;
use Fight\Common\Adapter\EventSourcing\Dbal\DbalEventStore;
use Fight\Common\Adapter\FileStorage\FlysystemStorage;
use Fight\Common\Adapter\Filesystem\Symfony\SymfonyFilesystem;
use Fight\Common\Adapter\HttpClient\Guzzle\GuzzleClient;
use Fight\Common\Adapter\HttpClient\Guzzle\GuzzleMessageFactory;
use Fight\Common\Adapter\HttpClient\Guzzle\GuzzleStreamFactory;
use Fight\Common\Adapter\HttpClient\Guzzle\GuzzleUriFactory;
use Fight\Common\Adapter\Http\Symfony\Controller\ErrorController;
use Fight\Common\Adapter\Http\Symfony\EventSubscriber\SymfonyExceptionSubscriber;
use Fight\Common\Adapter\Http\Symfony\EventSubscriber\SymfonyValidationSubscriber;
use Fight\Common\Adapter\Mail\Null\NullMailTransport;
use Fight\Common\Adapter\Mail\Symfony\SymfonyMailFactory;
use Fight\Common\Adapter\Mail\Symfony\SymfonyMailTransport;
use Fight\Common\Adapter\Messaging\Command\Sync\CommandPipeline;
use Fight\Common\Adapter\Messaging\Command\Sync\Routing\CommandRouter;
use Fight\Common\Adapter\Messaging\Command\Sync\Routing\ServiceAwareCommandRouter;
use Fight\Common\Adapter\Messaging\Command\Sync\RoutingCommandBus;
use Fight\Common\Adapter\Messaging\Event\Sync\ServiceAwareEventDispatcher;
use Fight\Common\Adapter\Messaging\Query\QueryPipeline;
use Fight\Common\Adapter\Messaging\Query\Routing\QueryRouter;
use Fight\Common\Adapter\Messaging\Query\Routing\ServiceAwareQueryRouter;
use Fight\Common\Adapter\Messaging\Query\RoutingQueryBus;
use Fight\Common\Adapter\Messaging\Symfony\MessengerCommandBus;
use Fight\Common\Adapter\Messaging\Symfony\MessengerEventDispatcher;
use Fight\Common\Adapter\Observability\Audit\NullAuditLog;
use Fight\Common\Adapter\Observability\Health\HealthReporter;
use Fight\Common\Adapter\Observability\Metrics\NullMetricsCollector;
use Fight\Common\Adapter\Process\Symfony\SymfonyProcessRunner;
use Fight\Common\Adapter\Sms\Null\NullSmsTransport;
use Fight\Common\Adapter\Templating\PhpEngine;
use Fight\Common\Adapter\Templating\TwigEngine;
use Fight\Common\Application\FileStorage\StorageService;
use Fight\Common\Application\FileTransfer\FileTransferService;
use Fight\Common\Application\FileTransfer\Transport\FileTransport;
use Fight\Common\Application\HttpClient\HttpService;
use Fight\Common\Application\HttpClient\Message\MessageFactory;
use Fight\Common\Application\HttpClient\Message\StreamFactory;
use Fight\Common\Application\HttpClient\Message\UriFactory;
use Fight\Common\Application\Mail\MailService;
use Fight\Common\Application\Messaging\Command\AsynchronousCommandBus;
use Fight\Common\Application\Messaging\Command\SynchronousCommandBus;
use Fight\Common\Application\Messaging\Event\AsynchronousEventDispatcher;
use Fight\Common\Application\Messaging\Event\SynchronousEventDispatcher;
use Fight\Common\Application\Messaging\Query\QueryBus;
use Fight\Common\Application\Scheduler\Scheduler;
use Fight\Common\Application\Sms\Message\SmsFactory;
use Fight\Common\Application\Sms\SmsService;
use Fight\Common\Application\Validation\ValidationService;
use Fight\Common\Application\Cache\Cache;
use Fight\Common\Application\FileStorage\FileStorage;
use Fight\Common\Application\Filesystem\Filesystem;
use Fight\Common\Application\HttpClient\Transport\HttpClient;
use Fight\Common\Application\Mail\Message\MailFactory;
use Fight\Common\Application\Mail\Transport\MailTransport;
use Fight\Common\Application\Observability\AuditLog;
use Fight\Common\Application\Observability\HealthAggregator;
use Fight\Common\Application\Observability\MetricsCollector;
use Fight\Common\Application\Process\ProcessRunner;
use Fight\Common\Application\Sms\Transport\SmsTransport;
use Fight\Common\Application\Templating\TemplateEngine;
use Fight\Common\Domain\Value\DateTime\Timezone;
use Fight\Common\Domain\EventSourcing\EventStore;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->instanceof(EventMappingProvider::class)
        ->tag('common.event_mapping_provider');
    $services->set(ProjectEventMappingProvider::class)
        ->tag('common.event_mapping_provider');
    $services->set(EventMapper::class)
        ->arg('$providers', [])
        ->public();
    $services->set(PlatformProfile::class)->public();
    $services->set(JsonSerializer::class);
    $services->alias(Serializer::class, JsonSerializer::class);
    $services->set(SymfonyMessageSerializer::class);
    $services->set(MessengerCommandBus::class)
        ->arg('$sender', service('messenger.transport.fight_common_async'));
    $services->alias(AsynchronousCommandBus::class, MessengerCommandBus::class);
    $services->set(MessengerEventDispatcher::class)
        ->arg('$sender', service('messenger.transport.fight_common_async'));
    $services->alias(AsynchronousEventDispatcher::class, MessengerEventDispatcher::class);
    $services->alias(SenderInterface::class, 'messenger.transport.fight_common_async');
    $services->set(ServiceAwareCommandRouter::class)
        ->arg('$container', service('service_container'))
        ->public();
    $services->alias(CommandRouter::class, ServiceAwareCommandRouter::class);
    $services->set(RoutingCommandBus::class);
    $services->set(CommandPipeline::class)
        ->arg('$commandBus', service(RoutingCommandBus::class));
    $services->alias(SynchronousCommandBus::class, CommandPipeline::class);
    $services->set(ServiceAwareQueryRouter::class)
        ->arg('$container', service('service_container'))
        ->public();
    $services->alias(QueryRouter::class, ServiceAwareQueryRouter::class);
    $services->set(RoutingQueryBus::class);
    $services->set(QueryPipeline::class)
        ->arg('$queryBus', service(RoutingQueryBus::class));
    $services->alias(QueryBus::class, QueryPipeline::class);
    $services->set(ServiceAwareEventDispatcher::class)
        ->arg('$container', service('service_container'))
        ->public();
    $services->alias(SynchronousEventDispatcher::class, ServiceAwareEventDispatcher::class);
    $services->set(SymfonyUrlGenerator::class)
        ->arg('$urlGenerator', service(UrlGeneratorInterface::class))
        ->public();
    $services->alias(UrlGenerator::class, SymfonyUrlGenerator::class);
    $services->set(DoctrineTransactionalUnitOfWork::class);
    $services->alias(TransactionalUnitOfWork::class, DoctrineTransactionalUnitOfWork::class);
    $services->set(Psr6Cache::class);
    $services->alias(Cache::class, Psr6Cache::class);
    $services->set(CacheItemPoolInterface::class, FilesystemAdapter::class)
        ->arg('$namespace', 'fight-common')
        ->arg('$defaultLifetime', 0)
        ->arg('$directory', '%kernel.cache_dir%/fight-common');
    $services->set(SymfonyFilesystem::class);
    $services->alias(Filesystem::class, SymfonyFilesystem::class);
    $services->set(LocalFilesystemAdapter::class)
        ->arg('$location', '%kernel.project_dir%/var/storage');
    $services->set(FilesystemOperator::class, Flysystem::class)
        ->arg('$adapter', service(LocalFilesystemAdapter::class));
    $services->set(FlysystemStorage::class);
    $services->alias(FileStorage::class, FlysystemStorage::class);
    $services->set(StorageService::class)
        ->call('addStorage', ['default', service(FlysystemStorage::class)]);
    $services->set(NullFileTransport::class);
    $services->alias(FileTransport::class, NullFileTransport::class);
    $services->set(FileTransferService::class)
        ->call('addTransport', ['default', service(NullFileTransport::class)]);
    $services->set(ClientInterface::class, Client::class);
    $services->set(GuzzleClient::class);
    $services->set(GuzzleMessageFactory::class);
    $services->set(GuzzleStreamFactory::class);
    $services->set(GuzzleUriFactory::class);
    $services->set(HttpService::class)
        ->arg('$httpClient', service(GuzzleClient::class))
        ->arg('$messageFactory', service(GuzzleMessageFactory::class))
        ->arg('$streamFactory', service(GuzzleStreamFactory::class))
        ->arg('$uriFactory', service(GuzzleUriFactory::class));
    $services->alias(HttpClient::class, GuzzleClient::class);
    $services->alias(MessageFactory::class, GuzzleMessageFactory::class);
    $services->alias(StreamFactory::class, GuzzleStreamFactory::class);
    $services->alias(UriFactory::class, GuzzleUriFactory::class);
    $services->set(SymfonyProcessRunner::class);
    $services->alias(ProcessRunner::class, SymfonyProcessRunner::class);
    $services->set(Timezone::class)->arg('$timezone', 'UTC');
    $services->set(Scheduler::class)
        ->factory([Scheduler::class, 'withProcessRunner'])
        ->arg('$timezone', service(Timezone::class))
        ->arg('$tempDirectory', '%kernel.cache_dir%/scheduler')
        ->arg('$processRunner', service(SymfonyProcessRunner::class))
        ->arg('$mailService', service(MailService::class));
    $services->set(SymfonyMailFactory::class);
    $services->set(SymfonyMailTransport::class);
    $services->set(NullMailTransport::class);
    $services->set(MailService::class)
        ->arg('$transport', service(NullMailTransport::class))
        ->arg('$factory', service(SymfonyMailFactory::class));
    $services->alias(MailFactory::class, SymfonyMailFactory::class);
    $services->alias(MailTransport::class, NullMailTransport::class);
    $services->set(NullSmsTransport::class);
    $services->set(SmsService::class)
        ->arg('$transport', service(NullSmsTransport::class));
    $services->alias(SmsFactory::class, SmsService::class);
    $services->alias(SmsTransport::class, NullSmsTransport::class);
    $services->set(NullMetricsCollector::class);
    $services->alias(MetricsCollector::class, NullMetricsCollector::class);
    $services->set(NullAuditLog::class);
    $services->alias(AuditLog::class, NullAuditLog::class);
    $services->set(HealthReporter::class);
    $services->alias(HealthAggregator::class, HealthReporter::class);
    $services->set(TwigEngine::class);
    $services->set(PhpEngine::class)
        ->arg('$paths', ['%kernel.project_dir%/templates']);
    $services->alias(TemplateEngine::class, TwigEngine::class);
    $services->set(DbalEventStore::class)
        ->arg('$connection', service('doctrine.dbal.default_connection'));
    $services->alias(EventStore::class, DbalEventStore::class);
    $services->set(ValidationService::class);
    $services->set(ErrorController::class);
    $services->set(SymfonyValidationSubscriber::class);
    $services->set(SymfonyExceptionSubscriber::class);
};
