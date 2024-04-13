<?php

namespace App\Entity;

use App\Repository\OrderEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Iteo\Customer\Domain\ValueObject\Order\OrderId;

#[Entity(repositoryClass: OrderEntityRepository::class)]
#[Table(name: '`orders`')]
class OrderEntity
{
    #[Id, Column(type: Types::GUID)]
    public string $id;

    #[ManyToOne(CustomerEntity::class, cascade: ['persist'], inversedBy: 'orders')]
    public CustomerEntity $customer;

    public function __construct(string $id, CustomerEntity $customer)
    {
        $this->id = $id;
        $this->customer = $customer;
    }

    public function orderId(): OrderId
    {
        return new OrderId($this->id);
    }
}
