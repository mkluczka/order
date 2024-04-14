<?php

namespace App\Infrastructure\Framework\ORM\Repository;

use App\Infrastructure\Framework\ORM\Entity\OrderEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Iteo\Order\Domain\OrderState\OrderState;
use Iteo\Order\Domain\Persistence\OrderStateRepository;
use Iteo\Shared\OrderId;

/**
 * @extends ServiceEntityRepository<OrderEntity>
 *
 * @method OrderEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderEntity[]    findAll()
 * @method OrderEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class OrderEntityRepository extends ServiceEntityRepository implements OrderStateRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderEntity::class);
    }

    public function save(OrderState $state): void
    {
        $orderEntity = $this->findByOrderId($state->orderId);
        if (null === $orderEntity) {
            $orderEntity = new OrderEntity();
            $orderEntity->id = (string) $state->orderId;
            $orderEntity->clientId = (string) $state->clientId;
        }

        $this->getEntityManager()->persist($orderEntity);
    }

    private function findByOrderId(OrderId $orderId): ?OrderEntity
    {
        return $this->find((string) $orderId);
    }
}
