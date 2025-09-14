<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\postJson;

it('logs in a user with correct credentials', function () {
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'password' => Hash::make('password123'),
    ]);

    $payload = [
        'email' => 'john@example.com',
        'password' => 'password123',
    ];

    $response = postJson('/api/login', $payload);

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                'user' => [
                    'id',
                    'name',
                    'email',
                ],
                'token',
            ],
        ]);
});

it('fails to login with wrong password', function () {
    $user = User::factory()->create([
        'email' => 'jane@example.com',
        'password' => Hash::make('correct-password'),
    ]);

    $payload = [
        'email' => 'jane@example.com',
        'password' => 'wrong-password',
    ];

    $response = postJson('/api/login', $payload);

    $response->assertUnauthorized()
        ->assertJson([
            'message' => 'Invalid login credentials',
        ]);
});

it('fails to login with non-existent email', function () {
    $payload = [
        'email' => 'notfound@example.com',
        'password' => 'irrelevant123',
    ];

    $response = postJson('/api/login', $payload);

    $response->assertUnauthorized()
        ->assertJson([
            'message' => 'Invalid login credentials',
        ]);
});

it('fails validation when required fields are missing', function () {
    $response = postJson('/api/login', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email', 'password']);
});
