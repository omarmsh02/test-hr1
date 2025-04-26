<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Home Route
Route::get('/', function () {
    return view('auth.login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Role-Based Dashboards
Route::middleware(['auth'])->group(function () {
    // Redirect to the appropriate dashboard based on user role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'manager') {
            return redirect()->route('manager.dashboard');
        } else {
            return redirect()->route('employee.dashboard');
        }
    })->name('dashboard');

    // Admin Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    // Manager Dashboard
    Route::get('/manager/dashboard', [DashboardController::class, 'managerDashboard'])
        ->middleware('role:manager')
        ->name('manager.dashboard');

    // Employee Dashboard
    Route::get('/employee/dashboard', [DashboardController::class, 'employeeDashboard'])
        ->middleware('role:employee')
        ->name('employee.dashboard');
});
    // Admin Routes
    Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    // Users
    
    
    Route::resource('users', UserController::class)->names([
        'index'   => 'admin.users.index',
        'create'  => 'admin.users.create',
        'store'   => 'admin.users.store',
        'show'    => 'admin.users.show',
        'edit'    => 'admin.users.edit',
        'update'  => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);

    // Departments
    Route::resource('departments', DepartmentController::class)->names([
        'index'   => 'admin.departments.index',
        'create'  => 'admin.departments.create',
        'store'   => 'admin.departments.store',
        'show'    => 'admin.departments.show',
        'edit'    => 'admin.departments.edit',
        'update'  => 'admin.departments.update',
        'destroy' => 'admin.departments.destroy',
    ]);

    // Holidays
    Route::resource('holidays', HolidayController::class)->names([
        'index'   => 'admin.holidays.index',
        'create'  => 'admin.holidays.create',
        'store'   => 'admin.holidays.store',
        'show'    => 'admin.holidays.show',
        'edit'    => 'admin.holidays.edit',
        'update'  => 'admin.holidays.update',
        'destroy' => 'admin.holidays.destroy',
    ]);

    // Leaves
    Route::resource('leaves', LeaveController::class)->names([
        'index'   => 'admin.leaves.index',
        'create'  => 'admin.leaves.create',
        'store'   => 'admin.leaves.store',
        'show'    => 'admin.leaves.show',
        'edit'    => 'admin.leaves.edit',
        'update'  => 'admin.leaves.update',
        'destroy' => 'admin.leaves.destroy',
    ]);
    Route::put('/leaves/{leave}/status', [LeaveController::class, 'updateStatus'])->name('admin.leaves.update-status');

    // Policies
    // Index: Display a listing of policies
    Route::get('/policies', [PolicyController::class, 'index'])->name('admin.policies.index');
    Route::get('/policies/create', [PolicyController::class, 'create'])->name('admin.policies.create');
    Route::post('/policies', [PolicyController::class, 'store'])->name('admin.policies.store');
    Route::get('/policies/{policy}', [PolicyController::class, 'show'])->name('admin.policies.show');
    Route::get('/policies/{policy}/edit', [PolicyController::class, 'edit'])->name('admin.policies.edit');
    Route::put('/policies/{policy}', [PolicyController::class, 'update'])->name('admin.policies.update');
    Route::delete('/policies/{policy}', [PolicyController::class, 'destroy'])->name('admin.policies.destroy');

    // Salaries
    Route::resource('salaries', SalaryController::class)->names([
        'index'   => 'admin.salaries.index',
        'create'  => 'admin.salaries.create',
        'store'   => 'admin.salaries.store',
        'show'    => 'admin.salaries.show',
        'edit'    => 'admin.salaries.edit',
        'update'  => 'admin.salaries.update',
        'destroy' => 'admin.salaries.destroy',
    ]);
    Route::post('/salaries/generate-payslips', [SalaryController::class, 'generatePayslips'])->name('admin.salaries.generate-payslips');

    // Requests
    Route::get('/requests', [RequestController::class, 'adminIndex'])->name('admin.requests.index');
    Route::get('/requests/{request}', [RequestController::class, 'show'])->name('admin.requests.show');
    Route::put('/requests/{request}/status', [RequestController::class, 'updateStatus'])->name('admin.requests.update-status');
});

// Manager Routes
Route::prefix('manager')->middleware(['auth', 'role:manager'])->group(function () {
    // Leaves
    Route::get('/leaves', [LeaveController::class, 'managerIndex'])->name('manager.leaves.index');
    Route::put('/leaves/{leave}/status', [LeaveController::class, 'updateStatus'])->name('manager.leaves.update-status');

    // Attendance
    Route::get('/attendance', [AttendanceController::class, 'managerIndex'])->name('manager.attendance.index');

    // Requests
    Route::get('/requests', [RequestController::class, 'managerIndex'])->name('manager.requests.index');
    Route::put('/requests/{request}/status', [RequestController::class, 'updateStatus'])->name('manager.requests.update-status');
});


// Employee Routes
Route::prefix('employee')->middleware(['auth', 'role:employee'])->group(function () {
    // Attendance
    Route::get('/attendance', [AttendanceController::class, 'employeeIndex'])->name('employee.attendance.index');
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.clockin');
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.clockout');

    // Leaves
    Route::get('/leaves', [LeaveController::class, 'index'])->name('employee.leaves.index');
    Route::get('/leaves/create', [LeaveController::class, 'create'])->name('leave.create');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('leave.store');

    // Salary
    Route::get('/salary', [SalaryController::class, 'employeeIndex'])->name('employee.salary.index');

    // Requests
    Route::get('/requests', [RequestController::class, 'employeeIndex'])->name('employee.requests.index');
    Route::post('/requests', [RequestController::class, 'store'])->name('employee.requests.store');

    // Policies
    Route::get('/policies', [PolicyController::class, 'employeeIndex'])->name('policies.employeeIndex');
});

// Chat Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
    Route::get('/chats/{user}', [ChatController::class, 'show'])->name('chats.show');
    Route::post('/chats/{user}', [ChatController::class, 'store'])->name('chats.store');
});

// Profile Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});