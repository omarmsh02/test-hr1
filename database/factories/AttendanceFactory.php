<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(), // Automatically creates a related user if needed
            'attendance_date' => $this->faker->dateTimeThisYear(),
            'check_in' => $this->faker->time(),
            'check_out' => $this->faker->time(),
            'status' => $this->faker->randomElement(['present', 'late', 'absent']),
            'notes' => $this->faker->sentence,
        ];
    }
}