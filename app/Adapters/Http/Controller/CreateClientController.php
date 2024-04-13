<?php

declare(strict_types=1);

namespace App\Adapters\Http\Controller;

use App\Adapters\Http\Request\CreateClientRequest;
use Iteo\Client\Application\CreateClient\CreateClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/clients", methods: ["POST"])]
final readonly class CreateClientController
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(
        #[ValueResolver("create_client")]
        CreateClientRequest $request
    ): Response {
        $this->messageBus->dispatch(
            new CreateClient(
                (string) $request->clientId,
                (float) $request->balance,
            )
        );

        return new JsonResponse(status: 204);
    }
}
