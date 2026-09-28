@extends('layouts.dashboard')

@section('title', 'Generate Report')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Leave Management /</span> Generate Report
    </h4>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Employee wise Leave Register Report</h5>
        </div>
        <div class="card-body">
            <form method="GET" id="reportForm" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label" for="employee_id">Employee</label>
                    <select class="form-select select2" id="employee_id" name="employee_id" required>
                        <option value=""></option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->employee_id }}">
                                {{ $employee->name }} ({{ $employee->employee_id }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="year">Year</label>
                    <select class="form-select" id="year" name="year">
                        @foreach ($years as $yearOption)
                            <option value="{{ $yearOption }}" @selected($yearOption === 2026)>{{ $yearOption }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" formaction="{{ route('reports.leave-register.show') }}" class="btn btn-primary">
                        <i class="bx bx-show"></i> Show Report
                    </button>
                    <button type="submit" formaction="{{ route('reports.leave-register.download') }}" class="btn btn-outline-primary">
                        <i class="bx bx-printer"></i> Print Report
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('page-js')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('.select2').select2({
                width: '100%',
            });
        });
    </script>
@endsection
