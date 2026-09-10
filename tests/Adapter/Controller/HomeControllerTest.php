<?php

declare(strict_types=1);

namespace App\Tests\Adapter\Controller;

use App\Adapter\Controller\HomeController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Twig\Environment;

#[CoversClass(HomeController::class)]
final class HomeControllerTest extends TestCase
{
    public function testIndexRendersTheConfiguredApplicationName(): void
    {
        $parameterBag = $this->createMock(ContainerBagInterface::class);
        $parameterBag->expects(self::once())
            ->method('get')
            ->with('project.application_name')
            ->willReturn('Configured Starter');

        $twig = $this->createMock(Environment::class);
        $twig->expects(self::once())
            ->method('render')
            ->with('home/index.html.twig', ['applicationName' => 'Configured Starter'])
            ->willReturn('<h1>Configured Starter</h1>');

        $container = $this->createStub(ContainerInterface::class);
        $container->method('has')->willReturnMap([
            ['parameter_bag', true],
            ['twig', true],
        ]);
        $container->method('get')->willReturnMap([
            ['parameter_bag', $parameterBag],
            ['twig', $twig],
        ]);

        $controller = new HomeController();
        $controller->setContainer($container);

        $response = $controller->index();

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('<h1>Configured Starter</h1>', $response->getContent());
    }
}
