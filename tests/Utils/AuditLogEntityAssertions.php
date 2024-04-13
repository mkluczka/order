<?php

declare(strict_types=1);

namespace Tests\Utils;

use App\Repository\AuditLogEntityRepository;
use PHPUnit\Framework\Assert;

trait AuditLogEntityAssertions
{
    protected function getAuditLogEntityRepository(): AuditLogEntityRepository
    {
        return self::getContainer()->get(AuditLogEntityRepository::class);
    }

    protected function assertDatabaseHasCustomerCreated(string $customerId, float $initialBalance): void
    {
        $result = self::getAuditLogEntityRepository()->findAll();

        Assert::assertCount(1, $result);
        Assert::assertEquals('[event] CustomerCreated', $result[0]->message);
        Assert::assertSame(['customerId' => $customerId, 'initialBalance' => $initialBalance], $result[0]->payload);
    }
}
