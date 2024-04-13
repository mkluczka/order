<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Uuid;

final class CreateClientRequest
{
    public function __construct(
        #[NotBlank]
        #[Uuid]
        public ?string $clientId,
        #[NotBlank]
        #[Type('float')]
        public ?float $balance,
    ) {
    }

    /**
     * @param array<mixed> $data
     * @return self
     */
    public static function fromData(array $data): self
    {
        $clientId = $data['clientId'] ?? null;
        $balance = $data['balance'] ?? null;

        return new self(
            is_string($clientId) ? $clientId : null,
            is_float($balance) ? $balance : null,
        );
    }
}
