<?php

declare(strict_types=1);

namespace Iteo\Client\Domain\ClientState;

use Iteo\Client\Domain\Client;

trait ClientStateTrait
{
    public function save(): ClientState
    {
        return new ClientState(
            $this->id,
            $this->balance,
            $this->orders,
            $this->isBlocked,
        );
    }

    public static function restore(ClientState $state): Client
    {
        $client = new Client($state->clientId);
        $client->balance = $state->balance;
        $client->orders = $state->orders;
        $client->isBlocked = $state->isBlocked;

        return $client;
    }
}
