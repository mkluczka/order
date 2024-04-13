<?php

namespace App\Entity;

use App\Repository\OrderEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Iteo\Client\Domain\ValueObject\Order\OrderId;

#[Entity(repositoryClass: OrderEntityRepository::class)]
#[Table(name: '`orders`')]
class OrderEntity
{
    #[Id, Column(type: Types::GUID)]
    public string $id;

    #[ManyToOne(ClientEntity::class, cascade: ['persist'], inversedBy: 'orders')]
    public ClientEntity $client;

    public function __construct(string $id, ClientEntity $clientEntity)
    {
        $this->id = $id;
        $this->client = $clientEntity;
    }

    public function orderId(): OrderId
    {
        return new OrderId($this->id);
    }
}
