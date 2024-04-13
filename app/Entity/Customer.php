<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CustomerEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Iteo\Customer\Domain\CustomerState\CustomerState;

#[ORM\Entity(repositoryClass: CustomerEntityRepository::class)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::GUID)]
    public string $uuid;

    #[ORM\Column]
    public float $balance;

    public function getId(): ?int
    {
        return $this->id;
    }

    public static function fromCustomerState(CustomerState $state): self
    {
        $entity = new self();
        $entity->uuid = (string) $state->customerId;
        $entity->balance = $state->balance->amount->asFloat();

        return $entity;
    }
}
