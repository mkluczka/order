<?php

declare(strict_types=1);

namespace App\Adapters\Http\Controller;

use Iteo\Client\Application\PlaceOrder\PlaceOrder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/orders", methods: ["POST"])]
final readonly class PlaceOrderController
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function __invoke(Request $request): Response
    {
        $data = $request->toArray();

        $this->messageBus->dispatch(
            new PlaceOrder(
                $data['orderId'],
                $data['clientId'],
                $data['products']
            )
        );

        return new JsonResponse(status: 204);
    }
}
