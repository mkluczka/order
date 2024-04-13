<?php

declare(strict_types=1);

namespace Iteo\Client\Domain\ClientState;

use Iteo\Client\Domain\ValueObject\ClientId;
use Iteo\Client\Domain\ValueObject\Order\OrderId;
use Iteo\Shared\Money\Money;

final readonly class ClientState
{
    /**
     * @param array<OrderId> $orders
     */
    public function __construct(
        public ClientId $clientId,
        public Money $balance,
        public array $orders,
        public bool $isBlocked,
    ) {
    }
}
