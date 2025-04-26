<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'), // Default password
            'department_id' => Department::inRandomOrder()->first()?->id ?? Department::factory(), // Assign a random department
            'role' => $this->faker->randomElement(['admin', 'manager', 'employee']),
        ];
    }
}