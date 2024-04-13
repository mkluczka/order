<?php

declare(strict_types=1);

namespace App\Adapters\Http\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class CreateClientRequestValueResolver implements ValueResolverInterface
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return iterable<CreateClientRequest>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() === CreateClientRequest::class) {
            $createClientRequest = CreateClientRequest::fromData($request->toArray());

            $errors = $this->validator->validate($createClientRequest);

            if (!$errors->count()) {
                return [$createClientRequest];
            }

            throw new ValidationFailedException('Invalid request', $errors);
        }

        return [];
    }
}
