<?php

declare(strict_types=1);

namespace Iteo\Order\Domain;

use Iteo\Order\Domain\Event\OrderCreated;
use Iteo\Order\Domain\Exception\CannotPlaceOrderOnBlockedClient;
use Iteo\Order\Domain\Exception\ClientHasInsufficentFunds;
use Iteo\Order\Domain\Exception\OrderSizeTooSmall;
use Iteo\Order\Domain\Exception\OrderWeightTooBig;
use Iteo\Order\Domain\OrderState\OrderState;
use Iteo\Order\Domain\ValueObject\Client;
use Iteo\Order\Domain\ValueObject\ProductList;
use Iteo\Shared\ClientId;
use Iteo\Shared\DomainEvent\DomainEventsTrait;
use Iteo\Shared\OrderId;

final class Order
{
    use DomainEventsTrait;

    private const MIN_ORDER_SIZE = 5;
    private const MAX_WEIGHT = 24_000;

    public function __construct(
        public readonly OrderId $id,
        public readonly ClientId $clientId,
    ) {
    }

    public static function create(OrderId $orderId, ProductList $productList, Client $client): self
    {
        if ($client->isBlocked) {
            throw new CannotPlaceOrderOnBlockedClient($client->id);
        }

        if ($productList->totalPrice->isGreaterThan($client->balance)) {
            throw new ClientHasInsufficentFunds($productList->totalPrice, $client->balance);
        }

        if ($productList->totalWeight > self::MAX_WEIGHT) {
            throw new OrderWeightTooBig($productList->totalWeight, self::MAX_WEIGHT);
        }

        if ($productList->orderSize < self::MIN_ORDER_SIZE) {
            throw new OrderSizeTooSmall($productList->orderSize, self::MIN_ORDER_SIZE);
        }

        $order = new self($orderId, $client->id);

        $order->recordEvent(
            new OrderCreated(
                $order->id,
                $client->id,
                $productList->totalPrice,
                $productList->items,
            )
        );

        return $order;
    }

    public function save(): OrderState
    {
        return new OrderState(
            $this->id,
            $this->clientId,
        );
    }
}
