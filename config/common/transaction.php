<?php

declare(strict_types=1);

use Fight\Common\Adapter\Persistence\Doctrine\DoctrineTransactionalUnitOfWork;
use Fight\Common\Application\Repository\TransactionalUnitOfWork;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->set(DoctrineTransactionalUnitOfWork::class);
    $services->alias(TransactionalUnitOfWork::class, DoctrineTransactionalUnitOfWork::class);
};
