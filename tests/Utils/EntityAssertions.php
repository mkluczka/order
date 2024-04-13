<?php

declare(strict_types=1);

namespace Tests\Utils;

use App\Infrastructure\Framework\ORM\Entity\ClientEntity;
use App\Infrastructure\Framework\ORM\Entity\OrderEntity;
use App\Infrastructure\Framework\ORM\Repository\ClientEntityRepository;
use App\Infrastructure\Framework\ORM\Repository\OrderEntityRepository;
use PHPUnit\Framework\Assert;

trait EntityAssertions
{
    protected function assertNoClientInDatabase(): void
    {
        $result = $this->getClientEntityRepository()->findAll();

        Assert::assertEmpty($result);
    }

    protected function assertClientInDatabase(string $clientId, float $expectedBalance, bool $isBlocked = false): void
    {
        $entity = $this->getClientEntityRepository()->findById($clientId);

        Assert::assertInstanceOf(ClientEntity::class, $entity);
        Assert::assertSame($expectedBalance, $entity->balance);
        Assert::assertEquals($isBlocked, $entity->isBlocked);
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
