<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),  // Creates or uses a User
            'room_id' => Room::factory(),  // Creates or uses a Room
            'check_in' => $this->faker->dateTimeBetween('now', '+1 month'),
            'check_out' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'guests_count' => $this->faker->numberBetween(1, 5),
            'total_price' => $this->faker->randomFloat(2, 50, 500),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled', 'completed']),
            'special_requests' => $this->faker->optional()->sentence(),
        ];
    }
}
