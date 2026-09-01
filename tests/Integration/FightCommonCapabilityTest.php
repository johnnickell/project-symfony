<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Composition\EventSourcing\ProjectCapabilityActivated;
use App\Composition\FrameworkSupport\PlatformProfile;
use App\Composition\FrameworkSupport\SecurityProfile;
use App\Composition\FrameworkSupport\ReceiptCanonicalizer;
use App\Kernel;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Fight\Common\Adapter\Http\Symfony\JSendResponse;
use Fight\Common\Adapter\Messaging\Symfony\MessengerCommandBus;
use Fight\Common\Adapter\Messaging\Symfony\MessengerEventDispatcher;
use Fight\Common\Adapter\Messaging\Symfony\Serializer\SymfonyMessageSerializer;
use Fight\Common\Adapter\Middleware\Symfony\JsonRequestMiddleware;
use Fight\Common\Adapter\Persistence\Doctrine\DoctrineTransactionalUnitOfWork;
use Fight\Common\Adapter\Routing\Symfony\SymfonyUrlGenerator;
use Fight\Common\Application\Serialization\JsonSerializer;
use Fight\Common\Domain\EventSourcing\EventMapper;
use Fight\Common\Domain\Messaging\Command\Command;
use Fight\Common\Domain\Messaging\Command\CommandMessage;
use Fight\Common\Domain\Messaging\Event\Event;
use Fight\Common\Domain\Messaging\Event\EventMessage;
use Fight\Common\Domain\Messaging\MessageId;
use Fight\Common\Domain\Messaging\Meta;
use Fight\Common\Domain\Type\Arrayable;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\BusNameStamp;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;

final class FightCommonCapabilityTest extends TestCase
{
    #[RunInSeparateProcess]
    public function testCredentialBoundSecurityAndPrivatePublicationPortsResolveFromApplicationConfiguration(): void
    {
        putenv('FIGHT_COMMON_HMAC_PUBLIC=project-public-key');
        putenv('FIGHT_COMMON_HMAC_PRIVATE=0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef');
        putenv('FIGHT_COMMON_JWT_SECRET=0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef');
        putenv('MERCURE_URL=https://mercure.example.test/.well-known/mercure');
        putenv('MERCURE_PUBLIC_URL=https://mercure.example.test/.well-known/mercure');
        putenv('MERCURE_JWT_TOKEN=eyJhbGciOiJIUzI1NiJ9.eyJtZXJjdXJlIjp7fX0.signature');

        $kernel = new Kernel('test', true);

        try {
            $kernel->boot();
            $profile = $kernel->getContainer()->get(SecurityProfile::class);
            $token = $profile->tokenEncoder->encode(['subject' => 'platform'], new DateTimeImmutable('+5 minutes'));

            self::assertSame('platform', $profile->tokenDecoder->decode($token)['subject']);
            self::assertInstanceOf(SecurityProfile::class, $profile);
        } finally {
            $kernel->shutdown();
        }
    }

    #[RunInSeparateProcess]
    public function testTheBootedKernelProvidesTheCompleteDefaultPlatformProfile(): void
    {
        $kernel = new Kernel('test', true);

        try {
            $kernel->boot();
            $profile = $kernel->getContainer()->get(PlatformProfile::class);

            $hash = $profile->passwordHasher->hash('profile-password');
            self::assertTrue($profile->passwordValidator->validate('profile-password', $hash));
            self::assertTrue($profile->validation->validate(
                ['capability' => 'symfony'],
                [['field' => 'capability', 'label' => 'Capability', 'rules' => 'required']],
            )->has('capability'));
            self::assertSame('https://example.test/platform', (string) $profile->http->createUri('https://example.test/platform'));
            self::assertSame('/', $profile->routing->generate('homepage'));
            self::assertSame('', $profile->fileTransfer->getTransport('default')->retrieveFileContents('not-configured'));
            self::assertNull($profile->mail->createMessage()->getSubject());
            self::assertCount(0, $profile->health->report()->results());
        } finally {
            $kernel->shutdown();
        }
    }

    #[RunInSeparateProcess]
    public function testTheBootedKernelCollectsTheProjectEventMappingProvider(): void
    {
        $kernel = new Kernel('test', true);

        try {
            $kernel->boot();
            $mapper = $kernel->getContainer()->get(EventMapper::class);
            self::assertInstanceOf(EventMapper::class, $mapper);

            $mapped = $mapper->map(EventMessage::create(new ProjectCapabilityActivated('messenger')));

            self::assertSame('project.capability-activated', $mapped->eventName());
            self::assertSame(['capability' => 'messenger'], $mapped->data());
        } finally {
            $kernel->shutdown();
        }
    }

