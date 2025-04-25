<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    /**
     * Display admin policies index
     */
    public function index()
    {
        $policies = Policy::latest()->get();
        return view('admin.policies.index', compact('policies'));
    }

    /**
     * Show policy creation form
     */
    public function create()
    {
        $categories = [
            'HR' => 'Human Resources',
            'IT' => 'Information Technology',
            'Finance' => 'Finance',
            'General' => 'General',
            'Safety' => 'Health & Safety',
        ];
        
        return view('admin.policies.create', compact('categories'));
    }

    /**
     * Store a new policy
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:50',
            'is_active' => 'required|boolean',
        ]);
        
        Policy::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category' => $validated['category'],
            'is_active' => (bool)$validated['is_active'],
        ]);
        
        return redirect()->route('admin.policies.index')
            ->with('success', 'Policy created successfully');
    }

    /**
     * Display policy details
     */
    public function show(Policy $policy)
    {
        return view('admin.policies.show', compact('policy'));
    }

    /**
     * Show policy edit form
     */
    public function edit(Policy $policy)
    {
        $categories = [
            'HR' => 'Human Resources',
            'IT' => 'Information Technology',
            'Finance' => 'Finance',
            'General' => 'General',
            'Safety' => 'Health & Safety',
        ];
        
        return view('admin.policies.edit', compact('policy', 'categories'));
    }

    /**
     * Update policy
     */
    public function update(Request $request, Policy $policy)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:50',
            'is_active' => 'required|boolean',
        ]);
        
        $policy->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category' => $validated['category'],
            'is_active' => (bool)$validated['is_active'],
        ]);
        
        return redirect()->route('admin.policies.index')
            ->with('success', 'Policy updated successfully');
    }

    /**
     * Delete policy
     */
    public function destroy(Policy $policy)
    {
        $policy->delete();
        return redirect()->route('admin.policies.index')
            ->with('success', 'Policy deleted successfully');
    }

    /**
     * Display employee policies view
     */
    public function employeeIndex(Request $request)
    {
        $category = $request->category ?? 'all';
        
        $query = Policy::where('is_active', true);
        
        if ($category !== 'all') {
            $query->where('category', $category);
        }
        
        $policies = $query->get();
        
        $categories = Policy::where('is_active', true)
            ->select('category')
            ->distinct()
            ->pluck('category');
            
        return view('employee.policies.index', compact('policies', 'categories', 'category'));
    }
}