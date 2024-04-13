<?php

declare(strict_types=1);

namespace App\Customer;

use App\Entity\AuditLog;
use App\Repository\AuditLogEntityRepository;
use Iteo\Customer\Domain\Event\CustomerCreated;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class AuditLogEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private AuditLogEntityRepository $auditLogRepository)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CustomerCreated::class => 'onCustomerCreated',
        ];
    }

    public function onCustomerCreated(CustomerCreated $event): void
    {
        $auditLog = new AuditLog();
        $auditLog->message = '[event] CustomerCreated';
        $auditLog->payload = [
            'customerId' => (string) $event->customerId,
            'initialBalance' => $event->initialBalance->amount,
        ];

        $this->auditLogRepository->save($auditLog);
    }
}
