@extends('layouts.staff')

@section('content')
<div id="kt_content_container" class="container-xxl">
    <!-- Alerts -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filter Form -->
    <div class="card mb-5">
        <div class="card-header">
            <h3 class="card-title">Filter Complaints</h3>
        </div>
        <div class="card-body">
            <form id="filter_form" method="GET" action="{{ route('staff.complaints.index') }}">
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <label for="date_from" class="fs-5 fw-semibold form-label mb-2">From Date</label>
                        <input type="date" name="date_from" id="date_from" class="form-control form-control-solid" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3 mb-4">
                        <label for="date_to" class="fs-5 fw-semibold form-label mb-2">To Date</label>
                        <input type="date" name="date_to" id="date_to" class="form-control form-control-solid" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3 mb-4">
                        <label for="type" class="fs-5 fw-semibold form-label mb-2">Complaint Type</label>
                        <select name="type" id="type" class="form-select form-select-solid">
                            <option value="">All Types</option>
                            @foreach ($complaintTypes as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Complaints Table -->
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="card-title">Complaints</h3>
            </div>
            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-complaints-table-toolbar="base">
                    <!-- Add any toolbar actions if needed -->
                </div>
                <div class="d-flex justify-content-end align-items-center d-none" data-kt-complaints-table-toolbar="selected">
                    <div class="fw-bold me-5">
                        <span class="me-2" data-kt-complaints-table-select="selected_count"></span>Selected
                    </div>
                    <button type="button" class="btn btn-danger" data-kt-complaints-table-select="delete_selected">Delete Selected</button>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_complaints_table">
                <thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th class="w-10px pe-2">
                            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_complaints_table .form-check-input" value="1" />
                            </div>
                        </th>
                        <th class="min-w-125px">Customer</th>
                        <th class="min-w-125px">Type</th>
                        <th class="min-w-125px">Description</th>
                        <th class="min-w-125px">Status</th>
                        <th class="min-w-125px">Created At</th>
                        <th class="text-end min-w-100px">Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @forelse ($complaints as $complaint)
                        <tr>
                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="{{ $complaint->id }}" />
                                </div>
                            </td>
                            <td>{{ $complaint->customer->first_name }} {{ $complaint->customer->surname }}</td>
                            <td>{{ ucfirst($complaint->type) }}</td>
                            <td>{{ $complaint->description }}</td>
                            <td>
                                <div class="badge badge-light-{{ $complaint->status == 'resolved' ? 'success' : ($complaint->status == 'pending' ? 'warning' : 'primary') }}">
                                    {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                                </div>
                            </td>
                            <td>{{ $complaint->created_at->format('Y-m-d') }}</td>
                            <td class="text-end">
                                <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    Actions
                                    <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                </a>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4" data-kt-menu="true">
                                    @can('update-complaints')
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_complaint_update_modal{{ $complaint->id }}">Update Status</a>
                                        </div>
                                    @endcan
                                    @can('delete-complaints')
                                        <div class="menu-item px-3">
                                            <form action="{{ route('staff.complaints.destroy', $complaint) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="menu-link px-3" onclick="return confirm('Are you sure you want to delete this complaint?')">Delete</button>
                                            </form>
                                        </div>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        <!-- Update Modal -->
                        @can('update-complaints')
                            <div class="modal fade" id="kt_complaint_update_modal{{ $complaint->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h2 class="fw-bold">Update Complaint</h2>
                                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                <i class="ki-duotone ki-cross fs-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </div>
                                        </div>
                                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            <form action="{{ route('staff.complaints.update', $complaint) }}" method="POST" id="update_complaint_form_{{ $complaint->id }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="fv-row mb-10">
                                                    <label class="fs-5 fw-semibold form-label mb-5">Status</label>
                                                    <select name="status" class="form-select form-select-solid" required>
                                                        <option value="pending" {{ $complaint->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="in_progress" {{ $complaint->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                        <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                                    </select>
                                                </div>
                                                <div class="fv-row mb-10">
                                                    <label class="fs-5 fw-semibold form-label mb-5">Resolution Notes</label>
                                                    <textarea name="resolution_notes" class="form-control border-0 p-0 pe-10 resize-none min-h-25px" data-kt-autosize="true" rows="3" placeholder="Enter resolution notes (required if resolved)">{{ $complaint->resolution_notes }}</textarea>
                                                </div>
                                                <div class="text-center">
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endcan
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No Complaints found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    @if ($complaints->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">Previous</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $complaints->previousPageUrl() }}&date_from={{ request('date_from') }}&date_to={{ request('date_to') }}&type={{ request('type') }}">Previous</a>
                        </li>
                    @endif

                    @foreach ($complaints->getUrlRange(1, $complaints->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $complaints->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}&date_from={{ request('date_from') }}&date_to={{ request('date_to') }}&type={{ request('type') }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    @if ($complaints->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $complaints->nextPageUrl() }}&date_from={{ request('date_from') }}&date_to={{ request('date_to') }}&type={{ request('type') }}">Next</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">Next</span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterForm = document.getElementById('filter_form');
            const dateFrom = document.getElementById('date_from');
            const dateTo = document.getElementById('date_to');
            const type = document.getElementById('type');
            let filterTimeout;

            // Update URL for filters
            function updateURL() {
                const formData = new FormData(filterForm);
                const url = new URL('{{ route("staff.complaints.index") }}');
                formData.forEach((value, key) => {
                    if (value) url.searchParams.set(key, value);
                });
                window.location.href = url.toString();
            }

            // Debounced input handler
            function handleInput() {
                clearTimeout(filterTimeout);
                filterTimeout = setTimeout(updateURL, 500);
            }

            dateFrom.addEventListener('change', handleInput);
            dateTo.addEventListener('change', handleInput);
            type.addEventListener('change', handleInput);

            // Handle checkbox selection
            const masterCheckbox = document.querySelector('#kt_complaints_table .form-check-input[data-kt-check="true"]');
            const checkboxes = document.querySelectorAll('#kt_complaints_table .form-check-input:not([data-kt-check="true"])');
            const toolbarBase = document.querySelector('[data-kt-complaints-table-toolbar="base"]');
            const toolbarSelected = document.querySelector('[data-kt-complaints-table-toolbar="selected"]');
            const selectedCount = document.querySelector('[data-kt-complaints-table-select="selected_count"]');

            masterCheckbox.addEventListener('change', function () {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = masterCheckbox.checked;
                });
                updateSelectedCount();
            });

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });

            function updateSelectedCount() {
                const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
                selectedCount.textContent = checkedCount;
                if (checkedCount > 0) {
                    toolbarBase.classList.add('d-none');
                    toolbarSelected.classList.remove('d-none');
                } else {
                    toolbarBase.classList.remove('d-none');
                    toolbarSelected.classList.add('d-none');
                }
            }

            // Resolution notes validation
            document.querySelectorAll('[id^="update_complaint_form_"]').forEach(form => {
                form.addEventListener('submit', function (e) {
                    const status = form.querySelector('select[name="status"]').value;
                    const resolutionNotes = form.querySelector('textarea[name="resolution_notes"]').value.trim();
                    if (status === 'resolved' && !resolutionNotes) {
                        e.preventDefault();
                        alert('Resolution notes are required when setting the status to Resolved.');
                    }
                });
            });
        });
    </script>
    <style>
        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }
        .pagination .page-link {
            color: #007bff;
        }
        .pagination .page-item.disabled .page-link {
            color: #6c757d;
        }
    </style>
@endsection