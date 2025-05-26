<?php
namespace App\Http\Controllers;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments
     */
    public function index()
    {
        $departments = Department::with('manager')
            ->withCount('users') // Add user count for the template
            ->paginate(5);
        return view('admin.departments.index', compact('departments'));
    }
    
    /**
     * Show the form for creating a new department
     */
    public function create()
    {
        $managers = User::where('role', 'manager')->get();
        return view('admin.departments.create', compact('managers'));
    }
    
    /**
     * Store a newly created department
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string',
            'manager_id' => 'required|exists:users,id',
            'location' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric',
        ]);
        
        Department::create($validated);
        
        return redirect()->route('admin.departments.index')
            ->with('success', 'Department created successfully.');
    }
    
    /**
     * Display the specified department
     */
    public function show(Department $department)
    {
        $department->load('manager', 'users');
        return view('admin.departments.show', compact('department'));
    }
    
    /**
     * Show the form for editing the specified department
     */
    public function edit(Department $department)
    {
        $managers = User::where('role', 'manager')->get();
        return view('admin.departments.edit', compact('department', 'managers'));
    }
    
    /**
     * Update the specified department
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'nullable|string',
            'manager_id' => 'required|exists:users,id',
            'location' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric',
        ]);
        
        $department->update($validated);
        
        return redirect()->route('admin.departments.index')
            ->with('success', 'Department updated successfully.');
    }
    
    /**
     * Remove the specified department
     */
    public function destroy(Department $department)
    {
        // Add comprehensive logging
        Log::info('Delete request received for department:', [
            'id' => $department->id,
            'name' => $department->name,
            'method' => request()->method(),
            'url' => request()->fullUrl()
        ]);

        try {
            // Check if department has users (optional safety check)
            $userCount = $department->users()->count();
            Log::info('Department user count: ' . $userCount);

            // Perform deletion
            $department->delete();
            
            Log::info('Department deleted successfully: ' . $department->id);
            
            return redirect()->route('admin.departments.index')
                ->with('success', 'Department "' . $department->name . '" deleted successfully.');
                
        } catch (\Exception $e) {
            Log::error('Department deletion failed:', [
                'department_id' => $department->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.departments.index')
                ->with('error', 'Failed to delete department: ' . $e->getMessage());
        }
    }
}