<?php

declare(strict_types=1);

namespace App\Adapters\Http\Controller;

use Iteo\Order\Application\Create\CreateOrder;
use Iteo\Shared\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route("/orders", methods: ["POST"])]
final readonly class CreateOrderController
{
    public function __construct(
        private CommandBus $commandBus,
        private ValidatorInterface $validator,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $data = $this->getValidatedData($request);

        $this->commandBus->dispatch(
            new CreateOrder(
                $data['orderId'],
                $data['clientId'],
                $data['products']
            )
        );

        return new JsonResponse(status: 204);
    }

    /**
     * @return array{orderId: string, clientId: string, products: array<mixed>}
     */
    public function getValidatedData(Request $request): array
    {
        /** @var array{orderId: string, clientId: string, products: array<mixed>} $data */
        $data = $request->toArray();

        $result = $this->validator->validate($data, new Collection([
            'orderId' => new Uuid(),
            'clientId' => new Uuid(),
            'products' => new All([
                new Collection([
                    'productId' => [new Required(), new NotBlank(), new Type('string')],
                    'quantity' => [new Required(), new NotBlank(), new Type('int')],
                    'price' => [new Required(), new NotBlank(), new Type('number')],
                    'weight' => [new Required(), new NotBlank(), new Type('number')],
                ]),
            ])
        ]));

        if ($result->count()) {
            throw new ValidationFailedException($data, $result);
        }

        return $data;
    }
}
