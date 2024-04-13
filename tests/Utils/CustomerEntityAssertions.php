<?php

declare(strict_types=1);

namespace Tests\Utils;

use App\Entity\Customer;
use App\Repository\CustomerEntityRepository;
use PHPUnit\Framework\Assert;

trait CustomerEntityAssertions
{
    protected function getCustomerEntityRepository(): CustomerEntityRepository
    {
        return self::getContainer()->get(CustomerEntityRepository::class);
    }

    protected function databaseHasCustomer(string $customerId, float $expectedBalance): void
    {
        $entity = self::getCustomerEntityRepository()->findOneBy(['uuid' => $customerId]);

        Assert::assertIsInt($entity?->getId());
        Assert::assertInstanceOf(Customer::class, $entity);
        Assert::assertSame($expectedBalance, $entity->balance);
    }
}
