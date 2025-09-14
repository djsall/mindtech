<?php

namespace Database\Factories;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected Restaurant $restaurant;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'restaurant_id' => $this->restaurant->id,
            'items' => [
                [
                    'id' => $this->restaurant->menuItems()->inRandomOrder()->first()->id,
                    'quantity' => $this->faker->numberBetween(1, 10),
                    'instructions' => $this->faker->sentence(),
                ],
            ],
        ];
    }

    public function forRestaurant(Restaurant $restaurant): static
    {
        $this->restaurant = $restaurant;

        return $this;
    }
}
