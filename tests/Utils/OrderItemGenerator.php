<?php

declare(strict_types=1);

namespace Tests\Utils;

use Iteo\Client\Domain\ValueObject\Order\Order;
use Iteo\Client\Domain\ValueObject\Order\OrderId;
use Iteo\Client\Domain\ValueObject\Order\OrderItem\OrderItem;
use Iteo\Client\Domain\ValueObject\Order\OrderItem\ProductId;
use Iteo\Client\Domain\ValueObject\Order\OrderItem\Quantity;
use Iteo\Client\Domain\ValueObject\Order\OrderItem\Weight;
use Iteo\Shared\Decimal\Decimal;
use Iteo\Shared\Money\Money;
use Ramsey\Uuid\Uuid;

final class OrderItemGenerator
{
    /**
     * @param array<int> $quantities
     */
    public static function orderWithItemQuantities(array $quantities): Order
    {
        $orderItems = [];

        foreach ($quantities as $quantity) {
            $orderItems[] = self::orderItem($quantity);
        }

        return self::order($orderItems);
    }

    /**
     * @param array<OrderItem> $items
     * @return Order
     */
    public static function order(array $items): Order
    {
        return new Order(
            new OrderId(Uuid::uuid4()->toString()),
            $items
        );
    }

    public static function orderItem(int $quantity = 1, float $weight = 55.5, float $price = 12.34): OrderItem
    {
        return new OrderItem(
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
        $orderItems = [];

        foreach ($items as $item) {
            $orderItems[] = self::orderItem($item[1], $item[0]);
        }

        return self::order($orderItems);
    }

    /**
     * @param array{0: float, 1: int}[] $items
     * @return Order
     */
    public static function orderWithPriceAndQuantities(array $items): Order
    {
        $orderItems = [];

        foreach ($items as $item) {
            $orderItems[] = self::orderItem($item[1], price: $item[0]);
        }

        return self::order($orderItems);
    }
}
