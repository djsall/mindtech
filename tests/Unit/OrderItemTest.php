<?php

use App\DataObjects\OrderItem;

test('order item value-object', function () {
    $item = new \App\DataObjects\OrderItem(1, 2, 'No seaweed please');

    expect($item)->toBeInstanceOf(OrderItem::class)
        ->id->toBe(1)
        ->quantity->toBe(2)
        ->instructions->toBe('No seaweed please');
});
