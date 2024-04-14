<?php

declare(strict_types=1);

namespace Iteo\Order\Domain\ValueObject;

use Iteo\Shared\ClientId;
use Iteo\Shared\Money\Money;

final readonly class Client
{
    public function __construct(
        public ClientId $id,
        public Money $balance,
        public bool $isBlocked,
    ) {
    }
}
