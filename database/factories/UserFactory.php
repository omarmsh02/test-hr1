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
            'password' => bcrypt('omar123'),
            'department_id' => Department::inRandomOrder()->first()?->id ?? Department::factory(),
            'role' => $this->faker->randomElement(['admin', 'manager', 'employee']),
        ];
    }

    // Optional: Add state methods for clearer usage

    public function admin()
    {
        return $this->state(fn () => ['role' => 'admin']);
    }

    public function manager()
    {
        return $this->state(fn () => ['role' => 'manager']);
    }

    public function employee()
    {
        return $this->state(fn () => ['role' => 'employee']);
    }
}