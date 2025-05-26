<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
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

// Common Attendance Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/attendance/checkIn', [AttendanceController::class, 'checkIn'])->name('attendance.checkIn');
    Route::post('/attendance/break', [AttendanceController::class, 'recordBreak'])->name('attendance.break');
    Route::post('/attendance/checkOut', [AttendanceController::class, 'checkOut'])->name('attendance.checkOut');
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
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('admin.users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // Departments
    Route::get('/departments', [DepartmentController::class, 'index'])->name('admin.departments.index');
    Route::get('/departments/create', [DepartmentController::class, 'create'])->name('admin.departments.create');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('admin.departments.store');
    Route::get('/departments/{department}', [DepartmentController::class, 'show'])->name('admin.departments.show');
    Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('admin.departments.edit');
    Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('admin.departments.update');
    Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('admin.departments.destroy');


    // Holidays
    Route::get('/holidays', [HolidayController::class, 'index'])->name('admin.holidays.index');
    Route::get('/holidays/create', [HolidayController::class, 'create'])->name('admin.holidays.create');
    Route::post('/holidays', [HolidayController::class, 'store'])->name('admin.holidays.store');
    Route::get('/holidays/{holiday}', [HolidayController::class, 'show'])->name('admin.holidays.show');
    Route::get('/holidays/{holiday}/edit', [HolidayController::class, 'edit'])->name('admin.holidays.edit');
    Route::put('/holidays/{holiday}', [HolidayController::class, 'update'])->name('admin.holidays.update');
    Route::delete('/holidays/{holiday}', [HolidayController::class, 'destroy'])->name('admin.holidays.destroy');

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
    Route::put('/requests/{request}/status', [RequestController::class, 'updateStatus'])->name('admin.requests.updateStatus');
});

// Manager Routes
Route::prefix('manager')->middleware(['auth', 'role:manager'])->group(function () {
    // Leaves - Fixed routes with consistent naming
    Route::get('/leaves', [LeaveController::class, 'managerIndex'])->name('manager.leaves.index');
    Route::get('/leaves/{leave}', [LeaveController::class, 'show'])->name('manager.leaves.show');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('manager.leaves.store');
    // Fixed: Changed from 'updateStatus' to 'update-status' to match the form action
    Route::put('/leaves/{leave}/status', [LeaveController::class, 'updateStatus'])->name('manager.leaves.update-status');

    // Attendance
    Route::get('/attendance', [AttendanceController::class, 'managerIndex'])->name('manager.attendance.index');
    Route::get('/attendance/{user}', [AttendanceController::class, 'show'])->name('manager.attendance.show');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('manager.attendance.store');
    Route::put('/attendance/{attendance}', [AttendanceController::class, 'update'])->name('manager.attendance.update');
    Route::delete('/attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('manager.attendance.destroy');
    // Requests
    Route::get('/requests', [RequestController::class, 'managerIndex'])->name('manager.requests.index');
    Route::get('/requests/{request}', [RequestController::class, 'show'])->name('manager.requests.show');
    Route::put('/requests/{request}/status', [RequestController::class, 'updateStatus'])->name('manager.requests.updateStatus');

    // Policies
    Route::get('/policies', [PolicyController::class, 'managerIndex'])->name('manager.policies.index');

    // Salary
    Route::get('/salary', [SalaryController::class, 'managerIndex'])->name('manager.salary.index');
});

// Employee Routes
Route::prefix('employee')->middleware(['auth', 'role:employee'])->group(function () {
    // Attendance
    Route::get('/attendance', [AttendanceController::class, 'employeeIndex'])->name('employee.attendance.index');

    Route::resource('/holiday', HolidayController::class)->names([
        'index'   => 'employee.holidays.calender',
        'create'  => 'employee.holidays.create',
        'store'   => 'employee.holidays.store',
        'show'    => 'employee.holidays.show',
        'edit'    => 'employee.holidays.edit',
        'update'  => 'employee.holidays.update',
        'destroy' => 'employee.holidays.destroy',
    ]);

    // Leaves
    Route::get('/leaves', [LeaveController::class, 'index'])->name('employee.leaves.index');
    Route::get('/leaves/create', [LeaveController::class, 'create'])->name('employee.leaves.create');
    Route::get('/leaves/{leave}', [LeaveController::class, 'show'])->name('employee.leaves.show');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('employee.leaves.store');

    // Salary
    Route::get('/salary', [SalaryController::class, 'employeeIndex'])->name('employee.salary.index');

    // Requests
    Route::resource('requests', RequestController::class)->names([
        'index'   => 'employee.requests.index',
        'create'  => 'employee.requests.create',
        'store'   => 'employee.requests.store',
        'show'    => 'employee.requests.show',
        'edit'    => 'employee.requests.edit',
        'update'  => 'employee.requests.update',
        'destroy' => 'employee.requests.destroy',
    ]);

    // Policies
    Route::get('/policies', [PolicyController::class, 'employeeIndex'])->name('employee.policies.index');
});
