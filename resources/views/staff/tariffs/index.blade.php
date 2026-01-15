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

    <!-- Insights Section -->
    <div class="card mb-5">
        <div class="card-header">
            <h3 class="card-title">Tariff Insights</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="card bg-light-primary">
                        <div class="card-body">
                            <h5>Total Tariffs</h5>
                            <p class="fs-2 fw-bold">{{ $total_tariffs }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card bg-light-info">
                        <div class="card-body">
                            <h5>Status Breakdown</h5>
                            <ul class="list-unstyled">
                                <li>Approved: {{ $status_counts['approved'] ?? 0 }}</li>
                                <li>Pending: {{ $status_counts['pending'] ?? 0 }}</li>
                                <li>Rejected: {{ $status_counts['rejected'] ?? 0 }}</li>
                                <li>Pending Delete: {{ $status_counts['pending_delete'] ?? 0 }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tariffs Table -->
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" id="search_tariff" class="form-control form-control-solid w-250px ps-13" placeholder="Search Tariffs" value="{{ request('search_tariff') }}" />
                </div>
                <form action="{{ route('staff.tariffs.index') }}" method="GET" class="d-flex align-items-center ms-3">
                    <select name="type" class="form-select form-select-solid w-150px" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        <option value="billing" {{ request('type') == 'billing' ? 'selected' : '' }}>Billing</option>
                        <option value="service" {{ request('type') == 'service' ? 'selected' : '' }}>Service</option>
                    </select>
                </form>
            </div>
            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-tariff-table-toolbar="base">
                    @can('create-tariff')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_tariff_create_modal">Add Tariff</button>
                    @endcan
                </div>
                <div class="d-flex justify-content-end align-items-center d-none" data-kt-tariff-table-toolbar="selected">
                    <div class="fw-bold me-5">
                        <span class="me-2" data-kt-tariff-table-select="selected_count"></span>Selected
                    </div>
                    <button type="button" class="btn btn-danger" data-kt-tariff-table-select="delete_selected">Delete Selected</button>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_tariff_table">
                <thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th class="w-10px pe-2">
                            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_tariff_table .form-check-input" value="1" />
                            </div>
                        </th>
                        <th class="min-w-125px">Name</th>
                        <th class="min-w-125px">Catcode</th>
                        <th class="min-w-125px">Category</th>
                        <th class="min-w-125px">Amount</th>
                        <th class="min-w-125px">Status</th>
                        <th class="text-end min-w-100px">Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @forelse ($tariffs as $tariff)
                        <tr>
                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="{{ $tariff->id }}" />
                                </div>
                            </td>
                            <td>{{ $tariff->name }}</td>
                            <td>{{ $tariff->catcode }}</td>
                            <td>{{ $tariff->category->name }}</td>
                            <td>{{ number_format($tariff->amount, 2) }}</td>
                            <td>
                                <div class="badge badge-light-{{ $tariff->status == 'approved' ? 'success' : ($tariff->status == 'pending' || $tariff->status == 'pending_delete' ? 'warning' : 'danger') }}">
                                    {{ ucfirst(str_replace('_', ' ', $tariff->status)) }}
                                </div>
                            </td>
                            <td class="text-end">
                                <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    Actions
                                    <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                </a>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_tariff_view_modal{{ $tariff->id }}">View</a>
                                    </div>
                                    @can('edit-tariff')
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_tariff_edit_modal{{ $tariff->id }}">Edit</a>
                                        </div>
                                    @endcan
                                    @can('delete-tariff')
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_tariff_delete_modal{{ $tariff->id }}">Delete</a>
                                        </div>
                                    @endcan
                                    @can('approve-tariff')
                                        @if ($tariff->status == 'pending' || $tariff->status == 'pending_delete')
                                            <div class="menu-item px-3">
                                                <form action="{{ route('staff.tariffs.approve', $tariff->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="menu-link px-3">Approve</button>
                                                </form>
                                            </div>
                                            <div class="menu-item px-3">
                                                <form action="{{ route('staff.tariffs.reject', $tariff->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="menu-link px-3">Reject</button>
                                                </form>
                                            </div>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        <!-- View Modal -->
                        <div class="modal fade" id="kt_tariff_view_modal{{ $tariff->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered mw-650px">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="fw-bold">View Tariff</h2>
                                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                            <i class="ki-duotone ki-cross fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        Name: {{ $tariff->name }}<br>
                                        Catcode: {{ $tariff->catcode }}<br>
                                        Category: {{ $tariff->category->name }}<br>
                                        Amount: {{ number_format($tariff->amount, 2) }}<br>
                                        Description: {{ $tariff->description ?? 'N/A' }}<br>
                                        Status: {{ ucfirst(str_replace('_', ' ', $tariff->status)) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Edit Modal -->
                        @can('edit-tariff')
                            <div class="modal fade" id="kt_tariff_edit_modal{{ $tariff->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h2 class="fw-bold">Edit Tariff</h2>
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
                                            <form action="{{ route('staff.tariffs.update', $tariff->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="fv-row mb-10">
                                                    <label class="fs-5 fw-semibold form-label mb-5">Name</label>
                                                    <input type="text" name="name" value="{{ $tariff->name }}" class="form-control form-control-solid" required />
                                                </div>
                                                <div class="fv-row mb-10">
                                                    <label class="fs-5 fw-semibold form-label mb-5">Catcode Suffix (Two Digits)</label>
                                                    <input type="text" name="suffix" value="{{ substr($tariff->catcode, -2) }}" class="form-control form-control-solid" required pattern="\d{2}" title="Must be exactly two digits" />
                                                </div>
                                                <div class="fv-row mb-10">
                                                    <label class="fs-5 fw-semibold form-label mb-5">Category</label>
                                                    <select name="category_id" class="form-select form-select-solid" required>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}" {{ $tariff->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="fv-row mb-10">
                                                    <label class="fs-5 fw-semibold form-label mb-5">Amount</label>
                                                    <input type="number" name="amount" value="{{ $tariff->amount }}" step="0.01" class="form-control form-control-solid" required />
                                                </div>
                                                <div class="fv-row mb-10">
                                                    <label class="fs-5 fw-semibold form-label mb-5">Description</label>
                                                    <textarea name="description" class="form-control form-control-solid">{{ $tariff->description }}</textarea>
                                                </div>
                                                @if (auth('staff')->user()->hasRole('manager'))
                                                    <div class="alert alert-info">
                                                        This action requires Super Admin approval.
                                                    </div>
                                                @endif
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
                        <!-- Delete Modal -->
                        @can('delete-tariff')
                            <div class="modal fade" id="kt_tariff_delete_modal{{ $tariff->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h2 class="fw-bold">Confirm Deletion</h2>
                                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                <i class="ki-duotone ki-cross fs-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to request deletion of {{ $tariff->name }} ({{ $tariff->catcode }})? This action will set the status to pending for admin approval.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('staff.tariffs.destroy', $tariff->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Request Deletion</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endcan
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No Tariffs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    @if ($tariffs->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">Previous</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $tariffs->previousPageUrl() }}&search_tariff={{ request('search_tariff') }}">Previous</a>
                        </li>
                    @endif

                    @foreach ($tariffs->getUrlRange(1, $tariffs->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $tariffs->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}&search_tariff={{ request('search_tariff') }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    @if ($tariffs->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $tariffs->nextPageUrl() }}&search_tariff={{ request('search_tariff') }}">Next</a>
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

    <!-- Create Modal -->
    <div class="modal fade" id="kt_tariff_create_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Add Tariff</h2>
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
                    <form action="{{ route('staff.tariffs.store') }}" method="POST">
                        @csrf
                        <div class="fv-row mb-10">
                            <label class="fs-5 fw-semibold form-label mb-5">Name</label>
                            <input type="text" name="name" class="form-control form-control-solid" required />
                        </div>
                        <div class="fv-row mb-10">
                            <label class="fs-5 fw-semibold form-label mb-5">Catcode Suffix (Two Digits)</label>
                            <input type="text" name="suffix" class="form-control form-control-solid" required pattern="\d{2}" title="Must be exactly two digits" />
                        </div>
                        <div class="fv-row mb-10">
                            <label class="fs-5 fw-semibold form-label mb-5">Category</label>
                            <select name="category_id" class="form-select form-select-solid" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row mb-10">
                            <label class="fs-5 fw-semibold form-label mb-5">Amount</label>
                            <input type="number" name="amount" step="0.01" class="form-control form-control-solid" required />
                        </div>
                        <div class="fv-row mb-10">
                            <label class="fs-5 fw-semibold form-label mb-5">Description</label>
                            <textarea name="description" class="form-control form-control-solid"></textarea>
                        </div>
                        @if (auth('staff')->user()->hasRole('manager'))
                            <div class="alert alert-info">
                                This action requires Super Admin approval.
                            </div>
                        @endif
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search_tariff');
            let searchTimeout;

            // Update URL for search
            function updateURL() {
                const search = searchInput.value;
                const url = new URL('{{ route("staff.tariffs.index") }}');
                if (search) url.searchParams.set('search_tariff', search);
                window.location.href = url.toString();
            }

            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(updateURL, 500);
            });

            // Handle checkbox selection
            const masterCheckbox = document.querySelector('#kt_tariff_table .form-check-input[data-kt-check="true"]');
            const checkboxes = document.querySelectorAll('#kt_tariff_table .form-check-input:not([data-kt-check="true"])');
            const toolbarBase = document.querySelector('[data-kt-tariff-table-toolbar="base"]');
            const toolbarSelected = document.querySelector('[data-kt-tariff-table-toolbar="selected"]');
            const selectedCount = document.querySelector('[data-kt-tariff-table-select="selected_count"]');

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