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

        <!-- Staff Table -->
        <div class="card mb-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" name="search_staff" data-kt-staff-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search Staff" value="{{ request('search_staff') }}" />
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
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_role_table">
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
                                                    <div class="fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">Name</label>
                                                        <input type="text" name="name" value="{{ $member->name }}" class="form-control form-control-solid" required />
                                                    </div>
                                                    <div class="fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">Email</label>
                                                        <input type="email" name="email" value="{{ $member->email }}" class="form-control form-control-solid" required />
                                                    </div>
                                                    <div class="fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">Password</label>
                                                        <input type="password" name="password" class="form-control form-control-solid" placeholder="Leave blank to keep unchanged" />
                                                    </div>
                                                    <div class="fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">Confirm Password</label>
                                                        <input type="password" name="password_confirmation" class="form-control form-control-solid" />
                                                    </div>
                                                    <div class="fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">District</label>
                                                        <input type="text" name="district" value="{{ $member->district }}" class="form-control form-control-solid" required />
                                                    </div>
                                                    <div class="fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">Zone</label>
                                                        <input type="text" name="zone" value="{{ $member->zone }}" class="form-control form-control-solid" required />
                                                    </div>
                                                    <div class="fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">Subzone</label>
                                                        <input type="text" name="subzone" value="{{ $member->subzone }}" class="form-control form-control-solid" required />
                                                    </div>
                                                    <div class="fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">Road</label>
                                                        <input type="text" name="road" value="{{ $member->road }}" class="form-control form-control-solid" required />
                                                    </div>
                                                    <div class="fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">SUCC</label>
                                                        <input type="text" name="succ" value="{{ $member->succ }}" class="form-control form-control-solid" required />
                                                    </div>
                                                    <div class="fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">Roles</label>
                                                        <select name="roles[]" class="form-control form-control-solid" data-control="select2" multiple>
                                                            @foreach ($roles as $role)
                                                                <option value="{{ $role->name }}" {{ $member->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
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
            </div>
        </div>

        <!-- Roles Table -->
        <div class="card mb-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" name="search_role" data-kt-role-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search Roles" value="{{ request('search_role') }}" />
                    </div>
                </div>
                <div class="card-toolbar">
                    @can('create-role', Spatie\Permission\Models\Role::class)
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_role_create_modal">Add Role</button>
                    @endcan
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_role_table">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_role_table .form-check-input" value="1" />
                                </div>
                            </th>
                            <th class="min-w-125px">Name</th>
                            <th class="min-w-125px">Permissions</th>
                            <th class="text-end min-w-100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @forelse ($roles as $role)
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="{{ $role->id }}" />
                                    </div>
                                </td>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->permissions->pluck('name')->join(', ') }}</td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        Actions
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                    </a>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_role_view_modal{{ $role->id }}">View</a>
                                        </div>
                                        @can('edit-role', Spatie\Permission\Models\Role::class)
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_role_edit_modal{{ $role->id }}">Edit</a>
                                            </div>
                                        @endcan
                                        @can('delete-role', Spatie\Permission\Models\Role::class)
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_role_delete_modal{{ $role->id }}">Delete</a>
                                            </div>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            <!-- View Modal -->
                            <div class="modal fade" id="kt_role_view_modal{{ $role->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h2 class="fw-bold">View Role</h2>
                                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                <i class="ki-duotone ki-cross fs-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            Name: {{ $role->name }}<br>
                                            Permissions: {{ $role->permissions->pluck('name')->join(', ') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Edit Modal -->
                            @can('edit-role', Spatie\Permission\Models\Role::class)
                                <div class="modal fade" id="kt_role_edit_modal{{ $role->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered mw-650px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h2 class="fw-bold">Edit Role</h2>
                                                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                    <i class="ki-duotone ki-cross fs-1">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </div>
                                            </div>
                                            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                <form action="{{ route('staff.roles.update', $role->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">Name</label>
                                                        <input type="text" name="name" value="{{ $role->name }}" class="form-control form-control-solid" required />
                                                    </div>
                                                    <div class="fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">Permissions</label>
                                                        <select name="permissions[]" class="form-control form-control-solid" data-control="select2" multiple>
                                                            @foreach ($permissions as $permission)
                                                                <option value="{{ $permission->name }}" {{ $role->hasPermissionTo($permission->name) ? 'selected' : '' }}>{{ $permission->name }}</option>
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
                            @can('delete-role', Spatie\Permission\Models\Role::class)
                                <div class="modal fade" id="kt_role_delete_modal{{ $role->id }}" tabindex="-1" aria-hidden="true">
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
                                                Are you sure you want to request deletion of the role {{ $role->name }}? This action will set the status to pending for admin approval.
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('staff.roles.destroy', $role->id) }}" method="POST" class="d-inline">
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
                                <td colspan="4" class="text-center">No Roles found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Permissions Table -->
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" name="search_permission" data-kt-permission-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search Permissions" value="{{ request('search_permission') }}" />
                    </div>
                </div>
                <div class="card-toolbar">
                    @can('create-permission', Spatie\Permission\Models\Permission::class)
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_permission_create_modal">Add Permission</button>
                    @endcan
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_permission_table">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_permission_table">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_permission_table .form-check-input" value="1" />
                                    </div>
                                </th>
                                <th class="min-w-125px">Name</th>
                                <th class="text-end min-w-100px">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @forelse ($permissions as $permission)
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" value="{{ $permission->id }}" />
                                        </div>
                                    </td>
                                    <td>{{ $permission->name }}</td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Actions
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                        </a>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_permission_view_modal{{ $permission->id }}">View</a>
                                            </div>
                                            @can('edit-permission', Spatie\Permission\Models\Permission::class)
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_permission_edit_modal{{ $permission->id }}">Edit</a>
                                                </div>
                                            @endcan
                                            @can('delete-permission', Spatie\Permission\Models\Permission::class)
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_permission_delete_modal{{ $permission->id }}">Delete</a>
                                                </div>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                <!-- View Modal -->
                                <div class="modal fade" id="kt_permission_view_modal{{ $permission->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered mw-650px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h2 class="fw-bold">View Permission</h2>
                                                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                    <i class="ki-duotone ki-cross fs-1">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                Name: {{ $permission->name }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Edit Modal -->
                                @can('edit-permission', Spatie\Permission\Models\Permission::class)
                                    <div class="modal fade" id="kt_permission_edit_modal{{ $permission->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="fw-bold">Edit Permission</h2>
                                                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                        <i class="ki-duotone ki-cross fs-1">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </div>
                                                </div>
                                                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                    <form action="{{ route('staff.permissions.update', $permission->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="fv-row mb-10">
                                                            <label class="fs-5 fw-semibold form-label mb-5">Name</label>
                                                            <input type="text" name="name" value="{{ $permission->name }}" class="form-control form-control-solid" required />
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
                                @can('delete-permission', Spatie\Permission\Models\Permission::class)
                                    <div class="modal fade" id="kt_permission_delete_modal{{ $permission->id }}" tabindex="-1" aria-hidden="true">
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
                                                    Are you sure you want to request deletion of the permission {{ $permission->name }}? This action will set the status to pending for admin approval.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('staff.permissions.destroy', $permission->id) }}" method="POST" class="d-inline">
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
                                    <td colspan="3" class="text-center">No Permissions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        <!-- Create Modals -->
        <div class="modal fade" id="kt_staff_create_modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-650px">
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
                            <div class="fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">Name</label>
                                <input type="text" name="name" class="form-control form-control-solid" required />
                            </div>
                            <div class="fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">Email</label>
                                <input type="email" name="email" class="form-control form-control-solid" required />
                            </div>
                            <div class="fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">Password</label>
                                <input type="password" name="password" class="form-control form-control-solid" required />
                            </div>
                            <div class="fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control form-control-solid" required />
                            </div>
                            <div class="fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">District</label>
                                <input type="text" name="district" class="form-control form-control-solid" required />
                            </div>
                            <div class="fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">Zone</label>
                                <input type="text" name="zone" class="form-control form-control-solid" required />
                            </div>
                            <div class="fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">Subzone</label>
                                <input type="text" name="subzone" class="form-control form-control-solid" required />
                            </div>
                            <div class="fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">Road</label>
                                <input type="text" name="road" class="form-control form-control-solid" required />
                            </div>
                            <div class="fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">SUCC</label>
                                <input type="text" name="succ" class="form-control form-control-solid" required />
                            </div>
                            @if (auth('staff')->user()->hasAnyRole(['super-admin', 'manager']))
                                <div class="fv-row mb-10">
                                    <label class="fs-5 fw-semibold form-label mb-5">Roles</label>
                                    <select name="roles[]" class="form-control form-control-solid" data-control="select2" multiple>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if (auth('staff')->user()->hasRole('manager'))
                                    <div class="alert alert-info">
                                        This action requires Super Admin approval.
                                    </div>
                                @endif
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
        <div class="modal fade" id="kt_role_create_modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">Add Role</h2>
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="ki-duotone ki-cross fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                    </div>
                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                        <form action="{{ route('staff.roles.store') }}" method="POST">
                            @csrf
                            <div class="fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">Name</label>
                                <input type="text" name="name" class="form-control form-control-solid" required />
                            </div>
                            <div class="fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">Permissions</label>
                                <select name="permissions[]" class="form-control form-control-solid" data-control="select2" multiple>
                                    @foreach ($permissions as $permission)
                                        <option value="{{ $permission->name }}">{{ $permission->name }}</option>
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
        <div class="modal fade" id="kt_permission_create_modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">Add Permission</h2>
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="ki-duotone ki-cross fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                    </div>
                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                        <form action="{{ route('staff.permissions.store') }}" method="POST">
                            @csrf
                            <div class="fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">Name</label>
                                <input type="text" name="name" class="form-control form-control-solid" required />
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
        $(document).ready(function() {
            $('select[data-control="select2"]').select2({
                minimumResultsForSearch: Infinity
            });

            $('[data-kt-staff-table-filter="search"]').on('keyup', function() {
                var value = $(this).val();
                window.location.href = "{{ route('staff.staff.index') }}?search_staff=" + encodeURIComponent(value);
            });

            $('[data-kt-role-table-filter="search"]').on('keyup', function() {
                var value = $(this).val();
                window.location.href = "{{ route('staff.staff.index') }}?search_role=" + encodeURIComponent(value);
            });

            $('[data-kt-permission-table-filter="search"]').on('keyup', function() {
                var value = $(this).val();
                window.location.href = "{{ route('staff.staff.index') }}?search_permission=" + encodeURIComponent(value);
            });
        });
    </script>
@endsection