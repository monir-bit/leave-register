<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeaveRegisterRequest;
use App\Http\Requests\UpdateLeaveRegisterRequest;
use App\Models\Employee;
use App\Models\LeaveRegister;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveRegisterController extends Controller
{
    private const PER_PAGE_OPTIONS = [10, 20, 50, 100];

    public function index(Request $request): View
    {
        $perPage = in_array((int) $request->get('per_page'), self::PER_PAGE_OPTIONS, true)
            ? (int) $request->get('per_page')
            : 20;
        $search = trim((string) $request->get('search'));

        $leaveRegisters = LeaveRegister::query()
            ->with(['employee', 'leaveType'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('employee_id', 'like', "%{$search}%")
                        ->orWhere('reason', 'like', "%{$search}%")
                        ->orWhereHas('employee', fn ($q2) => $q2->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('leaveType', fn ($q2) => $q2->where('name', 'like', "%{$search}%"));
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('leave-registers.index', [
            'leaveRegisters' => $leaveRegisters,
            'perPage' => $perPage,
            'perPageOptions' => self::PER_PAGE_OPTIONS,
            'search' => $search,
            'employees' => Employee::orderBy('name')->get(['employee_id', 'name']),
            'leaveTypes' => LeaveType::orderBy('position')->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function create(): View
    {
        return view('leave-registers.create', [
            'employees' => Employee::orderBy('name')->get(['employee_id', 'name']),
            'leaveTypes' => LeaveType::orderBy('position')->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(StoreLeaveRegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['days'] = $this->calculateDays($data['from_date'], $data['to_date']);

        LeaveRegister::create($data);

        return redirect()->route('leave-registers.index')->with('status', 'Leave register created successfully.');
    }

    public function update(UpdateLeaveRegisterRequest $request, LeaveRegister $leaveRegister): RedirectResponse
    {
        $data = $request->validated();
        $data['days'] = $this->calculateDays($data['from_date'], $data['to_date']);

        $leaveRegister->update($data);

        return redirect()->route('leave-registers.index')->with('status', 'Leave register updated successfully.');
    }

    public function destroy(LeaveRegister $leaveRegister): RedirectResponse
    {
        $leaveRegister->delete();

        return redirect()->route('leave-registers.index')->with('status', 'Leave register deleted successfully.');
    }

    private function calculateDays(string $from, string $to): int
    {
        return Carbon::parse($from)->diffInDays(Carbon::parse($to)) + 1;
    }
}
