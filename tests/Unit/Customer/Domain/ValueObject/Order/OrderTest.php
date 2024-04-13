<?php

declare(strict_types=1);

namespace Tests\Unit\Customer\Domain\ValueObject\Order;

use Iteo\Customer\Domain\ValueObject\Order\Exception\OrderSizeTooSmall;
use Iteo\Customer\Domain\ValueObject\Order\Exception\OrderWeightTooBig;
use Monolog\Test\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Utils\OrderItemGenerator as Items;

final class OrderTest extends TestCase
{
    /**
     * @param int[] $itemQuantities
     */
    #[DataProvider('provideOrderWithInvalidSize')]
    public function testOrderWithInvalidSize(array $itemQuantities, int $expectedQuantity): void
    {
        $this->expectException(OrderSizeTooSmall::class);
        $this->expectExceptionMessageMatches("/$expectedQuantity given/");
        $this->expectExceptionMessageMatches('/at least 5 required/');

        Items::orderWithItemQuantities($itemQuantities);
    }

    /**
     * @return array{0: int[], 1: int}[]
     */
    public static function provideOrderWithInvalidSize(): array
    {
        return [
            [[1], 1],
            [[4], 4],
            [[1, 2], 3],
            [[1, 1, 1, 1], 4],
        ];
    }

    /**
     * @param array<array{0: float, 1: int}> $items
     * @param int $expectedWeight
     */
    #[DataProvider('provideOrderWithInvalidWeight')]
    public function testOrderWithInvalidWeight(array $items, int $expectedWeight): void
    {
        $this->expectException(OrderWeightTooBig::class);
        $this->expectExceptionMessageMatches("/$expectedWeight given/");
        $this->expectExceptionMessageMatches('/max 24000 allowed/');

        Items::orderWithWeighAndQuantities($items);
    }

    /**
     * @return array{0: array<array{0: float, 1: int}>, 1: int}[]
     */
    public static function provideOrderWithInvalidWeight(): array
    {
        return [
            [[[1.0, 24_001]], 24_001],
            [[[2.0, 12_001]], 24_002],
            [
                [
                    [1.0, 12_000],
                    [1.0, 12_001],
                ],
                24_002,
            ],
            [
                [
                    [1.0, 4_000],
                    [2.0, 4_000],
                    [1.0, 12_001],
                ],
                24_002,
            ],
        ];
    }
}
