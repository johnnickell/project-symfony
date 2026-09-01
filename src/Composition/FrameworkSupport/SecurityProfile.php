<?php

declare(strict_types=1);

namespace App\Composition\FrameworkSupport;

use Fight\Common\Application\Auth\Authenticator;
use Fight\Common\Application\Auth\RequestService;
use Fight\Common\Application\Auth\Security\TokenDecoder;
use Fight\Common\Application\Auth\Security\TokenEncoder;
use Fight\Common\Application\Socket\PrivatePublisher;
use Fight\Common\Application\Socket\Publisher;

/** Credential-bound Common ports resolved from application environment values. */
final readonly class SecurityProfile
{
    public function __construct(
        public Authenticator $hmacAuthenticator,
        public RequestService $hmacRequestService,
        public TokenEncoder $tokenEncoder,
        public TokenDecoder $tokenDecoder,
        public Publisher $publisher,
        public PrivatePublisher $privatePublisher,
    ) {
    }
}
