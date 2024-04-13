<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain\CustomerState;

use Iteo\Customer\Domain\ValueObject\CustomerId;
use Iteo\Customer\Domain\ValueObject\Order\OrderId;
use Iteo\Shared\Money\Money;

final readonly class CustomerState
{
    /**
     * @param CustomerId $customerId
     * @param Money $balance
     * @param array<OrderId> $orders
     */
    public function __construct(
        public CustomerId $customerId,
        public Money $balance,
        public array $orders,
    ) {
    }
}
