<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Http;

use Fight\Common\Adapter\Http\Symfony\JSendResponse;
use Fight\Common\Domain\Type\Arrayable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Presents the test-only JSON middleware journey
 */
final class JsonJourneyController
{
    /**
     * Returns the parsed request as a safe test presentation
     */
    public function __invoke(Request $request): Response
    {
        return JSendResponse::success(new JsonJourneyPresentation($request->request->all()));
    }
}

/**
 * Carries the accepted test request data
 */
final readonly class JsonJourneyPresentation implements Arrayable
{
    /**
     * Constructs JsonJourneyPresentation
     *
     * @param array<string, mixed> $data
     */
    public function __construct(private array $data)
    {
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return ['accepted' => $this->data];
    }
}
