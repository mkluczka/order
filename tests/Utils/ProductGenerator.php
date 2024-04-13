<?php

declare(strict_types=1);

namespace Tests\Utils;

use Iteo\Client\Domain\ValueObject\Order\Order;
use Iteo\Client\Domain\ValueObject\Order\OrderId;
use Iteo\Client\Domain\ValueObject\Order\Product\Product;
use Iteo\Client\Domain\ValueObject\Order\Product\ProductId;
use Iteo\Client\Domain\ValueObject\Order\Product\Quantity;
use Iteo\Client\Domain\ValueObject\Order\Product\Weight;
use Iteo\Shared\Decimal\Decimal;
use Iteo\Shared\Money\Money;
use Ramsey\Uuid\Uuid;

final class ProductGenerator
{
    /**
     * @param array<int> $quantities
     */
    public static function orderWithItemQuantities(array $quantities): Order
    {
        $products = [];

        foreach ($quantities as $quantity) {
            $products[] = self::product($quantity);
        }

        return self::order($products);
    }

    /**
     * @param array<Product> $items
     * @return Order
     */
    public static function order(array $items): Order
    {
        return new Order(
            new OrderId(Uuid::uuid4()->toString()),
            $items
        );
    }

    public static function product(int $quantity = 1, float $weight = 55.5, float $price = 12.34): Product
    {
        return new Product(
            new ProductId(Uuid::uuid4()->toString()),
            new Money(Decimal::fromFloat($price)),
            new Weight(Decimal::fromFloat($weight)),
            new Quantity($quantity),
        );
    }

    /**
     * @param array{0: float, 1: int}[] $items
     * @return Order
     */
    public static function orderWithWeighAndQuantities(array $items): Order
    {
        $products = [];

        foreach ($items as $item) {
            $products[] = self::product($item[1], $item[0]);
        }

        return self::order($products);
    }

    /**
     * @param array{0: float, 1: int}[] $items
     * @return Order
     */
    public static function orderWithPriceAndQuantities(array $items): Order
    {
        $products = [];

        foreach ($items as $item) {
            $products[] = self::product($item[1], price: $item[0]);
        }

        return self::order($products);
    }
}
