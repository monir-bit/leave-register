<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeaveTypeRequest;
use App\Http\Requests\UpdateLeaveTypeRequest;
use App\Models\LeaveType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LeaveTypeController extends Controller
{
    public function index(): View
    {
        $leaveTypes = LeaveType::orderBy('position')->orderBy('name')->get();

        return view('leave-types.index', [
            'leaveTypes' => $leaveTypes,
        ]);
    }

    public function store(StoreLeaveTypeRequest $request): RedirectResponse
    {
        LeaveType::create($request->validated());

        return redirect()->route('leave-types.index')->with('status', 'Leave type created successfully.');
    }

    public function update(UpdateLeaveTypeRequest $request, LeaveType $leaveType): RedirectResponse
    {
        $leaveType->update($request->validated());

        return redirect()->route('leave-types.index')->with('status', 'Leave type updated successfully.');
    }
}
