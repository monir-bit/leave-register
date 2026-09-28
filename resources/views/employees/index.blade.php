@extends('layouts.dashboard')

@section('title', 'Employees')

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Employees /</span> List
    </h4>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">All Employees</h5>

            <div class="d-flex align-items-center gap-2">
                <form action="{{ route('employees.index') }}" method="GET" class="d-flex align-items-center gap-2">
                    <select name="per_page" class="form-select" onchange="this.form.submit()">
                        @foreach ($perPageOptions as $option)
                            <option value="{{ $option }}" @selected($perPage === $option)>{{ $option }} / page</option>
                        @endforeach
                    </select>

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search employees..."
                        value="{{ $search }}"
                    />
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-search"></i>
                    </button>
                    @if ($search !== '')
                        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Reset</a>
                    @endif
                </form>

                <a href="{{ route('employees.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Create Employee
                </a>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Department</th>
                        <th>Date of Joining</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($employees as $employee)
                        <tr>
                            <td>{{ $employee->employee_id }}</td>
                            <td><strong>{{ $employee->name }}</strong></td>
                            <td>{{ $employee->designation }}</td>
                            <td>{{ $employee->department }}</td>
                            <td>{{ $employee->date_of_joining->format('d M, Y') }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('employees.leave-balances.index', $employee) }}">
                                            <i class="bx bx-wallet me-1"></i> Show Balances
                                        </a>
                                        <a
                                            class="dropdown-item"
                                            href="javascript:void(0);"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editEmployeeModal"
                                            data-action="{{ route('employees.update', $employee) }}"
                                            data-employee_id="{{ $employee->employee_id }}"
                                            data-name="{{ $employee->name }}"
                                            data-designation="{{ $employee->designation }}"
                                            data-department="{{ $employee->department }}"
                                            data-date_of_joining="{{ $employee->date_of_joining->format('Y-m-d') }}"
                                        >
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                        <a
                                            class="dropdown-item"
                                            href="javascript:void(0);"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteEmployeeModal"
                                            data-action="{{ route('employees.destroy', $employee) }}"
                                            data-name="{{ $employee->name }}"
                                        >
                                            <i class="bx bx-trash me-1"></i> Delete
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No employees found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($employees->hasPages())
            <div class="card-footer">
                {{ $employees->onEachSide(1)->links() }}
            </div>
        @endif
    </div>

    <!-- Edit Employee Modal -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editEmployeeForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="edit_employee_id">Employee ID</label>
                            <input type="text" class="form-control" id="edit_employee_id" name="employee_id" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="edit_name">Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="edit_designation">Designation</label>
                            <input type="text" class="form-control" id="edit_designation" name="designation" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="edit_department">Department</label>
                            <input type="text" class="form-control" id="edit_department" name="department" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="edit_date_of_joining">Date of Joining</label>
                            <input type="date" class="form-control" id="edit_date_of_joining" name="date_of_joining" required />
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

    <!-- Delete Employee Modal -->
    <div class="modal fade" id="deleteEmployeeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="deleteEmployeeForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <strong id="delete_employee_name"></strong>? This action cannot be undone.
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
            var editModal = document.getElementById('editEmployeeModal');
            editModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                document.getElementById('editEmployeeForm').action = button.getAttribute('data-action');
                document.getElementById('edit_employee_id').value = button.getAttribute('data-employee_id');
                document.getElementById('edit_name').value = button.getAttribute('data-name');
                document.getElementById('edit_designation').value = button.getAttribute('data-designation');
                document.getElementById('edit_department').value = button.getAttribute('data-department');
                document.getElementById('edit_date_of_joining').value = button.getAttribute('data-date_of_joining');
            });

            var deleteModal = document.getElementById('deleteEmployeeModal');
            deleteModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                document.getElementById('deleteEmployeeForm').action = button.getAttribute('data-action');
                document.getElementById('delete_employee_name').textContent = button.getAttribute('data-name');
            });
        });
    </script>
@endsection
