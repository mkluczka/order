<?php

declare(strict_types=1);

namespace Tests\Unit\Order\Domain\ValueObject;

use Iteo\Order\Domain\ValueObject\Product\Product;
use Iteo\Order\Domain\ValueObject\Product\Quantity;
use Iteo\Order\Domain\ValueObject\Product\Weight;
use Iteo\Order\Domain\ValueObject\ProductList;
use Iteo\Shared\Money\Money;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ProductListTest extends TestCase
{
    /**
     * @param array{float, int}[] $productPrices
     */
    #[DataProvider('provideCalculateTotalPrice')]
    public function testCalculateTotalPrice(array $productPrices, float $expectedPrice): void
    {
        $productList = new ProductList(
            array_map(
                fn (array $item) => new Product(
                    'PR1',
                    Money::fromFloat($item[0]),
                    Weight::fromFloat(12.34),
                    new Quantity($item[1]),
                ),
                $productPrices,
            ),
        );

        $this->assertEquals($expectedPrice, $productList->totalPrice->asFloat());
    }

    /**
     * @return array{array{float, int}[]}[]
     */
    public static function provideCalculateTotalPrice(): iterable
    {
        yield [[[0.0, 0]], 0];

        yield [
            [[1.0, 15]],
            15,
        ];

        yield [
            [
                [1.0, 3],
                [2.0, 4],
            ],
            11.
        ];
    }

    /**
     * @param array{float, int}[] $productWeights
     */
    #[DataProvider('provideCalculateTotalWeight')]
    public function testCalculateTotalWeight(array $productWeights, float $expectedWeight): void
    {
        $productList = new ProductList(
            array_map(
                fn (array $item) => new Product(
                    'PR2',
                    Money::fromFloat(11.11),
                    Weight::fromFloat($item[0]),
                    new Quantity($item[1]),
                ),
                $productWeights,
            ),
        );

        $this->assertEquals($expectedWeight, $productList->totalWeight);
    }

    /**
     * @return array{array{float, int}[]}[]
     */
    public static function provideCalculateTotalWeight(): iterable
    {
        yield [[[0.0, 0]], 0];

        yield [
            [[1.0, 15]],
            15,
        ];

        yield [
            [
                [1.0, 3],
                [2.0, 4],
            ],
            11.
        ];
    }

    /**
     * @param array<int> $productQuantities
     */
    #[DataProvider('provideCalculateTotalSize')]
    public function testCalculateTotalSize(array $productQuantities, int $expectedSize): void
    {
        $productList = new ProductList(
            array_map(
                fn (int $quantity) => new Product(
                    'POR3',
                    Money::fromFloat(11.11),
                    Weight::fromFloat(22.22),
                    new Quantity($quantity),
                ),
                $productQuantities,
            ),
        );

        $this->assertEquals($expectedSize, $productList->orderSize);
    }

    /**
     * @return array{array<int>,int}[]
     */
    public static function provideCalculateTotalSize(): iterable
    {
        yield [
            [1],
            1,
        ];

        yield [
            [1, 2, 3],
            6,
        ];


        yield [
            [1, 2, 33],
            36,
        ];
    }
}
