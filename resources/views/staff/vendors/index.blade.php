@extends('layouts.staff')

@section('content')
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirm Delete</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Delete this vendor?</p>
                    <p class="fw-bold text-truncate" id="vendorNameToDelete"></p>
                    <p class="text-danger text-sm">Cannot be undone</p>
                </div>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-sm btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-row me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Vendor Management</h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">Home</li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Vendors</li>
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page title-->
                <!--begin::Actions-->
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="{{ route('staff.vendors.create') }}" class="btn btn-primary btn-flex flex-nowrap text-nowrap">
                        <i class="ki-duotone ki-plus fs-3"></i>
                        Add Vendor
                    </a>
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-xxl">
                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-6">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <!--begin::Search-->
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <input type="text" id="searchInput" class="form-control form-control-solid w-250px ps-13" placeholder="Search vendors..." />
                            </div>
                            <!--end::Search-->
                        </div>
                        <!--begin::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <!--begin::Toolbar-->
                            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                <!--begin::Filter-->
                                <button type="button" class="btn btn-sm btn-icon btn-light-primary me-3" id="filterButton" title="Filter">
                                    <i class="ki-duotone ki-filter fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </button>
                                <!--begin::Menu 1-->
                                <div class="menu menu-sub menu-sub-dropdown w-225px" id="filterMenu">
                                    <!--begin::Header-->
                                    <div class="px-5 py-3 border-bottom">
                                        <div class="fs-6 text-dark fw-bold">Filter</div>
                                    </div>
                                    <!--end::Header-->
                                    <!--begin::Content-->
                                    <div class="px-5 py-3">
                                        <!--begin::Input group-->
                                        <div class="mb-3">
                                            <label class="form-label fs-6 fw-semibold mb-2">Status:</label>
                                            <div class="fv-row">
                                                <!--begin:Options-->
                                                <label class="form-check form-check-sm form-check-custom form-check-solid mb-2">
                                                    <input class="form-check-input status-filter" type="checkbox" value="approved" id="ktFilterApproved" />
                                                    <label class="form-check-label" for="ktFilterApproved">
                                                        <div class="fw-semibold text-gray-800">Approved</div>
                                                    </label>
                                                </label>
                                                <label class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input class="form-check-input status-filter" type="checkbox" value="pending" id="ktFilterPending" />
                                                    <label class="form-check-label" for="ktFilterPending">
                                                        <div class="fw-semibold text-gray-800">Pending</div>
                                                    </label>
                                                </label>
                                                <!--end::Options-->
                                            </div>
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="d-flex justify-content-end pt-2">
                                            <button type="button" class="btn btn-sm btn-light btn-active-light-primary me-2" id="resetFilters">Reset</button>
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <!--end::Content-->
                                </div>
                                <!--end::Menu 1-->
                                <!--end::Filter-->
                            </div>
                            <!--end::Toolbar-->
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                            <!--begin::Table head-->
                            <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2 d-none d-md-table-cell">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox" id="selectAll" value="1" />
                                        </div>
                                    </th>
                                    <th class="min-w-125px">Vendor</th>
                                    <th class="min-w-125px d-none d-lg-table-cell">Email</th>
                                    <th class="min-w-125px d-none d-lg-table-cell">Code</th>
                                    <th class="min-w-125px d-none d-xl-table-cell">Location</th>
                                    <th class="min-w-125px">Status</th>
                                    <th class="min-w-70px">Actions</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-semibold text-gray-600">
                                @forelse($vendors as $vendor)
                                <tr data-status="{{ $vendor->approved ? 'approved' : 'pending' }}" data-search="{{ strtolower($vendor->name . ' ' . $vendor->email . ' ' . $vendor->vendor_code . ' ' . ($vendor->area->name ?? '') . ' ' . ($vendor->ward->name ?? '') . ' ' . ($vendor->lga->name ?? '')) }}">
                                    <!--begin::Checkbox-->
                                    <td class="d-none d-md-table-cell">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input vendor-checkbox" type="checkbox" value="{{ $vendor->id }}" />
                                        </div>
                                    </td>
                                    <!--end::Checkbox-->
                                    <!--begin::Vendor=-->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <!--begin:: Avatar -->
                                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3 d-none d-sm-block">
                                                <div class="symbol-label">
                                                    <span class="fs-3 bg-light-primary text-primary fw-bold me-3">{{ strtoupper(substr($vendor->name, 0, 1)) }}</span>
                                                </div>
                                            </div>
                                            <!--end::Avatar-->
                                            <!--begin::Vendor info-->
                                            <div class="d-flex flex-column">
                                                <a href="{{ route('staff.vendors.show', $vendor) }}" class="text-gray-800 text-hover-primary mb-1">{{ $vendor->name }}</a>
                                                <span class="text-muted d-block d-sm-none">ID: {{ $vendor->id }}</span>
                                                <span class="text-muted d-none d-sm-block">ID: {{ $vendor->id }}</span>
                                            </div>
                                            <!--end::Vendor info-->
                                        </div>
                                    </td>
                                    <!--end::Vendor=-->
                                    <!--begin::Email=-->
                                    <td class="d-none d-lg-table-cell">
                                        <a href="mailto:{{ $vendor->email }}" class="text-gray-600 text-hover-primary mb-1">{{ $vendor->email }}</a>
                                    </td>
                                    <!--end::Email=-->
                                    <!--begin::Code=-->
                                    <td class="d-none d-lg-table-cell">
                                        <span class="text-gray-800 fw-bold">#{{ $vendor->vendor_code }}</span>
                                    </td>
                                    <!--end::Code=-->
                                    <!--begin::Location=-->
                                    <td class="d-none d-xl-table-cell">
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 fw-bold">{{ $vendor->area->name ?? 'N/A' }}</span>
                                            <span class="text-muted fw-semibold">{{ $vendor->ward->name ?? 'N/A' }}, {{ $vendor->lga->name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <!--end::Location=-->
                                    <!--begin::Status=-->
                                    <td>
                                        <div class="badge @if($vendor->approved) badge-light-success @else badge-light-warning @endif">
                                            @if($vendor->approved)
                                                Approved
                                            @else
                                                Pending
                                            @endif
                                        </div>
                                    </td>
                                    <!--end::Status=-->
                                    <!--begin::Actions=-->
                                    <td>
                                        <a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                        <!--begin::Menu-->
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="{{ route('staff.vendors.show', $vendor) }}" class="menu-link px-3">View</a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="{{ route('staff.vendors.edit', $vendor) }}" class="menu-link px-3">Edit</a>
                                            </div>
                                            <!--end::Menu item-->
                                            @if(!$vendor->approved)
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <form action="{{ route('staff.vendors.approve', $vendor) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="menu-link px-3 border-0 bg-transparent">Approve</button>
                                                </form>
                                            </div>
                                            <!--end::Menu item-->
                                            @endif
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a class="menu-link px-3 text-danger border-0 bg-transparent delete-vendor" href="javascript:void(0);" 
                                                    data-vendor-id="{{ $vendor->id }}" 
                                                    data-vendor-name="{{ $vendor->name }}">
                                                    Delete
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>
                                        <!--end::Menu-->
                                    </td>
                                    <!--end::Actions=-->
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="d-flex flex-column align-items-center justify-content-center py-10">
                                            <div class="symbol symbol-100px symbol-circle mb-6">
                                                <div class="symbol-label fs-3x fw-bold bg-light-primary text-primary">
                                                    <i class="ki-duotone ki-shop fs-1">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                    </i>
                                                </div>
                                            </div>
                                            <h3 class="text-gray-800 mb-2">No vendors found</h3>
                                            <p class="text-gray-500 fs-6">Get started by creating a new vendor.</p>
                                            <a href="{{ route('staff.vendors.create') }}" class="btn btn-primary mt-4">
                                                <i class="ki-duotone ki-plus fs-3"></i>
                                                Create Vendor
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->
                        
                        @if($vendors->hasPages())
                        <!--begin::Pagination-->
                        <div class="d-flex flex-stack flex-wrap pt-10">
                            <div class="fs-6 fw-semibold text-gray-700">Showing {{ $vendors->firstItem() ?? 0 }} to {{ $vendors->lastItem() ?? 0 }} of {{ $vendors->total() }} entries</div>
                            <ul class="pagination">
                                {{-- Previous Page Link --}}
                                @if ($vendors->onFirstPage())
                                    <li class="page-item previous disabled">
                                        <a href="#" class="page-link">
                                            <i class="previous"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item previous">
                                        <a href="{{ $vendors->previousPageUrl() }}" class="page-link">
                                            <i class="previous"></i>
                                        </a>
                                    </li>
                                @endif
            
                                {{-- Pagination Elements --}}
                                @foreach ($vendors->links()->elements[0] as $page => $url)
                                    @if ($page == $vendors->currentPage())
                                        <li class="page-item active">
                                            <a href="#" class="page-link">{{ $page }}</a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach
            
                                {{-- Next Page Link --}}
                                @if ($vendors->hasMorePages())
                                    <li class="page-item next">
                                        <a href="{{ $vendors->nextPageUrl() }}" class="page-link">
                                            <i class="next"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item next disabled">
                                        <a href="#" class="page-link">
                                            <i class="next"></i>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <!--end::Pagination-->
                        @endif
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get DOM elements
            const searchInput = document.getElementById('searchInput');
            const filterButton = document.getElementById('filterButton');
            const filterMenu = document.getElementById('filterMenu');
            const resetFiltersBtn = document.getElementById('resetFilters');
            const statusFilters = document.querySelectorAll('.status-filter');
            const vendorRows = document.querySelectorAll('#kt_customers_table tbody tr');
            const selectAllCheckbox = document.getElementById('selectAll');
            const vendorCheckboxes = document.querySelectorAll('.vendor-checkbox');
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            let vendorToDelete = null;

            // Toggle filter menu visibility
            filterButton.addEventListener('click', function() {
                filterMenu.classList.toggle('show');
            });

            // Close filter menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!filterButton.contains(event.target) && !filterMenu.contains(event.target)) {
                    filterMenu.classList.remove('show');
                }
            });

            // Close filter menu when clicking on reset or apply button
            resetFiltersBtn.addEventListener('click', function() {
                statusFilters.forEach(filter => {
                    filter.checked = false;
                });
                filterMenu.classList.remove('show');
                applyFilters();
            });

            // Add event listeners to delete action links
            document.querySelectorAll('.delete-vendor').forEach(button => {
                button.addEventListener('click', function() {
                    const vendorId = this.getAttribute('data-vendor-id');
                    const vendorName = this.getAttribute('data-vendor-name');
                    
                    document.getElementById('vendorNameToDelete').textContent = vendorName;
                    vendorToDelete = { id: vendorId, element: this };
                    
                    deleteModal.show();
                });
            });

            // Handle delete confirmation
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                if (vendorToDelete) {
                    // Create and submit a form to delete the vendor
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/staff/vendors/${vendorToDelete.id}`;
                    form.style.display = 'none';

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';

                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });

            // Function to apply search filter
            function applySearchFilter() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                
                vendorRows.forEach(row => {
                    const searchData = row.getAttribute('data-search');
                    const isVisible = searchData.includes(searchTerm);
                    row.style.display = isVisible ? '' : 'none';
                });
            }

            // Function to apply status filter
            function applyStatusFilter() {
                const approvedChecked = document.getElementById('ktFilterApproved').checked;
                const pendingChecked = document.getElementById('ktFilterPending').checked;
                
                // If no filters selected, show all rows
                if (!approvedChecked && !pendingChecked) {
                    vendorRows.forEach(row => {
                        if (row.style.display !== 'none') { // Only consider rows not hidden by search
                            row.style.display = '';
                        }
                    });
                    return;
                }
                
                vendorRows.forEach(row => {
                    if (row.style.display === 'none') return; // Skip rows hidden by search filter
                    
                    const status = row.getAttribute('data-status');
                    const showApproved = approvedChecked && status === 'approved';
                    const showPending = pendingChecked && status === 'pending';
                    
                    if (showApproved || showPending) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Function to apply all filters
            function applyFilters() {
                // First apply search filter
                applySearchFilter();
                // Then apply status filter
                applyStatusFilter();
            }

            // Event listeners for search
            searchInput.addEventListener('input', applyFilters);

            // Event listeners for status filters
            statusFilters.forEach(filter => {
                filter.addEventListener('change', applyStatusFilter);
            });

            // Select all checkbox functionality
            selectAllCheckbox.addEventListener('change', function() {
                vendorCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });

            // Individual checkbox functionality
            vendorCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    // Check if all checkboxes are selected
                    const allChecked = Array.from(vendorCheckboxes).every(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                });
            });
        });
    </script>
@endsection