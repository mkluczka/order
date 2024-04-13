<?php

declare(strict_types=1);

namespace Tests\Utils;

use App\Entity\CustomerEntity;
use App\Entity\OrderEntity;
use App\Repository\CustomerEntityRepository;
use App\Repository\OrderEntityRepository;
use PHPUnit\Framework\Assert;

trait EntityAssertions
{
    protected function assertNoClientInDatabase(): void
    {
        $result = $this->getCustomerEntityRepository()->findAll();

        Assert::assertEmpty($result);
    }

    protected function assertClientInDatabase(string $customerId, float $expectedBalance): void
    {
        $entity = $this->getCustomerEntityRepository()->findById($customerId);

        Assert::assertInstanceOf(CustomerEntity::class, $entity);
        Assert::assertSame($expectedBalance, $entity->balance);
    }

    private function getCustomerEntityRepository(): CustomerEntityRepository
    {
        return self::getContainer()->get(CustomerEntityRepository::class);
    }

    /**
     * @param array<string> $customerOrdersIds
     */
    protected function assertOrdersInDatabase(string $customerId, array $customerOrdersIds): void
    {
        $result = $this->getOrderEntityRepository()->findBy(['customer' => $customerId]);

        Assert::assertCount(count($customerOrdersIds), $result);
        Assert::assertEquals($customerOrdersIds, array_map(fn (OrderEntity $order) => $order->id, $result));
    }

    private function getOrderEntityRepository(): OrderEntityRepository
    {
        return self::getContainer()->get(OrderEntityRepository::class);
    }
}
