@extends('layouts.dashboard')

@section('title', 'Leave Types')

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Leave Management /</span> Leave Types
    </h4>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Leave Types</h5>

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLeaveTypeModal">
                <i class="bx bx-plus"></i> Create
            </button>
        </div>

        <div class="card-body">
            @if ($leaveTypes->isEmpty())
                <p class="text-muted mb-0">No leave types found.</p>
            @else
                <ul class="list-group">
                    @foreach ($leaveTypes as $leaveType)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>
                                {{ $leaveType->name }}
                                <span class="text-muted">&mdash; {{ $leaveType->amount_of_days }} days/year</span>
                            </span>

                            <button
                                type="button"
                                class="btn btn-sm btn-icon"
                                data-bs-toggle="modal"
                                data-bs-target="#editLeaveTypeModal"
                                data-action="{{ route('leave-types.update', $leaveType) }}"
                                data-id="{{ $leaveType->id }}"
                                data-name="{{ $leaveType->name }}"
                                data-amount_of_days="{{ $leaveType->amount_of_days }}"
                            >
                                <i class="bx bx-edit-alt"></i>
                            </button>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Create Leave Type Modal -->
    <div class="modal fade" id="createLeaveTypeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('leave-types.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create Leave Type</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="name">Name</label>
                            <input
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Sick Leave"
                            />
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="amount_of_days">Amount of Days (per year)</label>
                            <input
                                type="number"
                                min="1"
                                class="form-control @error('amount_of_days') is-invalid @enderror"
                                id="amount_of_days"
                                name="amount_of_days"
                                value="{{ old('amount_of_days') }}"
                                placeholder="10"
                            />
                            @error('amount_of_days')
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

    <!-- Edit Leave Type Modal -->
    <div class="modal fade" id="editLeaveTypeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editLeaveTypeForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_leave_type_id" name="leave_type_id" value="{{ old('leave_type_id') }}" />
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Leave Type</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="edit_name">Name</label>
                            <input
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="edit_name"
                                name="name"
                                required
                            />
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="edit_amount_of_days">Amount of Days (per year)</label>
                            <input
                                type="number"
                                min="1"
                                class="form-control @error('amount_of_days') is-invalid @enderror"
                                id="edit_amount_of_days"
                                name="amount_of_days"
                                required
                            />
                            @error('amount_of_days')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
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
@endsection

@section('page-js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var editModal = document.getElementById('editLeaveTypeModal');
            editModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                document.getElementById('editLeaveTypeForm').action = button.getAttribute('data-action');
                document.getElementById('edit_leave_type_id').value = button.getAttribute('data-id');
                document.getElementById('edit_name').value = button.getAttribute('data-name');
                document.getElementById('edit_amount_of_days').value = button.getAttribute('data-amount_of_days');
            });

            @if ($errors->any())
                @if (old('leave_type_id'))
                    document.getElementById('editLeaveTypeForm').action = '{{ url('/leave-types') }}/{{ old('leave_type_id') }}';
                    document.getElementById('edit_leave_type_id').value = '{{ old('leave_type_id') }}';
                    document.getElementById('edit_name').value = '{{ old('name') }}';
                    document.getElementById('edit_amount_of_days').value = '{{ old('amount_of_days') }}';
                    new bootstrap.Modal(editModal).show();
                @else
                    new bootstrap.Modal(document.getElementById('createLeaveTypeModal')).show();
                @endif
            @endif
        });
    </script>
@endsection
