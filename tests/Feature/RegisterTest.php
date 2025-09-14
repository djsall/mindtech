<?php

use App\Models\User;

use function Pest\Laravel\postJson;

it('registers a user successfully', function () {
    $payload = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = postJson('/api/register', $payload);

    $response->assertSuccessful()
        ->assertJsonStructure([
            'data' => [
                'token',
                'user' => [
                    'id',
                    'name',
                    'email',
                ],
            ],
        ]);

    expect(User::where('email', 'john@example.com')->exists())->toBeTrue();
});

it('fails when required fields are missing', function () {
    $response = postJson('/api/register', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'email', 'password']);
});

it('fails when email is not unique', function () {
    User::factory()->create(['email' => 'duplicate@example.com']);

    $payload = [
        'name' => 'Jane Doe',
        'email' => 'duplicate@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = postJson('/api/register', $payload);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

it('fails when password confirmation does not match', function () {
    $payload = [
        'name' => 'Mismatch User',
        'email' => 'mismatch@example.com',
        'password' => 'password123',
        'password_confirmation' => 'differentpassword',
    ];

    $response = postJson('/api/register', $payload);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['password']);
});
