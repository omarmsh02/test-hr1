<?php

namespace Database\Factories;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveFactory extends Factory
{
    protected $model = Leave::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(), // Automatically creates a related user if needed
            'type' => $this->faker->randomElement(['vacation', 'sick', 'personal']),
            'start_date' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'end_date' => $this->faker->dateTimeBetween('+1 day', '+2 months'),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'reason' => $this->faker->sentence,
        ];
    }
}