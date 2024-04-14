<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared;

use App\Infrastructure\Framework\ORM\Repository\AuditLogEntityRepository;
use Iteo\Client\Domain\Event\ClientBlocked;
use Iteo\Client\Domain\Event\ClientCharged;
use Iteo\Client\Domain\Event\ClientCreated;
use Iteo\Client\Domain\Event\ClientToppedUp;
use Iteo\Order\Domain\Event\OrderCreated;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class AuditLogEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private AuditLogEntityRepository $auditLogRepository)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ClientCreated::class => 'saveAuditLog',
            ClientCharged::class => 'saveAuditLog',
            ClientBlocked::class => 'saveAuditLog',
            ClientToppedUp::class => 'saveAuditLog',
            OrderCreated::class => 'saveAuditLog',
        ];
    }

    public function saveAuditLog(object $event): void
    {
        $this->auditLogRepository->addNewFromObject('domain-event', $event);
    }
}
