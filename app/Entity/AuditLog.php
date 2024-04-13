<?php

namespace App\Entity;

use App\Repository\AuditLogEntityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuditLogEntityRepository::class)]
class AuditLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    public string $message;

    /**
     * @var null|array<mixed> $payload
     */
    #[ORM\Column(length: 255, nullable: true)]
    public ?array $payload = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
