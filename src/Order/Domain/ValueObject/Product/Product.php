<?php

declare(strict_types=1);

namespace Iteo\Order\Domain\ValueObject\Product;

use Iteo\Shared\Money\Money;

final readonly class Product
{
    public Weight $weight;
    public Money $price;

    public function __construct(
        public string $productId,
        public Money $productPrice,
        public Weight $productWeight,
        public Quantity $quantity,
    ) {
        $this->price = $this->productPrice->multiplyBy($this->quantity->value);
        $this->weight = new Weight($this->productWeight->decimal->multiplyBy($this->quantity->value));
    }
}
