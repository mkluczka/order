<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CustomerEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Iteo\Customer\Domain\CustomerState\CustomerState;
use Iteo\Customer\Domain\ValueObject\CustomerId;
use Iteo\Shared\Money\Money;

#[Entity(repositoryClass: CustomerEntityRepository::class)]
#[Table("customers")]
class CustomerEntity
{
    #[Id, Column(type: Types::GUID)]
    public string $id;

    #[Column]
    public float $balance;

    #[OneToMany(OrderEntity::class, mappedBy: 'customer')]
    public Collection $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public static function fromCustomerState(CustomerState $state): self
    {
        $entity = new self();
        $entity->id = (string) $state->customerId;
        $entity->balance = $state->balance->amount->asFloat();

        return $entity;
    }

    public function applyCustomerState(CustomerState $customerState): void
    {
        $this->balance = $customerState->balance->amount->asFloat();
    }

    public function asCustomerState(): CustomerState
    {
        return new CustomerState(
            new CustomerId($this->id),
            Money::fromFloat($this->balance),
            $this->orders
                ->map(
                    function (OrderEntity $orderEntity) {
                        return $orderEntity->orderId();
                    }
                )
                ->toArray(),
        );
    }
}
