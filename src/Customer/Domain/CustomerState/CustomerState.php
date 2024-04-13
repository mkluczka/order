<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain\CustomerState;

use Iteo\Customer\Domain\ValueObject\CustomerId;
use Iteo\Shared\Money\Money;

final readonly class CustomerState
{
    public function __construct(
        public CustomerId $customerId,
        public Money $balance,
    ) {
    }
}
