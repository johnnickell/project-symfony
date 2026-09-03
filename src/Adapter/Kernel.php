<?php

declare(strict_types=1);

namespace App\Adapter;

use App\Adapter\DependencyInjection\LazyMessagingServiceVisibilityCompilerPass;
use Fight\Common\Adapter\ServiceContainer\Symfony\CommandFilterCompilerPass;
use Fight\Common\Adapter\ServiceContainer\Symfony\CommandHandlerCompilerPass;
use Fight\Common\Adapter\ServiceContainer\Symfony\EventSubscriberCompilerPass;
use Fight\Common\Adapter\ServiceContainer\Symfony\QueryFilterCompilerPass;
use Fight\Common\Adapter\ServiceContainer\Symfony\QueryHandlerCompilerPass;
use Fight\Common\Adapter\ServiceContainer\Symfony\TemplateHelperCompilerPass;
use Fight\Common\Application\Messaging\Command\CommandFilter;
use Fight\Common\Application\Messaging\Command\CommandHandler;
use Fight\Common\Application\Messaging\Event\EventSubscriber;
use Fight\Common\Application\Messaging\Query\QueryFilter;
use Fight\Common\Application\Messaging\Query\QueryHandler;
use Fight\Common\Application\Templating\TemplateHelper;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
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

        $container->registerForAutoconfiguration(CommandHandler::class)
            ->addTag('common.command_handler');
        $container->registerForAutoconfiguration(CommandFilter::class)->addTag('common.command_filter');
        $container->registerForAutoconfiguration(EventSubscriber::class)
            ->addTag('common.event_subscriber');
        $container->registerForAutoconfiguration(QueryHandler::class)
            ->addTag('common.query_handler');
        $container->registerForAutoconfiguration(QueryFilter::class)->addTag('common.query_filter');
        $container->registerForAutoconfiguration(TemplateHelper::class)->addTag('common.template_helper');

        $container->addCompilerPass(
            new LazyMessagingServiceVisibilityCompilerPass(),
            PassConfig::TYPE_BEFORE_OPTIMIZATION,
        );

        $container->addCompilerPass(new CommandFilterCompilerPass());
        $container->addCompilerPass(new CommandHandlerCompilerPass());
        $container->addCompilerPass(new EventSubscriberCompilerPass());
        $container->addCompilerPass(new QueryFilterCompilerPass());
        $container->addCompilerPass(new QueryHandlerCompilerPass());
        $container->addCompilerPass(new TemplateHelperCompilerPass());
    }
}
