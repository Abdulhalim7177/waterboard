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
                    <!--begin::Search and Filters Form-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" name="search_reservoirs" id="search_reservoirs" class="form-control form-control-solid w-250px ps-13" placeholder="Search Reservoirs" />
                    </div>
                    <!--end::Search and Filters Form-->

                    <!--begin::Toolbar-->
                    <div class="d-flex align-items-center gap-2 flex-shrink-0 ms-auto">
                        <!--begin::Add reservoir-->
                        <a href="{{ route('staff.reservoirs.create') }}" class="btn btn-primary">Add Reservoir</a>
                        <!--end::Add reservoir-->
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
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_reservoirs_table">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-125px">Reference</th>
                            <th class="min-w-125px">Name</th>
                            <th class="min-w-125px">Description</th>
                            <th class="min-w-125px">Type</th>
                            <th class="min-w-125px">Price</th>
                            <th class="text-end min-w-100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @forelse ($reservoirs as $reservoir)
                            <tr>
                                <td>{{ $reservoir['ref'] ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('staff.reservoirs.show', $reservoir['id']) }}" class="text-gray-800 text-hover-primary mb-1">
                                        {{ $reservoir['label'] ?? $reservoir['ref'] ?? 'Unknown Reservoir' }}
                                    </a>
                                </td>
                                <td>{{ Str::limit($reservoir['description'] ?? 'N/A', 50) }}</td>
                                <td>
                                    @if(($reservoir['type'] ?? 0) == 0)
                                        <span class="badge badge-light-primary">Product</span>
                                    @else
                                        <span class="badge badge-light-info">Service</span>
                                    @endif
                                </td>
                                <td>{{ $reservoir['price'] ?? 'N/A' }}</td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        Actions
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                    </a>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4" data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="{{ route('staff.reservoirs.show', $reservoir['id']) }}" class="menu-link px-3">View</a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="{{ route('staff.reservoirs.edit', $reservoir['id']) }}" class="menu-link px-3">Edit</a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $reservoir['id'] }}">Delete</a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
                                </td>
                            </tr>
                            <!--begin::Delete Modal-->
                            <div class="modal fade" id="deleteModal{{ $reservoir['id'] }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $reservoir['id'] }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $reservoir['id'] }}">Confirm Deletion</h5>
                                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                <i class="ki-duotone ki-cross fs-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete {{ $reservoir['label'] ?? $reservoir['ref'] ?? 'Reservoir' }} ({{ $reservoir['ref'] ?? 'N/A' }})? This action cannot be undone.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('staff.reservoirs.destroy', $reservoir['id']) }}" method="POST" class="d-inline">
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
                                <td colspan="6" class="text-center">
                                    <i class="ki-duotone ki-organization fs-2x text-muted mb-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                    <p class="mb-0">No reservoirs found in the asset management system.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <!--end::Table-->
                <div class="mt-3">
                    {{ $reservoirs->links('pagination::bootstrap-5') }}
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
            // Handle search input (with debounce)
            let searchTimeout;
            $('#search_reservoirs').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    // Implement search functionality if needed
                    console.log('Searching for: ' + $('#search_reservoirs').val());
                }, 500);
            });
        });
    </script>
@endsection
