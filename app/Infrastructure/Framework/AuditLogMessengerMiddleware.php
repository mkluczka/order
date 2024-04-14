<?php

declare(strict_types=1);

namespace App\Infrastructure\Framework;

use App\Infrastructure\Framework\ORM\Repository\AuditLogEntityRepository;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final class AuditLogMessengerMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly AuditLogEntityRepository $auditLogEntityRepository)
    {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();

        $typeStamp = $envelope->last(MessageTypeStamp::class);

        if ($typeStamp) {
            $this->auditLogEntityRepository->addNewFromObject($typeStamp->type, $message);
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
