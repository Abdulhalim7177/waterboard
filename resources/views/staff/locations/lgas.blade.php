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

        <!-- LGAs Table -->
        <div class="card mb-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex flex-column flex-md-row align-items-md-center position-relative my-1">
                        <div class="d-flex align-items-center position-relative">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <input type="text" id="search_lga" class="form-control form-control-solid w-100px w-md-250px ps-13" placeholder="Search LGAs" value="{{ request('search_lga') }}" />
                        </div>
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end" data-kt-lga-table-toolbar="base">
                        @can('create-lga')
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_lga_create_modal">
                                <i class="ki-duotone ki-plus fs-2"></i>
                                <span class="d-none d-md-inline">Add LGA</span>
                            </button>
                        @endcan
                    </div>
                    <div class="d-flex justify-content-end align-items-center d-none" data-kt-lga-table-toolbar="selected">
                        <div class="fw-bold me-5">
                            <span class="me-2" data-kt-lga-table-select="selected_count"></span>Selected
                        </div>
                        <button type="button" class="btn btn-danger" data-kt-lga-table-select="delete_selected">
                            <i class="ki-duotone ki-trash fs-2"></i>
                            <span class="d-none d-md-inline">Delete Selected</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <!-- Desktop Table -->
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_lga_table">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_lga_table .form-check-input" value="1" />
                                    </div>
                                </th>
                                <th class="min-w-100px">Name</th>
                                <th class="min-w-75px">Code</th>
                                <th class="min-w-100px">State</th>
                                <th class="min-w-100px">Status</th>
                                <th class="text-end min-w-75px">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @forelse ($lgas as $lga)
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" value="{{ $lga->id }}" />
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 text-hover-primary mb-1">{{ $lga->name }}</span>
                                                <span class="text-muted fs-7">Code: {{ $lga->code }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell">{{ $lga->code }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 text-hover-primary mb-1">{{ $lga->state }}</span>
                                            <span class="text-muted fs-7 d-md-none">Code: {{ $lga->code }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="badge badge-light-{{ $lga->status == 'approved' ? 'success' : ($lga->status == 'pending' || $lga->status == 'pending_delete' ? 'warning' : 'danger') }}">
                                            {{ ucfirst(str_replace('_', ' ', $lga->status)) }}
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Actions
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                        </a>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_lga_view_modal{{ $lga->id }}">View</a>
                                            </div>
                                            @can('edit-lga')
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_lga_edit_modal{{ $lga->id }}">Edit</a>
                                                </div>
                                            @endcan
                                            @can('delete-lga')
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_lga_delete_modal{{ $lga->id }}">Delete</a>
                                                </div>
                                            @endcan
                                            @can('approve-lga')
                                                @if ($lga->status == 'pending' || $lga->status == 'pending_delete')
                                                    <div class="menu-item px-3">
                                                        <form action="{{ route('staff.lgas.approve', $lga->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="menu-link px-3">Approve</button>
                                                        </form>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <form action="{{ route('staff.lgas.reject', $lga->id) }}" method="POST" class="d-inline">
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
                                <div class="modal fade" id="kt_lga_view_modal{{ $lga->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered mw-650px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h2 class="fw-bold">View LGA</h2>
                                                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                    <i class="ki-duotone ki-cross fs-1">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                Name: {{ $lga->name }}<br>
                                                Code: {{ $lga->code }}<br>
                                                State: {{ $lga->state }}<br>
                                                Status: {{ ucfirst(str_replace('_', ' ', $lga->status)) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Edit Modal -->
                                @can('edit-lga')
                                    <div class="modal fade" id="kt_lga_edit_modal{{ $lga->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="fw-bold">Edit LGA</h2>
                                                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                        <i class="ki-duotone ki-cross fs-1">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </div>
                                                </div>
                                                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                    <form action="{{ route('staff.lgas.update', $lga->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="fv-row mb-10">
                                                            <label class="fs-5 fw-semibold form-label mb-5">Name</label>
                                                            <input type="text" name="name" value="{{ $lga->name }}" class="form-control form-control-solid" required />
                                                        </div>
                                                        <div class="fv-row mb-10">
                                                            <label class="fs-5 fw-semibold form-label mb-5">Code</label>
                                                            <input type="text" name="code" value="{{ $lga->code }}" class="form-control form-control-solid" required />
                                                        </div>
                                                        <div class="fv-row mb-10">
                                                            <label class="fs-5 fw-semibold form-label mb-5">State</label>
                                                            <input type="text" name="state" value="{{ $lga->state }}" class="form-control form-control-solid" required />
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
                                <!-- Delete Modal -->
                                @can('delete-lga')
                                    <div class="modal fade" id="kt_lga_delete_modal{{ $lga->id }}" tabindex="-1" aria-hidden="true">
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
                                                    Are you sure you want to request deletion of {{ $lga->name }} ({{ $lga->code }})? This action will set the status to pending for admin approval.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('staff.lgas.destroy', $lga->id) }}" method="POST" class="d-inline">
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
                                    <td colspan="6" class="text-center">No LGAs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Mobile Cards -->
                <div class="d-md-none d-none">
                    @forelse ($lgas as $lga)
                        <div class="card mb-5 mb-xl-8 border border-gray-300">
                            <div class="card-header border-0 pt-5">
                                <div class="d-flex flex-stack flex-wrap gap-2 w-100">
                                    <div class="d-flex align-items-center">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox" value="{{ $lga->id }}" />
                                        </div>
                                        <div class="d-flex flex-column">
                                            <a href="#" class="text-gray-800 text-hover-primary fs-5 fw-bold" data-bs-toggle="modal" data-bs-target="#kt_lga_view_modal{{ $lga->id }}">{{ $lga->name }}</a>
                                            <span class="text-muted fs-7">Code: {{ $lga->code }}</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="badge badge-light-{{ $lga->status == 'approved' ? 'success' : ($lga->status == 'pending' || $lga->status == 'pending_delete' ? 'warning' : 'danger') }}">
                                            {{ ucfirst(str_replace('_', ' ', $lga->status)) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-3 pb-5">
                                <div class="d-flex flex-stack flex-wrap gap-3">
                                    <div class="d-flex flex-column">
                                        <div class="text-muted fs-7">State</div>
                                        <div class="text-gray-800 fw-bold">{{ $lga->state }}</div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <div class="text-muted fs-7">Actions</div>
                                        <div class="d-flex gap-2">
                                            <a href="#" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#kt_lga_view_modal{{ $lga->id }}">View</a>
                                            @can('edit-lga')
                                                <a href="#" class="btn btn-sm btn-light-warning" data-bs-toggle="modal" data-bs-target="#kt_lga_edit_modal{{ $lga->id }}">Edit</a>
                                            @endcan
                                            @can('delete-lga')
                                                <a href="#" class="btn btn-sm btn-light-danger" data-bs-toggle="modal" data-bs-target="#kt_lga_delete_modal{{ $lga->id }}">Delete</a>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <div class="text-muted fs-3">No LGAs found.</div>
                        </div>
                    @endforelse
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        @if ($lgas->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">Previous</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $lgas->previousPageUrl() }}&search_lga={{ request('search_lga') }}">Previous</a>
                            </li>
                        @endif

                        @foreach ($lgas->getUrlRange(1, $lgas->lastPage()) as $page => $url)
                            <li class="page-item {{ $page == $lgas->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}&search_lga={{ request('search_lga') }}">{{ $page }}</a>
                            </li>
                        @endforeach

                        @if ($lgas->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $lgas->nextPageUrl() }}&search_lga={{ request('search_lga') }}">Next</a>
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

        <!-- Create Modal -->
        <div class="modal fade" id="kt_lga_create_modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">Add LGA</h2>
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="ki-duotone ki-cross fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                    </div>
                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                        <form action="{{ route('staff.lgas.store') }}" method="POST">
                            @csrf
                            <div class="fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">Name</label>
                                <input type="text" name="name" class="form-control form-control-solid" required />
                            </div>
                            <div class="fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">Code</label>
                                <input type="text" name="code" class="form-control form-control-solid" required />
                            </div>
                            <div class="fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">State</label>
                                <input type="text" name="state" class="form-control form-control-solid" required />
                            </div>
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
            const searchInput = document.getElementById('search_lga');
            let searchTimeout;

            // Update URL for search
            function updateURL() {
                const search = searchInput.value;
                const url = new URL('{{ route("staff.lgas.index") }}');
                if (search) url.searchParams.set('search_lga', search);
                window.location.href = url.toString();
            }

            // Debounced search input handler
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(updateURL, 500);
            });

            // Handle checkbox selection
            const masterCheckbox = document.querySelector('#kt_lga_table .form-check-input[data-kt-check="true"]');
            const checkboxes = document.querySelectorAll('#kt_lga_table .form-check-input:not([data-kt-check="true"])');
            const toolbarBase = document.querySelector('[data-kt-lga-table-toolbar="base"]');
            const toolbarSelected = document.querySelector('[data-kt-lga-table-toolbar="selected"]');
            const selectedCount = document.querySelector('[data-kt-lga-table-select="selected_count"]');

            // Master checkbox handler
            masterCheckbox.addEventListener('change', function () {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = masterCheckbox.checked;
                });
                updateSelectedCount();
            });

            // Individual checkbox handlers
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });

            // Update selected count and toolbar visibility
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

            // Responsive adjustments
            function adjustForScreenSize() {
                const isMobile = window.innerWidth < 768;
                
                // Adjust table behavior for mobile
                if (isMobile) {
                    // On mobile, we might want to collapse some columns
                    document.querySelectorAll('.table td.d-md-none').forEach(el => {
                        el.classList.remove('d-md-none');
                    });
                    document.querySelectorAll('.table td.d-none.d-md-table-cell').forEach(el => {
                        el.classList.add('d-none');
                    });
                } else {
                    // On desktop, show all columns
                    document.querySelectorAll('.table td.d-md-none').forEach(el => {
                        el.classList.add('d-md-none');
                    });
                    document.querySelectorAll('.table td.d-none.d-md-table-cell').forEach(el => {
                        el.classList.remove('d-none');
                    });
                }
            }

            // Run on load and resize
            adjustForScreenSize();
            window.addEventListener('resize', adjustForScreenSize);
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