<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Money;

use Iteo\Shared\Decimal\Decimal;
use Iteo\Shared\Money\Exception\MoneyAmountMustNotBeNegative;
use Iteo\Shared\Money\Money;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

final class MoneyTest extends TestCase
{
    #[TestWith([0.0, '0.00'])]
    #[TestWith([1.1, '1.10'])]
    public function testCreate(float $amount, string $expected): void
    {
        $this->assertEquals($expected, (string) new Money(Decimal::fromFloat($amount)));
    }

    #[TestWith([-1, '-1.00 given'])]
    #[TestWith([-3, '-3.00 given'])]
    public function testFailCreateWithNegativeAmount(float $amount, string $expectedMessage): void
    {
        $this->expectException(MoneyAmountMustNotBeNegative::class);
        $this->expectExceptionMessageMatches("/$expectedMessage/");

        new Money(Decimal::fromFloat($amount));
    }

    #[TestWith([1.0, 1.0, '2.00'])]
    #[TestWith([2.0, 2.0, '4.00'])]
    public function testPlus(float $left, float $right, string $expected): void
    {
        $this->assertEquals(
            $expected,
            (string) (new Money(Decimal::fromFloat($left)))->plus(new Money(Decimal::fromFloat($right))),
        );
    }

    #[TestWith([1.0, 3, '3.00'])]
    #[TestWith([2.0, 4, '8.00'])]
    public function testMultiplyBy(float $left, int $multiplyBy, string $expected): void
    {
        $this->assertEquals(
            $expected,
            (string) (new Money(Decimal::fromFloat($left)))->multiplyBy($multiplyBy),
        );
    }

    public function testMultiplyByNegativeFails(): void
    {
        $this->expectException(MoneyAmountMustNotBeNegative::class);

        (new Money(Decimal::fromFloat(5.0)))->multiplyBy(-5);
    }

    #[TestWith([1.0, 2.0, false])]
    #[TestWith([2.0, 1.0, true])]
    #[TestWith([2.0, 2.0, false])]
    public function testIsGreaterThen(float $left, float $right, bool $expected): void
    {
        $this->assertEquals(
            $expected,
            (new Money(Decimal::fromFloat($left)))->isGreaterThen(new Money(Decimal::fromFloat($right))),
        );
    }

    #[TestWith([1.0, 1.0, '0.00'])]
    #[TestWith([2.0, 2.0, '0.00'])]
    #[TestWith([3.0, 1.0, '2.00'])]
    public function testMinus(float $left, float $right, string $expected): void
    {
        $this->assertEquals(
            $expected,
            (string) (new Money(Decimal::fromFloat($left)))->minus(new Money(Decimal::fromFloat($right))),
        );
    }

    public function testMinusOverNegativeFails(): void
    {
        $this->expectException(MoneyAmountMustNotBeNegative::class);

        (new Money(Decimal::fromFloat(5.0)))->minus(new Money(Decimal::fromFloat(10.0)));
    }

    #[TestWith([1.0, 1.0, true])]
    #[TestWith([1.0, 2.0, false])]
    public function testEquals(float $left, float $right, bool $expected): void
    {
        $this->assertEquals(
            $expected,
            (new Money(Decimal::fromFloat($left)))->equals(new Money(Decimal::fromFloat($right))),
        );
    }
}
