<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Policy; // Import the Policy model

class PolicySeeder extends Seeder
{
    public function run()
    {
        Policy::factory()->count(10)->create();
    }
}