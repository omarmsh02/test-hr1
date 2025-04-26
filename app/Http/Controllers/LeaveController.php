<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveController extends Controller
{
    /**
     * Display employee leaves
     */
    public function index()
    {
        $user = auth()->user();
        $leaves = Leave::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate leave balance directly in the controller
        $leaveBalance = $this->calculateLeaveBalance($user->id);
        
        return view('admin.leaves.index', compact('leaves', 'leaveBalance'));
    }

    /**
     * Show leave request form
     */
    public function create()
    {
        $leaveTypes = [
            'annual' => 'Annual Leave',
            'sick' => 'Sick Leave',
            'personal' => 'Personal Leave',
            'unpaid' => 'Unpaid Leave',
            'other' => 'Other',
        ];

        // Calculate leave balance directly in the controller
        $leaveBalance = $this->calculateLeaveBalance(auth()->id());

        return view('admin.leaves.create', compact('leaveTypes', 'leaveBalance'));
    }

    /**
     * Store leave request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:annual,sick,personal,unpaid,other',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
        ]);

        $user = auth()->user();

        // Check leave balance directly in the controller
        if ($validated['type'] !== 'unpaid') {
            $days = $this->calculateLeaveDays($validated['start_date'], $validated['end_date']);
            $balance = $this->calculateLeaveBalance($user->id);

            if ($days > ($balance[$validated['type']] ?? 0)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Insufficient leave balance for the selected type.');
            }
        }

        Leave::create([
            'user_id' => $user->id,
            'type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return redirect()->route('employee.leaves.index')
            ->with('success', 'Leave request submitted successfully.');
    }

    /**
     * Display leave details
     */
    public function show(Leave $leave)
    {
        // Ensure user can only view their own leaves
        if ($leave->user_id !== auth()->id() && auth()->user()->role === 'employee') {
            abort(403);
        }

        return view('employee.leaves.show', compact('leave'));
    }

    /**
     * Display admin leave index
     */
    public function adminIndex(Request $request)
    {
        $status = $request->status ?? 'all';
        $query = Leave::with('user');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $leaves = $query->latest()->get();

        return view('admin.leaves.index', compact('leaves', 'status'));
    }

    /**
     * Display manager leave index
     */
    public function managerIndex(Request $request)
    {
        $user = auth()->user();
        $status = $request->status ?? 'all';

        // Get employees from the manager's department
        $departmentEmployees = User::where('department', $user->department)
            ->where('role', 'employee')
            ->pluck('id');

        $query = Leave::whereIn('user_id', $departmentEmployees)
            ->with('user');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $leaves = $query->latest()->get();

        return view('manager.leaves.index', compact('leaves', 'status'));
    }

    /**
     * Update leave status (admin/manager)
     */
    public function updateStatus(Request $request, Leave $leave)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'comment' => 'nullable|string|max:500',
        ]);

        $leave->update([
            'status' => $validated['status'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()->back()
            ->with('success', 'Leave request ' . $validated['status'] . ' successfully.');
    }

    /**
     * Helper method to calculate leave balance
     */
    protected function calculateLeaveBalance($userId)
    {
        // Example logic for calculating leave balance
        $balance = [
            'annual' => 15, // Default annual leave days
            'sick' => 10,   // Default sick leave days
            'personal' => 5,// Default personal leave days
        ];

        // Fetch used leave days from the database
        $usedLeaves = Leave::where('user_id', $userId)
            ->where('status', 'approved')
            ->get()
            ->groupBy('type')
            ->map(function ($leaves) {
                return $leaves->sum(function ($leave) {
                    return $leave->end_date->diffInDays($leave->start_date) + 1;
                });
            });

        // Subtract used leaves from the default balance
        foreach ($balance as $type => $days) {
            $balance[$type] -= $usedLeaves[$type] ?? 0;
        }

        return $balance;
    }

    /**
     * Helper method to calculate leave days between two dates
     */
    protected function calculateLeaveDays($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // Exclude weekends if needed (optional logic)
        $days = 0;
        while ($start->lte($end)) {
            if (!in_array($start->dayOfWeek, [Carbon::SUNDAY, Carbon::SATURDAY])) {
                $days++;
            }
            $start->addDay();
        }

        return $days;
    }
}