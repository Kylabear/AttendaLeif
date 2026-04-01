<?php

use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Employee\AttendanceController as EmployeeAttendanceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:employee'])->prefix('attendance')->name('attendance.')->group(function () {
    Route::post('/clock-in', [EmployeeAttendanceController::class, 'clockIn'])->name('clock-in');
    Route::post('/clock-out', [EmployeeAttendanceController::class, 'clockOut'])->name('clock-out');
    Route::post('/set-status', [EmployeeAttendanceController::class, 'setStatus'])->name('set-status');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::patch('/employees/{user}/role', [EmployeeController::class, 'updateRole'])->name('employees.update-role');
    Route::delete('/employees/{user}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

    Route::get('/attendance/daily', [AdminAttendanceController::class, 'daily'])->name('attendance.daily');
    Route::get('/attendance/daily/export', [AdminAttendanceController::class, 'exportDaily'])->name('attendance.daily.export');
    Route::get('/attendance/trends', [AdminAttendanceController::class, 'trends'])->name('attendance.trends');
    Route::get('/attendance/trends/export', [AdminAttendanceController::class, 'exportTrends'])->name('attendance.trends.export');

    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
});

require __DIR__.'/auth.php';
