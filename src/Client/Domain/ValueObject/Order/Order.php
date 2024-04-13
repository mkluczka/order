<?php

declare(strict_types=1);

namespace Iteo\Client\Domain\ValueObject\Order;

use Iteo\Client\Domain\ValueObject\Order\Exception\OrderSizeTooSmall;
use Iteo\Client\Domain\ValueObject\Order\Exception\OrderWeightTooBig;
use Iteo\Client\Domain\ValueObject\Order\OrderItem\OrderItem;
use Iteo\Client\Domain\ValueObject\Order\OrderItem\Quantity;
use Iteo\Client\Domain\ValueObject\Order\OrderItem\Weight;
use Iteo\Shared\Decimal\Decimal;
use Iteo\Shared\Money\Money;

final readonly class Order
{
    private const int MIN_ORDER_SIZE = 5;
    private const float MAX_WEIGHT = 24_000;

    public Money $price;

    /**
     * @param array<OrderItem> $orderItems
     */
    public function __construct(
        public OrderId $id,
        public array $orderItems,
    ) {
        $this->validateOrderWeight();
        $this->validateOrderSize();

        $this->price = $this->calculateOrderPrice();
    }

    private function validateOrderWeight(): void
    {
        /** @var Weight $orderWeight */
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
        /** @var Quantity $orderSize */
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

    private function calculateOrderPrice(): Money
    {
        return array_reduce(
            $this->orderItems,
            function (Money $carry, OrderItem $orderItem) {
                return $carry->plus($orderItem->price);
            },
            new Money(Decimal::fromFloat(0))
        );
    }
}
