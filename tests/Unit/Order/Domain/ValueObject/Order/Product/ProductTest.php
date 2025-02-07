<?php

declare(strict_types=1);

namespace Tests\Unit\Order\Domain\ValueObject\Order\Product;

use Iteo\Order\Domain\ValueObject\Product\Product;
use Iteo\Order\Domain\ValueObject\Product\Quantity;
use Iteo\Order\Domain\ValueObject\Product\Weight;
use Iteo\Shared\Decimal\Decimal;
use Iteo\Shared\Money\Money;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

final class ProductTest extends TestCase
{
    #[TestWith([0.0, 0, 0.0])]
    #[TestWith([1.1, 3, 3.3])]
    #[TestWith([1.1, 5, 5.5])]
    #[TestWith([2.2, 6, 13.2])]
    #[TestWith([5, 5, 25])]
    public function testCalculateWeightOnCreate(float $productWeight, int $productQuantity, float $expectedWeight): void
    {
        $sut = new Product(
            'f0138d27-46b8-4b46-b386-560566916386',
            new Money(Decimal::fromFloat(11.11)),
            new Weight(Decimal::fromFloat($productWeight)),
            new Quantity($productQuantity),
        );

        $this->assertEquals($expectedWeight, $sut->weight->decimal->asFloat());
    }

    #[TestWith([0.0, 0, 0.0])]
    #[TestWith([1.1, 3, 3.3])]
    #[TestWith([1.1, 5, 5.5])]
    #[TestWith([2.2, 6, 13.2])]
    #[TestWith([5, 5, 25])]
    public function testCalculatePriceOnCreate(float $productPrice, int $productQuantity, float $expectedPrice): void
    {
        $sut = new Product(
            'f0138d27-46b8-4b46-b386-560566916386',
            new Money(Decimal::fromFloat($productPrice)),
            new Weight(Decimal::fromFloat(10)),
            new Quantity($productQuantity),
        );

        $this->assertTrue(
            $sut->price->equals(new Money(Decimal::fromFloat($expectedPrice))),
        );
    }
}
