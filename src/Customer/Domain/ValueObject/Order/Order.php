<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain\ValueObject\Order;

use Iteo\Customer\Domain\ValueObject\Order\Exception\OrderSizeTooSmall;
use Iteo\Customer\Domain\ValueObject\Order\Exception\OrderWeightTooBig;
use Iteo\Customer\Domain\ValueObject\Order\OrderItem\OrderItem;
use Iteo\Customer\Domain\ValueObject\Order\OrderItem\Quantity;
use Iteo\Customer\Domain\ValueObject\Order\OrderItem\Weight;
use Iteo\Shared\Decimal\Decimal;

final readonly class Order
{
    private const int MIN_ORDER_SIZE = 5;
    private const float MAX_WEIGHT = 24_000;

    /**
     * @param array<OrderItem> $orderItems
     */
    public function __construct(
        public OrderId $id,
        public array $orderItems,
    ) {
        $this->validateOrderWeight();
        $this->validateOrderSize();
    }

    private function validateOrderWeight(): void
    {
        $orderWeight = array_reduce(
            $this->orderItems,
            function (Weight $carry, OrderItem $orderItem) {
                return $carry->plus($orderItem->weight);
            },
            new Weight(Decimal::fromFloat(0))
        );

        if ($orderWeight->isMoreThen(Decimal::fromFloat(self::MAX_WEIGHT))) {
            throw new OrderWeightTooBig($orderWeight, self::MAX_WEIGHT);
        }
    }

    private function validateOrderSize(): void
    {
        $orderSize = array_reduce(
            $this->orderItems,
            function (Quantity $carry, OrderItem $orderItem) {
                return $carry->plus($orderItem->productQuantity);
            },
            new Quantity(0),
        );

        if ($orderSize->isLessThen(self::MIN_ORDER_SIZE)) {
            throw new OrderSizeTooSmall($orderSize, self::MIN_ORDER_SIZE);
        }
    }
}
