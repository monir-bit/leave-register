@extends('layouts.dashboard')

@section('title', 'Create Employee')

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Employees /</span> Create Employee
    </h4>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Employee Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('employees.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="employee_id">Employee ID</label>
                            <input
                                type="text"
                                class="form-control @error('employee_id') is-invalid @enderror"
                                id="employee_id"
                                name="employee_id"
                                value="{{ old('employee_id') }}"
                                placeholder="EMP-0001"
                            />
                            @error('employee_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="name">Name</label>
                            <input
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="John Doe"
                            />
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="designation">Designation</label>
                            <input
                                type="text"
                                class="form-control @error('designation') is-invalid @enderror"
                                id="designation"
                                name="designation"
                                value="{{ old('designation') }}"
                                placeholder="Software Engineer"
                            />
                            @error('designation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="department">Department</label>
                            <input
                                type="text"
                                class="form-control @error('department') is-invalid @enderror"
                                id="department"
                                name="department"
                                value="{{ old('department') }}"
                                placeholder="Engineering"
                            />
                            @error('department')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="date_of_joining">Date of Joining</label>
                            <input
                                type="date"
                                class="form-control @error('date_of_joining') is-invalid @enderror"
                                id="date_of_joining"
                                name="date_of_joining"
                                value="{{ old('date_of_joining') }}"
                            />
                            @error('date_of_joining')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Save Employee</button>
                        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
