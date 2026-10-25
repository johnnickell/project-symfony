<?php

declare(strict_types=1);

namespace App\Adapter\Homepage;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class HomeResponder
 */
final readonly class HomeResponder
{
    /**
     * Constructs HomeResponder
     */
    public function __construct(private Environment $twig)
    {
    }

    /**
     * Returns the configured homepage presentation
     */
    public function respond(string $applicationName): Response
    {
        return new Response(
            $this->twig->render('home/index.html.twig', ['applicationName' => $applicationName]),
            Response::HTTP_OK,
            ['Content-Type' => 'text/html; charset=UTF-8']
        );
    }
}
