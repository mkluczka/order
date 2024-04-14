<?php

declare(strict_types=1);

namespace App\Infrastructure\Framework\ORM\Repository;

use App\Infrastructure\Framework\ORM\Entity\ClientEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Iteo\Client\Domain\ClientState\ClientState;
use Iteo\Client\Domain\Persistence\ClientStateRepository;
use Iteo\Shared\ClientId;
use Iteo\Shared\Money\Money;

/**
 * @extends ServiceEntityRepository<ClientEntity>
 *
 * @method ClientEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientEntity[]    findAll()
 * @method ClientEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class ClientEntityRepository extends ServiceEntityRepository implements ClientStateRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientEntity::class);
    }

    public function save(ClientState $clientState): void
    {
        $clientId = (string) $clientState->clientId;

        $clientEntity = $this->find($clientId) ?? new ClientEntity();

        $clientEntity->id = $clientId;
        $clientEntity->balance = $clientState->balance->amount->asFloat();
        $clientEntity->isBlocked = $clientState->isBlocked;

        $this->getEntityManager()->persist($clientEntity);
    }

    public function findById(ClientId|string $clientId): ?ClientEntity
    {
        return $this->find((string) $clientId);
    }

    public function findByClientId(ClientId $clientId): ?ClientState
    {
        $entity = $this->find((string) $clientId);

        return $entity
            ? new ClientState(
                new ClientId($entity->id),
                Money::fromFloat($entity->balance),
                $entity->isBlocked,
            )
            : null;
    }
}
