<?php

declare(strict_types=1);

namespace Iteo\Client\Domain;

use Iteo\Client\Domain\ClientState\ClientStateTrait;
use Iteo\Client\Domain\Event\ClientBlocked;
use Iteo\Client\Domain\Event\ClientCharged;
use Iteo\Client\Domain\Event\ClientCreated;
use Iteo\Client\Domain\Event\ClientToppedUp;
use Iteo\Client\Domain\Exception\InsufficentFunds;
use Iteo\Shared\ClientId;
use Iteo\Shared\DomainEvent\DomainEventsTrait;
use Iteo\Shared\Money\Money;

final class Client
{
    use DomainEventsTrait;
    use ClientStateTrait;

    private Money $balance;

    private bool $isBlocked = false;

    public function __construct(private readonly ClientId $id)
    {
    }

    public static function create(ClientId $clientId, string $clientName, Money $initialBalance): self
    {
        $client = new self($clientId);
        $client->balance = $initialBalance;

        $client->recordEvent(
            new ClientCreated(
                $clientId,
                $clientName,
                $initialBalance,
            )
        );

        return $client;
    }


    public function block(): void
    {
        if ($this->isBlocked) {
            return;
        }

        $this->isBlocked = true;

        $this->recordEvent(new ClientBlocked($this->id));
    }

    public function topUp(Money $additionalAmount): void
    {
        if ($additionalAmount->equals(Money::fromFloat(0))) {
            return;
        }

        $previousBalance = $this->balance;
        $this->balance = $this->balance->plus($additionalAmount);

        $this->recordEvent(
            new ClientToppedUp(
                $this->id,
                $previousBalance,
                $this->balance,
            )
        );
    }

    public function charge(Money $amount): void
    {
        if ($amount->equals(Money::fromFloat(0))) {
            return;
        }

        if ($amount->isGreaterThan($this->balance)) {
            throw new InsufficentFunds($amount, $this->balance);
        }

        $previousBalance = $this->balance;
        $this->balance = $this->balance->minus($amount);

        $this->recordEvent(new ClientCharged($this->id, $previousBalance, $this->balance));
    }
}
