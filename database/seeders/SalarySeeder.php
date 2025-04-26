<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Salary; // Import the Salary model

class SalarySeeder extends Seeder
{
    public function run()
    {
        Salary::factory()->count(10)->create();
    }
}