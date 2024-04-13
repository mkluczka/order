<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Iteo\Customer\Domain\CustomerState\CustomerState;
use Iteo\Customer\Domain\CustomerState\CustomerStateRepository;

/**
 * @extends ServiceEntityRepository<Customer>
 *
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class CustomerEntityRepository extends ServiceEntityRepository implements CustomerStateRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function save(CustomerState $customerState): void
    {
        $entity = Customer::fromCustomerState($customerState);

        $this->getEntityManager()->persist($entity);
    }
}
