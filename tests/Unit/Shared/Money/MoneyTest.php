<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Money;

use Iteo\Shared\Money\Exception\MoneyAmountMustNotBeNegative;
use Iteo\Shared\Money\Money;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

final class MoneyTest extends TestCase
{
    #[TestWith([0.0, '0'])]
    #[TestWith([1.1, '1.1'])]
    #[TestWith([112.221, '112.221'])]
    public function testCreate(float $amount, string $expected): void
    {
        $this->assertEquals($expected, (string) new Money($amount));
    }

    #[TestWith([-1])]
    #[TestWith([-3])]
    public function testFailCreateWithNegativeAmount(float $amount): void
    {
        $this->expectException(MoneyAmountMustNotBeNegative::class);
        $this->expectExceptionMessageMatches("/$amount given/");

        new Money($amount);
    }
}
