<?php

declare(strict_types=1);

namespace App\Adapter;

use Fight\Common\Adapter\ServiceContainer\Symfony\CommandFilterCompilerPass;
use Fight\Common\Adapter\ServiceContainer\Symfony\CommandHandlerCompilerPass;
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
        $bundles = require dirname(__DIR__, 2).'/config/bundles.php';

        foreach ($bundles as $class => $environments) {
            if (($environments['all'] ?? false) || ($environments[$this->environment] ?? false)) {
                yield new $class();
            }
        }
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import(dirname(__DIR__, 2).'/config/packages/*.php');
        if (is_dir(dirname(__DIR__, 2).'/config/packages/'.$this->environment)) {
            $container->import(dirname(__DIR__, 2).'/config/packages/'.$this->environment.'/*.php');
        }
        $container->import(dirname(__DIR__, 2).'/config/services.php');
        $container->import(dirname(__DIR__, 2).'/config/common/*.php');
        if ($this->environment === 'test') {
            $container->import(dirname(__DIR__, 2).'/config/services_test.php');
        }
    }

    protected function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new CommandFilterCompilerPass());
        $container->addCompilerPass(new CommandHandlerCompilerPass());
        $container->addCompilerPass(new EventSubscriberCompilerPass());
        $container->addCompilerPass(new QueryFilterCompilerPass());
        $container->addCompilerPass(new QueryHandlerCompilerPass());
        $container->addCompilerPass(new TemplateHelperCompilerPass());
    }
}
