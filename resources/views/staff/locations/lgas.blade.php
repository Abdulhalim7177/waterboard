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
                    <div class="d-flex flex-column flex-md-row align-items-md-center position-relative my-1">
                        <div class="d-flex align-items-center position-relative">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <input type="text" id="search_lga" class="form-control form-control-solid w-100px w-md-250px ps-13" placeholder="Search LGAs" value="{{ request('search_lga') }}" />
                        </div>
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end" data-kt-lga-table-toolbar="base">
                        @can('create-lga')
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_lga_create_modal">
                                <i class="ki-duotone ki-plus fs-2"></i>
                                <span class="d-none d-md-inline">Add LGA</span>
                            </button>
                        @endcan
                    </div>
                    <div class="d-flex justify-content-end align-items-center d-none" data-kt-lga-table-toolbar="selected">
                        <div class="fw-bold me-5">
                            <span class="me-2" data-kt-lga-table-select="selected_count"></span>Selected
                        </div>
                        <button type="button" class="btn btn-danger" data-kt-lga-table-select="delete_selected">
                            <i class="ki-duotone ki-trash fs-2"></i>
                            <span class="d-none d-md-inline">Delete Selected</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <!--begin::Summary Widgets-->
                <div class="row g-6 g-xl-9 mb-6 mb-xl-9">
                    <div class="col-sm-6 col-xl-4 mb-5 mb-xl-10">
                        <div class="card card-flush h-md-50 mb-xl-10">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <h6 class="text-gray-400 fw-semibold mb-1">Total LGAs</h6>
                                    <div class="d-flex flex-column">
                                        <span class="fs-2hx fw-bold text-gray-800 lh-1 ls-n2">{{ $lgas->count() }}</span>
                                    </div>
                                </div>
                                <div class="symbol symbol-60px">
                                    <div class="symbol-label bg-light-primary">
                                        <i class="ki-duotone ki-geolocation fs-1 text-primary">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Summary Widgets-->

                <!-- Desktop Table -->
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_lga_table">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_lga_table .form-check-input" value="1" />
                                    </div>
                                </th>
                                <th class="min-w-100px">Name</th>
                                <th class="min-w-75px">Code</th>
                                                                <th class="min-w-100px">Status</th>
                                <th class="text-end min-w-75px">Actions</th>
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
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 text-hover-primary mb-1">{{ $lga->name }}</span>
                                                <span class="text-muted fs-7">Code: {{ $lga->code }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell">{{ $lga->code }}</td>
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
                                                <a href="#kt_lga_view_modal{{ $lga->id }}" class="menu-link px-3" data-bs-toggle="modal">View</a>
                                            </div>
                                            @can('edit-lga')
                                                <div class="menu-item px-3">
                                                    <a href="#kt_lga_edit_modal{{ $lga->id }}" class="menu-link px-3" data-bs-toggle="modal">Edit</a>
                                                </div>
                                            @endcan
                                            @can('delete-lga')
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('staff.lgas.destroy', $lga->id) }}" class="menu-link px-3" data-method="delete" data-confirm="Are you sure you want to request deletion of {{ $lga->name }} ({{ $lga->code }})? This action will set the status to pending for admin approval.">Delete</a>
                                                </div>
                                            @endcan
                                            @can('approve-lga')
                                                @if ($lga->status == 'pending' || $lga->status == 'pending_delete')
                                                    <div class="menu-item px-3">
                                                        <a href="{{ route('staff.lgas.approve', $lga->id) }}" class="menu-link px-3" data-method="put" data-confirm="Are you sure you want to approve this LGA?">Approve</a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a href="{{ route('staff.lgas.reject', $lga->id) }}" class="menu-link px-3" data-method="put" data-confirm="Are you sure you want to reject this LGA?">Reject</a>
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
                                                Status: {{ ucfirst(str_replace('_', ' ', $lga->status)) }}<br>
                                                Total Customers: {{ $lga->customers_count }}
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

                                <!-- Approve Modal -->
                                @can('approve-lga')
                                    @if ($lga->status == 'pending' || $lga->status == 'pending_delete')
                                    <div class="modal fade" id="kt_lga_approve_modal{{ $lga->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="fw-bold">Confirm Approval</h2>
                                                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                        <i class="ki-duotone ki-cross fs-1">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </div>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to approve this LGA?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('staff.lgas.approve', $lga->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-success">Approve</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endcan

                                <!-- Reject Modal -->
                                @can('reject-lga')
                                    @if ($lga->status == 'pending' || $lga->status == 'pending_delete')
                                    <div class="modal fade" id="kt_lga_reject_modal{{ $lga->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="fw-bold">Confirm Rejection</h2>
                                                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                                        <i class="ki-duotone ki-cross fs-1">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </div>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to reject this LGA?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('staff.lgas.reject', $lga->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-danger">Reject</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endcan
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No LGAs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <select id="items_per_page" class="form-select form-select-solid me-3">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="all">All</option>
                        </select>
                        <span id="pagination_description"></span>
                    </div>
                    <nav>
                        <ul class="pagination" id="pagination_links">
                        </ul>
                    </nav>
                </div>
                
                <!-- Mobile Cards -->
                <div class="d-md-none d-none">
                    @forelse ($lgas as $lga)
                        <div class="card mb-5 mb-xl-8 border border-gray-300">
                            <div class="card-header border-0 pt-5">
                                <div class="d-flex flex-stack flex-wrap gap-2 w-100">
                                    <div class="d-flex align-items-center">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox" value="{{ $lga->id }}" />
                                        </div>
                                        <div class="d-flex flex-column">
                                            <a href="#" class="text-gray-800 text-hover-primary fs-5 fw-bold" data-bs-toggle="modal" data-bs-target="#kt_lga_view_modal{{ $lga->id }}">{{ $lga->name }}</a>
                                            <span class="text-muted fs-7">Code: {{ $lga->code }}</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="badge badge-light-{{ $lga->status == 'approved' ? 'success' : ($lga->status == 'pending' || $lga->status == 'pending_delete' ? 'warning' : 'danger') }}">
                                            {{ ucfirst(str_replace('_', ' ', $lga->status)) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-3 pb-5">
                                <div class="d-flex flex-stack flex-wrap gap-3">
                                    <div class="d-flex flex-column">
                                        <div class="text-muted fs-7">Actions</div>
                                        <div class="d-flex gap-2">
                                            <a href="#" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#kt_lga_view_modal{{ $lga->id }}">View</a>
                                            @can('edit-lga')
                                                <a href="#" class="btn btn-sm btn-light-warning" data-bs-toggle="modal" data-bs-target="#kt_lga_edit_modal{{ $lga->id }}">Edit</a>
                                            @endcan
                                            @can('delete-lga')
                                                <a href="#" class="btn btn-sm btn-light-danger" data-bs-toggle="modal" data-bs-target="#kt_lga_delete_modal{{ $lga->id }}">Delete</a>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <div class="text-muted fs-3">No LGAs found.</div>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>

        <!-- Create Modal -->
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
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search_lga');
            const itemsPerPageSelect = document.getElementById('items_per_page');
            const lgaTableBody = document.querySelector('#kt_lga_table tbody');
            const allRows = Array.from(lgaTableBody.querySelectorAll('tr'));
            const paginationLinks = document.getElementById('pagination_links');
            const paginationDescription = document.getElementById('pagination_description');

            let currentPage = 1;
            let itemsPerPage = parseInt(itemsPerPageSelect.value);
            let filteredRows = allRows;

            function reinitializeScripts() {
                // Re-initialize KTMenu for the dropdowns
                KTMenu.createInstances();

                // Re-attach event listeners for data-method links
                document.querySelectorAll('a[data-method]').forEach(function (link) {
                    // Prevent multiple listeners by checking for a marker
                    if (link.getAttribute('data-listener-attached')) {
                        return;
                    }
                    link.setAttribute('data-listener-attached', 'true');
                    link.addEventListener('click', function (e) {
                        e.preventDefault();
                        let method = e.currentTarget.getAttribute('data-method');
                        let action = e.currentTarget.getAttribute('href');
                        let confirmMessage = e.currentTarget.getAttribute('data-confirm');

                        if (confirm(confirmMessage)) {
                            let form = document.createElement('form');
                            form.setAttribute('method', 'POST');
                            form.setAttribute('action', action);

                            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            let csrfInput = document.createElement('input');
                            csrfInput.setAttribute('type', 'hidden');
                            csrfInput.setAttribute('name', '_token');
                            csrfInput.setAttribute('value', csrfToken);
                            form.appendChild(csrfInput);

                            if (method.toLowerCase() !== 'post') {
                                let methodInput = document.createElement('input');
                                methodInput.setAttribute('type', 'hidden');
                                methodInput.setAttribute('name', '_method');
                                methodInput.setAttribute('value', method);
                                form.appendChild(methodInput);
                            }

                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            }

            function renderTable() {
                lgaTableBody.innerHTML = '';
                const start = (currentPage - 1) * itemsPerPage;
                const end = itemsPerPage === -1 ? filteredRows.length : start + itemsPerPage;
                const paginatedRows = filteredRows.slice(start, end);

                paginatedRows.forEach(row => lgaTableBody.appendChild(row));

                const totalFiltered = filteredRows.length;
                const startEntry = totalFiltered > 0 ? start + 1 : 0;
                const endEntry = itemsPerPage === -1 ? totalFiltered : Math.min(start + itemsPerPage, totalFiltered);

                paginationDescription.textContent = `Showing ${startEntry} to ${endEntry} of ${totalFiltered} entries`;

                // Re-initialize scripts for new rows
                reinitializeScripts();
            }

            function renderPagination() {
                paginationLinks.innerHTML = '';
                if (itemsPerPage === -1) return;

                const totalPages = Math.ceil(filteredRows.length / itemsPerPage);

                if (totalPages <= 1) return;

                // Previous button
                const prevLi = document.createElement('li');
                prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
                const prevA = document.createElement('a');
                prevA.className = 'page-link';
                prevA.href = '#';
                prevA.textContent = 'Previous';
                prevA.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (currentPage > 1) {
                        currentPage--;
                        renderTable();
                        renderPagination();
                    }
                });
                prevLi.appendChild(prevA);
                paginationLinks.appendChild(prevLi);

                // Page numbers
                for (let i = 1; i <= totalPages; i++) {
                    const li = document.createElement('li');
                    li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                    const a = document.createElement('a');
                    a.className = 'page-link';
                    a.href = '#';
                    a.textContent = i;
                    a.addEventListener('click', (e) => {
                        e.preventDefault();
                        currentPage = i;
                        renderTable();
                        renderPagination();
                    });
                    li.appendChild(a);
                    paginationLinks.appendChild(li);
                }

                // Next button
                const nextLi = document.createElement('li');
                nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
                const nextA = document.createElement('a');
                nextA.className = 'page-link';
                nextA.href = '#';
                nextA.textContent = 'Next';
                nextA.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (currentPage < totalPages) {
                        currentPage++;
                        renderTable();
                        renderPagination();
                    }
                });
                nextLi.appendChild(nextA);
                paginationLinks.appendChild(nextLi);
            }

            function filterAndPaginate() {
                const searchTerm = searchInput.value.toLowerCase();
                filteredRows = allRows.filter(row => {
                    const lgaName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const lgaCode = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    return lgaName.includes(searchTerm) || lgaCode.includes(searchTerm);
                });
                currentPage = 1;
                renderTable();
                renderPagination();
            }

            searchInput.addEventListener('input', filterAndPaginate);

            itemsPerPageSelect.addEventListener('change', function () {
                const value = this.value;
                if (value === 'all') {
                    itemsPerPage = -1;
                } else {
                    itemsPerPage = parseInt(value);
                }
                currentPage = 1;
                renderTable();
                renderPagination();
            });

            // Initial render
            filterAndPaginate();
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