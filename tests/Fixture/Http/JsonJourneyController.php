<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Http;

use Fight\Common\Adapter\Http\Symfony\JSendResponse;
use Fight\Common\Domain\Type\Arrayable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class JsonJourneyController
{
    public function __invoke(Request $request): Response
    {
        return JSendResponse::success(new JsonJourneyPresentation($request->request->all()));
    }
}

final readonly class JsonJourneyPresentation implements Arrayable
{
    /** @param array<string, mixed> $data */
    public function __construct(private array $data)
    {
    }

    public function toArray(): array
    {
        return ['accepted' => $this->data];
    }
}
