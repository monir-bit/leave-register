<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeLeaveBalanceRequest;
use App\Http\Requests\UpdateEmployeeLeaveBalanceRequest;
use App\Models\Employee;
use App\Models\EmployeeLeaveBalance;
use App\Models\LeaveType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmployeeLeaveBalanceController extends Controller
{
    public function index(Employee $employee): View
    {
        $leaveBalances = $employee->leaveBalances()
            ->with('leaveType')
            ->orderByDesc('year')
            ->get();

        return view('employees.leave-balances.index', [
            'employee' => $employee,
            'leaveBalances' => $leaveBalances,
            'leaveTypes' => LeaveType::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(StoreEmployeeLeaveBalanceRequest $request, Employee $employee): RedirectResponse
    {
        $employee->leaveBalances()->create($request->validated());

        return redirect()->route('employees.leave-balances.index', $employee)
            ->with('status', 'Leave balance added successfully.');
    }

    public function update(UpdateEmployeeLeaveBalanceRequest $request, Employee $employee, EmployeeLeaveBalance $leaveBalance): RedirectResponse
    {
        abort_if($leaveBalance->employee_id !== $employee->employee_id, 404);

        $leaveBalance->update($request->validated());

        return redirect()->route('employees.leave-balances.index', $employee)
            ->with('status', 'Leave balance updated successfully.');
    }

    public function destroy(Employee $employee, EmployeeLeaveBalance $leaveBalance): RedirectResponse
    {
        abort_if($leaveBalance->employee_id !== $employee->employee_id, 404);

        $leaveBalance->delete();

        return redirect()->route('employees.leave-balances.index', $employee)
            ->with('status', 'Leave balance deleted successfully.');
    }
}
