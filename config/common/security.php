<?php

declare(strict_types=1);

use Fight\Common\Adapter\Auth\Security\JwtDecoder;
use Fight\Common\Adapter\Auth\Security\JwtEncoder;
use Fight\Common\Adapter\Auth\Security\PhpPasswordHasher;
use Fight\Common\Adapter\Auth\Security\PhpPasswordValidator;
use Fight\Common\Application\Auth\Security\PasswordHasher;
use Fight\Common\Application\Auth\Security\PasswordValidator;
use Fight\Common\Application\Auth\Security\TokenDecoder;
use Fight\Common\Application\Auth\Security\TokenEncoder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->set(PhpPasswordHasher::class)->arg('$algorithm', PASSWORD_ARGON2ID);
    $services->alias(PasswordHasher::class, PhpPasswordHasher::class);
    $services->set(PhpPasswordValidator::class)->arg('$algorithm', PASSWORD_ARGON2ID);
    $services->alias(PasswordValidator::class, PhpPasswordValidator::class);
    $services->set(JwtEncoder::class)->arg('$hexSecret', '%env(FIGHT_COMMON_JWT_SECRET)%');
    $services->alias(TokenEncoder::class, JwtEncoder::class);
    $services->set(JwtDecoder::class)->arg('$hexSecret', '%env(FIGHT_COMMON_JWT_SECRET)%');
    $services->alias(TokenDecoder::class, JwtDecoder::class);
};
