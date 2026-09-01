<?php

declare(strict_types=1);

use Fight\Common\Domain\EventSourcing\EventMapper;
use Fight\Common\Domain\EventSourcing\EventMappingProvider;
use Fight\Common\Application\Repository\TransactionalUnitOfWork;
use Fight\Common\Application\Routing\UrlGenerator;
use Fight\Common\Application\Serialization\JsonSerializer;
use Fight\Common\Adapter\Messaging\Symfony\Serializer\SymfonyMessageSerializer;
use Fight\Common\Adapter\Persistence\Doctrine\DoctrineTransactionalUnitOfWork;
use Fight\Common\Adapter\Routing\Symfony\SymfonyUrlGenerator;
use Fight\Common\Domain\Serialization\Serializer;
use App\Composition\EventSourcing\ProjectEventMappingProvider;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->instanceof(EventMappingProvider::class)
        ->tag('common.event_mapping_provider');
    $services->set(ProjectEventMappingProvider::class)
        ->tag('common.event_mapping_provider');
    $services->set(EventMapper::class)
        ->arg('$providers', [])
        ->public();
    $services->set(JsonSerializer::class);
    $services->alias(Serializer::class, JsonSerializer::class);
    $services->set(SymfonyMessageSerializer::class);
    $services->set(SymfonyUrlGenerator::class)
        ->arg('$urlGenerator', service(UrlGeneratorInterface::class))
        ->public();
    $services->alias(UrlGenerator::class, SymfonyUrlGenerator::class);
    $services->set(DoctrineTransactionalUnitOfWork::class);
    $services->alias(TransactionalUnitOfWork::class, DoctrineTransactionalUnitOfWork::class);
};
