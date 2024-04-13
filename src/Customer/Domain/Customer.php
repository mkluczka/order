<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain;

use Iteo\Customer\Domain\CustomerState\CustomerStateTrait;
use Iteo\Customer\Domain\Event\CustomerCharged;
use Iteo\Customer\Domain\Event\CustomerCreated;
use Iteo\Customer\Domain\Event\OrderPlaced;
use Iteo\Customer\Domain\Exception\InsufficentFunds;
use Iteo\Customer\Domain\ValueObject\CustomerId;
use Iteo\Customer\Domain\ValueObject\Order\Order;
use Iteo\Shared\DomainEvent\DomainEventsTrait;
use Iteo\Shared\Money\Money;

final class Customer
{
    use DomainEventsTrait;
    use CustomerStateTrait;

    private Money $balance;

    public function __construct(
        private readonly CustomerId $id,
    ) {
    }

    public static function create(CustomerId $customerId, Money $initialBalance): self
    {
        $customer = new self($customerId);
        $customer->balance = $initialBalance;

        $customer->recordEvent(
            new CustomerCreated(
                $customerId,
                $initialBalance,
            )
        );

        return $customer;
    }

    public function placeOrder(Order $order): void
    {
        if ($order->price->isGreaterThen($this->balance)) {
            throw new InsufficentFunds($order->price, $this->balance);
        }

        $previousBalance = $this->balance;
        $this->balance = $this->balance->minus($order->price);

        $this->recordEvent(
            new OrderPlaced(
                $order->id,
                $this->id,
                $order->orderItems,
            )
        );

        if (!$previousBalance->equals($this->balance)) {
            $this->recordEvent(
                new CustomerCharged(
                    $this->id,
                    $previousBalance,
                    $this->balance,
                )
            );
        }
    }
}
