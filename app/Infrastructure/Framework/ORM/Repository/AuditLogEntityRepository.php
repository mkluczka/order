<?php

namespace App\Infrastructure\Framework\ORM\Repository;

use App\Infrastructure\Framework\ORM\Entity\AuditLogEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;

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
    public function __construct(ManagerRegistry $registry, private readonly SerializerInterface $serializer)
    {
        parent::__construct($registry, AuditLogEntity::class);
    }

    public function addNewFromObject(string $logType, object $auditLogTarget): void
    {
        $class = get_class($auditLogTarget);

        $reflectionClass = new \ReflectionClass($class);

        $entity = new AuditLogEntity();
        $entity->message = "[$logType] {$reflectionClass->getShortName()}";
        $entity->payload = json_decode($this->serializer->serialize($auditLogTarget, 'json'), true);

        $this->getEntityManager()->persist($entity);
    }
}
