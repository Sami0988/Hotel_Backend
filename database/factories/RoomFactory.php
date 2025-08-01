<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   public function definition(): array
{
    return [
        'room_number' => $this->faker->unique()->numberBetween(100, 999),
        'type' => $this->faker->randomElement(['Single', 'Double', 'Suite']),
        'capacity' => $this->faker->numberBetween(1, 4),
        'price_per_night' => $this->faker->randomFloat(2, 50, 300),
        'status' => $this->faker->randomElement(['available', 'occupied', 'maintenance']),
    ];
}

}
