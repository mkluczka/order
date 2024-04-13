<?php

declare(strict_types=1);

namespace Tests\Unit\Customer\Domain\ValueObject\Order\OrderItem;

use Iteo\Customer\Domain\ValueObject\Order\OrderItem\OrderItem;
use Iteo\Customer\Domain\ValueObject\Order\OrderItem\ProductId;
use Iteo\Customer\Domain\ValueObject\Order\OrderItem\Quantity;
use Iteo\Customer\Domain\ValueObject\Order\OrderItem\Weight;
use Iteo\Shared\Decimal\Decimal;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

final class OrderItemTest extends TestCase
{
    #[TestWith([0.0, 0, 0.0])]
    #[TestWith([1.1, 3, 3.3])]
    #[TestWith([1.1, 5, 5.5])]
    #[TestWith([2.2, 6, 13.2])]
    #[TestWith([5, 5, 25])]
    public function testCalculateWeightOnCreate(float $productWeight, int $productQuantity, float $expectedWeight): void
    {
        $sut = new OrderItem(
            new ProductId('f0138d27-46b8-4b46-b386-560566916386'),
            new Weight(Decimal::fromFloat($productWeight)),
            new Quantity($productQuantity),
        );

        $this->assertEquals($expectedWeight, $sut->weight->decimal->asFloat());
    }
}
