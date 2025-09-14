<?php

namespace App\DataObjects;

use Spatie\LaravelData\Data;

class OrderItem extends Data
{
    public function __construct(
        public int $id,
        public int $quantity,
        public ?string $instructions,
    ) {}
}
