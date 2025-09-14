<?php

use App\Models\Restaurant;

use function Pest\Laravel\getJson;

beforeEach(function () {
    // Assuming $this->token is already set globally in your Pest setup
    $this->headers = [
        'Authorization' => "Bearer {$this->token}",
        'Accept' => 'application/json',
    ];
});

it('returns a list of restaurants', function () {
    Restaurant::factory()->count(3)->create();

    $response = getJson('/api/restaurants', $this->headers);

    $response->assertOk()
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                ],
            ],
        ]);
});

it('returns a single restaurant with menu items', function () {
    $restaurant = Restaurant::factory()->hasMenuItems(2)->create();

    $response = getJson("/api/restaurants/{$restaurant->id}/menu", $this->headers);

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'menuItems' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                    ],
                ],
            ],
        ]);

    $responseData = $response->json('data');

    // Check the menu items count
    expect(count($responseData['menuItems']))->toBe(2);
});
