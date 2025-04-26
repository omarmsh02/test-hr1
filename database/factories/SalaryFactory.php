<?php

namespace Database\Factories;

use App\Models\Salary;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalaryFactory extends Factory
{
    protected $model = Salary::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(), // Automatically creates a related user if needed
            'amount' => $this->faker->randomFloat(2, 1000, 10000), // Random salary amount (e.g., 1000.00 to 10000.00)
            'currency' => $this->faker->randomElement(['USD', 'EUR', 'GBP']),
            'effective_date' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
            'notes' => $this->faker->sentence,
        ];
    }
}