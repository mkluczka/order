<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain\Event;

use Iteo\Customer\Domain\ValueObject\CustomerId;
use Iteo\Shared\DomainEvent\DomainEvent;
use Iteo\Shared\Money\Money;

final readonly class CustomerCharged implements DomainEvent
{
    public function __construct(
        public CustomerId $customerId,
        public Money $previousBalance,
        public Money $currentBalance,
    ) {
    }
}
