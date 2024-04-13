<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain\ValueObject\Order\OrderItem;

final readonly class OrderItem
{
    public Weight $weight;

    public function __construct(
        public ProductId $productId,
        public Weight $productWeight,
        public Quantity $productQuantity,
    ) {
        $this->weight = $this->productWeight->multiplyBy($this->productQuantity->value);
    }
}
