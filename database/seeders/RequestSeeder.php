<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Request; // Import the Request model

class RequestSeeder extends Seeder
{
    public function run()
    {
        Request::factory()->count(10)->create();
    }
}