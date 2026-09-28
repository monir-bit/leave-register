@extends('layouts.dashboard')

@section('title', 'Leave Register Report')

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Leave Management /</span> Leave Register Report
    </h4>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">{{ $employee->name }} ({{ $employee->employee_id }}) &mdash; {{ $year }}</h5>

            <div class="d-flex gap-2">
                <a
                    href="{{ route('reports.leave-register.download', ['employee_id' => $employee->employee_id, 'year' => $year]) }}"
                    class="btn btn-primary"
                >
                    <i class="bx bx-printer"></i> Print Report
                </a>
                <a href="{{ route('reports.leave-register.create') }}" class="btn btn-outline-secondary">
                    <i class="bx bx-arrow-back"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="text-center mb-4">
                <h4 class="fw-bold mb-1">Ananda Bazar Media Limited</h4>
                <h6 class="fw-bold">Leave Register Report</h6>
            </div>

            <p class="mb-2"><strong>Employee Name:</strong> {{ $employee->name }}</p>

            <div class="row mb-4">
                <div class="col-md-4"><strong>Designation:</strong> {{ $employee->designation }}</div>
                <div class="col-md-4"><strong>Department:</strong> {{ $employee->department }}</div>
                <div class="col-md-4"><strong>Joining Date:</strong> {{ $employee->date_of_joining->format('d-m-Y') }}</div>
            </div>

            <h6 class="fw-bold">Leave Applied in Days</h6>
            <p class="mb-3"><strong>Year:</strong> {{ $year }}</p>

            <div class="table-responsive text-nowrap mb-4">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Leave Type</th>
                            <th>Applied</th>
                            <th>Available (This Year)</th>
                            <th>Carried Forward</th>
                            <th>Total Available</th>
                            <th>Remaining</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leaveTypeSummary as $summary)
                            <tr>
                                <td>{{ $summary['name'] }}</td>
                                <td>{{ $summary['applied'] }}</td>
                                <td>{{ $summary['available_this_year'] }}</td>
                                <td>{{ $summary['carried_forward'] }}</td>
                                <td>{{ $summary['total_available'] }}</td>
                                <td>{{ $summary['remaining'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Year</th>
                            <th>Month</th>
                            <th>Leave Type</th>
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>Days</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($leaveRegisters as $leaveRegister)
                            <tr>
                                <td>{{ $leaveRegister->from_date->format('Y') }}</td>
                                <td>{{ $leaveRegister->from_date->format('M') }}</td>
                                <td>{{ $leaveRegister->leaveType?->name }}</td>
                                <td>{{ $leaveRegister->from_date->format('d-m-Y') }}</td>
                                <td>{{ $leaveRegister->to_date->format('d-m-Y') }}</td>
                                <td>{{ $leaveRegister->days }}</td>
                                <td>{{ $leaveRegister->reason }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No leave records found for {{ $year }}.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
