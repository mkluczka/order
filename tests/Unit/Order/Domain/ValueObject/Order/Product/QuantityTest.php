<?php

declare(strict_types=1);

namespace Tests\Unit\Order\Domain\ValueObject\Order\Product;

use Iteo\Order\Domain\ValueObject\Product\Exception\QuantityMustByNonNegative;
use Iteo\Order\Domain\ValueObject\Product\Quantity;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

final class QuantityTest extends TestCase
{
    #[TestWith([1, '1'])]
    #[TestWith([4, '4'])]
    public function testCreate(int $quantity, string $expected): void
    {
        $this->assertEquals(
            $expected,
            (string) new Quantity($quantity),
        );
    }

    #[TestWith([-1])]
    #[TestWith([-2])]
    public function testInvalidQuantity(int $invalidQuantity): void
    {
        $this->expectException(QuantityMustByNonNegative::class);
        $this->expectExceptionMessageMatches("/$invalidQuantity given/");

        new Quantity($invalidQuantity);
    }

    #[TestWith([1, 2, '3'])]
    #[TestWith([3, 4, '7'])]
    #[TestWith([4, 3, '7'])]
    public function testPlus(int $left, int $right, string $expected): void
    {
        $this->assertEquals(
            $expected,
            (new Quantity($left))->plus(new Quantity($right))->value,
        );
    }

    #[TestWith([1, 2, true])]
    #[TestWith([2, 1, false])]
    #[TestWith([2, 2, false])]
    public function testIsLessThen(int $left, int $right, bool $expected): void
    {
        $this->assertEquals(
            $expected,
            (new Quantity($left))->isLessThan($right),
        );
    }
}
