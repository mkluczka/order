<?php

namespace App\Infrastructure\Framework\ORM\Repository;

use App\Infrastructure\Framework\ORM\Entity\AuditLogEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AuditLogEntity>
 *
 * @method AuditLogEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuditLogEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuditLogEntity[]    findAll()
 * @method AuditLogEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class AuditLogEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuditLogEntity::class);
    }

    public function save(AuditLogEntity $entity): void
    {
        $this->getEntityManager()->persist($entity);
    }
}
