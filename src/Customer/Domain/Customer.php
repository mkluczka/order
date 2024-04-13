<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain;

use Iteo\Customer\Domain\Event\CustomerCreated;
use Iteo\Customer\Domain\ValueObject\CustomerId;
use Iteo\Shared\DomainEvent\DomainEventsTrait;
use Iteo\Shared\Money\Money;

final class Customer
{
    use DomainEventsTrait;

    private Money $balance;

    public function __construct(
        private readonly CustomerId $id,
    ) {
    }

    public static function create(CustomerId $customerId, Money $initialBalance): self
    {
        $customer = new self($customerId);
        $customer->balance = $initialBalance;

        $customer->recordEvent(
            new CustomerCreated(
                $customerId,
                $initialBalance,
            )
        );

        return $customer;
    }
}
