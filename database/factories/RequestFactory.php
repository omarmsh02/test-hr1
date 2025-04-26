<?php

namespace Database\Factories;

use App\Models\Request;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RequestFactory extends Factory
{
    protected $model = Request::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(), // Automatically creates a related user if needed
            'type' => $this->faker->randomElement(['leave', 'equipment', 'other']),
            'description' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}