<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeLeaveBalance;
use App\Models\LeaveRegister;
use App\Models\LeaveType;
use App\Services\LeaveEntitlementService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    private const YEARS = [2026, 2027, 2028, 2029, 2030];

    public function __construct(private readonly LeaveEntitlementService $leaveEntitlementService)
    {
    }

    public function create(): View
    {
        return view('reports.leave-register.create', [
            'employees' => Employee::orderBy('name')->get(['employee_id', 'name']),
            'years' => self::YEARS,
        ]);
    }

    public function show(Request $request): View
    {
        return view('reports.leave-register.show', $this->reportData($request));
    }

    public function download(Request $request): Response
    {
        $data = $this->reportData($request);

        return Pdf::loadView('reports.leave-register.pdf', [
            ...$data,
            'banglaFontRegular' => $this->fontDataUri('HindSiliguri-Regular.ttf'),
            'banglaFontBold' => $this->fontDataUri('HindSiliguri-Bold.ttf'),
        ])->setPaper('a4', 'portrait')->download('leave-register-report.pdf');
    }

    private function reportData(Request $request): array
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'string', 'exists:employees,employee_id'],
            'year' => ['required', 'integer', 'in:'.implode(',', self::YEARS)],
        ]);

        $employee = Employee::where('employee_id', $validated['employee_id'])->firstOrFail();
        $year = (int) $validated['year'];

        $leaveRegisters = LeaveRegister::with('leaveType')
            ->where('employee_id', $employee->employee_id)
            ->whereYear('from_date', $year)
            ->orderBy('from_date')
            ->get();

        return [
            'employee' => $employee,
            'year' => $year,
            'leaveRegisters' => $leaveRegisters,
            'leaveTypeSummary' => $this->leaveTypeSummary($employee, $leaveRegisters, $year),
        ];
    }

    private function leaveTypeSummary(Employee $employee, Collection $leaveRegisters, int $year): Collection
    {
        $appliedByType = $leaveRegisters->groupBy('leave_type_id')->map(fn ($group) => $group->sum('days'));

        $carriedForwardByType = EmployeeLeaveBalance::where('employee_id', $employee->employee_id)
            ->where('year', '<', $year)
            ->get()
            ->groupBy('leave_type_id')
            ->map(fn ($group) => $group->sum('balance_in_days'));

        return LeaveType::orderBy('name')->get()->map(function (LeaveType $leaveType) use ($employee, $year, $appliedByType, $carriedForwardByType) {
            $applied = $appliedByType->get($leaveType->id, 0);
            $availableThisYear = $this->leaveEntitlementService->calculateAvailableDays($employee, $leaveType, $year);
            $carriedForward = $carriedForwardByType->get($leaveType->id, 0);
            $totalAvailable = $availableThisYear + $carriedForward;

            return [
                'name' => $leaveType->name,
                'applied' => $applied,
                'available_this_year' => $availableThisYear,
                'carried_forward' => $carriedForward,
                'total_available' => $totalAvailable,
                'remaining' => $totalAvailable - $applied,
            ];
        });
    }

    private function fontDataUri(string $filename): string
    {
        return Cache::rememberForever("report-font:{$filename}", function () use ($filename) {
            return base64_encode(file_get_contents(resource_path("fonts/{$filename}")));
        });
    }
}
