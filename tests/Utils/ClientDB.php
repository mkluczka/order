<?php

declare(strict_types=1);

namespace Tests\Utils;

use App\Infrastructure\Framework\ORM\Entity\ClientEntity;
use Doctrine\ORM\EntityManagerInterface;

trait ClientDB
{
    private static function entityManager(): EntityManagerInterface
    {
        return self::getContainer()->get(EntityManagerInterface::class);
    }

    protected static function dbAddClient(string $clientId): void
    {
        $client = new ClientEntity();
        $client->id = $clientId;
        $client->balance = 111;
        $client->isBlocked = false;

        self::entityManager()->persist($client);
        self::entityManager()->flush();
    }
}
