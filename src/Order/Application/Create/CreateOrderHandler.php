<?php

declare(strict_types=1);

namespace Iteo\Order\Application\Create;

use Iteo\Client\Ports\Summary\GetSummary as GetClientSummary;
use Iteo\Order\Domain\Exception\OrderClientWasNotFound;
use Iteo\Order\Domain\Exception\OrderIdIsAlreadyUsed;
use Iteo\Order\Domain\Order;
use Iteo\Order\Domain\Persistence\OrderRepository;
use Iteo\Order\Domain\Specification\OrderIdWasUsed;
use Iteo\Order\Domain\ValueObject\Client;
use Iteo\Order\Domain\ValueObject\Product\Product;
use Iteo\Order\Domain\ValueObject\Product\Quantity;
use Iteo\Order\Domain\ValueObject\Product\Weight;
use Iteo\Order\Domain\ValueObject\ProductList;
use Iteo\Shared\ClientId;
use Iteo\Shared\Money\Money;
use Iteo\Shared\OrderId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateOrderHandler
{
    public function __construct(
        private OrderIdWasUsed $orderIdWasUsed,
        private GetClientSummary $getClientSummary,
        private OrderRepository $orderRepository,
    ) {
    }

    public function __invoke(CreateOrder $command): void
    {
        $orderId = new OrderId($command->orderId);
        $clientId = new ClientId($command->clientId);

        if ($this->orderIdWasUsed->isSatisfiedBy($orderId)) {
            throw new OrderIdIsAlreadyUsed($orderId);
        }

        $orderClient = $this->getOrderClient($clientId);
        $productList = new ProductList($this->buildProducts($command->products));

        $order = Order::create($orderId, $productList, $orderClient);

        $this->orderRepository->save($order);
    }

    private function getOrderClient(ClientId $clientId): Client
    {
        $clientSummary = $this->getClientSummary->byClientId($clientId);

        if (null === $clientSummary) {
            throw new OrderClientWasNotFound($clientId);
        }

        return new Client(
            $clientId,
            $clientSummary->balance,
            $clientSummary->isBlocked,
        );
    }

    /**
     * @param array{productId: string, quantity: int, weight: float, price: float}[] $rawProducts
     * @return Product[]
     */
    private function buildProducts(array $rawProducts): array
    {
        return array_map(
            function (array $item) {
                return new Product(
                    $item['productId'],
                    Money::fromFloat($item['price']),
                    Weight::fromFloat($item['weight']),
                    new Quantity($item['quantity'])
                );
            },
            $rawProducts
        );
    }
}
