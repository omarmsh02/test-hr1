<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $users = User::query()
            ->when(request('department'), function ($query) {
                $query->where('department_id', request('department'));
            })
            ->when(request('role'), function ($query) {
                $query->where('role', request('role'));
            })
            ->with('department')
            ->paginate(5);

        $departments = Department::all();

        return view('admin.users.index', compact('users', 'departments'));
    }

    /**
     * Show form to create a new user
     */
    public function create()
    {
        $departments = Department::all();
        return view('admin.users.create', compact('departments'));
    }

    /**
     * Store a new user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'department' => 'required|exists:departments,id',
            'role' => 'required|in:admin,manager,employee',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'department_id' => $validated['department'],
            'role' => $validated['role'],
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

    /**
     * Show user details
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show form to edit user
     */
    public function edit(User $user)
    {
        $departments = Department::all();
        return view('admin.users.edit', compact('user', 'departments'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'department' => 'required|exists:departments,id',
            'role' => 'required|in:admin,manager,employee',
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'department_id' => $validated['department'],
            'role' => $validated['role'],
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }
}