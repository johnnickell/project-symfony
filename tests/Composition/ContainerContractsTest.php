<?php

declare(strict_types=1);

namespace App\Tests\Composition;

use App\Tests\Fixture\BootedTestKernel;
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
use Fight\Common\Application\Mail\MailService;
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
use Fight\Common\Application\Scheduler\Scheduler;
use Fight\Common\Application\Sms\Message\SmsFactory;
use Fight\Common\Application\Sms\SmsService;
use Fight\Common\Application\Sms\Transport\SmsTransport;
use Fight\Common\Application\Socket\PrivatePublisher;
use Fight\Common\Application\Socket\Publisher;
use Fight\Common\Application\Templating\TemplateEngine;
use Fight\Common\Application\Validation\ValidationService;
use Fight\Common\Domain\EventSourcing\EventMapper;
use Fight\Common\Domain\EventSourcing\EventStore;
use Fight\Common\Domain\Serialization\Serializer;
use PHPUnit\Framework\TestCase;

final class ContainerContractsTest extends TestCase
{
    use BootedTestKernel;

    public function testEveryConfiguredFightContractResolvesDirectlyFromTheTestContainer(): void
    {
        putenv('FIGHT_COMMON_HMAC_PUBLIC=project-public-key');
        putenv('FIGHT_COMMON_HMAC_PRIVATE=0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef');
        putenv('FIGHT_COMMON_JWT_SECRET=0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef');
        putenv('MERCURE_URL=https://mercure.example.test/.well-known/mercure');
        putenv('MERCURE_PUBLIC_URL=https://mercure.example.test/.well-known/mercure');
        putenv('MERCURE_JWT_TOKEN=test-token');

        [$kernel, $container] = $this->bootTestKernel();

        try {
            $contracts = [
                Authenticator::class, RequestService::class, PasswordHasher::class, PasswordValidator::class,
                TokenEncoder::class, TokenDecoder::class, Cache::class, MutableCache::class, FileStorage::class,
                StorageService::class, Filesystem::class, FileTransferService::class, FileTransport::class,
                HttpService::class, HttpClient::class, MessageFactory::class, StreamFactory::class, UriFactory::class,
                MailService::class, MailFactory::class, MailTransport::class, CommandBus::class,
                SynchronousCommandBus::class, AsynchronousCommandBus::class, EventDispatcher::class,
                SynchronousEventDispatcher::class, AsynchronousEventDispatcher::class, QueryBus::class,
                AuditLog::class, HealthAggregator::class, MetricsCollector::class, ProcessRunner::class,
                TransactionalUnitOfWork::class, UrlGenerator::class, Scheduler::class, SmsFactory::class,
                SmsService::class, SmsTransport::class, Publisher::class, PrivatePublisher::class,
                TemplateEngine::class, ValidationService::class, EventMapper::class, EventStore::class,
                Serializer::class,
            ];

            foreach ($contracts as $contract) {
                self::assertInstanceOf($contract, $container->get($contract), $contract);
            }
        } finally {
            $kernel->shutdown();
        }
    }
}
