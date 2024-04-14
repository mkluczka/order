<?php

namespace App\Infrastructure\Framework\ORM\Entity;

use App\Infrastructure\Framework\ORM\Repository\AuditLogEntityRepository;
use Doctrine\DBAL\Types\Types;
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

    #[Column(type: Types::STRING)]
    public string $createdAt;

    public function __construct()
    {
        $this->createdAt = date('Y-m-d H:i:s');
    }

    /**
     * @return array{?int, string, string, string}
     */
    public function asArray(): array
    {
        return [
            $this->id,
            $this->message,
            (string) json_encode($this->payload, JSON_PRETTY_PRINT),
            $this->createdAt,
        ];
    }
}
