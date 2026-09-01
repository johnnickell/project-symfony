<?php

declare(strict_types=1);

namespace App;

use App\Composition\Compiler\ProjectServicePass;
use Fight\Common\Adapter\ServiceContainer\Symfony\CommandFilterCompilerPass;
use Fight\Common\Adapter\ServiceContainer\Symfony\CommandHandlerCompilerPass;
use Fight\Common\Adapter\ServiceContainer\Symfony\EventMappingProviderCompilerPass;
use Fight\Common\Adapter\ServiceContainer\Symfony\EventSubscriberCompilerPass;
use Fight\Common\Adapter\ServiceContainer\Symfony\QueryFilterCompilerPass;
use Fight\Common\Adapter\ServiceContainer\Symfony\QueryHandlerCompilerPass;
use Fight\Common\Adapter\ServiceContainer\Symfony\TemplateHelperCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

final class Kernel extends BaseKernel
{
    public function registerBundles(): iterable
    {
        /** @var array<class-string, array<string, bool>> $bundles */
        $bundles = require dirname(__DIR__).'/config/bundles.php';

        foreach ($bundles as $class => $environments) {
            if (($environments['all'] ?? false) || ($environments[$this->environment] ?? false)) {
                yield new $class();
            }
        }
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import(dirname(__DIR__).'/config/packages/*.php');
        $container->import(dirname(__DIR__).'/config/services.php');
        $container->import(dirname(__DIR__).'/config/common/*.php');
    }

    protected function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new ProjectServicePass());
        $container->addCompilerPass(new CommandFilterCompilerPass());
        $container->addCompilerPass(new CommandHandlerCompilerPass());
        $container->addCompilerPass(new EventMappingProviderCompilerPass());
        $container->addCompilerPass(new EventSubscriberCompilerPass());
        $container->addCompilerPass(new QueryFilterCompilerPass());
        $container->addCompilerPass(new QueryHandlerCompilerPass());
        $container->addCompilerPass(new TemplateHelperCompilerPass());
    }
}
