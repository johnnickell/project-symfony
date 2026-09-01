<?php

declare(strict_types=1);

namespace App\Composition\FrameworkSupport;

use Fight\Common\Application\Cache\Cache;
use Fight\Common\Application\Auth\Security\PasswordHasher;
use Fight\Common\Application\Auth\Security\PasswordValidator;
use Fight\Common\Application\FileStorage\StorageService;
use Fight\Common\Application\FileTransfer\FileTransferService;
use Fight\Common\Application\Filesystem\Filesystem;
use Fight\Common\Application\HttpClient\HttpService;
use Fight\Common\Application\Mail\MailService;
use Fight\Common\Application\Messaging\Command\AsynchronousCommandBus;
use Fight\Common\Application\Messaging\Command\SynchronousCommandBus;
use Fight\Common\Application\Messaging\Event\AsynchronousEventDispatcher;
use Fight\Common\Application\Messaging\Event\SynchronousEventDispatcher;
use Fight\Common\Application\Messaging\Query\QueryBus;
use Fight\Common\Application\Observability\AuditLog;
use Fight\Common\Application\Observability\HealthAggregator;
use Fight\Common\Application\Observability\MetricsCollector;
use Fight\Common\Application\Process\ProcessRunner;
use Fight\Common\Application\Repository\TransactionalUnitOfWork;
use Fight\Common\Application\Routing\UrlGenerator;
use Fight\Common\Application\Scheduler\Scheduler;
use Fight\Common\Application\Sms\SmsService;
use Fight\Common\Application\Templating\TemplateEngine;
use Fight\Common\Application\Validation\ValidationService;
use Fight\Common\Domain\EventSourcing\EventStore;

/**
 * The default Common services supplied by this Symfony composition root.
 *
 * Application code depends on the public contracts; this catalog gives the
 * integration suite one booted-container proof that those defaults resolve.
 */
final readonly class PlatformProfile
{
    public function __construct(
        public PasswordHasher $passwordHasher,
        public PasswordValidator $passwordValidator,
        public ValidationService $validation,
        public SynchronousCommandBus $synchronousCommands,
        public AsynchronousCommandBus $asynchronousCommands,
        public QueryBus $queries,
        public SynchronousEventDispatcher $synchronousEvents,
        public AsynchronousEventDispatcher $asynchronousEvents,
        public Cache $cache,
        public TransactionalUnitOfWork $unitOfWork,
        public EventStore $eventStore,
        public Filesystem $filesystem,
        public StorageService $storage,
        public FileTransferService $fileTransfer,
        public HttpService $http,
        public ProcessRunner $processes,
        public Scheduler $scheduler,
        public UrlGenerator $routing,
        public MailService $mail,
        public SmsService $sms,
        public TemplateEngine $templates,
        public HealthAggregator $health,
        public AuditLog $audit,
        public MetricsCollector $metrics,
    ) {
    }
}
