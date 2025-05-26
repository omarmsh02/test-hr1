<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Salary;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    /**
     * Display admin salaries index
     */
    public function index()
    {
        $employees = User::where('role', 'employee')
            ->orWhere('role', 'manager')
            ->with('currentSalary')
            ->paginate(5);

        return view('admin.salaries.index', compact('employees'));
    }

    /**
     * Show salary creation form
     */
    public function create()
    {
        $employees = User::where('role', 'employee')
            ->orWhere('role', 'manager')
            ->with('currentSalary')
            ->get();

        $currencies = ['USD', 'EUR', 'GBP', 'JPY', 'INR', 'AUD', 'CAD'];

        return view('admin.salaries.create', compact('employees', 'currencies'));
    }

    /**
     * Store a new salary record
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'effective_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        Salary::create($validated);

        return redirect()->route('admin.salaries.index')
            ->with('success', 'Salary record created successfully');
    }

    /**
     * Display salary details
     */
    public function show(Salary $salary)
    {
        return view('admin.salaries.show', compact('salary'));
    }

    /**
     * Show salary edit form
     */
    public function edit(Salary $salary)
    {
        $currencies = ['USD', 'EUR', 'GBP', 'JPY', 'INR', 'AUD', 'CAD'];
        return view('admin.salaries.edit', compact('salary', 'currencies'));
    }

    /**
     * Update salary record
     */
    public function update(Request $request, Salary $salary)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'effective_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $salary->update($validated);

        return redirect()->route('admin.salaries.index')
            ->with('success', 'Salary record updated successfully');
    }

    /**
     * Delete salary record
     */
    public function destroy(Salary $salary)
    {
        $salary->delete();
        return redirect()->route('admin.salaries.index')
            ->with('success', 'Salary record deleted successfully');
    }

    /**
     * Display employee salary view
     */
    public function employeeIndex()
    {
        $user = auth()->user();
        $salaries = Salary::where('user_id', $user->id)
            ->orderBy('effective_date', 'desc')
            ->paginate(5);

        $currentSalary = $user->currentSalary;

        return view('employee.salary.index', compact('salaries', 'currentSalary'));
    }

    /**
     * Display manager salary overview
     */
    public function managerIndex()
    {
        $user = auth()->user();
        $currentSalary = $user->currentSalary;
        $salaries = Salary::where('user_id', $user->id)
            ->orderBy('effective_date', 'desc')
            ->paginate(5);
            
        return view('manager.salary.index', compact('currentSalary', 'salaries'));
    }
}