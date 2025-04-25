<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,       // Seed users first
            DepartmentSeeder::class, // Departments depend on users (e.g., managers)
            HolidaySeeder::class,    // Holidays are independent
            LeaveSeeder::class,      // Leaves depend on users
            SalarySeeder::class,     // Salaries depend on users
            PolicySeeder::class,     // Policies are independent
            AttendanceSeeder::class, // Attendance depends on users
        ]);
    }
}