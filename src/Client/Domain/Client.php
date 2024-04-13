<?php

declare(strict_types=1);

namespace Iteo\Client\Domain;

use Iteo\Client\Domain\ClientState\ClientStateTrait;
use Iteo\Client\Domain\Event\ClientCharged;
use Iteo\Client\Domain\Event\ClientCreated;
use Iteo\Client\Domain\Event\OrderPlaced;
use Iteo\Client\Domain\Exception\InsufficentFunds;
use Iteo\Client\Domain\ValueObject\ClientId;
use Iteo\Client\Domain\ValueObject\Order\Order;
use Iteo\Client\Domain\ValueObject\Order\OrderId;
use Iteo\Shared\DomainEvent\DomainEventsTrait;
use Iteo\Shared\Money\Money;

final class Client
{
    use DomainEventsTrait;
    use ClientStateTrait;

    private Money $balance;
    /** @var array<OrderId> */
    private array $orders = [];

    public function __construct(private readonly ClientId $id)
    {
    }

    public static function create(ClientId $clientId, Money $initialBalance): self
    {
        $client = new self($clientId);
        $client->balance = $initialBalance;

        $client->recordEvent(
            new ClientCreated(
                $clientId,
                $initialBalance,
            )
        );

        return $client;
    }

    public function placeOrder(Order $order): void
    {
        if (in_array($order->id, $this->orders, true)) {
            return;
        }

        if ($order->price->isGreaterThen($this->balance)) {
            throw new InsufficentFunds($order->price, $this->balance);
        }

        $previousBalance = $this->balance;
        $this->balance = $this->balance->minus($order->price);
        $this->orders[] = $order->id;

        $this->recordEvent(
            new OrderPlaced(
                $order->id,
                $this->id,
                $order->orderItems,
            )
        );

        if (!$previousBalance->equals($this->balance)) {
            $this->recordEvent(
                new ClientCharged(
                    $this->id,
                    $previousBalance,
                    $this->balance,
                )
            );
        }
    }
}
