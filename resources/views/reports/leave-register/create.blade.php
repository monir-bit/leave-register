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
                    <button type="button" id="printReportBtn" class="btn btn-outline-primary">
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

            document.getElementById('printReportBtn').addEventListener('click', function () {
                var form = document.getElementById('reportForm');

                if (!form.reportValidity()) {
                    return;
                }

                var params = new URLSearchParams(new FormData(form)).toString();
                var printUrl = '{{ route('reports.leave-register.print') }}' + '?' + params;

                var iframe = document.getElementById('printFrame');
                if (!iframe) {
                    iframe = document.createElement('iframe');
                    iframe.id = 'printFrame';
                    iframe.style.position = 'fixed';
                    iframe.style.right = '0';
                    iframe.style.bottom = '0';
                    iframe.style.width = '0';
                    iframe.style.height = '0';
                    iframe.style.border = 'none';
                    document.body.appendChild(iframe);
                }

                iframe.onload = function () {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                };
                iframe.src = printUrl;
            });
        });
    </script>
@endsection
