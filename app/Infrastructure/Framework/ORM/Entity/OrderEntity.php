<?php

namespace App\Infrastructure\Framework\ORM\Entity;

use App\Infrastructure\Framework\ORM\Repository\OrderEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: OrderEntityRepository::class)]
#[Table(name: '`orders`')]
class OrderEntity
{
    #[Id, Column(type: Types::GUID)]
    public string $id;

    #[Column(type: Types::GUID)]
    public string $clientId;
}
