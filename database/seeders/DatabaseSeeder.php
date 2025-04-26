<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            DepartmentSeeder::class, // Seed departments first
            UserSeeder::class,       // Then seed users
            AttendanceSeeder::class,
            HolidaySeeder::class,
            LeaveSeeder::class,
            PolicySeeder::class,
            RequestSeeder::class,
            SalarySeeder::class,
        ]);

        // Assign managers to departments after seeding users
        $this->assignManagersToDepartments();
    }

    private function assignManagersToDepartments()
    {
        $departments = \App\Models\Department::all();
        $managers = \App\Models\User::where('role', 'manager')->get();

        foreach ($departments as $department) {
            $manager = $managers->random(); // Assign a random manager
            $department->update(['manager_id' => $manager->id]);
        }
    }
}