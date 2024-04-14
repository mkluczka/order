<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Decimal;

use Iteo\Shared\Decimal\Decimal;
use Iteo\Shared\Decimal\InvalidDecimalFormat;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

final class DecimalTest extends TestCase
{
    #[DataProvider('provideProperDecimals')]
    public function testProperDecimals(float $decimalCandidate, string $expected): void
    {
        $this->assertEquals(
            $expected,
            (string) Decimal::fromFloat($decimalCandidate),
        );
    }

    /**
     * @return array{float, string}[]
     */
    public static function provideProperDecimals(): array
    {
        return [
            [0.0, '0.00'],
            [1.23, '1.23'],
            [2.34, '2.34'],
            [-34.34, '-34.34'],
            [999.99, '999.99'],
        ];
    }

    #[TestWith([1.123])]
    #[TestWith([1.1234])]
    #[TestWith([1.12345])]
    public function testInvalidDecimals(float $decimalCandidate): void
    {
        $this->expectException(InvalidDecimalFormat::class);
        $this->expectExceptionMessageMatches("/$decimalCandidate given/");

        Decimal::fromFloat($decimalCandidate);
    }

    #[TestWith([0.0, 0, '0.00'])]
    #[TestWith([1.1, 3, '3.30'])]
    #[TestWith([1.1, 5, '5.50'])]
    #[TestWith([2.2, 6, '13.20'])]
    #[TestWith([5, 5, '25.00'])]
    public function testMultiply(float $decimalCandidate, int $multiplyBy, string $expected): void
    {
        $this->assertEquals(
            $expected,
            (string) Decimal::fromFloat($decimalCandidate)->multiplyBy($multiplyBy),
        );
    }

    #[TestWith([0.0, 0.0, '0.00'])]
    #[TestWith([2.0, 2.0, '4.00'])]
    #[TestWith([2.0, -2.0, '0.00'])]
    public function testPlus(float $left, float $right, string $expected): void
    {
        $this->assertEquals(
            $expected,
            (string) Decimal::fromFloat($left)->plus(Decimal::fromFloat($right)),
        );
    }

    #[TestWith([0.0, 0.0, false])]
    #[TestWith([1.0, 0.0, true])]
    #[TestWith([-1.0, 0.0, false])]
    public function testIsMoreThen(float $left, float $right, bool $expected): void
    {
        $this->assertEquals(
            $expected,
            Decimal::fromFloat($left)->isGreaterThan(Decimal::fromFloat($right)),
        );
    }

    #[TestWith([1.0, 2.0, '-1.00'])]
    #[TestWith([2.0, 1.0, '1.00'])]
    #[TestWith([0.0, 0.0, '0.00'])]
    public function testMinus(float $left, float $right, string $expected): void
    {
        $this->assertEquals(
            $expected,
            (string) Decimal::fromFloat($left)->minus(Decimal::fromFloat($right)),
        );
    }

    #[TestWith([1.0, 1.0, true])]
    #[TestWith([1.00, 1, true])]
    #[TestWith([2.0, 1.0, false])]
    public function testEquals(float $left, float $right, bool $expected): void
    {
        $this->assertEquals(
            $expected,
            Decimal::fromFloat($left)->equals(Decimal::fromFloat($right)),
        );
    }
}
