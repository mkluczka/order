<?php

declare(strict_types=1);

namespace Iteo\Client\Application\Command\PlaceOrder;

use Iteo\Client\Domain\Exception\OrderIdIsAlreadyUsed;
use Iteo\Client\Domain\Persistence\ClientRepository;
use Iteo\Client\Domain\Specification\OrderIdWasUsed;
use Iteo\Client\Domain\ValueObject\ClientId;
use Iteo\Client\Domain\ValueObject\Order\Order;
use Iteo\Client\Domain\ValueObject\Order\OrderId;
use Iteo\Client\Domain\ValueObject\Order\Product\Product;
use Iteo\Client\Domain\ValueObject\Order\Product\ProductId;
use Iteo\Client\Domain\ValueObject\Order\Product\Quantity;
use Iteo\Client\Domain\ValueObject\Order\Product\Weight;
use Iteo\Shared\Money\Money;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class PlaceOrderHandler
{
    public function __construct(
        private OrderIdWasUsed $orderIdWasUsed,
        private ClientRepository $clientRepository
    ) {
    }

    public function __invoke(PlaceOrder $command): void
    {
        $orderId = new OrderId($command->orderId);
        $clientId = new ClientId($command->clientId);

        $order = new Order($orderId, $this->buildProducts($command));

        if (!$this->orderIdWasUsed->isSatisfiedBy($orderId)) {
            throw new OrderIdIsAlreadyUsed($orderId);
        }

        $client = $this->clientRepository->getByClientId($clientId);
        $client->placeOrder($order);

        $this->clientRepository->save($client);
    }

    /**
     * @return array<Product>
     */
    private function buildProducts(PlaceOrder $command): array
    {
        return array_map(
            function (array $item) {
                return new Product(
                    new ProductId($item['productId']),
                    Money::fromFloat($item['price']),
                    Weight::fromFloat($item['weight']),
                    new Quantity($item['quantity'])
                );
            },
            $command->products
        );
    }
}
