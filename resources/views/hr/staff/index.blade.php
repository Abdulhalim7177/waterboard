@extends('layouts.staff')

@section('content')
    <!--begin::Container-->
    <div class="container-xxl">
        <!--begin::Staff Management Navigation-->
        @include('staff.partials.navigation')
        <!--end::Staff Management Navigation-->
        
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

        @if (session('newStaff') || session('existingStaff'))
            <div class="alert alert-custom alert-light-primary fade show mb-5" role="alert">
                <div class="alert-icon">
                    <i class="flaticon-information"></i>
                </div>
                <div class="alert-text">
                    <h4 class="alert-heading">Staff Insight</h4>
                    <p>{{ session('info') }}</p>
                    <div class="d-flex justify-content-end">
                        <form action="{{ route('staff.hr.staff.sync') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">Sync</button>
                        </form>
                    </div>
                    <hr>
                    <h5>New Staff</h5>
                    @if (count(session('newStaff')) > 0)
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (session('newStaff') as $staff)
                                    <tr>
                                        <td>{{ $staff['employee_id'] }}</td>
                                        <td>{{ $staff['first_name'] }} {{ $staff['surname'] }}</td>
                                        <td>{{ $staff['email'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No new staff found.</p>
                    @endif

                    <h5 class="mt-4">Existing Staff</h5>
                    @if (count(session('existingStaff')) > 0)
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (session('existingStaff') as $staff)
                                    <tr>
                                        <td>{{ $staff['employee_id'] }}</td>
                                        <td>{{ $staff['first_name'] }} {{ $staff['surname'] }}</td>
                                        <td>{{ $staff['email'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No existing staff found.</p>
                    @endif
                </div>
                <div class="alert-close">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true"><i class="ki ki-close"></i></span>
                    </button>
                </div>
            </div>
        @endif
        <!--end::Alerts-->

        <!--begin::Stats Widget-->
        @include('hr.staff.partials.stats_widget')
        <!--end::Stats Widget-->

        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <!--begin::Card title-->
                <div class="card-title w-100 d-flex align-items-center justify-content-between flex-wrap">
                    <!--begin::Search and Filters Form-->
                    <form id="staff_filter_form" method="GET" class="d-flex align-items-center gap-3 flex-wrap">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <input type="text" name="search" id="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search Staff" value="{{ request('search') }}" />
                        </div>
                        <!--end::Search-->
                        <!--begin::Filters-->
                        <div class="w-150px">
                            <select name="status" id="status" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Status">
                                <option value="">All</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="on_leave" {{ request('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                            </select>
                        </div>
                        <div class="w-200px">
                            <select name="department" id="department" data-control="select2" class="form-select form-select-solid" data-placeholder="All Departments">
                                <option value="">All Departments</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department }}" {{ request('department') == $department ? 'selected' : '' }}>{{ $department }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--end::Filters-->
                        <!--begin::Filter Actions-->
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('staff.hr.staff.index') }}" class="btn btn-light">Reset Filters</a>
                        <!--end::Filter Actions-->
                    </form>
                    <!--end::Search and Filters Form-->

                    <!--begin::Toolbar-->
                    <div class="d-flex flex-column flex-md-row align-items-md-center gap-2 flex-shrink-0 ms-auto w-100 w-md-auto">
                        <!--begin::Import-->
                        <button type="button" class="btn btn-light-primary w-100 w-md-auto" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="ki-duotone ki-entrance-left fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Import Staff
                        </button>
                        <!--end::Import-->

                        <!--begin::Refresh Button-->
                        <a href="{{ route('staff.hr.staff.index', ['insight' => 'true']) }}" class="btn btn-light-primary w-100 w-md-auto" id="refreshStaffBtn">
                            <i class="ki-duotone ki-refresh fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Refresh
                        </a>
                        <!--end::Refresh Button-->
                                                
                                                                        <!--begin::Export Dropdown-->                        <div class="dropdown w-100 w-md-auto">
                            <button class="btn btn-light-primary dropdown-toggle w-100" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ki-duotone ki-exit-up fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Export
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                                <li><a class="dropdown-item" href="{{ route('staff.hr.staff.export.excel') }}">Export Excel</a></li>
                                <li><a class="dropdown-item" href="{{ route('staff.hr.staff.export.pdf') }}">Export PDF</a></li>
                            </ul>
                        </div>
                        <!--end::Export Dropdown-->
                        

                    </div>
                    <!--end::Toolbar-->
                </div>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Applied Filters-->
                @if (request()->hasAny(['search', 'department', 'status']))
                    <div class="alert alert-info mb-5">
                        <strong>Applied Filters:</strong>
                        @if (request('search')) Search: {{ request('search') }} @endif
                        @if (request('status')) | Status: {{ ucfirst(str_replace('_', ' ', request('status'))) }} @endif
                        @if (request('department')) | Department: {{ request('department') }} @endif
                    </div>
                @endif
                <!--end::Applied Filters-->
                
                <!--begin::Table-->
                <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_staff_table">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_staff_table .form-check-input" value="1" />
                                </div>
                            </th>
                            <th class="min-w-125px">Staff ID</th>
                            <th class="min-w-125px">Name</th>
                            <th class="min-w-125px">Email</th>
                            <th class="min-w-125px">Department</th>
                            <th class="min-w-125px">Rank</th>
                            <th class="min-w-125px">Cadre</th>
                            <th class="min-w-125px">Grade Level</th>
                            <th class="min-w-125px">Employment Status</th>
                            <th class="text-end min-w-100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @forelse ($staffs as $staff)
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="{{ $staff->id }}" />
                                    </div>
                                </td>
                                <td>{{ $staff->staff_id }}</td>
                                <td>
                                    <a href="{{ route('staff.hr.staff.show', $staff) }}" class="text-gray-800 text-hover-primary mb-1">
                                        {{ $staff ? trim($staff->first_name . ' ' . ($staff->middle_name ?? '') . ' ' . ($staff->surname ?? '')) : 'N/A' }}
                                    </a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-600 text-hover-primary mb-1">{{ $staff->email }}</a>
                                </td>
                                <td>{{ $staff->department->name ?? 'N/A' }}</td>
                                <td>{{ $staff->rank->name ?? 'N/A' }}</td>
                                <td>{{ $staff->cadre->name ?? 'N/A' }}</td>
                                <td>{{ $staff->gradeLevel->name ?? 'N/A' }}</td>
                                <td>
                                    <div class="badge badge-light-{{ $staff->employment_status == 'active' ? 'success' : ($staff->employment_status == 'on_leave' ? 'warning' : 'danger') }}">
                                        {{ ucfirst(str_replace('_', ' ', $staff->employment_status)) }}
                                    </div>
                                </td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        Actions
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                    </a>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="{{ route('staff.hr.staff.show', $staff) }}" class="menu-link px-3">View</a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="{{ route('staff.hr.staff.edit', $staff) }}" class="menu-link px-3">Edit</a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#removeModal{{ $staff->id }}">Remove</a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $staff->id }}">Delete</a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
                                </td>
                            </tr>
                            <!--begin::Delete Modal-->
                            <div class="modal fade" id="deleteModal{{ $staff->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $staff->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $staff->id }}">Confirm Deletion</h5>
                                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                <i class="ki-duotone ki-cross fs-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete {{ $staff ? trim($staff->first_name . ' ' . ($staff->middle_name ?? '') . ' ' . ($staff->surname ?? '')) : 'this staff member' }} ({{ $staff->staff_id ?? 'N/A' }})? This action cannot be undone.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('staff.hr.staff.destroy', $staff) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Delete Modal-->
                            
                            <!--begin::Remove Modal-->
                            <div class="modal fade" id="removeModal{{ $staff->id }}" tabindex="-1" aria-labelledby="removeModalLabel{{ $staff->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="removeModalLabel{{ $staff->id }}">Confirm Removal from HRM</h5>
                                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                <i class="ki-duotone ki-cross fs-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <p class="text-warning">
                                                <i class="ki-duotone ki-information-5 fs-1 me-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                                <strong>Warning:</strong> This will remove the staff member from the HRM system and trigger a full sync.
                                            </p>
                                            <p>
                                                Are you sure you want to remove {{ $staff ? trim($staff->first_name . ' ' . ($staff->middle_name ?? '') . ' ' . ($staff->surname ?? '')) : 'this staff member' }} ({{ $staff->staff_id ?? 'N/A' }}) from the HRM system? This action will trigger a full data refresh.
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('staff.hr.staff.remove', $staff) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Remove from HRM</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Remove Modal-->
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No staff found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <!--end::Table-->
                <div class="mt-3">
                    {{ $staffs->appends([
                        'search' => request('search'),
                        'department' => request('department'),
                        'status' => request('status')
                    ])->links('pagination::bootstrap-5') }}
                </div>
            </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->

        <!--begin::Import Modal-->
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Staff</h5>
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="ki-duotone ki-cross fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                    </div>
                    <form id="import_staff_form" action="{{ route('staff.hr.staff.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="import_file" class="form-label">Upload Excel File</label>
                                <input type="file" name="file" id="import_file" class="form-control" accept=".xlsx,.xls,.csv" required>
                                <small class="form-text text-muted">
                                    File must include headers: staff_id, first_name, surname, email, mobile_no, date_of_birth, date_of_first_appointment, gender, middle_name, state_of_origin, lga, ward, nationality, nin, address, rank, staff_no, department, expected_next_promotion, expected_retirement_date, status, highest_qualifications, grade_level_limit, appointment_type, years_of_service.
                                    <a href="{{ route('staff.hr.staff.template') }}">Download template file</a>.
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
        <!--end::Import Modal-->
        

    </div>
    <!--end::Container-->
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2 for searchable dropdowns
            $('select[data-control="select2"]').select2({
                minimumResultsForSearch: 10,
                placeholder: function() {
                    return $(this).data('placeholder') || $(this).find('option:first').text();
                }
            });

            // Submit form on dropdown change
            document.getElementById('status').addEventListener('change', function() {
                document.getElementById('staff_filter_form').submit();
            });
            document.getElementById('department').addEventListener('change', function() {
                document.getElementById('staff_filter_form').submit();
            });

            // Submit form on search input (with debounce)
            let searchTimeout;
            document.getElementById('search').addEventListener('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    document.getElementById('staff_filter_form').submit();
                }, 500);
            });

            // Handle import form submission
            document.getElementById('import_staff_form').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                const button = form.querySelector('.import-btn');
                const indicatorLabel = button.querySelector('.indicator-label');
                const indicatorProgress = button.querySelector('.indicator-progress');

                // Set loading state
                button.disabled = true;
                indicatorLabel.style.display = 'none';
                indicatorProgress.style.display = 'inline-block';

                // Submit form via fetch
                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    button.disabled = false;
                    indicatorLabel.style.display = 'inline-block';
                    indicatorProgress.style.display = 'none';
                    const importModal = bootstrap.Modal.getInstance(document.getElementById('importModal'));
                    importModal.hide();
                    showAlert(data.message, 'success');
                })
                .catch(error => {
                    button.disabled = false;
                    indicatorLabel.style.display = 'inline-block';
                    indicatorProgress.style.display = 'none';
                    let errorMessage = 'An error occurred while importing staff.';
                    if (error.responseJSON && error.responseJSON.error) {
                        errorMessage = error.responseJSON.error;
                    }
                    showAlert(errorMessage, 'danger');
                });
            });

            // Handle export dropdown forms
            document.querySelectorAll('.export-btn').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    form.submit();
                });
            });
            

            
            // Function to show alert
            function showAlert(message, type) {
                // Remove existing alerts
                const existingAlerts = document.querySelectorAll('.alert');
                existingAlerts.forEach(alert => alert.remove());
                
                // Create alert element based on type
                let alertClass = 'alert-';
                switch(type) {
                    case 'success':
                        alertClass += 'success';
                        break;
                    case 'error':
                        alertClass += 'danger';
                        break;
                    default:
                        alertClass += 'info';
                }
                
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert ${alertClass} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x`;
                alertDiv.style.cssText = 'z-index: 1050; margin-top: 60px; max-width: 500px;';
                alertDiv.innerHTML = `
                    <i class="ki-duotone ki-check-circle me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                
                // Add alert to page
                document.body.appendChild(alertDiv);
                
                // Add close functionality
                const closeBtn = alertDiv.querySelector('.btn-close');
                closeBtn.addEventListener('click', function() {
                    alertDiv.remove();
                });
                
                // Auto dismiss after 5 seconds
                setTimeout(function() {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 5000);
            }
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