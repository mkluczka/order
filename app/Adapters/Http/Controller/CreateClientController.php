<?php

declare(strict_types=1);

namespace App\Adapters\Http\Controller;

use Iteo\Client\Application\Create\CreateClient;
use Iteo\Shared\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route("/clients", methods: ["POST"], format: 'json')]
final readonly class CreateClientController
{
    public function __construct(
        private CommandBus $commandBus,
        private ValidatorInterface $validator,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $data = $request->toArray();

        $result = $this->validator->validate($data, new Collection([
            'clientId' => new Uuid(),
            'balance' => new Required([new NotBlank(), new Type('number')]),
        ], allowExtraFields: true));

        if ($result->count()) {
            throw new ValidationFailedException($data, $result);
        }

        $this->commandBus->dispatch(
            new CreateClient(
                (string) $data['clientId'],
                (float) $data['balance'],
            )
        );

        return new JsonResponse(status: 204);
    }
}
