<?php

declare(strict_types=1);

namespace Tests\Unit\Customer\Domain;

use Iteo\Customer\Domain\Customer;
use Iteo\Customer\Domain\Event\CustomerCreated;
use Iteo\Customer\Domain\ValueObject\CustomerId;
use Iteo\Shared\Decimal\Decimal;
use Iteo\Shared\Money\Money;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

final class CustomerCreateTest extends TestCase
{
    #[TestWith(['b13d1d65-1866-4e04-af33-6ea236d224de', 55.55])]
    #[TestWith(['ad85a94f-3d2d-41d3-8ac9-eede433420ee', 11.11])]
    #[TestWith(['e329e5e3-fcdd-4481-af26-98b79edd08bf', 0.0])]
    #[TestWith(['e329e5e3-fcdd-4481-af26-98b79edd08bf', 0])]
    public function testCreate(string $customerId, float $initialBalance): void
    {
        $customerId = new CustomerId($customerId);
        $initialBalance = new Money(Decimal::fromFloat($initialBalance));

        $expectedEvents = [
            new CustomerCreated($customerId, $initialBalance),
        ];

        $customer = Customer::create($customerId, $initialBalance);

        $this->assertEquals($expectedEvents, $customer->collectEvents());
    }
}
