<?php

namespace Database\Seeders;

use App\Models\Leave;
use Illuminate\Database\Seeder;

class LeaveSeeder extends Seeder
{
    public function run()
    {
        // Ensure there are users in the database
        if (\App\Models\User::count() > 0) {
            Leave::factory()->count(5)->create();
        } else {
            throw new \Exception('No users found in the database. Please seed users first.');
        }
    }
}