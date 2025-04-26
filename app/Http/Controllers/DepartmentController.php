<?php
namespace App\Http\Controllers;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
class DepartmentController extends Controller
{
    /**
     * Display a listing of departments
     */
    public function index()
    {
        $departments = Department::with('manager')->get();
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
        $department->load('manager');
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
        $department->delete();

        return redirect()->route('admin.departments.index')
        ->with('success', 'Department deleted successfully.');
    }
}