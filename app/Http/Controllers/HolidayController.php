<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    /**
     * Display a listing of holidays
     */
    public function index()
    {
        $holidays = Holiday::orderBy('date')->get();
        return view('admin.holidays.index', compact('holidays'));
    }

    /**
     * Show the form for creating a new holiday
     */
    public function create()
    {
        $holidayTypes = [
            'public' => 'Public Holiday',
            'company' => 'Company Holiday',
            'optional' => 'Optional Holiday',
        ];
        
        return view('admin.holidays.create', compact('holidayTypes'));
    }

    /**
     * Store a newly created holiday
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|string|in:public,company,optional',
            'description' => 'nullable|string',
        ]);
        
        Holiday::create($validated);
        
        return redirect()->route('admin.holidays.index')
            ->with('success', 'Holiday created successfully');
    }

    /**
     * Display the specified holiday
     */
    public function show(Holiday $holiday)
    {
        return view('admin.holidays.show', compact('holiday'));
    }

    /**
     * Show the form for editing the holiday
     */
    public function edit(Holiday $holiday)
    {
        $holidayTypes = [
            'public' => 'Public Holiday',
            'company' => 'Company Holiday',
            'optional' => 'Optional Holiday',
        ];
        
        return view('admin.holidays.edit', compact('holiday', 'holidayTypes'));
    }

    /**
     * Update the holiday
     */
    public function update(Request $request, Holiday $holiday)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|string|in:public,company,optional',
            'description' => 'nullable|string',
        ]);
        
        $holiday->update($validated);
        
        return redirect()->route('admin.holidays.index')
            ->with('success', 'Holiday updated successfully');
    }

    /**
     * Remove the holiday
     */
    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        
        return redirect()->route('admin.holidays.index')
            ->with('success', 'Holiday deleted successfully');
    }

    /**
     * Display employee holidays calendar
     */
    public function calendar(Request $request)
    {
        $year = $request->year ?? now()->year;
        $holidays = Holiday::whereYear('date', $year)
            ->orderBy('date')
            ->get();
            
        return view('employee.holidays.calendar', compact('holidays', 'year'));
    }

    /**
     * Get upcoming holidays
     */
    public function upcoming()
    {
        $holidays = Holiday::where('date', '>=', now())
            ->orderBy('date')
            ->take(5)
            ->get();
            
        return response()->json(['holidays' => $holidays]);
    }
}