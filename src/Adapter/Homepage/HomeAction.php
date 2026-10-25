<?php

declare(strict_types=1);

namespace App\Adapter\Homepage;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeAction
 */
final readonly class HomeAction
{
    /**
     * Constructs HomeAction
     */
    public function __construct(private HomeResponder $responder, private string $applicationName)
    {
    }

    /**
     * Handles the homepage request
     */
    public function handle(): Response
    {
        return $this->responder->respond($this->applicationName);
    }
}
