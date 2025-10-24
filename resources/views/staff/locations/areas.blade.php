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

        <!-- Areas Table -->
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <!-- Search and filters - responsive layout -->
                    <div class="d-flex flex-column flex-md-row align-items-md-center position-relative my-1">
                        <div class="d-flex align-items-center position-relative mb-3 mb-md-0">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <input type="text" id="search_area" class="form-control form-control-solid w-100px w-md-250px ps-13" placeholder="Search Areas" value="{{ request('search_area') }}" />
                        </div>
                        <div class="d-flex flex-column flex-md-row mt-3 mt-md-0">
                            <div class="w-100 w-md-150px me-0 me-md-3 mb-3 mb-md-0 ms-0 ms-md-3">
                                <select id="lga_filter" class="form-select form-select-solid">
                                    <option value="">All LGAs</option>
                                    @foreach (App\Models\Lga::where('status', 'approved')->get() as $lga)
                                        <option value="{{ $lga->id }}" {{ request('lga_filter') == $lga->id ? 'selected' : '' }}>{{ $lga->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-100 w-md-150px me-0 me-md-3">
                                <select id="ward_filter" class="form-select form-select-solid">
                                    <option value="">All Wards</option>
                                    @foreach (App\Models\Ward::where('status', 'approved')->when(request('lga_filter'), function($query) { return $query->where('lga_id', request('lga_filter')); })->get() as $ward)
                                        <option value="{{ $ward->id }}" {{ request('ward_filter') == $ward->id ? 'selected' : '' }}>{{ $ward->name }} ({{ $ward->lga->name }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end" data-kt-area-table-toolbar="base">
                        @can('create-area')
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_area_create_modal">
                                <i class="ki-duotone ki-plus fs-2"></i>
                                <span class="d-none d-md-inline">Add Area</span>
                            </button>
                        @endcan
                    </div>
                    <div class="d-flex justify-content-end align-items-center d-none" data-kt-area-table-toolbar="selected">
                        <div class="fw-bold me-5">
                            <span class="me-2" data-kt-area-table-select="selected_count"></span>Selected
                        </div>
                        <button type="button" class="btn btn-danger" data-kt-area-table-select="delete_selected">
                            <i class="ki-duotone ki-trash fs-2"></i>
                            <span class="d-none d-md-inline">Delete Selected</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <!--begin::Summary Widgets-->
                <div class="row g-6 g-xl-9 mb-6 mb-xl-9">
                    <div class="col-sm-6 col-xl-4 mb-5 mb-xl-10">
                        <div class="card card-flush h-md-50 mb-xl-10">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <h6 class="text-gray-400 fw-semibold mb-1">Total Areas</h6>
                                    <div class="d-flex flex-column">
                                        <span class="fs-2hx fw-bold text-gray-800 lh-1 ls-n2">{{ $areas->total() }}</span>
                                    </div>
                                </div>
                                <div class="symbol symbol-60px">
                                    <div class="symbol-label bg-light-primary">
                                        <i class="ki-duotone ki-geolocation fs-1 text-primary">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-4 mb-5 mb-xl-10">
                        <div class="card card-flush h-md-50 mb-xl-10">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <h6 class="text-gray-400 fw-semibold mb-1">Total Staff</h6>
                                    <div class="d-flex flex-column">
                                        <span class="fs-2hx fw-bold text-gray-800 lh-1 ls-n2">{{ $areas->sum('staffs_count') }}</span>
                                    </div>
                                </div>
                                <div class="symbol symbol-60px">
                                    <div class="symbol-label bg-light-info">
                                        <i class="ki-duotone ki-people fs-1 text-info">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-4 mb-5 mb-xl-10">
                        <div class="card card-flush h-md-50 mb-xl-10">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <h6 class="text-gray-400 fw-semibold mb-1">Total Customers</h6>
                                    <div class="d-flex flex-column">
                                        <span class="fs-2hx fw-bold text-gray-800 lh-1 ls-n2">{{ $areas->sum('customers_count') }}</span>
                                    </div>
                                </div>
                                <div class="symbol symbol-60px">
                                    <div class="symbol-label bg-light-success">
                                        <i class="ki-duotone ki-user fs-1 text-success">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Summary Widgets-->

                <!-- Desktop Table -->
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_area_table">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_area_table .form-check-input" value="1" />
                                    </div>
                                </th>
                                <th class="min-w-100px">Name</th>
                                <th class="min-w-100px">Ward</th>
                                <th class="min-w-100px">Status</th>
                                <th class="text-end min-w-75px">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @forelse ($areas as $area)
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" value="{{ $area->id }}" />
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 text-hover-primary mb-1">{{ $area->name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 text-hover-primary mb-1">{{ $area->ward ? $area->ward->name : '—' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="badge badge-light-{{ $area->status == 'approved' ? 'success' : ($area->status == 'pending' || $area->status == 'pending_delete' ? 'warning' : 'danger') }}">
                                            {{ ucfirst(str_replace('_', ' ', $area->status)) }}
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Actions
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                        </a>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_area_view_modal{{ $area->id }}">View</a>
                                            </div>
                                            @can('edit-area')
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_area_edit_modal{{ $area->id }}">Edit</a>
                                                </div>
                                            @endcan
                                            @can('delete-area')
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_area_delete_modal{{ $area->id }}">Delete</a>
                                                </div>
                                            @endcan
                                            @can('approve-area')
                                                @if ($area->status == 'pending' || $area->status == 'pending_delete')
                                                    <div class="menu-item px-3">
                                                        <form action="{{ route('staff.areas.approve', $area->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="menu-link px-3">Approve</button>
                                                        </form>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <form action="{{ route('staff.areas.reject', $area->id) }}" method="POST" class="d-inline">
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
                                <div class="modal fade" id="kt_area_view_modal{{ $area->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered mw-650px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h2 class="fw-bold">View Area</h2>
                                                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                    <i class="ki-duotone ki-cross fs-1">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </div>
                                            </div>
                                            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                Name: {{ $area->name }}<br>
                                                Ward: {{ $area->ward ? $area->ward->name : '—' }}<br>
                                                LGA: {{ ($area->ward && $area->ward->lga) ? $area->ward->lga->name : '—' }}<br>
                                                Status: {{ ucfirst(str_replace('_', ' ', $area->status)) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Edit Modal -->
                                @can('edit-area')
                                    <div class="modal fade" id="kt_area_edit_modal{{ $area->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="fw-bold">Edit Area</h2>
                                                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                        <i class="ki-duotone ki-cross fs-1">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </div>
                                                </div>
                                                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                    <form action="{{ route('staff.areas.update', $area->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="fv-row mb-10">
                                                            <label class="fs-5 fw-semibold form-label mb-5">Name</label>
                                                            <input type="text" name="name" value="{{ $area->name }}" class="form-control form-control-solid" required />
                                                        </div>
                                                        <div class="fv-row mb-10">
                                                            <label class="fs-5 fw-semibold form-label mb-5">Ward</label>
                                                            <select name="ward_id" class="form-select form-select-solid" required>
                                                                @foreach (App\Models\Ward::where('status', 'approved')->get() as $ward)
                                                                    <option value="{{ $ward->id }}" {{ $area->ward_id == $ward->id ? 'selected' : '' }}>{{ $ward->name }} ({{ $ward->lga->name }})</option>
                                                                @endforeach
                                                            </select>
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
                                @can('delete-area')
                                    <div class="modal fade" id="kt_area_delete_modal{{ $area->id }}" tabindex="-1" aria-hidden="true">
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
                                                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                    Are you sure you want to request deletion of {{ $area->name }}? This action will set the status to pending for admin approval.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('staff.areas.destroy', $area->id) }}" method="POST" class="d-inline">
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
                                    <td colspan="6" class="text-center">No Areas found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Mobile Cards -->
                <div class="d-md-none d-none">
                    @forelse ($areas as $area)
                        <div class="card mb-5 mb-xl-8 border border-gray-300">
                            <div class="card-header border-0 pt-5">
                                <div class="d-flex flex-stack flex-wrap gap-2 w-100">
                                    <div class="d-flex align-items-center">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox" value="{{ $area->id }}" />
                                        </div>
                                        <div class="d-flex flex-column">
                                            <a href="#" class="text-gray-800 text-hover-primary fs-5 fw-bold" data-bs-toggle="modal" data-bs-target="#kt_area_view_modal{{ $area->id }}">{{ $area->name }}</a>
                                            <span class="text-muted fs-7">Code: {{ $area->code }}</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="badge badge-light-{{ $area->status == 'approved' ? 'success' : ($area->status == 'pending' || $area->status == 'pending_delete' ? 'warning' : 'danger') }}">
                                            {{ ucfirst(str_replace('_', ' ', $area->status)) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-3 pb-5">
                                <div class="d-flex flex-stack flex-wrap gap-3">
                                    <div class="d-flex flex-column">
                                        <div class="text-muted fs-7">Ward</div>
                                        <div class="text-gray-800 fw-bold">{{ $area->ward ? $area->ward->name : '—' }}</div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <div class="text-muted fs-7">Actions</div>
                                        <div class="d-flex gap-2">
                                            <a href="#" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#kt_area_view_modal{{ $area->id }}">View</a>
                                            @can('edit-area')
                                                <a href="#" class="btn btn-sm btn-light-warning" data-bs-toggle="modal" data-bs-target="#kt_area_edit_modal{{ $area->id }}">Edit</a>
                                            @endcan
                                            @can('delete-area')
                                                <a href="#" class="btn btn-sm btn-light-danger" data-bs-toggle="modal" data-bs-target="#kt_area_delete_modal{{ $area->id }}">Delete</a>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <div class="text-muted fs-3">No Areas found.</div>
                        </div>
                    @endforelse
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        @if ($areas->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">Previous</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $areas->previousPageUrl() }}&search_area={{ request('search_area') }}&lga_filter={{ request('lga_filter') }}&ward_filter={{ request('ward_filter') }}">Previous</a>
                            </li>
                        @endif

                        @foreach ($areas->getUrlRange(1, $areas->lastPage()) as $page => $url)
                            <li class="page-item {{ $page == $areas->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}&search_area={{ request('search_area') }}&lga_filter={{ request('lga_filter') }}&ward_filter={{ request('ward_filter') }}">{{ $page }}</a>
                            </li>
                        @endforeach

                        @if ($areas->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $areas->nextPageUrl() }}&search_area={{ request('search_area') }}&lga_filter={{ request('lga_filter') }}&ward_filter={{ request('ward_filter') }}">Next</a>
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
        <div class="modal fade" id="kt_area_create_modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">Add Area</h2>
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="ki-duotone ki-cross fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                    </div>
                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                        <form action="{{ route('staff.areas.store') }}" method="POST">
                            @csrf
                            <div class="fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">Name</label>
                                <input type="text" name="name" class="form-control form-control-solid" required />
                            </div>
                            <div class="fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">Ward</label>
                                <select name="ward_id" class="form-select form-select-solid" required>
                                    @foreach (App\Models\Ward::where('status', 'approved')->get() as $ward)
                                        <option value="{{ $ward->id }}">{{ $ward->name }} ({{ $ward->lga->name }})</option>
                                    @endforeach
                                </select>
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
            const searchInput = document.getElementById('search_area');
            const lgaFilter = document.getElementById('lga_filter');
            const wardFilter = document.getElementById('ward_filter');
            let searchTimeout;

            // Update URL for search and filters
            function updateURL() {
                const search = searchInput.value;
                const lga = lgaFilter.value;
                const ward = wardFilter.value;
                const url = new URL('{{ route("staff.areas.index") }}');
                if (search) url.searchParams.set('search_area', search);
                if (lga) url.searchParams.set('lga_filter', lga);
                if (ward) url.searchParams.set('ward_filter', ward);
                window.location.href = url.toString();
            }

            // Debounced search input handler
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(updateURL, 500);
            });

            // Filter change handlers
            lgaFilter.addEventListener('change', updateURL);
            wardFilter.addEventListener('change', updateURL);

            // Handle checkbox selection
            const masterCheckbox = document.querySelector('#kt_area_table .form-check-input[data-kt-check="true"]');
            const checkboxes = document.querySelectorAll('#kt_area_table .form-check-input:not([data-kt-check="true"])');
            const toolbarBase = document.querySelector('[data-kt-area-table-toolbar="base"]');
            const toolbarSelected = document.querySelector('[data-kt-area-table-toolbar="selected"]');
            const selectedCount = document.querySelector('[data-kt-area-table-select="selected_count"]');

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

            // Dynamic ward filter based on LGA selection
            lgaFilter.addEventListener('change', function() {
                const lgaId = this.value;
                
                // If no LGA selected, show all wards
                if (!lgaId) {
                    wardFilter.innerHTML = '<option value="">All Wards</option>';
                    @foreach (App\Models\Ward::where('status', 'approved')->get() as $ward)
                        wardFilter.innerHTML += '<option value="{{ $ward->id }}">{{ $ward->name }} ({{ $ward->lga->name }})</option>';
                    @endforeach
                    return;
                }
                
                // Filter wards by selected LGA
                wardFilter.innerHTML = '<option value="">All Wards</option>';
                @foreach (App\Models\Ward::where('status', 'approved')->get() as $ward)
                    if ("{{ $ward->lga_id }}" === lgaId) {
                        wardFilter.innerHTML += '<option value="{{ $ward->id }}">{{ $ward->name }} ({{ $ward->lga->name }})</option>';
                    }
                @endforeach
            });

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