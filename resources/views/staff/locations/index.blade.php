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
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" name="search_lga" data-kt-lga-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search LGAs" value="{{ request('search_lga') }}" />
                    </div>
                </div>
                <div class="card-toolbar">
                    @can('create-lga')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_lga_create_modal">Add LGA</button>
                    @endcan
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_lga_table">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_lga_table .form-check-input" value="1" />
                                </div>
                            </th>
                            <th class="min-w-125px">Name</th>
                            <th class="min-w-125px">Code</th>
                            <th class="min-w-125px">State</th>
                            <th class="min-w-125px">Status</th>
                            <th class="text-end min-w-100px">Actions</th>
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
                                <td>{{ $lga->name }}</td>
                                <td>{{ $lga->code }}</td>
                                <td>{{ $lga->state }}</td>
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
                {{ $lgas->appends(['search_lga' => request('search_lga')])->links() }}
            </div>
            </div>
        </div>

        <!-- Wards Table -->
        <div class="card mb-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" name="search_ward" data-kt-ward-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search Wards" value="{{ request('search_ward') }}" />
                    </div>
                </div>
                <div class="card-toolbar">
                    @can('create-ward')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_ward_create_modal">Add Ward</button>
                    @endcan
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ward_table">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_ward_table .form-check-input" value="1" />
                                </div>
                            </th>
                            <th class="min-w-125px">Name</th>
                            <th class="min-w-125px">Code</th>
                            <th class="min-w-125px">LGA</th>
                            <th class="min-w-125px">Status</th>
                            <th class="text-end min-w-100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @forelse ($wards as $ward)
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="{{ $ward->id }}" />
                                    </div>
                                </td>
                                <td>{{ $ward->name }}</td>
                                <td>{{ $ward->code }}</td>
                                <td>{{ $ward->lga->name }}</td>
                                <td>
                                    <div class="badge badge-light-{{ $ward->status == 'approved' ? 'success' : ($ward->status == 'pending' || $ward->status == 'pending_delete' ? 'warning' : 'danger') }}">
                                        {{ ucfirst(str_replace('_', ' ', $ward->status)) }}
                                    </div>
                                </td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        Actions
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                    </a>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_ward_view_modal{{ $ward->id }}">View</a>
                                        </div>
                                        @can('edit-ward')
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_ward_edit_modal{{ $ward->id }}">Edit</a>
                                            </div>
                                        @endcan
                                        @can('delete-ward')
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_ward_delete_modal{{ $ward->id }}">Delete</a>
                                            </div>
                                        @endcan
                                        @can('approve-ward')
                                            @if ($ward->status == 'pending' || $ward->status == 'pending_delete')
                                                <div class="menu-item px-3">
                                                    <form action="{{ route('staff.wards.approve', $ward->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="menu-link px-3">Approve</button>
                                                    </form>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <form action="{{ route('staff.wards.reject', $ward->id) }}" method="POST" class="d-inline">
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
                            <div class="modal fade" id="kt_ward_view_modal{{ $ward->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h2 class="fw-bold">View Ward</h2>
                                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                <i class="ki-duotone ki-cross fs-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            Name: {{ $ward->name }}<br>
                                            Code: {{ $ward->code }}<br>
                                            LGA: {{ $ward->lga->name }}<br>
                                            Status: {{ ucfirst(str_replace('_', ' ', $ward->status)) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Edit Modal -->
                            @can('edit-ward')
                                <div class="modal fade" id="kt_ward_edit_modal{{ $ward->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered mw-650px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h2 class="fw-bold">Edit Ward</h2>
                                                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                    <i class="ki-duotone ki-cross fs-1">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </div>
                                            </div>
                                            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                <form action="{{ route('staff.wards.update', $ward->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">Name</label>
                                                        <input type="text" name="name" value="{{ $ward->name }}" class="form-control form-control-solid" required />
                                                    </div>
                                                    <div class="fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">Code</label>
                                                        <input type="text" name="code" value="{{ $ward->code }}" class="form-control form-control-solid" required />
                                                    </div>
                                                    <div class="fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">LGA</label>
                                                        <select name="lga_id" class="form-control form-control-solid" data-control="select2" required>
                                                            @foreach (App\Models\Lga::where('status', 'approved')->get() as $lga)
                                                                <option value="{{ $lga->id }}" {{ $ward->lga_id == $lga->id ? 'selected' : '' }}>{{ $lga->name }}</option>
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
                            @can('delete-ward')
                                <div class="modal fade" id="kt_ward_delete_modal{{ $ward->id }}" tabindex="-1" aria-hidden="true">
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
                                                Are you sure you want to request deletion of {{ $ward->name }} ({{ $ward->code }})? This action will set the status to pending for admin approval.
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('staff.wards.destroy', $ward->id) }}" method="POST" class="d-inline">
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
                                <td colspan="6" class="text-center">No Wards found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $wards->appends(['search_ward' => request('search_ward')])->links() }}
            </div>
            </div>
        </div>

        <!-- Areas Table -->
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" name="search_area" data-kt-area-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search Areas" value="{{ request('search_area') }}" />
                    </div>
                </div>
                <div class="card-toolbar">
                    @can('create-area')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_area_create_modal">Add Area</button>
                    @endcan
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_area_table">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_area_table .form-check-input" value="1" />
                                </div>
                            </th>
                            <th class="min-w-125px">Name</th>
                            <th class="min-w-125px">Code</th>
                            <th class="min-w-125px">Ward</th>
                            <th class="min-w-125px">Status</th>
                            <th class="text-end min-w-100px">Actions</th>
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
                                <td>{{ $area->name }}</td>
                                <td>{{ $area->code }}</td>
                                <td>{{ $area->ward->name }}</td>
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
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
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
                                        <div class="modal-body">
                                            Name: {{ $area->name }}<br>
                                            Code: {{ $area->code }}<br>
                                            Ward: {{ $area->ward->name }}<br>
                                            LGA: {{ $area->ward->lga->name }}<br>
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
                                                        <label class="fs-5 fw-semibold form-label mb-5">Code</label>
                                                        <input type="text" name="code" value="{{ $area->code }}" class="form-control form-control-solid" required />
                                                    </div>
                                                    <div class="fv-row mb-10">
                                                        <label class="fs-5 fw-semibold form-label mb-5">Ward</label>
                                                        <select name="ward_id" class="form-control form-control-solid" data-control="select2" required>
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
                                            <div class="modal-body">
                                                Are you sure you want to request deletion of {{ $area->name }} ({{ $area->code }})? This action will set the status to pending for admin approval.
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
                {{ $areas->appends(['search_area' => request('search_area')])->links() }}
            </div>
            </div>
        </div>

        <!-- Create Modals -->
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
        <div class="modal fade" id="kt_ward_create_modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">Add Ward</h2>
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="ki-duotone ki-cross fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                    </div>
                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                        <form action="{{ route('staff.wards.store') }}" method="POST">
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
                                <label class="fs-5 fw-semibold form-label mb-5">LGA</label>
                                <select name="lga_id" class="form-control form-control-solid" data-control="select2" required>
                                    @foreach (App\Models\Lga::where('status', 'approved')->get() as $lga)
                                        <option value="{{ $lga->id }}">{{ $lga->name }}</option>
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
                                <label class="fs-5 fw-semibold form-label mb-5">Code</label>
                                <input type="text" name="code" class="form-control form-control-solid" required />
                            </div>
                            <div class="fv-row mb-10">
                                <label class="fs-5 fw-semibold form-label mb-5">Ward</label>
                                <select name="ward_id" class="form-control form-control-solid" data-control="select2" required>
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
        $(document).ready(function() {
            $('select[data-control="select2"]').select2({
                minimumResultsForSearch: Infinity
            });

            $('[data-kt-lga-table-filter="search"]').on('keyup', function() {
                var value = $(this).val();
                window.location.href = "{{ route('staff.locations.index') }}?search_lga=" + encodeURIComponent(value);
            });

            $('[data-kt-ward-table-filter="search"]').on('keyup', function() {
                var value = $(this).val();
                window.location.href = "{{ route('staff.locations.index') }}?search_ward=" + encodeURIComponent(value);
            });

            $('[data-kt-area-table-filter="search"]').on('keyup', function() {
                var value = $(this).val();
                window.location.href = "{{ route('staff.locations.index') }}?search_area=" + encodeURIComponent(value);
            });
        });
    </script>
@endsection