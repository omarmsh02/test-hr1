<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'), // Default password for all users
            'role' => $this->faker->randomElement(['admin', 'manager', 'employee']),
        ];
    }
}