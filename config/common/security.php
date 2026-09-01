<?php

declare(strict_types=1);

use Fight\Common\Adapter\Auth\Hmac\HmacAuthenticator;
use App\Composition\FrameworkSupport\SecurityProfile;
use Fight\Common\Adapter\Auth\Hmac\HmacRequestService;
use Fight\Common\Adapter\Auth\Security\JwtDecoder;
use Fight\Common\Adapter\Auth\Security\JwtEncoder;
use Fight\Common\Adapter\Auth\Security\PhpPasswordHasher;
use Fight\Common\Adapter\Auth\Security\PhpPasswordValidator;
use Fight\Common\Adapter\Socket\MercureHubPublisher;
use Fight\Common\Adapter\Socket\PrivateMercureHubPublisher;
use Fight\Common\Application\Auth\Authenticator;
use Fight\Common\Application\Auth\RequestService;
use Fight\Common\Application\Auth\Security\PasswordHasher;
use Fight\Common\Application\Auth\Security\PasswordValidator;
use Fight\Common\Application\Auth\Security\TokenDecoder;
use Fight\Common\Application\Auth\Security\TokenEncoder;
use Fight\Common\Application\Socket\PrivatePublisher;
use Fight\Common\Application\Socket\Publisher;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Mercure\Hub;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Jwt\StaticTokenProvider;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->set(SecurityProfile::class)->public();

    $services->set(PhpPasswordHasher::class)
        ->arg('$algorithm', PASSWORD_ARGON2ID);
    $services->alias(PasswordHasher::class, PhpPasswordHasher::class);
    $services->set(PhpPasswordValidator::class)
        ->arg('$algorithm', PASSWORD_ARGON2ID);
    $services->alias(PasswordValidator::class, PhpPasswordValidator::class);

    // Authentication credentials stay application-owned. The definitions are
    // lazy, so a clone can boot with the safe password defaults while an
    // application supplies these values in its own environment configuration.
    $services->set(HmacAuthenticator::class)
        ->arg('$public', '%env(FIGHT_COMMON_HMAC_PUBLIC)%')
        ->arg('$private', '%env(FIGHT_COMMON_HMAC_PRIVATE)%')
        ->arg('$timeTolerance', 300);
    $services->alias(Authenticator::class, HmacAuthenticator::class);
    $services->set(HmacRequestService::class)
        ->arg('$public', '%env(FIGHT_COMMON_HMAC_PUBLIC)%')
        ->arg('$private', '%env(FIGHT_COMMON_HMAC_PRIVATE)%');
    $services->alias(RequestService::class, HmacRequestService::class);
    $services->set(JwtEncoder::class)
        ->arg('$hexSecret', '%env(FIGHT_COMMON_JWT_SECRET)%');
    $services->alias(TokenEncoder::class, JwtEncoder::class);
    $services->set(JwtDecoder::class)
        ->arg('$hexSecret', '%env(FIGHT_COMMON_JWT_SECRET)%');
    $services->alias(TokenDecoder::class, JwtDecoder::class);

    // Mercure endpoints and publish JWTs are deployment configuration, never
    // starter source. Both public and private Common ports use the same hub.
    $services->set(StaticTokenProvider::class)
        ->arg('$token', '%env(MERCURE_JWT_TOKEN)%');
    $services->set(Hub::class)
        ->arg('$url', '%env(MERCURE_URL)%')
        ->arg('$jwtProvider', service(StaticTokenProvider::class))
        ->arg('$publicUrl', '%env(MERCURE_PUBLIC_URL)%');
    $services->alias(HubInterface::class, Hub::class);
    $services->set(MercureHubPublisher::class);
    $services->alias(Publisher::class, MercureHubPublisher::class);
    $services->set(PrivateMercureHubPublisher::class);
    $services->alias(PrivatePublisher::class, PrivateMercureHubPublisher::class);
};
