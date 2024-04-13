<?php

namespace App\Repository;

use App\Entity\ClientEntity;
use App\Entity\OrderEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Iteo\Client\Domain\ClientState\ClientState;

/**
 * @extends ServiceEntityRepository<OrderEntity>
 *
 * @method OrderEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderEntity[]    findAll()
 * @method OrderEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class OrderEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderEntity::class);
    }

    public function applyOrders(ClientEntity $clientEntity, ClientState $state): void
    {
        $orderEntities = [];
        foreach ($state->orders as $orderId) {
            $orderEntity = $this->find($orderId);
            if (null === $orderEntity) {
                $orderEntity = new OrderEntity((string) $orderId, $clientEntity);
                $this->getEntityManager()->persist($orderEntity);
            }

            $orderEntities[] = $orderEntity;
        }

        $clientEntity->orders = new ArrayCollection($orderEntities);
    }
}
