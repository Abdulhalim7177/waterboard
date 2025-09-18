@extends('layouts.staff')

@section('content')
    <!--begin::Container-->
    <div class="container-xxl">
        <!--begin::Alerts-->
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
        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <!--end::Alerts-->

        <!--begin::Stats Widget-->
        <div class="row g-5 mb-8">
            <div class="col-md-3">
                <div class="card card-flush h-md-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Customers</h5>
                        <div class="display-6 fw-bold text-primary">{{ $stats['total'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-flush h-md-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Pending</h5>
                        <div class="display-6 fw-bold text-warning">{{ $stats['pending'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-flush h-md-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Approved</h5>
                        <div class="display-6 fw-bold text-success">{{ $stats['approved'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-flush h-md-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Rejected</h5>
                        <div class="display-6 fw-bold text-danger">{{ $stats['rejected'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Stats Widget-->

        <!--begin::Applied Filters-->
        @if (request()->hasAny(['search_customer', 'status_filter', 'lga_filter', 'ward_filter', 'area_filter', 'category_filter', 'tariff_filter']))
            <div class="alert alert-info mb-5">
                <strong>Applied Filters:</strong>
                @if (request('search_customer')) Search: {{ request('search_customer') }} @endif
                @if (request('status_filter')) | Status: {{ ucfirst(request('status_filter')) }} @endif
                @if (request('lga_filter')) | LGA: {{ App\Models\Lga::find(request('lga_filter'))?->name ?? 'N/A' }} @endif
                @if (request('ward_filter')) | Ward: {{ App\Models\Ward::find(request('ward_filter'))?->name ?? 'N/A' }} @endif
                @if (request('area_filter')) | Area: {{ App\Models\Area::find(request('area_filter'))?->name ?? 'N/A' }} @endif
                @if (request('category_filter')) | Category: {{ App\Models\Category::find(request('category_filter'))?->name ?? 'N/A' }} @endif
                @if (request('tariff_filter')) | Tariff: {{ App\Models\Tariff::find(request('tariff_filter'))?->name ?? 'N/A' }} @endif
            </div>
        @endif
        <!--end::Applied Filters-->

        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <!--begin::Card title-->
                <div class="card-title w-100 d-flex align-items-center justify-content-between flex-wrap">
                    <!--begin::Search and Filters Form-->
                    <form id="customer_filter_form" method="GET" class="d-flex align-items-center gap-3 flex-wrap">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <input type="text" name="search_customer" id="search_customer" class="form-control form-control-solid w-250px ps-13" placeholder="Search Customers" value="{{ request('search_customer') }}" />
                        </div>
                        <!--end::Search-->
                        <!--begin::Filters-->
                        <div class="w-150px">
                            <select name="status_filter" id="status_filter" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Status">
                                <option value="">All</option>
                                <option value="pending" {{ request('status_filter') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status_filter') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status_filter') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="w-200px">
                            <select name="lga_filter" id="lga_filter" data-control="select2" class="form-select form-select-solid" data-placeholder="All LGAs">
                                <option value="">All LGAs</option>
                                @foreach (App\Models\Lga::where('status', 'approved')->get() as $lga)
                                    <option value="{{ $lga->id }}" {{ request('lga_filter') == $lga->id ? 'selected' : '' }}>{{ $lga->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-200px">
                            <select name="ward_filter" id="ward_filter" data-control="select2" class="form-select form-select-solid" data-placeholder="All Wards">
                                <option value="">All Wards</option>
                                @foreach (App\Models\Ward::where('status', 'approved')->when(request('lga_filter'), function($query) { return $query->where('lga_id', request('lga_filter')); })->get() as $ward)
                                    <option value="{{ $ward->id }}" {{ request('ward_filter') == $ward->id ? 'selected' : '' }}>{{ $ward->name }} ({{ $ward->lga->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-200px">
                            <select name="area_filter" id="area_filter" data-control="select2" class="form-select form-select-solid" data-placeholder="All Areas">
                                <option value="">All Areas</option>
                                @foreach (App\Models\Area::where('status', 'approved')->when(request('ward_filter'), function($query) { return $query->where('ward_id', request('ward_filter')); })->get() as $area)
                                    <option value="{{ $area->id }}" {{ request('area_filter') == $area->id ? 'selected' : '' }}>{{ $area->name }} ({{ $area->ward->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-200px">
                            <select name="category_filter" id="category_filter" data-control="select2" class="form-select form-select-solid" data-placeholder="All Categories">
                                <option value="">All Categories</option>
                                @foreach (App\Models\Category::where('status', 'approved')->get() as $category)
                                    <option value="{{ $category->id }}" {{ request('category_filter') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-200px">
                            <select name="tariff_filter" id="tariff_filter" data-control="select2" class="form-select form-select-solid" data-placeholder="All Tariffs">
                                <option value="">All Tariffs</option>
                                @foreach (App\Models\Tariff::where('status', 'approved')->when(request('category_filter'), function($query) { return $query->where('category_id', request('category_filter')); })->get() as $tariff)
                                    <option value="{{ $tariff->id }}" {{ request('tariff_filter') == $tariff->id ? 'selected' : '' }}>{{ $tariff->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--end::Filters-->
                        <!--begin::Filter Actions-->
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('staff.customers.index') }}" class="btn btn-light">Reset Filters</a>
                        <!--end::Filter Actions-->
                    </form>
                    <!--end::Search and Filters Form-->

                    <!--begin::Toolbar-->
                    <div class="d-flex flex-column flex-md-row align-items-md-center gap-2 flex-shrink-0 ms-auto w-100 w-md-auto">
                        <!--begin::Import-->
                        @can('create-customer', App\Models\Customer::class)
                            <button type="button" class="btn btn-light-primary w-100 w-md-auto" data-bs-toggle="modal" data-bs-target="#importModal">
                                <i class="ki-duotone ki-entrance-left fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Import Customers
                            </button>
                        @endcan
                        <!--end::Import-->
                        
                        <!--begin::Export Dropdown-->
                        @can('view-customers', App\Models\Customer::class)
                            <div class="dropdown w-100 w-md-auto">
                                <button class="btn btn-light-primary dropdown-toggle w-100" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ki-duotone ki-exit-up fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Export
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                                    <li>
                                        <form id="export_csv_form" action="{{ route('staff.customers.export') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="format" value="csv">
                                            <input type="hidden" name="status" value="{{ request('status_filter') }}">
                                            <input type="hidden" name="search_customer" value="{{ request('search_customer') }}">
                                            <input type="hidden" name="lga_filter" value="{{ request('lga_filter') }}">
                                            <input type="hidden" name="ward_filter" value="{{ request('ward_filter') }}">
                                            <input type="hidden" name="area_filter" value="{{ request('area_filter') }}">
                                            <input type="hidden" name="category_filter" value="{{ request('category_filter') }}">
                                            <input type="hidden" name="tariff_filter" value="{{ request('tariff_filter') }}">
                                            <button type="submit" class="dropdown-item export-btn" data-format="csv">Export CSV</button>
                                        </form>
                                    </li>
                                    <li>
                                        <form id="export_excel_form" action="{{ route('staff.customers.export') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="format" value="xlsx">
                                            <input type="hidden" name="status" value="{{ request('status_filter') }}">
                                            <input type="hidden" name="search_customer" value="{{ request('search_customer') }}">
                                            <input type="hidden" name="lga_filter" value="{{ request('lga_filter') }}">
                                            <input type="hidden" name="ward_filter" value="{{ request('ward_filter') }}">
                                            <input type="hidden" name="area_filter" value="{{ request('area_filter') }}">
                                            <input type="hidden" name="category_filter" value="{{ request('category_filter') }}">
                                            <input type="hidden" name="tariff_filter" value="{{ request('tariff_filter') }}">
                                            <button type="submit" class="dropdown-item export-btn" data-format="xlsx">Export Excel</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endcan
                        <!--end::Export Dropdown-->
                        
                        <!--begin::Add customer-->
                        @can('create-customer', App\Models\Customer::class)
                            <a href="{{ route('staff.customers.create.personal') }}" class="btn btn-primary w-100 w-md-auto">Add Customer</a>
                        @endcan
                        <!--end::Add customer-->
                        
                        <!--begin::View pending-->
                        @can('approve-customer', App\Models\Customer::class)
                            <a href="{{ route('staff.customers.pending') }}" class="btn btn-primary ms-0 ms-md-2 w-100 w-md-auto mt-2 mt-md-0">View Pending Changes</a>
                        @endcan
                        <!--end::View pending-->
                    </div>
                    <!--end::Toolbar-->
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Group actions-->
                    <div class="d-flex justify-content-end align-items-center d-none" data-kt-customer-table-toolbar="selected">
                        <div class="fw-bold me-5">
                            <span class="me-2" data-kt-customer-table-select="selected_count"></span>Selected
                        </div>
                        <button type="button" class="btn btn-danger" data-kt-customer-table-select="delete_selected">Delete Selected</button>
                    </div>
                    <!--end::Group actions-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Table-->
                <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_customers_table .form-check-input" value="1" />
                                </div>
                            </th>
                            <th class="min-w-125px">Billing ID</th>
                            <th class="min-w-125px">Name</th>
                            <th class="min-w-125px">Email</th>
                            <th class="min-w-125px">Area</th>
                            <th class="min-w-125px">Status</th>
                            <th class="min-w-125px">Created Date</th>
                            <th class="text-end min-w-100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @forelse ($customers as $customer)
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="{{ $customer->id }}" />
                                    </div>
                                </td>
                                <td>{{ $customer->billing_id ?? 'Pending' }}</td>
                                <td>
                                    <a href="{{ route('staff.customers.show', $customer) }}" class="text-gray-800 text-hover-primary mb-1">
                                        {{ $customer->first_name }} {{ $customer->surname }}
                                    </a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-600 text-hover-primary mb-1">{{ $customer->email }}</a>
                                </td>
                                <td>{{ $customer->area->name ?? 'N/A' }}</td>
                                <td>
                                    <div class="badge badge-light-{{ $customer->status == 'approved' ? 'success' : ($customer->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($customer->status) }}
                                    </div>
                                </td>
                                <td>{{ $customer->created_at->format('d M Y, h:i A') }}</td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        Actions
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                    </a>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="{{ route('staff.customers.show', $customer) }}" class="menu-link px-3">View</a>
                                        </div>
                                        <!--end::Menu item-->
                                        @can('edit-customer', $customer)
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="{{ route('staff.customers.edit', $customer) }}" class="menu-link px-3">Edit</a>
                                            </div>
                                            <!--end::Menu item-->
                                        @endcan
                                        @can('approve-customer', App\Models\Customer::class)
                                            @if ($customer->status == 'pending')
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <form action="{{ route('staff.customers.approve', $customer) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="menu-link px-3">Approve</button>
                                                    </form>
                                                </div>
                                                <!--end::Menu item-->
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <form action="{{ route('staff.customers.reject', $customer) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="menu-link px-3">Reject</button>
                                                    </form>
                                                </div>
                                                <!--end::Menu item-->
                                            @endif
                                        @endcan
                                        @can('delete-customer', $customer)
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $customer->id }}">Delete</a>
                                            </div>
                                            <!--end::Menu item-->
                                        @endcan
                                    </div>
                                    <!--end::Menu-->
                                </td>
                            </tr>
                            <!--begin::Delete Modal-->
                            <div class="modal fade" id="deleteModal{{ $customer->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $customer->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $customer->id }}">Confirm Deletion</h5>
                                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                <i class="ki-duotone ki-cross fs-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete {{ $customer->first_name }} {{ $customer->surname }} ({{ $customer->billing_id ?? 'Pending' }})? This action cannot be undone.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('staff.customers.destroy', $customer) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Delete Modal-->
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No customers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <!--end::Table-->
                <div class="mt-3">
                    {{ $customers->appends([
                        'search_customer' => request('search_customer'),
                        'lga_filter' => request('lga_filter'),
                        'ward_filter' => request('ward_filter'),
                        'area_filter' => request('area_filter'),
                        'category_filter' => request('category_filter'),
                        'tariff_filter' => request('tariff_filter'),
                        'status_filter' => request('status_filter')
                    ])->links('pagination::bootstrap-5') }}
                </div>
            </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->

        <!--begin::Import Modal-->
        @can('create-customer', App\Models\Customer::class)
            <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="importModalLabel">Import Customers</h5>
                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                <i class="ki-duotone ki-cross fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <form id="import_customers_form" action="{{ route('staff.customers.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="import_file" class="form-label">Upload CSV or Excel File</label>
                                    <input type="file" name="file" id="import_file" class="form-control" accept=".csv,.xlsx" required>
                                    <small class="form-text text-muted">
                                        File must include headers: First Name, Surname, Middle Name, Email, Phone Number, Alternate Phone Number, Street Name, House Number, Landmark, LGA, Ward, Area, Category, Tariff, Delivery Code, Billing Condition, Water Supply Status, Latitude, Longitude, Altitude, Pipe Path, Polygon Coordinates, Password, Account Balance, Created At.
                                        <a href="{{ route('staff.customers.sample') }}">Download sample file</a>.
                                    </small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary import-btn">
                                    <span class="indicator-label">Import</span>
                                    <span class="indicator-progress">Processing...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan
        <!--end::Import Modal-->
    </div>
    <!--end::Container-->
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Select2 for searchable dropdowns
            $('select[data-control="select2"]').select2({
                minimumResultsForSearch: 10,
                placeholder: function() {
                    return $(this).data('placeholder') || $(this).find('option:first').text();
                }
            });

            // Submit form on dropdown change
            $('#lga_filter, #ward_filter, #area_filter, #category_filter, #tariff_filter, #status_filter').on('change', function() {
                $('#customer_filter_form').submit();
            });

            // Submit form on search input (with debounce)
            let searchTimeout;
            $('#search_customer').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    $('#customer_filter_form').submit();
                }, 500);
            });

            // Handle export button clicks
            $('.export-btn').on('click', function(e) {
                e.preventDefault();
                const $button = $(this);
                const format = $button.data('format');
                const $form = format === 'csv' ? $('#export_csv_form') : $('#export_excel_form');
                const originalText = $button.html();

                // Set loading state
                $button.prop('disabled', true).html(
                    '<span class="indicator-progress">Please wait... ' +
                    '<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>'
                );

                // Submit form via AJAX
                $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: $form.serialize(),
                    xhrFields: {
                        responseType: 'blob' // Handle binary response for file download
                    },
                    success: function(data, status, xhr) {
                        const disposition = xhr.getResponseHeader('Content-Disposition');
                        let filename = 'customers_export.' + format;
                        if (disposition && disposition.indexOf('attachment') !== -1) {
                            const matches = /filename="([^"]*)"/.exec(disposition);
                            if (matches != null && matches[1]) filename = matches[1];
                        }
                        const url = window.URL.createObjectURL(new Blob([data]));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', filename);
                        document.body.appendChild(link);
                        link.click();
                        link.remove();
                        window.URL.revokeObjectURL(url);
                        $button.prop('disabled', false).html(originalText);
                    },
                    error: function(xhr) {
                        $button.prop('disabled', false).html(originalText);
                        let errorMessage = 'An error occurred while exporting customers.';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        }
                        // Display error alert
                        const alert = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                            errorMessage +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        $('#kt_content_container').prepend(alert);
                    }
                });
            });

            // Handle import form submission
            $('#import_customers_form').on('submit', function(e) {
                e.preventDefault();
                const $form = $(this);
                const $button = $form.find('.import-btn');
                const originalText = $button.find('.indicator-label').html();

                // Set loading state
                $button.prop('disabled', true).find('.indicator-label').hide();
                $button.find('.indicator-progress').show();

                // Submit form via AJAX
                $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: new FormData($form[0]),
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $button.prop('disabled', false).find('.indicator-label').show();
                        $button.find('.indicator-progress').hide();
                        $('#importModal').modal('hide');
                        const alert = $('<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                            response.message +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        $('#kt_content_container').prepend(alert);
                    },
                    error: function(xhr) {
                        $button.prop('disabled', false).find('.indicator-label').show();
                        $button.find('.indicator-progress').hide();
                        let errorMessage = 'An error occurred while importing customers.';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        }
                        const alert = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                            errorMessage +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        $('#kt_content_container').prepend(alert);
                    }
                });
            });

            // Handle export dropdown forms
            $('.export-btn').on('click', function(e) {
                e.preventDefault();
                const $form = $(this).closest('form');
                const format = $(this).data('format');
                
                // Submit form
                $form.submit();
            });
        });
    </script>
@endsection

@section('styles')
    <style>
        @media (max-width: 767.98px) {
            .dropdown-menu {
                min-width: 100%;
            }
            
            .w-100.w-md-auto {
                width: 100% !important;
            }
            
            .ms-0.ms-md-2.mt-2.mt-md-0 {
                margin-left: 0 !important;
                margin-top: 0.5rem !important;
            }
        }
    </style>
@endsection