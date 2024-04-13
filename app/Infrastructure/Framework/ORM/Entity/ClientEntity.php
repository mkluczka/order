<?php

declare(strict_types=1);

namespace App\Infrastructure\Framework\ORM\Entity;

use App\Infrastructure\Framework\ORM\Repository\ClientEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Iteo\Client\Domain\ClientState\ClientState;
use Iteo\Client\Domain\ValueObject\ClientId;
use Iteo\Shared\Money\Money;

#[Entity(repositoryClass: ClientEntityRepository::class)]
#[Table("clients")]
class ClientEntity
{
    #[Id, Column(type: Types::GUID)]
    public string $id;

    #[Column]
    public float $balance;

    #[OneToMany(OrderEntity::class, mappedBy: 'client')]
    public Collection $orders;

    #[Column]
    public bool $isBlocked;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public static function fromClientState(ClientState $state): self
    {
        $entity = new self();
        $entity->id = (string) $state->clientId;
        $entity->balance = $state->balance->amount->asFloat();
        $entity->isBlocked = $state->isBlocked;

        return $entity;
    }

    public function applyClientState(ClientState $clientState): void
    {
        $this->balance = $clientState->balance->amount->asFloat();
        $this->isBlocked = $clientState->isBlocked;
    }

    public function asClientState(): ClientState
    {
        return new ClientState(
            new ClientId($this->id),
            Money::fromFloat($this->balance),
            $this->orders
                ->map(
                    function (OrderEntity $orderEntity) {
                        return $orderEntity->orderId();
                    }
                )
                ->toArray(),
            $this->isBlocked,
        );
    }
}
