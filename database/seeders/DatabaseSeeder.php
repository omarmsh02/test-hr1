<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Request;
use App\Models\Salary;
use App\Models\Policy;
use App\Models\Holiday;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create departments
        $departments = [
            ['name' => 'Human Resources', 'description' => 'HR Department'],
            ['name' => 'Information Technology', 'description' => 'IT Department'],
            ['name' => 'Finance', 'description' => 'Finance Department'],
            ['name' => 'Operations', 'description' => 'Operations Department'],
            ['name' => 'Marketing', 'description' => 'Marketing Department'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }

        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@workwise.com',
            'password' => Hash::make('admin123'),
            'department_id' => Department::where('name', 'Human Resources')->first()->id,
            'role' => 'admin'
        ]);

        // Create 4 managers
        $managers = [
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@workwise.com',
                'password' => Hash::make('manager123'),
                'department_id' => Department::where('name', 'Information Technology')->first()->id,
                'role' => 'manager'
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael.chen@workwise.com',
                'password' => Hash::make('manager123'),
                'department_id' => Department::where('name', 'Finance')->first()->id,
                'role' => 'manager'
            ],
            [
                'name' => 'David Wilson',
                'email' => 'david.wilson@workwise.com',
                'password' => Hash::make('manager123'),
                'department_id' => Department::where('name', 'Operations')->first()->id,
                'role' => 'manager'
            ],
            [
                'name' => 'Lisa Rodriguez',
                'email' => 'lisa.rodriguez@workwise.com',
                'password' => Hash::make('manager123'),
                'department_id' => Department::where('name', 'Marketing')->first()->id,
                'role' => 'manager'
            ]
        ];

        foreach ($managers as $managerData) {
            $manager = User::create($managerData);
            
            // Assign manager to department
            $department = Department::find($managerData['department_id']);
            $department->manager_id = $manager->id;
            $department->save();
        }

        // Create 5 employees for each manager (20 total)
        $employees = [
            // IT Department (Sarah Johnson)
            [
                'name' => 'John Smith', 'email' => 'john.smith@workwise.com',
                'department_id' => Department::where('name', 'Information Technology')->first()->id,
                'manager_id' => User::where('email', 'sarah.johnson@workwise.com')->first()->id
            ],
            [
                'name' => 'Emily Davis', 'email' => 'emily.davis@workwise.com',
                'department_id' => Department::where('name', 'Information Technology')->first()->id,
                'manager_id' => User::where('email', 'sarah.johnson@workwise.com')->first()->id
            ],
            [
                'name' => 'Robert Brown', 'email' => 'robert.brown@workwise.com',
                'department_id' => Department::where('name', 'Information Technology')->first()->id,
                'manager_id' => User::where('email', 'sarah.johnson@workwise.com')->first()->id
            ],
            [
                'name' => 'Jennifer Lee', 'email' => 'jennifer.lee@workwise.com',
                'department_id' => Department::where('name', 'Information Technology')->first()->id,
                'manager_id' => User::where('email', 'sarah.johnson@workwise.com')->first()->id
            ],
            [
                'name' => 'Daniel Kim', 'email' => 'daniel.kim@workwise.com',
                'department_id' => Department::where('name', 'Information Technology')->first()->id,
                'manager_id' => User::where('email', 'sarah.johnson@workwise.com')->first()->id
            ],
            
            // Finance Department (Michael Chen)
            [
                'name' => 'Amanda Wilson', 'email' => 'amanda.wilson@workwise.com',
                'department_id' => Department::where('name', 'Finance')->first()->id,
                'manager_id' => User::where('email', 'michael.chen@workwise.com')->first()->id
            ],
            [
                'name' => 'James Taylor', 'email' => 'james.taylor@workwise.com',
                'department_id' => Department::where('name', 'Finance')->first()->id,
                'manager_id' => User::where('email', 'michael.chen@workwise.com')->first()->id
            ],
            [
                'name' => 'Olivia Martinez', 'email' => 'olivia.martinez@workwise.com',
                'department_id' => Department::where('name', 'Finance')->first()->id,
                'manager_id' => User::where('email', 'michael.chen@workwise.com')->first()->id
            ],
            [
                'name' => 'William Anderson', 'email' => 'william.anderson@workwise.com',
                'department_id' => Department::where('name', 'Finance')->first()->id,
                'manager_id' => User::where('email', 'michael.chen@workwise.com')->first()->id
            ],
            [
                'name' => 'Sophia Thomas', 'email' => 'sophia.thomas@workwise.com',
                'department_id' => Department::where('name', 'Finance')->first()->id,
                'manager_id' => User::where('email', 'michael.chen@workwise.com')->first()->id
            ],
            
            // Operations Department (David Wilson)
            [
                'name' => 'Matthew Jackson', 'email' => 'matthew.jackson@workwise.com',
                'department_id' => Department::where('name', 'Operations')->first()->id,
                'manager_id' => User::where('email', 'david.wilson@workwise.com')->first()->id
            ],
            [
                'name' => 'Emma White', 'email' => 'emma.white@workwise.com',
                'department_id' => Department::where('name', 'Operations')->first()->id,
                'manager_id' => User::where('email', 'david.wilson@workwise.com')->first()->id
            ],
            [
                'name' => 'Christopher Harris', 'email' => 'christopher.harris@workwise.com',
                'department_id' => Department::where('name', 'Operations')->first()->id,
                'manager_id' => User::where('email', 'david.wilson@workwise.com')->first()->id
            ],
            [
                'name' => 'Ava Martin', 'email' => 'ava.martin@workwise.com',
                'department_id' => Department::where('name', 'Operations')->first()->id,
                'manager_id' => User::where('email', 'david.wilson@workwise.com')->first()->id
            ],
            [
                'name' => 'Andrew Thompson', 'email' => 'andrew.thompson@workwise.com',
                'department_id' => Department::where('name', 'Operations')->first()->id,
                'manager_id' => User::where('email', 'david.wilson@workwise.com')->first()->id
            ],
            
            // Marketing Department (Lisa Rodriguez)
            [
                'name' => 'Isabella Garcia', 'email' => 'isabella.garcia@workwise.com',
                'department_id' => Department::where('name', 'Marketing')->first()->id,
                'manager_id' => User::where('email', 'lisa.rodriguez@workwise.com')->first()->id
            ],
            [
                'name' => 'Ethan Martinez', 'email' => 'ethan.martinez@workwise.com',
                'department_id' => Department::where('name', 'Marketing')->first()->id,
                'manager_id' => User::where('email', 'lisa.rodriguez@workwise.com')->first()->id
            ],
            [
                'name' => 'Mia Robinson', 'email' => 'mia.robinson@workwise.com',
                'department_id' => Department::where('name', 'Marketing')->first()->id,
                'manager_id' => User::where('email', 'lisa.rodriguez@workwise.com')->first()->id
            ],
            [
                'name' => 'Alexander Clark', 'email' => 'alexander.clark@workwise.com',
                'department_id' => Department::where('name', 'Marketing')->first()->id,
                'manager_id' => User::where('email', 'lisa.rodriguez@workwise.com')->first()->id
            ],
            [
                'name' => 'Charlotte Rodriguez', 'email' => 'charlotte.rodriguez@workwise.com',
                'department_id' => Department::where('name', 'Marketing')->first()->id,
                'manager_id' => User::where('email', 'lisa.rodriguez@workwise.com')->first()->id
            ]
        ];

        foreach ($employees as $employee) {
            User::create([
                'name' => $employee['name'],
                'email' => $employee['email'],
                'password' => Hash::make('employee123'),
                'department_id' => $employee['department_id'],
                'role' => 'employee'
            ]);
        }

        // Create sample attendance records for employees (last 30 days)
        $users = User::where('role', 'employee')->get();
        $today = now();
        
        foreach ($users as $user) {
            for ($i = 1; $i <= 20; $i++) { // 20 working days
                $date = $today->copy()->subDays($i);
                
                // Skip weekends
                if ($date->isWeekend()) continue;
                
                $checkIn = $date->copy()->setTime(rand(8, 9), rand(0, 59), rand(0, 59));
                $checkOut = $checkIn->copy()->addHours(rand(7, 9))->addMinutes(rand(0, 59));
                
                $status = 'present';
                if ($checkIn->format('H') > 9) $status = 'late';
                
                Attendance::create([
                    'user_id' => $user->id,
                    'attendance_date' => $date->format('Y-m-d'),
                    'check_in' => $checkIn->format('H:i:s'),
                    'check_out' => $checkOut->format('H:i:s'),
                    'status' => $status,
                    'notes' => $status === 'late' ? 'Arrived late due to traffic' : null
                ]);
            }
        }

        // Create sample leave requests
        $leaveTypes = ['casual', 'sick', 'annual'];
        
        foreach ($users as $user) {
            // 1-2 leave requests per employee
            for ($i = 0; $i < rand(1, 2); $i++) {
                $startDate = $today->copy()->subDays(rand(5, 30));
                $endDate = $startDate->copy()->addDays(rand(1, 3));
                
                Leave::create([
                    'user_id' => $user->id,
                    'type' => $leaveTypes[array_rand($leaveTypes)],
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => rand(0, 1) ? 'approved' : 'pending',
                    'reason' => ['Family emergency', 'Medical appointment', 'Personal time', 'Vacation'][array_rand([0,1,2,3])]
                ]);
            }
        }

        // Create sample requests (hardware/software)
        $requestTypes = ['Laptop', 'Monitor', 'Software License', 'Desk Accessories', 'Phone'];
        
        foreach ($users as $user) {
            // 1-3 requests per employee
            for ($i = 0; $i < rand(1, 3); $i++) {
                Request::create([
                    'user_id' => $user->id,
                    'type' => $requestTypes[array_rand($requestTypes)],
                    'description' => 'Need this for work purposes',
                    'status' => ['pending', 'approved', 'rejected'][array_rand([0,1,2])]
                ]);
            }
        }

        // Create salary records
        $baseSalaries = [
            'Information Technology' => [6000, 8000],
            'Finance' => [5000, 7000],
            'Operations' => [4500, 6500],
            'Marketing' => [5000, 7500]
        ];
        
        foreach ($users as $user) {
            $deptName = $user->department->name;
            $range = $baseSalaries[$deptName];
            $salary = rand($range[0], $range[1]) * 100; // Convert to monthly amount
            
            Salary::create([
                'user_id' => $user->id,
                'amount' => $salary,
                'currency' => 'USD',
                'effective_date' => $today->copy()->subMonths(6),
                'notes' => 'Initial salary allocation'
            ]);
        }

        // Create company policies
        $policies = [
            [
                'title' => 'Attendance Policy',
                'content' => 'Employees must arrive by 9:00 AM. Late arrivals must be approved by managers.',
                'category' => 'HR'
            ],
            [
                'title' => 'Leave Policy',
                'content' => 'Employees get 12 casual leaves, 5 sick leaves, and 15 annual leaves per year.',
                'category' => 'HR'
            ],
            [
                'title' => 'Remote Work Policy',
                'content' => 'Employees may work remotely up to 2 days per week with manager approval.',
                'category' => 'HR'
            ],
            [
                'title' => 'IT Security Policy',
                'content' => 'All company data must be protected. Passwords must be changed every 90 days.',
                'category' => 'IT'
            ],
            [
                'title' => 'Expense Reimbursement',
                'content' => 'Submit expense reports within 30 days with proper receipts for reimbursement.',
                'category' => 'Finance'
            ]
        ];

        foreach ($policies as $policy) {
            Policy::create($policy);
        }

        // Create holidays
        $holidays = [
            ['name' => 'New Year\'s Day', 'date' => '2023-01-01', 'type' => 'public'],
            ['name' => 'Labor Day', 'date' => '2023-05-01', 'type' => 'public'],
            ['name' => 'Independence Day', 'date' => '2023-07-04', 'type' => 'public'],
            ['name' => 'Thanksgiving', 'date' => '2023-11-23', 'type' => 'public'],
            ['name' => 'Christmas Day', 'date' => '2023-12-25', 'type' => 'public'],
            ['name' => 'Company Foundation Day', 'date' => '2023-06-15', 'type' => 'company']
        ];

        foreach ($holidays as $holiday) {
            Holiday::create($holiday);
        }
    }
}