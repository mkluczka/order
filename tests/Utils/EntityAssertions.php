<?php

declare(strict_types=1);

namespace Tests\Utils;

use App\Entity\ClientEntity;
use App\Entity\OrderEntity;
use App\Repository\ClientEntityRepository;
use App\Repository\OrderEntityRepository;
use PHPUnit\Framework\Assert;

trait EntityAssertions
{
    protected function assertNoClientInDatabase(): void
    {
        $result = $this->getClientEntityRepository()->findAll();

        Assert::assertEmpty($result);
    }

    protected function assertClientInDatabase(string $clientId, float $expectedBalance): void
    {
        $entity = $this->getClientEntityRepository()->findById($clientId);

        Assert::assertInstanceOf(ClientEntity::class, $entity);
        Assert::assertSame($expectedBalance, $entity->balance);
    }

    private function getClientEntityRepository(): ClientEntityRepository
    {
        return self::getContainer()->get(ClientEntityRepository::class);
    }

    /**
     * @param array<string> $clientOrderIds
     */
    protected function assertOrdersInDatabase(string $clientId, array $clientOrderIds): void
    {
        $result = $this->getOrderEntityRepository()->findBy(['client' => $clientId]);

        Assert::assertCount(count($clientOrderIds), $result);
        Assert::assertEquals($clientOrderIds, array_map(fn (OrderEntity $order) => $order->id, $result));
    }

    private function getOrderEntityRepository(): OrderEntityRepository
    {
        return self::getContainer()->get(OrderEntityRepository::class);
    }
}
