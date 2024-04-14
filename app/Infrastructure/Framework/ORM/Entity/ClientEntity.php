<?php

declare(strict_types=1);

namespace App\Infrastructure\Framework\ORM\Entity;

use App\Infrastructure\Framework\ORM\Repository\ClientEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: ClientEntityRepository::class)]
#[Table("clients")]
class ClientEntity
{
    #[Id, Column(type: Types::GUID)]
    public string $id;

    #[Column]
    public float $balance;

    #[Column]
    public bool $isBlocked;
}
