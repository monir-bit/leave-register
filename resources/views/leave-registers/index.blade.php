@extends('layouts.dashboard')

@section('title', 'Leave Registers')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Leave Management /</span> Leave Registers
    </h4>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">All Leave Registers</h5>

            <div class="d-flex align-items-center gap-2">
                <form action="{{ route('leave-registers.index') }}" method="GET" class="d-flex align-items-center gap-2">
                    <select name="per_page" class="form-select" onchange="this.form.submit()">
                        @foreach ($perPageOptions as $option)
                            <option value="{{ $option }}" @selected($perPage === $option)>{{ $option }} / page</option>
                        @endforeach
                    </select>

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search leave registers..."
                        value="{{ $search }}"
                    />
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-search"></i>
                    </button>
                    @if ($search !== '')
                        <a href="{{ route('leave-registers.index') }}" class="btn btn-outline-secondary">Reset</a>
                    @endif
                </form>

                <a href="{{ route('leave-registers.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Create Leave Register
                </a>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>From Date</th>
                        <th>To Date</th>
                        <th>Days</th>
                        <th>Reason</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($leaveRegisters as $leaveRegister)
                        <tr>
                            <td>
                                <strong>{{ $leaveRegister->employee?->name }}</strong>
                                <div class="text-muted small">{{ $leaveRegister->employee_id }}</div>
                            </td>
                            <td>{{ $leaveRegister->leaveType?->name }}</td>
                            <td>{{ $leaveRegister->from_date->format('d M, Y') }}</td>
                            <td>{{ $leaveRegister->to_date->format('d M, Y') }}</td>
                            <td>{{ $leaveRegister->days }}</td>
                            <td class="text-wrap" style="max-width: 250px;">{{ $leaveRegister->reason }}</td>
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
                                            data-bs-target="#editLeaveRegisterModal"
                                            data-action="{{ route('leave-registers.update', $leaveRegister) }}"
                                            data-employee_id="{{ $leaveRegister->employee_id }}"
                                            data-leave_type_id="{{ $leaveRegister->leave_type_id }}"
                                            data-from_date="{{ $leaveRegister->from_date->format('Y-m-d') }}"
                                            data-to_date="{{ $leaveRegister->to_date->format('Y-m-d') }}"
                                            data-reason="{{ $leaveRegister->reason }}"
                                        >
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                        <a
                                            class="dropdown-item"
                                            href="javascript:void(0);"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteLeaveRegisterModal"
                                            data-action="{{ route('leave-registers.destroy', $leaveRegister) }}"
                                            data-name="{{ $leaveRegister->employee?->name }}"
                                        >
                                            <i class="bx bx-trash me-1"></i> Delete
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">No leave registers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($leaveRegisters->hasPages())
            <div class="card-footer">
                {{ $leaveRegisters->onEachSide(1)->links() }}
            </div>
        @endif
    </div>

    <!-- Edit Leave Register Modal -->
    <div class="modal fade" id="editLeaveRegisterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editLeaveRegisterForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Leave Register</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="edit_employee_id">Employee</label>
                            <select class="form-select select2-edit" id="edit_employee_id" name="employee_id">
                                <option value=""></option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->employee_id }}">
                                        {{ $employee->name }} ({{ $employee->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="edit_leave_type_id">Leave Type</label>
                            <select class="form-select select2-edit" id="edit_leave_type_id" name="leave_type_id">
                                <option value=""></option>
                                @foreach ($leaveTypes as $leaveType)
                                    <option value="{{ $leaveType->id }}">{{ $leaveType->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="edit_from_date">From Date</label>
                            <input type="date" class="form-control" id="edit_from_date" name="from_date" required />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="edit_to_date">To Date</label>
                            <input type="date" class="form-control" id="edit_to_date" name="to_date" required />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="edit_reason">Reason <span class="text-muted">(optional)</span></label>
                            <textarea class="form-control" id="edit_reason" name="reason" rows="3"></textarea>
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

    <!-- Delete Leave Register Modal -->
    <div class="modal fade" id="deleteLeaveRegisterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="deleteLeaveRegisterForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Leave Register</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete the leave register for
                        <strong id="delete_leave_register_name"></strong>? This action cannot be undone.
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
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('.select2-edit').select2({
                width: '100%',
                dropdownParent: $('#editLeaveRegisterModal'),
            });

            var editModal = document.getElementById('editLeaveRegisterModal');
            editModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                document.getElementById('editLeaveRegisterForm').action = button.getAttribute('data-action');

                $('#edit_employee_id').val(button.getAttribute('data-employee_id')).trigger('change');
                $('#edit_leave_type_id').val(button.getAttribute('data-leave_type_id')).trigger('change');

                document.getElementById('edit_from_date').value = button.getAttribute('data-from_date');
                document.getElementById('edit_to_date').value = button.getAttribute('data-to_date');
                document.getElementById('edit_reason').value = button.getAttribute('data-reason');
            });

            var deleteModal = document.getElementById('deleteLeaveRegisterModal');
            deleteModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                document.getElementById('deleteLeaveRegisterForm').action = button.getAttribute('data-action');
                document.getElementById('delete_leave_register_name').textContent = button.getAttribute('data-name');
            });
        });
    </script>
@endsection
