<?php

declare(strict_types=1);

namespace Iteo\Client\Domain\ValueObject\Order;

use Iteo\Client\Domain\ValueObject\Order\Exception\OrderSizeTooSmall;
use Iteo\Client\Domain\ValueObject\Order\Exception\OrderWeightTooBig;
use Iteo\Client\Domain\ValueObject\Order\Product\Product;
use Iteo\Client\Domain\ValueObject\Order\Product\Quantity;
use Iteo\Client\Domain\ValueObject\Order\Product\Weight;
use Iteo\Shared\Decimal\Decimal;
use Iteo\Shared\Money\Money;

final readonly class Order
{
    private const int MIN_ORDER_SIZE = 5;
    private const float MAX_WEIGHT = 24_000;

    public Money $price;

    /**
     * @param array<Product> $products
     */
    public function __construct(
        public OrderId $id,
        public array $products,
    ) {
        $this->validateOrderWeight();
        $this->validateOrderSize();

        $this->price = $this->calculateOrderPrice();
    }

    private function validateOrderWeight(): void
    {
        $orderWeight = array_reduce(
            $this->products,
            function (Weight $carry, Product $product) {
                return $carry->plus($product->weight);
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
            $this->products,
            function (Quantity $carry, Product $product) {
                return $carry->plus($product->productQuantity);
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
            $this->products,
            function (Money $carry, Product $product) {
                return $carry->plus($product->price);
            },
            new Money(Decimal::fromFloat(0))
        );
    }
}