    public function testMessengerDispatchAndSerializationPreserveCommandAndEventEnvelopes(): void
    {
        $sender = new CapturingSender();
        $command = new ProjectCommand('enable-messenger');
        $event = new ProjectEvent('messenger-enabled');
        $commandMessage = new CommandMessage(
            MessageId::generate(),
            new DateTimeImmutable('2026-08-31 12:00:00.000000+00:00'),
            $command,
            Meta::create(['journey' => 'latest']),
        );
        $eventMessage = new EventMessage(
            MessageId::generate(),
            new DateTimeImmutable('2026-08-31 12:00:01.000000+00:00'),
            $event,
            Meta::create(['journey' => 'latest']),
        );

        (new MessengerCommandBus($sender))->dispatch($commandMessage);
        (new MessengerEventDispatcher($sender))->dispatch($eventMessage);

        self::assertSame($commandMessage, $sender->sent[0]->getMessage());
        self::assertSame($eventMessage, $sender->sent[1]->getMessage());

        $serializer = new SymfonyMessageSerializer(new JsonSerializer());
        foreach ([$commandMessage, $eventMessage] as $message) {
            $envelope = new Envelope($message, [new BusNameStamp('project.capabilities')]);
            $decoded = $serializer->decode($serializer->encode($envelope));

            self::assertSame($message->toArray(), $decoded->getMessage()->toArray());
            self::assertSame('project.capabilities', $decoded->last(BusNameStamp::class)?->getBusName());
        }
    }

    public function testJsonRequestMiddlewareAndJSendResponseCreateNativeHttpValues(): void
    {
        $captured = [];
        $kernel = new class ($captured) implements HttpKernelInterface {
            /** @var array<string, mixed> */
            public array $captured;

            /** @param array<string, mixed> $captured */
            public function __construct(array $captured)
            {
                $this->captured = $captured;
            }

            public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true): Response
            {
                $this->captured = $request->request->all();

                return JSendResponse::success(new class implements Arrayable {
                    public function toArray(): array
                    {
                        return ['accepted' => true];
                    }
                });
            }
        };
        $request = Request::create('/capabilities', 'POST', [], [], [], ['CONTENT_TYPE' => 'application/json'], '{"capability":"messenger"}');

        $response = (new JsonRequestMiddleware($kernel))->handle($request);

        self::assertSame(['capability' => 'messenger'], $kernel->captured);
        self::assertSame(['status' => 'success', 'data' => ['accepted' => true]], json_decode((string) $response->getContent(), true));
        self::assertSame('application/json', $response->headers->get('Content-Type'));
    }

    #[RunInSeparateProcess]
    public function testBootedRoutingAndDoctrineTransactionsCommitAndRollBack(): void
    {
        $kernel = new Kernel('test', true);

        try {
            $kernel->boot();
            $container = $kernel->getContainer();
            $urlGenerator = $container->get(SymfonyUrlGenerator::class);
            self::assertInstanceOf(SymfonyUrlGenerator::class, $urlGenerator);
            self::assertSame('/', $urlGenerator->generate('homepage'));

            $entityManager = $container->get('doctrine.orm.entity_manager');
            self::assertInstanceOf(EntityManagerInterface::class, $entityManager);
            $connection = $entityManager->getConnection();
            $connection->executeStatement('CREATE TABLE journey_receipts (name VARCHAR(255) NOT NULL)');
            $unitOfWork = new DoctrineTransactionalUnitOfWork($entityManager);
            $unitOfWork->commitTransactional(
                static fn(EntityManagerInterface $manager): int => $manager->getConnection()->executeStatement(
                    "INSERT INTO journey_receipts (name) VALUES ('committed')",
                ),
            );

            try {
                $unitOfWork->commitTransactional(
                    static function (EntityManagerInterface $manager): never {
                        $manager->getConnection()->executeStatement("INSERT INTO journey_receipts (name) VALUES ('rolled-back')");

                        throw new RuntimeException('rollback proof');
                    },
                );
                self::fail('The transactional journey must propagate the rollback exception.');
            } catch (RuntimeException $exception) {
                self::assertSame('rollback proof', $exception->getMessage());
            }

            self::assertSame(['committed'], $connection->fetchFirstColumn('SELECT name FROM journey_receipts'));
        } finally {
            $kernel->shutdown();
        }
    }
}

final class CapturingSender implements SenderInterface
{
    /** @var list<Envelope> */
    public array $sent = [];

    public function send(Envelope $envelope): Envelope
    {
        $this->sent[] = $envelope;

        return $envelope;
    }
}

final readonly class ProjectCommand implements Command
{
    public function __construct(private string $name)
    {
    }

    public static function fromArray(array $data): static
    {
        return new self($data['name']);
    }

    public function toArray(): array
    {
        return ['name' => $this->name];
    }
}

final readonly class ProjectEvent implements Event
{
    public function __construct(private string $name)
    {
    }

    public static function fromArray(array $data): static
    {
        return new self($data['name']);
    }

    public function toArray(): array
    {
        return ['name' => $this->name];
    }
}
