<?php

declare(strict_types=1);

use Fight\Common\Adapter\HttpClient\Guzzle\GuzzleClient;
use Fight\Common\Adapter\HttpClient\Guzzle\GuzzleMessageFactory;
use Fight\Common\Adapter\HttpClient\Guzzle\GuzzleStreamFactory;
use Fight\Common\Adapter\HttpClient\Guzzle\GuzzleUriFactory;
use Fight\Common\Application\HttpClient\HttpService;
use Fight\Common\Application\HttpClient\Message\MessageFactory;
use Fight\Common\Application\HttpClient\Message\StreamFactory;
use Fight\Common\Application\HttpClient\Message\UriFactory;
use Fight\Common\Application\HttpClient\Transport\HttpClient;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->autoconfigure();
    $services->set(ClientInterface::class, Client::class);
    $services->set(GuzzleClient::class);
    $services->set(GuzzleMessageFactory::class);
    $services->set(GuzzleStreamFactory::class);
    $services->set(GuzzleUriFactory::class);
    $services->set(HttpService::class)
        ->arg('$httpClient', service(GuzzleClient::class))
        ->arg('$messageFactory', service(GuzzleMessageFactory::class))
        ->arg('$streamFactory', service(GuzzleStreamFactory::class))
        ->arg('$uriFactory', service(GuzzleUriFactory::class));
    $services->alias(HttpClient::class, GuzzleClient::class);
    $services->alias(MessageFactory::class, GuzzleMessageFactory::class);
    $services->alias(StreamFactory::class, GuzzleStreamFactory::class);
    $services->alias(UriFactory::class, GuzzleUriFactory::class);
};
