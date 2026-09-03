<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Tests\Fixture\BootedTestKernel;
use App\Tests\Fixture\Http\RecordingMercureHub;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Fight\Common\Application\Auth\Security\PasswordHasher;
use Fight\Common\Application\Auth\Security\PasswordValidator;
use Fight\Common\Application\Auth\Security\TokenDecoder;
use Fight\Common\Application\Auth\Security\TokenEncoder;
use Fight\Common\Application\Cache\MutableCache;
use Fight\Common\Application\FileTransfer\FileTransferService;
use Fight\Common\Application\FileStorage\FileStorage;
use Fight\Common\Application\FileStorage\StorageService;
use Fight\Common\Application\Filesystem\Filesystem;
use Fight\Common\Application\HttpClient\HttpService;
use Fight\Common\Application\Mail\MailService;
use Fight\Common\Application\Observability\HealthAggregator;
use Fight\Common\Application\Observability\AuditLog;
use Fight\Common\Application\Observability\MetricsCollector;
use Fight\Common\Application\Process\ProcessBuilder;
use Fight\Common\Application\Process\ProcessRunner;
use Fight\Common\Application\Repository\TransactionalUnitOfWork;
use Fight\Common\Application\Routing\UrlGenerator;
use Fight\Common\Application\Scheduler\Scheduler;
use Fight\Common\Application\Sms\SmsService;
use Fight\Common\Application\Validation\ValidationService;
use Fight\Common\Application\Socket\PrivatePublisher;
use Fight\Common\Application\Socket\Publisher;
use Fight\Common\Application\Templating\TemplateEngine;
use Fight\Common\Domain\Observability\AuditEntry;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class ProviderJourneyTest extends TestCase
{
    use BootedTestKernel;

    public function testBootedNativeProvidersPerformTheirFocusedJourneys(): void
    {
        [$kernel, $container] = $this->bootTestKernel();

        try {
            $hash = $container->get(PasswordHasher::class)->hash('profile-password');
            self::assertTrue($container->get(PasswordValidator::class)->validate('profile-password', $hash));
            self::assertSame(
                'symfony',
                $container->get(ValidationService::class)->validate(
                    ['capability' => 'symfony'],
                    [['field' => 'capability', 'label' => 'Capability', 'rules' => 'required']],
                )->get('capability'),
            );
            self::assertSame('cached', $container->get(MutableCache::class)->read('journey', static fn(): string => 'cached', 60));
            self::assertSame('https://example.test/platform', (string) $container->get(HttpService::class)->createUri('https://example.test/platform'));
            self::assertSame('/', $container->get(UrlGenerator::class)->generate('homepage'));
            self::assertSame('', $container->get(FileTransferService::class)->getTransport('default')->retrieveFileContents('not-configured'));
            self::assertNull($container->get(MailService::class)->createMessage()->getSubject());
            self::assertSame('profile', $container->get(SmsService::class)->createMessage('+15555550100', '+15555550101', 'profile')->getBody());
            self::assertCount(0, $container->get(HealthAggregator::class)->report()->results());
        } finally {
            $kernel->shutdown();
        }
    }

    public function testCredentialBoundTokenProvidersUseApplicationConfiguration(): void
    {
        putenv('FIGHT_COMMON_HMAC_PUBLIC=project-public-key');
        putenv('FIGHT_COMMON_HMAC_PRIVATE=0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef');
        putenv('FIGHT_COMMON_JWT_SECRET=0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef');

        [$kernel, $container] = $this->bootTestKernel();

        try {
            $token = $container->get(TokenEncoder::class)->encode(['subject' => 'platform'], new DateTimeImmutable('+5 minutes'));
            self::assertSame('platform', $container->get(TokenDecoder::class)->decode($token)['subject']);
        } finally {
            $kernel->shutdown();
        }
    }

    public function testBootedFilesystemProcessTemplateObservabilityAndPublicationProvidersBehave(): void
    {
        [$kernel, $container] = $this->bootTestKernel();
        $filesystemPath = sys_get_temp_dir().'/fight-symfony-'.bin2hex(random_bytes(8)).'/journey.txt';
        $storagePath = 'journeys/'.bin2hex(random_bytes(8)).'.txt';

        try {
            $filesystem = $container->get(Filesystem::class);
            $filesystem->put($filesystemPath, 'filesystem-journey');
            self::assertTrue($filesystem->isFile($filesystemPath));
            self::assertSame('filesystem-journey', $filesystem->get($filesystemPath));

            $storage = $container->get(FileStorage::class);
            $container->get(StorageService::class)->getStorage('default')->putFile($storagePath, 'storage-journey');
            self::assertTrue($storage->hasFile($storagePath));
            self::assertSame('storage-journey', $storage->getFileContents($storagePath));

            $output = '';
            $process = ProcessBuilder::create()
                ->prefix('php')
                ->arg('-r')
                ->arg('echo "process-journey";')
                ->stdout(static function (string $chunk) use (&$output): void {
                    $output .= $chunk;
                })
                ->getProcess();
            $runner = $container->get(ProcessRunner::class);
            $runner->attach($process);
            $runner->run();
            self::assertSame('process-journey', $output);

            $runs = 0;
            $filesystem->mkdir($kernel->getContainer()->getParameter('kernel.cache_dir').'/scheduler');
            $scheduler = $container->get(Scheduler::class);
            $scheduler->addJob('provider-journey', static fn(): bool => true, static function () use (&$runs): void {
                ++$runs;
            });
            $scheduler->run();
            self::assertSame(1, $runs);

            $templates = $container->get(TemplateEngine::class);
            self::assertTrue($templates->exists('home/index.html.twig'));
            self::assertTrue($templates->supports('home/index.html.twig'));
            self::assertStringContainsString('Provider Journey', $templates->render(
                'home/index.html.twig',
                ['applicationName' => 'Provider Journey'],
            ));

            $container->get(AuditLog::class)->record(AuditEntry::record('test-suite', 'provider-journey'));
            $metrics = $container->get(MetricsCollector::class);
            $metrics->increment('provider_journey_total');
            $metrics->gauge('provider_journey_active', 1.0);
            $metrics->histogram('provider_journey_seconds', 0.01);
            self::addToAssertionCount(4);

            $container->get(Publisher::class)->push('journey/public', 'public-message');
            $container->get(PrivatePublisher::class)->pushPrivate('journey/private', 'private-message');
            $updates = $container->get(RecordingMercureHub::class)->updates;
            self::assertCount(2, $updates);
            self::assertSame(['journey/public'], $updates[0]->getTopics());
            self::assertSame('public-message', $updates[0]->getData());
            self::assertFalse($updates[0]->isPrivate());
            self::assertSame(['journey/private'], $updates[1]->getTopics());
            self::assertSame('private-message', $updates[1]->getData());
            self::assertTrue($updates[1]->isPrivate());
        } finally {
            if (isset($filesystem)) {
                $filesystem->remove(dirname($filesystemPath));
            }
            if (isset($storage) && $storage->hasFile($storagePath)) {
                $storage->removeFile($storagePath);
            }
            $kernel->shutdown();
        }
    }

    public function testDoctrineTransactionContractCommitsAndRollsBack(): void
    {
        [$kernel, $container] = $this->bootTestKernel();

        try {
            $connection = $container->get('doctrine.dbal.default_connection');
            self::assertInstanceOf(Connection::class, $connection);
            $connection->executeStatement('CREATE TABLE journey_receipts (name VARCHAR(255) NOT NULL)');
            $unitOfWork = $container->get(TransactionalUnitOfWork::class);
            $unitOfWork->commitTransactional(
                static fn($manager): int => $manager->getConnection()->executeStatement(
                    "INSERT INTO journey_receipts (name) VALUES ('committed')",
                ),
            );

            try {
                $unitOfWork->commitTransactional(static function ($manager): never {
                    $manager->getConnection()->executeStatement("INSERT INTO journey_receipts (name) VALUES ('rolled-back')");
                    throw new RuntimeException('rollback proof');
                });
                self::fail('The transaction must propagate the rollback exception.');
            } catch (RuntimeException $exception) {
                self::assertSame('rollback proof', $exception->getMessage());
            }

            self::assertSame(['committed'], $connection->fetchFirstColumn('SELECT name FROM journey_receipts'));
        } finally {
            $kernel->shutdown();
        }
    }
}
