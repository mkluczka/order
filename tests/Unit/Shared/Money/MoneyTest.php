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
}
