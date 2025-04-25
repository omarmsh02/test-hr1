<?php

namespace App\Http\Controllers;

use App\Models\Request as EmployeeRequest;
use App\Models\User;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    /**
     * Display employee requests index
     */
    public function index()
    {
        $user = auth()->user();
        $requests = EmployeeRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('employee.requests.index', compact('requests'));
    }

    /**
     * Show request creation form
     */
    public function create()
    {
        $requestTypes = [
            'equipment' => 'Equipment Request',
            'software' => 'Software Request',
            'training' => 'Training Request',
            'document' => 'Document Request',
            'other' => 'Other Request',
        ];
        
        return view('employee.requests.create', compact('requestTypes'));
    }

    /**
     * Store a new request
     */
    public function store(Request $httpRequest)
    {
        $validated = $httpRequest->validate([
            'type' => 'required|string|in:equipment,software,training,document,other',
            'description' => 'required|string|max:1000',
        ]);
        
        $user = auth()->user();
        
        EmployeeRequest::create([
            'user_id' => $user->id,
            'type' => $validated['type'],
            'description' => $validated['description'],
            'status' => 'pending',
        ]);
        
        return redirect()->route('employee.requests.index')
            ->with('success', 'Request submitted successfully.');
    }

    /**
     * Display request details
     */
    public function show(EmployeeRequest $request)
    {
        // Ensure users can only view their own requests unless admin/manager
        if ($request->users_id !== auth()->id() && auth()->user()->role === 'employee') {
            abort(403);
        }
        
        return view('employee.requests.show', compact('request'));
    }

    /**
     * Display admin requests index
     */
    public function adminIndex(Request $httpRequest)
    {
        $status = $httpRequest->status ?? 'all';
        $type = $httpRequest->type ?? 'all';
        
        $query = EmployeeRequest::with('user');
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        if ($type !== 'all') {
            $query->where('type', $type);
        }
        
        $requests = $query->latest()->get();
        
        $requestTypes = [
            'equipment' => 'Equipment Request',
            'software' => 'Software Request',
            'training' => 'Training Request',
            'document' => 'Document Request',
            'other' => 'Other Request',
        ];
        
        return view('admin.requests.index', compact('requests', 'status', 'type', 'requestTypes'));
    }

    /**
     * Display manager requests index
     */
    public function managerIndex(Request $httpRequest)
    {
        $user = auth()->user();
        $status = $httpRequest->status ?? 'all';
        $type = $httpRequest->type ?? 'all';
        
        // Get employees from the manager's department
        $departmentEmployees = User::where('department', $user->department)
            ->where('role', 'employee')
            ->pluck('id');
            
        $query = EmployeeRequest::whereIn('user_id', $departmentEmployees)
            ->with('user');
            
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        if ($type !== 'all') {
            $query->where('type', $type);
        }
        
        $requests = $query->latest()->get();
        
        $requestTypes = [
            'equipment' => 'Equipment Request',
            'software' => 'Software Request',
            'training' => 'Training Request',
            'document' => 'Document Request',
            'other' => 'Other Request',
        ];
        
        return view('manager.requests.index', compact('requests', 'status', 'type', 'requestTypes'));
    }

    /**
     * Update request status (admin/manager)
     */
    public function updateStatus(Request $httpRequest, EmployeeRequest $request)
    {
        $validated = $httpRequest->validate([
            'status' => 'required|in:approved,rejected',
            'comment' => 'nullable|string|max:500',
        ]);
        
        $request->update([
            'status' => $validated['status'],
            'comment' => $validated['comment'] ?? null,
        ]);
        
        // Notification logic can be added here
        
        return redirect()->back()
            ->with('success', 'Request ' . $validated['status'] . ' successfully.');
    }
}