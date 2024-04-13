<?php

declare(strict_types=1);

namespace Iteo\Customer\Application\Command\CreateCustomer;

final readonly class CreateCustomer
{
    public function __construct(
        public string $customerId,
        public float $initialBalance,
    ) {
    }
}
