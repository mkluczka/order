<?php

declare(strict_types=1);

namespace Tests\Unit\Customer\Domain;

use Iteo\Customer\Domain\Customer;
use Iteo\Customer\Domain\CustomerRepository;
use Iteo\Customer\Domain\CustomerState\CustomerState;
use Iteo\Customer\Domain\CustomerState\CustomerStateRepository;
use Iteo\Customer\Domain\ValueObject\CustomerId;
use Iteo\Shared\Money\Money;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class CustomerRepositoryTest extends TestCase
{
    private CustomerRepository $sut;
    private CustomerStateRepository|MockObject $customerStateRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new CustomerRepository(
            $this->customerStateRepositoryMock = $this->createMock(CustomerStateRepository::class),
        );
    }

    public function testSaveCustomer(): void
    {
        $customerState = new CustomerState(
            new CustomerId('5adb7472-1a30-425b-b755-892805ba2065'),
            new Money(11.11)
        );
        $customer = Customer::restore($customerState);

        $this->customerStateRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->with($customerState);

        $this->sut->save($customer);
    }
}
