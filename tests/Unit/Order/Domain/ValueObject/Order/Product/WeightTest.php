<?php

declare(strict_types=1);

namespace Tests\Unit\Order\Domain\ValueObject\Order\Product;

use Iteo\Order\Domain\ValueObject\Product\Weight;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

final class WeightTest extends TestCase
{
    #[TestWith([1.0, '1.00'])]
    #[TestWith([4.5, '4.50'])]
    public function testCreate(float $weight, string $expected): void
    {
        $this->assertEquals(
            $expected,
            (string) Weight::fromFloat($weight),
        );
    }
}
