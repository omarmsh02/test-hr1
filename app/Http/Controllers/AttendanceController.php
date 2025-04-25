<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Services\AttendanceService;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Display employee attendance
     */
    public function employeeIndex()
    {
        $user = auth()->user();
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', today())
            ->first();
        
        $attendanceHistory = Attendance::where('user_id', $user->id)
            ->orderBy('attendance_date', 'desc')
            ->get();
        
        return view('employee.attendance.index', compact('todayAttendance', 'attendanceHistory'));
    }

    /**
     * Handle check-in
     */
    public function checkIn()
    {
        $user = auth()->user();
        $now = now();
        
        // Check if already checked in today
        $existing = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', $now->toDateString())
            ->first();
            
        if ($existing) {
            return redirect()->back()->with('error', 'You have already checked in today.');
        }
        
        // Determine if late (after 9 AM)
        $startTime = Carbon::createFromTimeString('09:00:00');
        $status = $now->gt($startTime) ? 'late' : 'present';
        
        Attendance::create([
            'user_id' => $user->id,
            'attendance_date' => $now->toDateString(),
            'check_in' => $now->toTimeString(),
            'status' => $status,
        ]);
        
        return redirect()->back()->with('success', 'Checked in successfully.');
    }

    /**
     * Handle check-out
     */
    public function checkOut()
    {
        $user = auth()->user();
        $now = now();
        
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', $now->toDateString())
            ->first();
            
        if (!$attendance) {
            return redirect()->back()->with('error', 'No check-in record found for today.');
        }
        
        if ($attendance->check_out) {
            return redirect()->back()->with('error', 'You have already checked out today.');
        }
        
        $attendance->update([
            'check_out' => $now->toTimeString(),
        ]);
        
        return redirect()->back()->with('success', 'Checked out successfully.');
    }

    /**
     * Display manager attendance view
     */
    public function managerIndex(Request $request)
    {
        $user = auth()->user();
        $department = $user->department;
        
        $employees = User::where('department', $department)
            ->where('role', 'employee')
            ->get();
            
        $date = $request->date ?? today()->toDateString();
        
        $attendanceData = $this->attendanceService
            ->getDepartmentAttendance($department, $date);
            
        return view('manager.attendance.index', compact('employees', 'attendanceData', 'date'));
    }

    /**
     * Display admin attendance report
     */
    public function report(Request $request)
    {
        $startDate = $request->start_date ?? today()->subDays(30)->toDateString();
        $endDate = $request->end_date ?? today()->toDateString();
        $department = $request->department ?? null;
        
        $attendanceStats = $this->attendanceService
            ->generateAttendanceReport($startDate, $endDate, $department);
            
        $departments = \App\Models\Department::all();
        
        return view('admin.attendance.report', compact(
            'attendanceStats', 
            'departments', 
            'startDate', 
            'endDate', 
            'department'
        ));
    }
}