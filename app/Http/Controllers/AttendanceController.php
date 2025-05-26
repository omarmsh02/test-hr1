<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AttendanceController extends Controller
{
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
     * Get today's attendance for the user (for API)
     */
    public function getTodayAttendance()
    {
        $user = auth()->user();
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', today())
            ->first();
            
        return response()->json([
            'success' => true,
            'attendance' => $todayAttendance
        ]);
    }

    /**
     * Handle check-in
     */
    public function checkIn(Request $request)
    {
        $user = auth()->user(); // Get the authenticated user
        $now = now(); // Current timestamp
        
        // Use provided time if available, otherwise use current time
        $checkInTime = $request->time ?? $now->toTimeString();

        // Check if the user has already checked in today
        $existing = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', $now->toDateString())
            ->first();

        if ($existing) {
            if ($existing->check_in) {
                return response()->json(['success' => false, 'message' => 'You have already checked in today.']);
            }
            
            // Update existing record with check-in time
            $existing->update([
                'check_in' => $checkInTime,
                'status' => $this->determineStatus($checkInTime)
            ]);
            
            return response()->json(['success' => true, 'message' => 'Checked in successfully at ' . $this->formatTimeForDisplay($checkInTime)]);
        }

        // Create a new attendance record
        Attendance::create([
            'user_id' => $user->id,
            'attendance_date' => $now->toDateString(),
            'check_in' => $checkInTime,
            'status' => $this->determineStatus($checkInTime),
        ]);

        return response()->json(['success' => true, 'message' => 'Checked in successfully at ' . $this->formatTimeForDisplay($checkInTime)]);
    }

    /**
     * Record break time
     */
    public function recordBreak(Request $request)
    {
        $user = auth()->user(); // Get the authenticated user
        $now = now(); // Current timestamp
        
        // Use provided time if available, otherwise use current time
        $breakTime = $request->time ?? $now->toTimeString();

        // Find today's attendance record
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', $now->toDateString())
            ->first();

        if (!$attendance) {
            return response()->json(['success' => false, 'message' => 'No check-in record found for today.']);
        }

        // Record the break time
        $attendance->update([
            'break_time' => $breakTime,
        ]);

        return response()->json(['success' => true, 'message' => 'Break recorded successfully at ' . $this->formatTimeForDisplay($breakTime)]);
    }

    /**
     * Handle check-out
     */
    public function checkOut(Request $request)
    {
        $user = auth()->user(); // Get the authenticated user
        $now = now(); // Current timestamp
        
        // Use provided time if available, otherwise use current time
        $checkOutTime = $request->time ?? $now->toTimeString();

        // Find today's attendance record
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', $now->toDateString())
            ->first();

        if (!$attendance) {
            return response()->json(['success' => false, 'message' => 'No check-in record found for today.']);
        }

        if ($attendance->check_out) {
            return response()->json(['success' => false, 'message' => 'You have already checked out today.']);
        }

        // Record the check-out time
        $attendance->update([
            'check_out' => $checkOutTime,
        ]);

        return response()->json(['success' => true, 'message' => 'Checked out successfully at ' . $this->formatTimeForDisplay($checkOutTime)]);
    }

    /**
     * Determine attendance status based on check-in time
     */
    private function determineStatus($checkInTime)
    {
        $startTime = Carbon::createFromTimeString('09:00:00');
        $checkInTimeCarbon = Carbon::createFromTimeString($checkInTime);
        
        return $checkInTimeCarbon->gt($startTime) ? 'late' : 'present';
    }
    
    /**
     * Format time for display (12-hour format with AM/PM)
     */
    private function formatTimeForDisplay($timeString)
    {
        return Carbon::createFromTimeString($timeString)->format('g:i A');
    }

    /**
     * Display manager attendance view
     */
    public function managerIndex(Request $request)
    {
        $user = auth()->user();
        $department = $user->department->id;
        
        $employees = User::where('department_id', $department)
            ->where('role', 'employee')
            ->get();
            
        $date = $request->date ?? today()->toDateString();
        
        // Get department attendance data directly in the controller
        $attendanceData = $this->getDepartmentAttendance($department, $date);
            
        return view('manager.attendance.index', compact('employees', 'attendanceData', 'date'));
    }

    /**
     * Get department attendance data
     */
    private function getDepartmentAttendance($department, $date)
    {
        return Attendance::whereHas('user', function($query) use ($department) {
                $query->where('department_id', $department);
            })
            ->whereDate('attendance_date', $date)
            ->with('user')
            ->get()
            ->keyBy('user_id');
    }

    /**
     * Display admin attendance report
     */
    public function report(Request $request)
    {
        $startDate = $request->start_date ?? today()->subDays(30)->toDateString();
        $endDate = $request->end_date ?? today()->toDateString();
        $department = $request->department ?? null;
        
        // Generate attendance report directly in the controller
        $attendanceStats = $this->generateAttendanceReport($startDate, $endDate, $department);
            
        $departments = \App\Models\Department::all();
        
        return view('admin.attendance.report', compact(
            'attendanceStats', 
            'departments', 
            'startDate', 
            'endDate', 
            'department'
        ));
    }

    /**
     * Calculate absentees
     */
    private function calculateAbsentees($startDate, $endDate, $department = null)
    {
        $totalWorkdays = $this->getWorkdaysBetweenDates($startDate, $endDate);
        
        $userQuery = User::where('role', 'employee');
        
        if ($department) {
            $userQuery->where('department_id', $department);
        }
        
        $totalEmployees = $userQuery->count();
        
        $totalPossibleAttendances = $totalEmployees * $totalWorkdays;
        
        $actualAttendances = Attendance::whereBetween('attendance_date', [$startDate, $endDate])
            ->when($department, function($query) use ($department) {
                return $query->whereHas('user', function($q) use ($department) {
                    $q->where('department_id', $department);
                });
            })
            ->count();
            
        return $totalPossibleAttendances - $actualAttendances;
    }

    /**
     * Calculate department summary
     */
    private function calculateDepartmentSummary($attendances)
    {
        return $attendances->groupBy(function($attendance) {
                return $attendance->user->department;
            })
            ->map(function($departmentAttendances) {
                return [
                    'total' => $departmentAttendances->count(),
                    'present' => $departmentAttendances->where('status', 'present')->count(),
                    'late' => $departmentAttendances->where('status', 'late')->count()
                ];
            });
    }

    /**
     * Calculate daily attendance
     */
    private function calculateDailyAttendance($attendances, $startDate, $endDate)
    {
        $period = new \DatePeriod(
            new \DateTime($startDate),
            new \DateInterval('P1D'),
            new \DateTime($endDate . ' 23:59:59')
        );
        
        $dailyData = [];
        
        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');
            $dailyAttendances = $attendances->where('attendance_date', $dateString);
            
            $dailyData[$dateString] = [
                'date' => $dateString,
                'day' => $date->format('l'),
                'total' => $dailyAttendances->count(),
                'present' => $dailyAttendances->where('status', 'present')->count(),
                'late' => $dailyAttendances->where('status', 'late')->count()
            ];
        }
        
        return $dailyData;
    }

    /**
     * Get workdays between dates
     */
    private function getWorkdaysBetweenDates($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $days = 0;
        
        for ($date = $start; $date->lte($end); $date->addDay()) {
            if (!$date->isWeekend()) {
                $days++;
            }
        }
        
        return $days;
    }
}