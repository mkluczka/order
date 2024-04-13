<?php

declare(strict_types=1);

namespace App\Infrastructure\Framework\ORM\Repository;

use App\Infrastructure\Framework\ORM\Entity\ClientEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Iteo\Client\Domain\ClientState\ClientState;
use Iteo\Client\Domain\Persistence\ClientStateRepository;
use Iteo\Client\Domain\ValueObject\ClientId;

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
    public function __construct(
        ManagerRegistry $registry,
        private readonly OrderEntityRepository $orderEntityRepository,
    ) {
        parent::__construct($registry, ClientEntity::class);
    }

    public function findById(ClientId|string $clientId): ?ClientEntity
    {
        return $this->find((string) $clientId);
    }

    public function save(ClientState $clientState): void
    {
        $entityManager = $this->getEntityManager();

        $clientEntity = $this->findById($clientState->clientId);

        if (null === $clientEntity) {
            $clientEntity = ClientEntity::fromClientState($clientState);
        } else {
            $clientEntity->applyClientState($clientState);
        }

        $this->orderEntityRepository->applyOrders($clientEntity, $clientState);

        $entityManager->persist($clientEntity);
    }

    public function findByClientId(ClientId $clientId): ?ClientState
    {
        $entity = $this->findById($clientId);

        return $entity?->asClientState();
    }
}
