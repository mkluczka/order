<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain\ValueObject\Order\OrderItem;

use Iteo\Shared\Money\Money;

final readonly class OrderItem
{
    public Weight $weight;
    public Money $price;

    public function __construct(
        public ProductId $productId,
        public Money $productPrice,
        public Weight $productWeight,
        public Quantity $productQuantity,
    ) {
        $this->price = $this->productPrice->multiplyBy($this->productQuantity->value);
        $this->weight = $this->productWeight->multiplyBy($this->productQuantity->value);
    }
}
