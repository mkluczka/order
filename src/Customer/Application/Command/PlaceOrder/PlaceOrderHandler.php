<?php

declare(strict_types=1);

namespace Iteo\Customer\Application\Command\PlaceOrder;

use Iteo\Customer\Domain\CustomerRepository;
use Iteo\Customer\Domain\ValueObject\CustomerId;
use Iteo\Customer\Domain\ValueObject\Order\Order;
use Iteo\Customer\Domain\ValueObject\Order\OrderId;
use Iteo\Customer\Domain\ValueObject\Order\OrderItem\OrderItem;
use Iteo\Customer\Domain\ValueObject\Order\OrderItem\ProductId;
use Iteo\Customer\Domain\ValueObject\Order\OrderItem\Quantity;
use Iteo\Customer\Domain\ValueObject\Order\OrderItem\Weight;
use Iteo\Shared\Money\Money;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class PlaceOrderHandler
{
    public function __construct(private CustomerRepository $customerRepository)
    {
    }

    public function __invoke(PlaceOrder $command): void
    {
        $customerId = new CustomerId($command->customerId);

        $customer = $this->customerRepository->getByCustomerId($customerId);
        $customer->placeOrder($this->buildOrder($command));

        $this->customerRepository->save($customer);
    }

    private function buildOrder(PlaceOrder $command): Order
    {
        return new Order(
            new OrderId($command->orderId),
            array_map(
                function (array $item) {
                    return new OrderItem(
                        new ProductId($item['productId']),
                        Money::fromFloat($item['price']),
                        Weight::fromFloat($item['weight']),
                        new Quantity($item['quantity'])
                    );
                },
                $command->orderItems
            )
        );
    }
}
