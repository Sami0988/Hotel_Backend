<?php

namespace Database\Factories;

use App\Models\TableReservation;
use App\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TableReservationFactory extends Factory
{
    protected $model = TableReservation::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'table_id' => Table::factory(), 
            'reservation_time' => $this->faker->dateTimeBetween('+1 days', '+1 month'),
            'guests_count' => $this->faker->numberBetween(1, 4),
            'special_requests' => $this->faker->sentence(),
            'status' => 'pending',
        ];
    }
}
