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
        <!--end::Alerts-->

        <!--begin::Stats Widget-->
        @if(isset($stats))
        <div class="row g-5 mb-8">
            <div class="col-md-3">
                <div class="card card-flush h-md-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Staff</h5>
                        <div class="display-6 fw-bold text-primary">{{ $stats['total'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-flush h-md-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Active</h5>
                        <div class="display-6 fw-bold text-success">{{ $stats['active'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-flush h-md-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">On Leave</h5>
                        <div class="display-6 fw-bold text-warning">{{ $stats['on_leave'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-flush h-md-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Pending</h5>
                        <div class="display-6 fw-bold text-info">{{ $stats['pending'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!--end::Stats Widget-->

        <!--begin::Applied Filters-->
        @if (request()->hasAny(['search_staff', 'status_filter']))
            <div class="alert alert-info mb-5">
                <strong>Applied Filters:</strong>
                @if (request('search_staff')) Search: {{ request('search_staff') }} @endif
                @if (request('status_filter')) | Status: {{ ucfirst(request('status_filter')) }} @endif
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
                    <form id="staff_filter_form" method="GET" class="d-flex align-items-center gap-3 flex-wrap">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <input type="text" name="search_staff" id="search_staff" class="form-control form-control-solid w-250px ps-13" placeholder="Search Staff" value="{{ request('search_staff') }}" />
                        </div>
                        <!--end::Search-->
                        <!--begin::Filters-->
                        <div class="w-150px">
                            <select name="status_filter" id="status_filter" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Status">
                                <option value="">All</option>
                                <option value="approved" {{ request('status_filter') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="pending" {{ request('status_filter') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="rejected" {{ request('status_filter') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <!--end::Filters-->
                        <!--begin::Filter Actions-->
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('staff.staff.roles') }}" class="btn btn-light">Reset Filters</a>
                        <!--end::Filter Actions-->
                    </form>
                    <!--end::Search and Filters Form-->

                    <!--begin::Toolbar-->
                    <div class="d-flex align-items-center gap-2 flex-shrink-0 ms-auto">
                        <!--begin::View pending-->
                        @can('approve-staff', App\Models\Staff::class)
                            <a href="{{ route('staff.staff.pending') }}" class="btn btn-primary">View Pending Changes</a>
                        @endcan
                        <!--end::View pending-->
                    </div>
                    <!--end::Toolbar-->
                </div>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
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
                            <th class="min-w-125px">Name</th>
                            <th class="min-w-125px">Email</th>
                            <th class="min-w-125px">Roles</th>
                            <th class="min-w-125px">Status</th>
                            <th class="text-end min-w-100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @forelse ($staff as $member)
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="{{ $member->id }}" />
                                    </div>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-800 text-hover-primary mb-1" data-bs-toggle="modal" data-bs-target="#kt_staff_view_modal{{ $member->id }}">
                                        {{ $member ? trim($member->first_name . ' ' . ($member->middle_name ?? '') . ' ' . ($member->surname ?? '')) : 'N/A' }}
                                    </a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-600 text-hover-primary mb-1">{{ $member->email }}</a>
                                </td>
                                <td>{{ $member->getRoleNames()->join(', ') }}</td>
                                <td>
                                    <div class="badge badge-light-{{ $member->status == 'approved' ? 'success' : ($member->status == 'pending' || $member->status == 'pending_delete' ? 'warning' : 'danger') }}">
                                        {{ ucfirst(str_replace('_', ' ', $member->status)) }}
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
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_staff_view_modal{{ $member->id }}">View</a>
                                        </div>
                                        <!--end::Menu item-->
                                        @if (auth('staff')->user()->hasAnyRole(['super-admin', 'manager']))
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_staff_assign_roles_modal{{ $member->id }}">Assign Roles</a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_staff_remove_roles_modal{{ $member->id }}">Remove Roles</a>
                                            </div>
                                            <!--end::Menu item-->
                                        @endif
                                    </div>
                                    <!--end::Menu-->
                                </td>
                            </tr>
                            <!--begin::View Modal-->
                            <div class="modal fade" id="kt_staff_view_modal{{ $member->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $member->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewModalLabel{{ $member->id }}">View Staff</h5>
                                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                <i class="ki-duotone ki-cross fs-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <div class="d-flex flex-column gap-4">
                                                <div class="d-flex align-items-center">
                                                    <span class="text-muted fs-7 fw-bold w-150px">Full Name</span>
                                                    <span class="text-dark fs-6">{{ $member ? trim($member->first_name . ' ' . ($member->middle_name ?? '') . ' ' . ($member->surname ?? '')) : 'N/A' }}</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <span class="text-muted fs-7 fw-bold w-150px">Email</span>
                                                    <span class="text-dark fs-6">{{ $member->email }}</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <span class="text-muted fs-7 fw-bold w-150px">Roles</span>
                                                    <span class="text-dark fs-6">{{ $member->getRoleNames()->join(', ') }}</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <span class="text-muted fs-7 fw-bold w-150px">Status</span>
                                                    <span class="badge badge-light-{{ $member->status == 'approved' ? 'success' : ($member->status == 'pending' || $member->status == 'pending_delete' ? 'warning' : 'danger') }}">{{ ucfirst(str_replace('_', ' ', $member->status)) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::View Modal-->
                            <!--begin::Assign Roles Modal-->
                            @if (auth('staff')->user()->hasAnyRole(['super-admin', 'manager']))
                                <div class="modal fade" id="kt_staff_assign_roles_modal{{ $member->id }}" tabindex="-1" aria-labelledby="assignRolesModalLabel{{ $member->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered mw-650px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h2 class="modal-title fw-bold" id="assignRolesModalLabel{{ $member->id }}">Assign Roles to {{ $member ? trim($member->first_name . ' ' . ($member->middle_name ?? '') . ' ' . ($member->surname ?? '')) : 'N/A' }}</h2>
                                                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                    <i class="ki-duotone ki-cross fs-1">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </div>
                                            </div>
                                            <form action="{{ route('staff.staff.assign-roles', $member->id) }}" method="POST" id="assignRolesForm{{ $member->id }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-8">
                                                        <label for="roles{{ $member->id }}" class="form-label fs-6 fw-bold mb-3">Select Roles</label>
                                                        <select name="roles[]" id="roles{{ $member->id }}" class="form-select form-select-solid" data-control="select2" multiple required>
                                                            @foreach ($roles as $role)
                                                                <option value="{{ $role->name }}" {{ $member->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="text-muted fs-7 mt-2">Select one or more roles to assign to this staff member.</div>
                                                    </div>
                                                    @if (auth('staff')->user()->hasRole('manager'))
                                                        <div class="alert alert-info">
                                                            <i class="ki-duotone ki-information fs-2 text-info me-2">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                                <span class="path3"></span>
                                                            </i>
                                                            This action requires Super Admin approval.
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary" id="submitAssignRoles{{ $member->id }}">
                                                        <span class="indicator-label">Assign Roles</span>
                                                        <span class="indicator-progress">Please wait... 
                                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                        </span>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <!--end::Assign Roles Modal-->
                            <!--begin::Remove Roles Modal-->
                            @if (auth('staff')->user()->hasAnyRole(['super-admin', 'manager']))
                                <div class="modal fade" id="kt_staff_remove_roles_modal{{ $member->id }}" tabindex="-1" aria-labelledby="removeRolesModalLabel{{ $member->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="removeRolesModalLabel{{ $member->id }}">Remove Roles from {{ $member ? trim($member->first_name . ' ' . ($member->middle_name ?? '') . ' ' . ($member->surname ?? '')) : 'N/A' }}</h5>
                                                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                    <i class="ki-duotone ki-cross fs-1">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </div>
                                            </div>
                                            <form action="{{ route('staff.staff.remove-roles', $member->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="remove_roles" class="form-label">Select Roles to Remove</label>
                                                        <select name="roles[]" id="remove_roles" class="form-control form-control-solid" data-control="select2" multiple required>
                                                            @foreach ($member->roles as $role)
                                                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @if (auth('staff')->user()->hasRole('manager'))
                                                        <div class="alert alert-info">
                                                            This action requires Super Admin approval.
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Remove Roles</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <!--end::Remove Roles Modal-->
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No staff found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <!--end::Table-->
                <div class="mt-3">
                    {{ $staff->appends([
                        'search_staff' => request('search_staff'),
                        'status_filter' => request('status_filter')
                    ])->links('pagination::bootstrap-5') }}
                </div>
            </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
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
            $('#status_filter').on('change', function() {
                $('#staff_filter_form').submit();
            });

            // Submit form on search input (with debounce)
            let searchTimeout;
            $('#search_staff').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    $('#staff_filter_form').submit();
                }, 500);
            });

            // Handle assign roles form submission
            $('form[id^="assignRolesForm"]').each(function() {
                $(this).on('submit', function() {
                    const formId = $(this).attr('id');
                    const submitButton = $('#submit' + formId);
                    
                    // Show loading indicator
                    submitButton.attr('data-kt-indicator', 'on');
                    submitButton.prop('disabled', true);
                });
            });
        });
    </script>
@endsection