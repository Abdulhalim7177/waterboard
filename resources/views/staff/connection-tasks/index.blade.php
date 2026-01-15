@extends('layouts.staff')

@section('page_title')
    Connection Tasks Management
@endsection

@section('page_description')
    Assign and track connection tasks
@endsection

@section('content')
<div class="container">
    <!-- Stats Cards -->
    <div class="row g-5 g-xl-8 mb-5">
        <div class="col-xl-2">
            <div class="card bg-light-primary card-xl-stretch mb-xl-8">
                <div class="card-body">
                    <span class="fs-2 fw-bold text-primary">{{ $stats['total'] }}</span>
                    <h3 class="text-primary fw-bold mt-2">Total</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-2">
            <div class="card bg-light-warning card-xl-stretch mb-xl-8">
                <div class="card-body">
                    <span class="fs-2 fw-bold text-warning">{{ $stats['pending'] }}</span>
                    <h3 class="text-warning fw-bold mt-2">Pending</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-2">
            <div class="card bg-light-info card-xl-stretch mb-xl-8">
                <div class="card-body">
                    <span class="fs-2 fw-bold text-info">{{ $stats['assigned'] }}</span>
                    <h3 class="text-info fw-bold mt-2">Assigned</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-2">
            <div class="card bg-light-dark card-xl-stretch mb-xl-8">
                <div class="card-body">
                    <span class="fs-2 fw-bold text-dark">{{ $stats['in_progress'] }}</span>
                    <h3 class="text-dark fw-bold mt-2">In Progress</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-2">
            <div class="card bg-light-success card-xl-stretch mb-xl-8">
                <div class="card-body">
                    <span class="fs-2 fw-bold text-success">{{ $stats['completed'] }}</span>
                    <h3 class="text-success fw-bold mt-2">Completed</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-2">
            <div class="card bg-light-danger card-xl-stretch mb-xl-8">
                <div class="card-body">
                    <span class="fs-2 fw-bold text-danger">{{ $stats['cancelled'] }}</span>
                    <h3 class="text-danger fw-bold mt-2">Cancelled</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-flush h-xl-100">
        <div class="card-header border-0 pt-7">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold text-dark">Connection Tasks</span>
            </h3>
            <div class="card-toolbar">
                <div class="d-flex flex-wrap align-items-end gap-4">
                    <div class="d-flex align-items-center position-relative my-1">
                        <input type="text" id="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search..." />
                    </div>
                    <form method="GET" action="{{ route('staff.connection-tasks.index') }}" class="d-flex flex-wrap align-items-end gap-4" id="filter-form">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                                       class="form-control form-control-solid w-200px" placeholder="Start Date" />
                            </div>
                            <div class="col-md-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                       class="form-control form-control-solid w-200px" placeholder="End Date" />
                            </div>
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select form-select-solid w-200px">
                                    <option value="">All</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="staff_id" class="form-label">Assigned To</label>
                                <select name="staff_id" id="staff_id" class="form-select form-select-solid w-250px" data-control="select2">
                                    <option value="">All</option>
                                    @foreach ($staff as $staff_member)
                                        <option value="{{ $staff_member->id }}" {{ request('staff_id') == $staff_member->id ? 'selected' : '' }}>{{ $staff_member->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="lga_id" class="form-label">LGA</label>
                                <select name="lga_id" id="lga_id" class="form-select form-select-solid w-200px" data-control="select2">
                                    <option value="">All</option>
                                    @foreach($lgas as $lga)
                                        <option value="{{ $lga->id }}" {{ request('lga_id') == $lga->id ? 'selected' : '' }}>{{ $lga->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="ward_id" class="form-label">Ward</label>
                                <select name="ward_id" id="ward_id" class="form-select form-select-solid w-200px" data-control="select2">
                                    <option value="">All</option>
                                    @foreach($wards as $ward)
                                        <option value="{{ $ward->id }}" {{ request('ward_id') == $ward->id ? 'selected' : '' }}>{{ $ward->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="area_id" class="form-label">Area</label>
                                <select name="area_id" id="area_id" class="form-select form-select-solid w-200px" data-control="select2">
                                    <option value="">All</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="position-relative align-self-end">
                            <button type="submit" class="btn btn-primary btn-sm">Apply Filters</button>
                            <a href="{{ route('staff.connection-tasks.index') }}" class="btn btn-light btn-sm ms-2">Clear Filters</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body pt-0 mt-5">
                <div class="table-responsive position-relative">
                    <table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_tasks_table">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th>Task ID</th>
                                <th>Customer Name</th>
                                <th>Service Type</th>
                                <th>Assigned To</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="fw-bold text-gray-600" id="tasks-table-body">
                            @forelse ($tasks as $task)
                                <tr data-customer-name="{{ $task->bill->customer->first_name }} {{ $task->bill->customer->surname }}"
                                    data-staff-name="{{ $task->staff->full_name ?? '' }}"
                                    data-billing-id="{{ $task->bill->billing_id }}"
                                    data-email="{{ $task->bill->customer->email }}"
                                    data-staff-email="{{ $task->staff->email ?? '' }}"
                                    data-staff-number="{{ $task->staff->staff_no ?? '' }}">
                                    <td>{{ $task->id }}</td>
                                    <td>{{ $task->bill->customer->first_name }} {{ $task->bill->customer->surname }}</td>
                                    <td>{{ $task->bill->tariff->name }}</td>
                                    <td>{{ $task->staff->full_name ?? 'Not Assigned' }}</td>
                                    <td>
                                        <span class="badge py-3 px-4 fs-7 badge-light-{{ $task->status === 'completed' ? 'success' : ($task->status === 'cancelled' ? 'danger' : 'warning') }}">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                    </td>
                                    <td>{{ $task->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-light btn-active-light-primary" data-bs-toggle="modal" data-bs-target="#viewTaskModal-{{ $task->id }}">View</button>
                                        <a href="{{ route('staff.connection-tasks.edit', $task->id) }}" class="btn btn-sm btn-light btn-active-light-primary">Assign/Update</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No connection tasks found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @foreach($tasks as $task)
    <!-- View Task Modal -->
    <div class="modal fade" id="viewTaskModal-{{ $task->id }}" tabindex="-1" aria-labelledby="viewTaskModalLabel-{{ $task->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewTaskModalLabel-{{ $task->id }}">Task #{{ $task->id }} Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Customer:</strong> {{ $task->bill->customer->first_name }} {{ $task->bill->customer->surname }}</p>
                    <p><strong>Service:</strong> {{ $task->bill->tariff->name }}</p>
                    <p><strong>Bill Amount:</strong> ₦{{ number_format($task->bill->amount, 2) }}</p>
                    <p><strong>Bill Status:</strong> {{ ucfirst($task->bill->status) }}</p>
                    <hr>
                    <p><strong>Assigned To:</strong> {{ $task->staff->full_name ?? 'Not Assigned' }}</p>
                    <p><strong>Task Status:</strong> {{ ucfirst(str_replace('_', ' ', $task->status)) }}</p>
                    <p><strong>Notes:</strong></p>
                    <p>{{ $task->notes ?? 'N/A' }}</p>
                    @if($task->pipe_path)
                    <p><strong>Pipe Path:</strong></p>
                    <pre>{{ json_encode($task->pipe_path, JSON_PRETTY_PRINT) }}</pre>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#staff_id').select2();
        $('#lga_id').select2();
        $('#ward_id').select2();
        $('#area_id').select2();

        const searchInput = document.getElementById('search');
        const tableBody = document.getElementById('tasks-table-body');
        const tableRows = tableBody.getElementsByTagName('tr');

        function filterRows() {
            const searchTerm = searchInput.value.toLowerCase();

            for (let i = 0; i < tableRows.length; i++) {
                const row = tableRows[i];
                const customerName = row.dataset.customerName.toLowerCase();
                const staffName = row.dataset.staffName.toLowerCase();
                const billingId = row.dataset.billingId.toLowerCase();
                const email = row.dataset.email.toLowerCase();
                const staffEmail = row.dataset.staffEmail.toLowerCase();
                const staffNumber = row.dataset.staffNumber.toLowerCase();

                if (customerName.includes(searchTerm) ||
                    staffName.includes(searchTerm) ||
                    billingId.includes(searchTerm) ||
                    email.includes(searchTerm) ||
                    staffEmail.includes(searchTerm) ||
                    staffNumber.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        searchInput.addEventListener('keyup', filterRows);
    });
</script>
@endsection
