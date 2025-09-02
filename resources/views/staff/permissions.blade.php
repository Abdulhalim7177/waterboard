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

    <!-- Permissions Table -->
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" name="search_permission" id="search_permission" class="form-control form-control-solid w-250px ps-13" placeholder="Search Permissions" value="{{ request('search_permission') }}" />
                </div>
            </div>
            <div class="card-toolbar">
                @can('create-permission', Spatie\Permission\Models\Permission::class)
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_permission_create_modal">Add Permission</button>
                @endcan
            </div>
        </div>
        <div class="card-body pt-0">
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
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    @if ($permissions->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">Previous</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $permissions->previousPageUrl() }}&search_permission={{ request('search_permission') }}">Previous</a>
                        </li>
                    @endif
                    @foreach ($permissions->getUrlRange(1, $permissions->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $permissions->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}&search_permission={{ request('search_permission') }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    @if ($permissions->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $permissions->nextPageUrl() }}&search_permission={{ request('search_permission') }}">Next</a>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Select2 for all select elements
            document.querySelectorAll('select[data-control="select2"]').forEach(select => {
                $(select).select2({
                    dropdownParent: select.closest('.modal') || document.body
                });
            });

            // Re-initialize Select2 when any modal is shown
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('shown.bs.modal', function () {
                    this.querySelectorAll('select[data-control="select2"]').forEach(select => {
                        if (!$(select).hasClass("select2-hidden-accessible")) {
                            $(select).select2({
                                dropdownParent: this
                            });
                        }
                    });
                });
            });

            // Debounced search
            const searchInput = document.getElementById('search_permission');
            let searchTimeout;

            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const value = searchInput.value;
                    const url = new URL('{{ route("staff.permissions.index") }}');
                    if (value) url.searchParams.set('search_permission', value);
                    window.location.href = url.toString();
                }, 500);
            });

            // Checkbox selection
            const masterCheckbox = document.querySelector('#kt_permission_table .form-check-input[data-kt-check="true"]');
            const checkboxes = document.querySelectorAll('#kt_permission_table .form-check-input:not([data-kt-check="true"])');

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
    </style>
@endsection