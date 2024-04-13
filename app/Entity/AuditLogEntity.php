<?php

namespace App\Entity;

use App\Repository\AuditLogEntityRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: AuditLogEntityRepository::class)]
#[Table('audit_log')]
class AuditLogEntity
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    public ?int $id = null;

    #[Column(length: 255)]
    public string $message;

    /**
     * @var null|array<mixed> $payload
     */
    #[Column(length: 255, nullable: true)]
    public ?array $payload = null;
}
