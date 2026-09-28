<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeLeaveBalanceController;
use App\Http\Controllers\LeaveRegisterController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::get('/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

    Route::get('/employees/{employee}/leave-balances', [EmployeeLeaveBalanceController::class, 'index'])->name('employees.leave-balances.index');
    Route::post('/employees/{employee}/leave-balances', [EmployeeLeaveBalanceController::class, 'store'])->name('employees.leave-balances.store');
    Route::put('/employees/{employee}/leave-balances/{leaveBalance}', [EmployeeLeaveBalanceController::class, 'update'])->name('employees.leave-balances.update');
    Route::delete('/employees/{employee}/leave-balances/{leaveBalance}', [EmployeeLeaveBalanceController::class, 'destroy'])->name('employees.leave-balances.destroy');

    Route::get('/leave-registers', [LeaveRegisterController::class, 'index'])->name('leave-registers.index');
    Route::get('/leave-registers/create', [LeaveRegisterController::class, 'create'])->name('leave-registers.create');
    Route::post('/leave-registers', [LeaveRegisterController::class, 'store'])->name('leave-registers.store');
    Route::put('/leave-registers/{leaveRegister}', [LeaveRegisterController::class, 'update'])->name('leave-registers.update');
    Route::delete('/leave-registers/{leaveRegister}', [LeaveRegisterController::class, 'destroy'])->name('leave-registers.destroy');

    Route::get('/leave-types', [LeaveTypeController::class, 'index'])->name('leave-types.index');
    Route::post('/leave-types', [LeaveTypeController::class, 'store'])->name('leave-types.store');
    Route::put('/leave-types/{leaveType}', [LeaveTypeController::class, 'update'])->name('leave-types.update');

    Route::get('/reports/leave-register', [ReportController::class, 'create'])->name('reports.leave-register.create');
    Route::get('/reports/leave-register/show', [ReportController::class, 'show'])->name('reports.leave-register.show');
    Route::get('/reports/leave-register/download', [ReportController::class, 'download'])->name('reports.leave-register.download');
});
