<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;

use function Pest\Laravel\getJson;
use function Pest\Laravel\patchJson;
use function Pest\Laravel\postJson;

beforeEach(function () {
    $this->headers = [
        'Authorization' => "Bearer {$this->token}",
        'Accept' => 'application/json',
    ];
});

it('lists orders for a restaurant', function () {
    $restaurant = Restaurant::factory()->hasMenuItems(10)->create();
    $orders = Order::factory()->count(3)->forRestaurant($restaurant)->create();

    $response = getJson("/api/restaurants/{$restaurant->id}/orders");

    $response->assertOk()
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'restaurantId',
                    'customerId',
                    'items',
                    'status',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
});

it('creates a new order successfully', function () {
    $user = User::factory()->create();
    $restaurant = Restaurant::factory()->hasMenuItems(10)->create();
    $menuItems = $restaurant->menuItems;

    $payload = [
        'customerId' => $user->id,
        'restaurantId' => $restaurant->id,
        'items' => $menuItems->map(fn ($item) => [
            'id' => $item->id,
            'quantity' => 2,
            'instructions' => 'Extra spicy',
        ])->toArray(),
    ];

    $response = postJson('/api/orders', $payload, $this->headers);

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                'id',
                'restaurantId',
                'customerId',
                'items',
                'status',
                'created_at',
                'updated_at',
            ],
        ]);

    expect(Order::where('restaurant_id', $restaurant->id)
        ->where('user_id', $user->id)->exists())->toBeTrue();
});

it('shows a single order', function () {
    $restaurant = Restaurant::factory()->hasMenuItems(10)->create();
    $order = Order::factory()->forRestaurant($restaurant)->create();

    $response = getJson("/api/restaurants/{$restaurant->id}/orders/{$order->id}");

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                'id',
                'restaurantId',
                'customerId',
                'items',
                'status',
                'created_at',
                'updated_at',
            ],
        ]);
});

it('updates the order status', function () {
    $restaurant = Restaurant::factory()->hasMenuItems(10)->create();
    $order = Order::factory()->forRestaurant($restaurant)->create();

    $payload = [
        'status' => OrderStatus::Delivered->value,
    ];

    $response = patchJson("/api/restaurants/{$restaurant->id}/orders/{$order->id}", $payload);

    $response->assertOk()
        ->assertJsonPath('data.status', OrderStatus::Delivered->value);

    expect($order->fresh()->status)->toBe(OrderStatus::Delivered);
});
