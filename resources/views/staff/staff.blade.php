@extends('layouts.staff')

@section('content')
    <div class="container-xxl">
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

    <!-- Staff Table -->
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" name="search_staff" id="search_staff" class="form-control form-control-solid w-250px ps-13" placeholder="Search Staff" value="{{ request('search_staff') }}" />
                </div>
                <!-- Filters for LGA, Ward, Area -->
                <div class="d-flex align-items-center position-relative my-1 ms-3">
                    <select name="filter_lga" id="filter_lga" class="form-control form-control-solid w-200px" data-control="select2">
                        <option value="">All LGAs</option>
                        @foreach ($lgas as $lga)
                            <option value="{{ $lga->id }}" {{ request('filter_lga') == $lga->id ? 'selected' : '' }}>{{ $lga->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex align-items-center position-relative my-1 ms-3">
                    <select name="filter_ward" id="filter_ward" class="form-control form-control-solid w-200px" data-control="select2">
                        <option value="">All Wards</option>
                        @foreach ($wards as $ward)
                            <option value="{{ $ward->id }}" {{ request('filter_ward') == $ward->id ? 'selected' : '' }}>{{ $ward->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex align-items-center position-relative my-1 ms-3">
                    <select name="filter_area" id="filter_area" class="form-control form-control-solid w-200px" data-control="select2">
                        <option value="">All Areas</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}" {{ request('filter_area') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-toolbar">
                @if (auth('staff')->user()->hasAnyRole(['super-admin', 'manager']))
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_staff_create_modal">Add Staff</button>
                @endif
            </div>
        </div>
        <div class="card-body pt-0">
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
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->email }}</td>
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
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_staff_view_modal{{ $member->id }}">View</a>
                                    </div>
                                    @can('edit', $member)
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_staff_edit_modal{{ $member->id }}">Edit</a>
                                        </div>
                                    @endcan
                                    @can('delete', $member)
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_staff_delete_modal{{ $member->id }}">Delete</a>
                                        </div>
                                    @endcan
                                    @if (auth('staff')->user()->hasAnyRole(['super-admin', 'manager']))
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_staff_assign_roles_modal{{ $member->id }}">Assign Roles</a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_staff_remove_roles_modal{{ $member->id }}">Remove Roles</a>
                                        </div>
                                    @endif
                                    @can('approve-staff', App\Models\Staff::class)
                                        @if ($member->status == 'pending' || $member->status == 'pending_delete')
                                            <div class="menu-item px-3">
                                                <form action="{{ route('staff.staff.approve', $member->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="menu-link px-3">Approve</button>
                                                </form>
                                            </div>
                                            <div class="menu-item px-3">
                                                <form action="{{ route('staff.staff.reject', $member->id) }}" method="POST" class="d-inline">
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
                        <div class="modal fade" id="kt_staff_view_modal{{ $member->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered mw-650px">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="fw-bold">View Staff</h2>
                                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                            <i class="ki-duotone ki-cross fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        Name: {{ $member->name }}<br>
                                        Email: {{ $member->email }}<br>
                                        Roles: {{ $member->getRoleNames()->join(', ') }}<br>
                                        District: {{ $member->district ?? 'N/A' }}<br>
                                        Zone: {{ $member->zone ?? 'N/A' }}<br>
                                        Subzone: {{ $member->subzone ?? 'N/A' }}<br>
                                        Road: {{ $member->road ?? 'N/A' }}<br>
                                        SUCC: {{ $member->succ ?? 'N/A' }}<br>
                                        LGA: {{ $member->lga ? $member->lga->name : 'N/A' }}<br>
                                        Ward: {{ $member->ward ? $member->ward->name : 'N/A' }}<br>
                                        Area: {{ $member->area ? $member->area->name : 'N/A' }}<br>
                                        Status: {{ ucfirst(str_replace('_', ' ', $member->status)) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Edit Modal -->
                        @can('edit', $member)
                            <div class="modal fade" id="kt_staff_edit_modal{{ $member->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h2 class="fw-bold">Edit Staff</h2>
                                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                <i class="ki-duotone ki-cross fs-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </div>
                                        </div>
                                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                            <form action="{{ route('staff.staff.update', $member->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="row">
                                                    <div class="col-md-6 fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">Name</label>
                                                        <input type="text" name="name" value="{{ $member->name }}" class="form-control form-control-solid" required />
                                                    </div>
                                                    <div class="col-md-6 fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">Email</label>
                                                        <input type="email" name="email" value="{{ $member->email }}" class="form-control form-control-solid" required />
                                                    </div>
                                                    <div class="col-md-6 fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">Password</label>
                                                        <input type="password" name="password" class="form-control form-control-solid" placeholder="Leave blank to keep unchanged" />
                                                    </div>
                                                    <div class="col-md-6 fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">Confirm Password</label>
                                                        <input type="password" name="password_confirmation" class="form-control form-control-solid" />
                                                    </div>
                                                    <div class="col-md-6 fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">District</label>
                                                        <input type="text" name="district" value="{{ $member->district }}" class="form-control form-control-solid" required />
                                                    </div>
                                                    <div class="col-md-6 fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">Zone</label>
                                                        <input type="text" name="zone" value="{{ $member->zone }}" class="form-control form-control-solid" required />
                                                    </div>
                                                    <div class="col-md-6 fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">Subzone</label>
                                                        <input type="text" name="subzone" value="{{ $member->subzone }}" class="form-control form-control-solid" required />
                                                    </div>
                                                    <div class="col-md-6 fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">Road</label>
                                                        <input type="text" name="road" value="{{ $member->road }}" class="form-control form-control-solid" required />
                                                    </div>
                                                    <div class="col-md-6 fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">SUCC</label>
                                                        <input type="text" name="succ" value="{{ $member->succ }}" class="form-control form-control-solid" required />
                                                    </div>
                                                    <div class="col-md-6 fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">LGA</label>
                                                        <select name="lga_id" class="form-control form-control-solid" data-control="select2">
                                                            <option value="">Select LGA</option>
                                                            @foreach ($lgas as $lga)
                                                                <option value="{{ $lga->id }}" {{ $member->lga_id == $lga->id ? 'selected' : '' }}>{{ $lga->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">Ward</label>
                                                        <select name="ward_id" class="form-control form-control-solid" data-control="select2">
                                                            <option value="">Select Ward</option>
                                                            @foreach ($wards as $ward)
                                                                <option value="{{ $ward->id }}" {{ $member->ward_id == $ward->id ? 'selected' : '' }}>{{ $ward->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">Area</label>
                                                        <select name="area_id" class="form-control form-control-solid" data-control="select2">
                                                            <option value="">Select Area</option>
                                                            @foreach ($areas as $area)
                                                                <option value="{{ $area->id }}" {{ $member->area_id == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">Roles</label>
                                                        <select name="roles[]" class="form-control form-control-solid" data-control="select2" multiple>
                                                            @foreach ($roles as $role)
                                                                <option value="{{ $role->name }}" {{ $member->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
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
                        <!-- Assign Roles Modal -->
                        @if (auth('staff')->user()->hasAnyRole(['super-admin', 'manager']))
                            <div class="modal fade" id="kt_staff_assign_roles_modal{{ $member->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h2 class="fw-bold">Assign Roles to {{ $member->name }}</h2>
                                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                <i class="ki-duotone ki-cross fs-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </div>
                                        </div>
                                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                            <form action="{{ route('staff.staff.assign-roles', $member->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="fv-row mb-10">
                                                    <label class="fs-5 fw-semibold form-label mb-5">Roles</label>
                                                    <select name="roles[]" class="form-control form-control-solid" data-control="select2" multiple required>
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role->name }}" {{ $member->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @if (auth('staff')->user()->hasRole('manager'))
                                                    <div class="alert alert-info">
                                                        This action requires Super Admin approval.
                                                    </div>
                                                @endif
                                                <div class="text-center">
                                                    <button type="submit" class="btn btn-primary">Assign Roles</button>
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- Remove Roles Modal -->
                        @if (auth('staff')->user()->hasAnyRole(['super-admin', 'manager']))
                            <div class="modal fade" id="kt_staff_remove_roles_modal{{ $member->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h2 class="fw-bold">Remove Roles from {{ $member->name }}</h2>
                                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                <i class="ki-duotone ki-cross fs-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </div>
                                        </div>
                                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                            <form action="{{ route('staff.staff.remove-roles', $member->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="fv-row mb-10">
                                                    <label class="fs-5 fw-semibold form-label mb-5">Select Roles to Remove</label>
                                                    <select name="roles[]" class="form-control form-control-solid" data-control="select2" multiple required>
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
                                                <div class="text-center">
                                                    <button type="submit" class="btn btn-primary">Remove Roles</button>
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- Delete Modal -->
                        @can('delete', $member)
                            <div class="modal fade" id="kt_staff_delete_modal{{ $member->id }}" tabindex="-1" aria-hidden="true">
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
                                            Are you sure you want to request deletion of {{ $member->name }} ({{ $member->email }})? This action will set the status to pending for admin approval.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('staff.staff.destroy', $member->id) }}" method="POST" class="d-inline">
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
                            <td colspan="6" class="text-center">No Staff found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    @if ($staff->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">Previous</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $staff->previousPageUrl() }}&search_staff={{ request('search_staff') }}&filter_lga={{ request('filter_lga') }}&filter_ward={{ request('filter_ward') }}&filter_area={{ request('filter_area') }}">Previous</a>
                        </li>
                    @endif
                    @foreach ($staff->getUrlRange(1, $staff->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $staff->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}&search_staff={{ request('search_staff') }}&filter_lga={{ request('filter_lga') }}&filter_ward={{ request('filter_ward') }}&filter_area={{ request('filter_area') }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    @if ($staff->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $staff->nextPageUrl() }}&search_staff={{ request('search_staff') }}&filter_lga={{ request('filter_lga') }}&filter_ward={{ request('filter_ward') }}&filter_area={{ request('filter_area') }}">Next</a>
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
    <div class="modal fade" id="kt_staff_create_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-750px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Add Staff</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <form action="{{ route('staff.staff.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">Name</label>
                                <input type="text" name="name" class="form-control form-control-solid" required />
                            </div>
                            <div class="col-md-6 fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">Email</label>
                                <input type="email" name="email" class="form-control form-control-solid" required />
                            </div>
                            <div class="col-md-6 fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">Password</label>
                                <input type="password" name="password" class="form-control form-control-solid" required />
                            </div>
                            <div class="col-md-6 fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control form-control-solid" required />
                            </div>
                            <div class="col-md-6 fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">District</label>
                                <input type="text" name="district" class="form-control form-control-solid" required />
                            </div>
                            <div class="col-md-6 fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">Zone</label>
                                <input type="text" name="zone" class="form-control form-control-solid" required />
                            </div>
                            <div class="col-md-6 fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">Subzone</label>
                                <input type="text" name="subzone" class="form-control form-control-solid" required />
                            </div>
                            <div class="col-md-6 fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">Road</label>
                                <input type="text" name="road" class="form-control form-control-solid" required />
                            </div>
                            <div class="col-md-6 fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">SUCC</label>
                                <input type="text" name="succ" class="form-control form-control-solid" required />
                            </div>
                            <div class="col-md-6 fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">LGA</label>
                                <select name="lga_id" class="form-control form-control-solid" data-control="select2">
                                    <option value="">Select LGA</option>
                                    @foreach ($lgas as $lga)
                                        <option value="{{ $lga->id }}">{{ $lga->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">Ward</label>
                                <select name="ward_id" class="form-control form-control-solid" data-control="select2">
                                    <option value="">Select Ward</option>
                                    @foreach ($wards as $ward)
                                        <option value="{{ $ward->id }}">{{ $ward->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">Area</label>
                                <select name="area_id" class="form-control form-control-solid" data-control="select2">
                                    <option value="">Select Area</option>
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if (auth('staff')->user()->hasAnyRole(['super-admin', 'manager']))
                                <div class="col-md-12 fv-row mb-10">
                                    <label class="fs-5 fw-semibold form-label mb-5">Roles</label>
                                    <select name="roles[]" class="form-control form-control-solid" data-control="select2" multiple>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if (auth('staff')->user()->hasRole('manager'))
                                    <div class="col-md-12 alert alert-info">
                                        This action requires Super Admin approval.
                                    </div>
                                @endif
                            @endif
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
@endsection

@section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Select2 for non-modal select elements
            document.querySelectorAll('select[data-control="select2"]:not(.modal select)').forEach(select => {
                $(select).select2({
                    placeholder: select.options[0].text,
                    allowClear: true
                });
            });

            // Initialize Select2 for modal selects when modal is shown
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('shown.bs.modal', function () {
                    this.querySelectorAll('select[data-control="select2"]').forEach(select => {
                        if (!$(select).hasClass("select2-hidden-accessible")) {
                            $(select).select2({
                                placeholder: select.options[0].text,
                                allowClear: true,
                                dropdownParent: $(this),
                                minimumResultsForSearch: 1 // Enable search for dropdowns
                            });
                        }
                    });
                });

                // Destroy Select2 instances when modal is hidden to prevent conflicts
                modal.addEventListener('hidden.bs.modal', function () {
                    this.querySelectorAll('select[data-control="select2"]').forEach(select => {
                        if ($(select).hasClass("select2-hidden-accessible")) {
                            $(select).select2('destroy');
                        }
                    });
                });
            });

            // Prevent keypress events in Select2 search from bubbling to other inputs
            document.addEventListener('keydown', function (event) {
                if (event.target.classList.contains('select2-search__field')) {
                    event.stopPropagation();
                }
            });

            // Debounced search and filters
            const searchInput = document.getElementById('search_staff');
            const lgaSelect = document.getElementById('filter_lga');
            const wardSelect = document.getElementById('filter_ward');
            const areaSelect = document.getElementById('filter_area');
            let filterTimeout;

            function updateURL() {
                const search = searchInput.value;
                const lga = lgaSelect.value;
                const ward = wardSelect.value;
                const area = areaSelect.value;
                const url = new URL('{{ route("staff.staff.index") }}');
                if (search) url.searchParams.set('search_staff', search);
                if (lga) url.searchParams.set('filter_lga', lga);
                if (ward) url.searchParams.set('filter_ward', ward);
                if (area) url.searchParams.set('filter_area', area);
                window.location.href = url.toString();
            }

            function handleInput() {
                clearTimeout(filterTimeout);
                filterTimeout = setTimeout(updateURL, 500);
            }

            searchInput.addEventListener('input', handleInput);
            lgaSelect.addEventListener('change', handleInput);
            wardSelect.addEventListener('change', handleInput);
            areaSelect.addEventListener('change', handleInput);

            // Checkbox selection
            const masterCheckbox = document.querySelector('#kt_staff_table .form-check-input[data-kt-check="true"]');
            const checkboxes = document.querySelectorAll('#kt_staff_table .form-check-input:not([data-kt-check="true"])');

            masterCheckbox.addEventListener('change', function () {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = masterCheckbox.checked;
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
        /* Ensure Select2 dropdowns appear above other elements */
        .select2-container--open {
            z-index: 9999;
        }
        .select2-container .select2-search__field {
            width: 100% !important;
        }
    </style>
@endsection