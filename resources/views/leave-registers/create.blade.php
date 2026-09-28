@extends('layouts.dashboard')

@section('title', 'Create Leave Register')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Leave Registers /</span> Create Leave Register
    </h4>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Leave Register Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('leave-registers.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="employee_id">Employee</label>
                            <select
                                class="form-select select2 @error('employee_id') is-invalid @enderror"
                                id="employee_id"
                                name="employee_id"
                            >
                                <option value=""></option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->employee_id }}" @selected(old('employee_id') === $employee->employee_id)>
                                        {{ $employee->name }} ({{ $employee->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="leave_type_id">Leave Type</label>
                            <select
                                class="form-select select2 @error('leave_type_id') is-invalid @enderror"
                                id="leave_type_id"
                                name="leave_type_id"
                            >
                                <option value=""></option>
                                @foreach ($leaveTypes as $leaveType)
                                    <option value="{{ $leaveType->id }}" @selected((int) old('leave_type_id') === $leaveType->id)>
                                        {{ $leaveType->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('leave_type_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="from_date">From Date</label>
                            <input
                                type="date"
                                class="form-control @error('from_date') is-invalid @enderror"
                                id="from_date"
                                name="from_date"
                                value="{{ old('from_date') }}"
                            />
                            @error('from_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="to_date">To Date</label>
                            <input
                                type="date"
                                class="form-control @error('to_date') is-invalid @enderror"
                                id="to_date"
                                name="to_date"
                                value="{{ old('to_date') }}"
                            />
                            @error('to_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="days_display">Days</label>
                            <input type="text" class="form-control" id="days_display" disabled placeholder="Auto-calculated" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="reason">Reason <span class="text-muted">(optional)</span></label>
                            <textarea
                                class="form-control @error('reason') is-invalid @enderror"
                                id="reason"
                                name="reason"
                                rows="3"
                            >{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Save Leave Register</button>
                        <a href="{{ route('leave-registers.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </form>
                </div>
            </div>
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

            function updateDaysDisplay() {
                var from = document.getElementById('from_date').value;
                var to = document.getElementById('to_date').value;
                var display = document.getElementById('days_display');

                if (!from || !to) {
                    display.value = '';
                    return;
                }

                var fromDate = new Date(from);
                var toDate = new Date(to);
                var diff = Math.round((toDate - fromDate) / (1000 * 60 * 60 * 24)) + 1;

                display.value = diff > 0 ? diff + (diff === 1 ? ' day' : ' days') : '';
            }

            document.getElementById('from_date').addEventListener('change', updateDaysDisplay);
            document.getElementById('to_date').addEventListener('change', updateDaysDisplay);
            updateDaysDisplay();
        });
    </script>
@endsection
