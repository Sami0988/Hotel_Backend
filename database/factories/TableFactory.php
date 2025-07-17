<?php

namespace Database\Factories;

use App\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;

class TableFactory extends Factory
{
    protected $model = Table::class;

   public function definition()
{
    $allowedStatuses = ['available', 'reserved', 'occupied', 'out_of_service'];
    $allowedChairTypes = ['standard', 'barstool', 'booth', 'armchair'];

    return [
        'table_number' => 'T' . $this->faker->unique()->numberBetween(100, 999),
        'seats' => $this->faker->numberBetween(2, 8),
        'status' => $this->faker->randomElement($allowedStatuses),
        'chair_type' => $this->faker->randomElement($allowedChairTypes),
        'location' => $this->faker->randomElement(['main_dining', 'rooftop', 'garden']),
        'min_guests' => 1,
        'max_guests' => $this->faker->numberBetween(4, 8),
        'is_smoking_allowed' => $this->faker->boolean(),
        'price_per_hour' => $this->faker->randomFloat(2, 20, 100),
        'description' => $this->faker->sentence,
    ];
}

}
