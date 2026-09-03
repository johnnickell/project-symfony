<?php

declare(strict_types=1);

use Fight\Common\Adapter\Observability\Audit\NullAuditLog;
use Fight\Common\Application\Observability\AuditLog;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->set(NullAuditLog::class);
    $services->alias(AuditLog::class, NullAuditLog::class);
};
