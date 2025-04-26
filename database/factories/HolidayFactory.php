<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Holiday;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Holiday>
 */
class HolidayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'date' => $this->faker->dateTimeThisYear(),
            'type' => $this->faker->randomElement(['public', 'national', 'regional']),
            'description' => $this->faker->sentence,
        ];
    }
}
