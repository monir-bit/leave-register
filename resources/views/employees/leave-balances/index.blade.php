@extends('layouts.dashboard')

@section('title', 'Leave Balances')

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Employees /</span> Leave Balances
    </h4>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">{{ $employee->name }} ({{ $employee->employee_id }})</h5>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLeaveBalanceModal">
                    <i class="bx bx-plus"></i> Add Balance
                </button>
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                    <i class="bx bx-arrow-back"></i> Back
                </a>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Year</th>
                        <th>Leave Type</th>
                        <th>Balance (Days)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($leaveBalances as $leaveBalance)
                        <tr>
                            <td>{{ $leaveBalance->year }}</td>
                            <td>{{ $leaveBalance->leaveType->name }}</td>
                            <td>{{ $leaveBalance->balance_in_days }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a
                                            class="dropdown-item"
                                            href="javascript:void(0);"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editLeaveBalanceModal"
                                            data-action="{{ route('employees.leave-balances.update', [$employee, $leaveBalance]) }}"
                                            data-year="{{ $leaveBalance->year }}"
                                            data-leave_type_id="{{ $leaveBalance->leave_type_id }}"
                                            data-leave_type_name="{{ $leaveBalance->leaveType->name }}"
                                            data-balance_in_days="{{ $leaveBalance->balance_in_days }}"
                                        >
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                        <a
                                            class="dropdown-item"
                                            href="javascript:void(0);"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteLeaveBalanceModal"
                                            data-action="{{ route('employees.leave-balances.destroy', [$employee, $leaveBalance]) }}"
                                            data-name="{{ $leaveBalance->leaveType->name }} ({{ $leaveBalance->year }})"
                                        >
                                            <i class="bx bx-trash me-1"></i> Delete
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">No leave balances recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Leave Balance Modal -->
    <div class="modal fade" id="createLeaveBalanceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('employees.leave-balances.store', $employee) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Leave Balance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label d-block">Leave Type</label>
                            <div class="dropdown">
                                <button
                                    type="button"
                                    class="btn btn-outline-secondary dropdown-toggle w-100 text-start @error('leave_type_id') is-invalid @enderror"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                    id="leave_type_dropdown_btn"
                                >
                                    @php
                                        $oldLeaveType = $leaveTypes->firstWhere('id', (int) old('leave_type_id'));
                                    @endphp
                                    {{ $oldLeaveType->name ?? 'Select Leave Type' }}
                                </button>
                                <ul class="dropdown-menu w-100" aria-labelledby="leave_type_dropdown_btn">
                                    @foreach ($leaveTypes as $leaveType)
                                        <li>
                                            <a
                                                class="dropdown-item leave-type-option"
                                                href="javascript:void(0);"
                                                data-value="{{ $leaveType->id }}"
                                            >
                                                {{ $leaveType->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                                <input type="hidden" id="leave_type_id" name="leave_type_id" value="{{ old('leave_type_id') }}" />
                            </div>
                            @error('leave_type_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="year">Year</label>
                            <input
                                type="number"
                                class="form-control @error('year') is-invalid @enderror"
                                id="year"
                                name="year"
                                value="{{ old('year', now()->year) }}"
                                min="2000"
                                max="2100"
                            />
                            @error('year')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="balance_in_days">Balance (Days)</label>
                            <input
                                type="number"
                                min="0"
                                class="form-control @error('balance_in_days') is-invalid @enderror"
                                id="balance_in_days"
                                name="balance_in_days"
                                value="{{ old('balance_in_days') }}"
                            />
                            @error('balance_in_days')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Leave Balance Modal -->
    <div class="modal fade" id="editLeaveBalanceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editLeaveBalanceForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Leave Balance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label d-block">Leave Type</label>
                            <div class="dropdown">
                                <button
                                    type="button"
                                    class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                    id="edit_leave_type_dropdown_btn"
                                >
                                    Select Leave Type
                                </button>
                                <ul class="dropdown-menu w-100" aria-labelledby="edit_leave_type_dropdown_btn">
                                    @foreach ($leaveTypes as $leaveType)
                                        <li>
                                            <a
                                                class="dropdown-item edit-leave-type-option"
                                                href="javascript:void(0);"
                                                data-value="{{ $leaveType->id }}"
                                            >
                                                {{ $leaveType->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                                <input type="hidden" id="edit_leave_type_id" name="leave_type_id" required />
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="edit_year">Year</label>
                            <input type="number" class="form-control" id="edit_year" name="year" min="2000" max="2100" required />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="edit_balance_in_days">Balance (Days)</label>
                            <input type="number" min="0" class="form-control" id="edit_balance_in_days" name="balance_in_days" required />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Leave Balance Modal -->
    <div class="modal fade" id="deleteLeaveBalanceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="deleteLeaveBalanceForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Leave Balance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete the balance for
                        <strong id="delete_leave_balance_name"></strong>? This action cannot be undone.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Create modal: Leave Type dropdown
            document.querySelectorAll('.leave-type-option').forEach(function (option) {
                option.addEventListener('click', function () {
                    document.getElementById('leave_type_id').value = this.getAttribute('data-value');
                    document.getElementById('leave_type_dropdown_btn').textContent = this.textContent.trim();
                });
            });

            // Edit modal: Leave Type dropdown
            document.querySelectorAll('.edit-leave-type-option').forEach(function (option) {
                option.addEventListener('click', function () {
                    document.getElementById('edit_leave_type_id').value = this.getAttribute('data-value');
                    document.getElementById('edit_leave_type_dropdown_btn').textContent = this.textContent.trim();
                });
            });

            var editModal = document.getElementById('editLeaveBalanceModal');
            editModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                document.getElementById('editLeaveBalanceForm').action = button.getAttribute('data-action');
                document.getElementById('edit_leave_type_id').value = button.getAttribute('data-leave_type_id');
                document.getElementById('edit_leave_type_dropdown_btn').textContent = button.getAttribute('data-leave_type_name');
                document.getElementById('edit_year').value = button.getAttribute('data-year');
                document.getElementById('edit_balance_in_days').value = button.getAttribute('data-balance_in_days');
            });

            var deleteModal = document.getElementById('deleteLeaveBalanceModal');
            deleteModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                document.getElementById('deleteLeaveBalanceForm').action = button.getAttribute('data-action');
                document.getElementById('delete_leave_balance_name').textContent = button.getAttribute('data-name');
            });
        });
    </script>
@endsection
