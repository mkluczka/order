<?php

declare(strict_types=1);

namespace Iteo\Customer\Application\Command\PlaceOrder;

use Iteo\Customer\Domain\Exception\OrderIdIsAlreadyUsed;
use Iteo\Customer\Domain\Persistence\CustomerRepository;
use Iteo\Customer\Domain\Specification\OrderIdWasUsed;
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
    public function __construct(
        private OrderIdWasUsed $orderIdWasUsed,
        private CustomerRepository $customerRepository
    ) {
    }

    public function __invoke(PlaceOrder $command): void
    {
        $orderId = new OrderId($command->orderId);
        $customerId = new CustomerId($command->customerId);

        $order = new Order($orderId, $this->buildOrderItems($command));

        if (!$this->orderIdWasUsed->isSatisfiedBy($orderId)) {
            throw new OrderIdIsAlreadyUsed($orderId);
        }

        $customer = $this->customerRepository->getByCustomerId($customerId);
        $customer->placeOrder($order);

        $this->customerRepository->save($customer);
    }

    /**
     * @return array<OrderItem>
     */
    private function buildOrderItems(PlaceOrder $command): array
    {
        return array_map(
            function (array $item) {
                return new OrderItem(
                    new ProductId($item['productId']),
                    Money::fromFloat($item['price']),
                    Weight::fromFloat($item['weight']),
                    new Quantity($item['quantity'])
                );
            },
            $command->orderItems
        );
    }
}
