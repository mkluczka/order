<?php

declare(strict_types=1);

namespace App\Infrastructure\Client;

use App\Infrastructure\Framework\ORM\Entity\AuditLogEntity;
use App\Infrastructure\Framework\ORM\Repository\AuditLogEntityRepository;
use Iteo\Client\Domain\Event\ClientCreated;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class AuditLogEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private AuditLogEntityRepository $auditLogRepository)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ClientCreated::class => 'onClientCreated',
        ];
    }

    public function onClientCreated(ClientCreated $event): void
    {
        $auditLog = new AuditLogEntity();
        $auditLog->message = '[event] ClientCreated';
        $auditLog->payload = [
            'clientId' => (string) $event->clientId,
            'initialBalance' => $event->initialBalance->amount->asFloat(),
        ];

        $this->auditLogRepository->save($auditLog);
    }
}
