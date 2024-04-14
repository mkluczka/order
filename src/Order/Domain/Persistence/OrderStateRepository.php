<?php

declare(strict_types=1);

namespace Iteo\Order\Domain\Persistence;

use Iteo\Order\Domain\OrderState\OrderState;

interface OrderStateRepository
{
    public function save(OrderState $state): void;
}
