<?php

namespace Database\Seeders;

use App\Models\Leave;
use Illuminate\Database\Seeder;

class LeaveSeeder extends Seeder
{
    public function run()
    {
        Leave::factory()->count(10)->create();
    }
}