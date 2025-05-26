<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Leave;
use App\Models\Holiday;
use App\Models\Attendance;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Admin dashboard
     */
    public function adminDashboard()
    {
        $stats = [
            'total_employees' => User::where('role', 'employee')->count(),
            'total_managers' => User::where('role', 'manager')->count(),
            'pending_leaves' => Leave::where('status', 'pending')->count(),
            'upcoming_holidays' => Holiday::whereDate('date', '>=', now())->count(),
        ];
        
        $recentLeaves = Leave::with('user')
            ->latest()
            ->take(5)
            ->get();
            
        return view('admin.dashboard', compact('stats', 'recentLeaves'));
    }

    /**
     * Manager dashboard
     */
    public function managerDashboard()
    {
        // Get the authenticated manager
        $manager = auth()->user();
        
        // Ensure the manager has a department
        if (!$manager->department_id) {
            return view('manager.dashboard', [
                'error' => 'You are not assigned to any department'
            ]);
        }

        // Get department employees (excluding self)
        $departmentUsers = User::where('department_id', $manager->department_id)
            ->where('id', '!=', $manager->id)
            ->with(['attendances' => function($query) {
                $query->whereDate('attendance_date', today());
            }])
            ->get();

        // Calculate stats
        $stats = [
            'department_employees' => $departmentUsers->where('role', 'employee')->count(),
            'pending_leaves' => Leave::whereIn('user_id', $departmentUsers->pluck('id'))
                ->where('status', 'pending')
                ->count(),
            'today_attendance' => Attendance::whereIn('user_id', $departmentUsers->pluck('id'))
                ->whereDate('attendance_date', today())
                ->whereIn('status', ['present', 'late'])
                ->count(),
        ];

        // Get recent leave requests
        $recentLeaves = Leave::whereIn('user_id', $departmentUsers->pluck('id'))
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('manager.dashboard', [
            'stats' => $stats,
            'departmentUsers' => $departmentUsers,
            'recentLeaves' => $recentLeaves,
            'department' => $manager->department
        ]);
    }

    /**
     * Employee dashboard
     */
    public function employeeDashboard()
    {
        $user = auth()->user();
        
        $stats = [
            'total_leaves' => $user->leaves()->count(),
            'pending_leaves' => $user->leaves()->where('status', 'pending')->count(),
            'total_attendance' => $user->attendances()->count(),
        ];
        
        $upcomingHolidays = Holiday::whereDate('date', '>=', now())
            ->orderBy('date')
            ->take(3)
            ->get();
            
        $recentAttendance = $user->attendances()
            ->latest('attendance_date')
            ->take(5)
            ->get();
            
        return view('employee.dashboard', compact('stats', 'upcomingHolidays', 'recentAttendance'));
    }
}