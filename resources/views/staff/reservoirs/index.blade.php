
@extends('layouts.staff')

@section('content')
<div class="container-fluid">
    <!--begin::Card-->
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" data-kt-reservoir-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search Reservoirs" />
                </div>
            </div>
            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-reservoir-table-toolbar="base">
                    <a href="{{ route('staff.reservoirs.create') }}" class="btn btn-primary">
                        <i class="ki-duotone ki-plus fs-2"></i>Add Reservoir
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body py-4">
            <!--begin::Table-->
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_reservoirs_table">
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-125px">ID</th>
                            <th class="min-w-125px">Name</th>
                            <th class="min-w-125px">Tanks</th>
                            <th class="min-w-125px">Capacity</th>
                            <th class="min-w-125px">Location</th>
                            <th class="text-end min-w-100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-semibold">
                        @foreach ($reservoirs as $reservoir)
                        <tr>
                            <td>{{ $reservoir['id'] }}</td>
                            <td>{{ $reservoir['label'] }}</td>
                            <td>{{ $reservoir['array_options']['options_tanks'] ?? 'N/A' }}</td>
                            <td>{{ $reservoir['array_options']['options_capacity'] ?? 'N/A' }}</td>
                            <td>{{ $reservoir['array_options']['options_location'] ?? 'N/A' }}</td>
                            <td class="text-end">
                                <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    Actions
                                    <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                </a>
                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                    <div class="menu-item px-3">
                                        <a href="{{ route('staff.reservoirs.show', $reservoir['id']) }}" class="menu-link px-3">View</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="{{ route('staff.reservoirs.edit', $reservoir['id']) }}" class="menu-link px-3">Edit</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3" data-kt-reservoir-table-filter="delete_row">Delete</a>
                                        <form action="{{ route('staff.reservoirs.destroy', $reservoir['id']) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                                <!--end::Menu-->
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!--end::Table-->
            {{ $reservoirs->links() }}
        </div>
    </div>
    <!--end::Card-->
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Search and filter functionality
        const filterSearch = document.querySelector('[data-kt-reservoir-table-filter="search"]');
        const table = document.getElementById('kt_reservoirs_table');
        const rows = table.getElementsByTagName('tr');

        filterSearch.addEventListener('keyup', function (e) {
            const searchText = e.target.value.toLowerCase();

            for (let i = 1; i < rows.length; i++) { // Start from 1 to skip the header row
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let found = false;
                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    if (cell) {
                        const cellText = cell.textContent || cell.innerText;
                        if (cellText.toLowerCase().indexOf(searchText) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                if (found) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            }
        });

        // Delete confirmation
        table.querySelectorAll('[data-kt-reservoir-table-filter="delete_row"]').forEach(function (element) {
            element.addEventListener('click', function (e) {
                e.preventDefault();
                Swal.fire({
                    text: "Are you sure you want to delete this reservoir?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, delete!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        element.nextElementSibling.submit();
                    }
                });
            });
        });
    });
</script>
@endsection
