<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CustomerEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Iteo\Customer\Domain\CustomerState\CustomerState;
use Iteo\Customer\Domain\Persistence\CustomerStateRepository;
use Iteo\Customer\Domain\ValueObject\CustomerId;

/**
 * @extends ServiceEntityRepository<CustomerEntity>
 *
 * @method CustomerEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerEntity[]    findAll()
 * @method CustomerEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class CustomerEntityRepository extends ServiceEntityRepository implements CustomerStateRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly OrderEntityRepository $orderEntityRepository,
    ) {
        parent::__construct($registry, CustomerEntity::class);
    }

    public function findById(CustomerId|string $customerId): ?CustomerEntity
    {
        return $this->find((string) $customerId);
    }

    public function save(CustomerState $customerState): void
    {
        $entityManager = $this->getEntityManager();

        $customerEntity = $this->findById($customerState->customerId);

        if (null === $customerEntity) {
            $customerEntity = CustomerEntity::fromCustomerState($customerState);
        } else {
            $customerEntity->applyCustomerState($customerState);
        }

        $this->orderEntityRepository->applyOrders($customerEntity, $customerState);

        $entityManager->persist($customerEntity);
    }

    public function findByCustomerId(CustomerId $customerId): ?CustomerState
    {
        $entity = $this->findById($customerId);

        return $entity?->asCustomerState();
    }
}
