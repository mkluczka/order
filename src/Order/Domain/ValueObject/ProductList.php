<?php

declare(strict_types=1);

namespace Iteo\Order\Domain\ValueObject;

use Iteo\Order\Domain\ValueObject\Product\Product;
use Iteo\Order\Domain\ValueObject\Product\Weight;
use Iteo\Shared\Decimal\Decimal;
use Iteo\Shared\Money\Money;

final readonly class ProductList
{
    public Money $totalPrice;
    public float $totalWeight;
    public int $orderSize;

    /**
     * @param array<Product> $items
     */
    public function __construct(public array $items)
    {
        $this->totalPrice = $this->calculateTotalPrice();
        $this->totalWeight = $this->calculateTotalWeight();
        $this->orderSize = $this->calculateOrderSize();
    }

    private function calculateTotalPrice(): Money
    {
        return array_reduce(
            $this->items,
            function (Money $carry, Product $product) {
                return $carry->plus($product->price);
            },
            new Money(Decimal::fromFloat(0))
        );
    }

    private function calculateTotalWeight(): float
    {
        $totalWeight = array_reduce(
            $this->items,
            function (Weight $carry, Product $product) {
                return new Weight($carry->decimal->plus($product->weight->decimal));
            },
            new Weight(Decimal::fromFloat(0))
        );

        return $totalWeight->asFloat();
    }

    private function calculateOrderSize(): int
    {
        return array_reduce(
            $this->items,
            function (int $carry, Product $product) {
                return $carry + $product->quantity->value;
            },
            0,
        );
    }
}
