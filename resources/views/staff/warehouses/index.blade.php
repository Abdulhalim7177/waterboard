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
        <!--end::Alerts-->

        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <!--begin::Card title-->
                <div class="card-title w-100 d-flex align-items-center justify-content-between flex-wrap">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" name="search_warehouses" id="search_warehouses" class="form-control form-control-solid w-250px ps-13" placeholder="Search Warehouses" />
                    </div>
                    <!--end::Search-->

                    <!--begin::Toolbar-->
                    <div class="d-flex align-items-center gap-2 flex-shrink-0 ms-auto">
                        <!--begin::Add warehouse-->
                        <a href="{{ route('staff.warehouses.create') }}" class="btn btn-primary">Add Warehouse</a>
                        <!--end::Add warehouse-->
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
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_warehouses_table">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-125px">Label</th>
                            <th class="min-w-125px">Description</th>
                            <th class="min-w-125px">Status</th>
                            <th class="text-end min-w-100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @forelse ($warehouses as $warehouse)
                            <tr>
                                <td>
                                    <a href="{{ route('staff.warehouses.edit', $warehouse['id']) }}" class="text-gray-800 text-hover-primary mb-1">
                                        {{ $warehouse['label'] ?? 'N/A' }}
                                    </a>
                                </td>
                                <td>{{ Str::limit($warehouse['description'] ?? 'N/A', 50) }}</td>
                                <td>
                                    @if(($warehouse['statut'] ?? 0) == 1)
                                        <span class="badge badge-light-success">Active</span>
                                    @else
                                        <span class="badge badge-light-danger">Inactive</span>
                                    @endif
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
                                            <a href="{{ route('staff.warehouses.edit', $warehouse['id']) }}" class="menu-link px-3">Edit</a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $warehouse['id'] }}">Delete</a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
                                </td>
                            </tr>
                            <!--begin::Delete Modal-->
                            <div class="modal fade" id="deleteModal{{ $warehouse['id'] }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $warehouse['id'] }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $warehouse['id'] }}">Confirm Deletion</h5>
                                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                <i class="ki-duotone ki-cross fs-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete {{ $warehouse['label'] ?? 'Warehouse' }}? This action cannot be undone.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('staff.warehouses.destroy', $warehouse['id']) }}" method="POST" class="d-inline">
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
                                <td colspan="4" class="text-center">
                                    <p class="mb-0">No warehouses found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
@endsection
