@extends('layouts.staff')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" name="search_user" data-kt-audit-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Search by User" value="{{ request('search_user') }}" />
                    </div>
                    <!--end::Search-->
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-audit-table-toolbar="base">
                        <!--begin::Filter-->
                        <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <i class="ki-duotone ki-filter fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>Filter
                        </button>
                        <!--begin::Menu 1-->
                        <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true" id="kt-toolbar-filter">
                            <!--begin::Header-->
                            <div class="px-7 py-5">
                                <div class="fs-4 text-dark fw-bold">Filter Options</div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Separator-->
                            <div class="separator border-gray-200"></div>
                            <!--end::Separator-->
                            <!--begin::Content-->
                            <div class="px-7 py-5">
                                <!--begin::Form-->
                                <form method="GET" action="{{ route('staff.audits.index') }}">
                                    <!--begin::Input group-->
                                    <div class="mb-10">
                                        <label class="form-label fs-5 fw-semibold mb-3">Event:</label>
                                        <select name="event" class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-audit-table-filter="event" data-dropdown-parent="#kt-toolbar-filter">
                                            <option value="">All Events</option>
                                            <option value="created" {{ request('event') === 'created' ? 'selected' : '' }}>Created</option>
                                            <option value="updated" {{ request('event') === 'updated' ? 'selected' : '' }}>Updated</option>
                                            <option value="approved" {{ request('event') === 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="rejected" {{ request('event') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            <option value="deleted" {{ request('event') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                                            <option value="create_requested" {{ request('event') === 'create_requested' ? 'selected' : '' }}>Create Requested</option>
                                            <option value="update_requested" {{ request('event') === 'update_requested' ? 'selected' : '' }}>Update Requested</option>
                                            <option value="delete_requested" {{ request('event') === 'delete_requested' ? 'selected' : '' }}>Deletion Requested</option>
                                            <option value="deletion_approved" {{ request('event') === 'deletion_approved' ? 'selected' : '' }}>Deletion Approved</option>
                                            <option value="deletion_rejected" {{ request('event') === 'deletion_rejected' ? 'selected' : '' }}>Deletion Rejected</option>
                                            <option value="roles_assigned" {{ request('event') === 'roles_assigned' ? 'selected' : '' }}>Roles Assigned</option>
                                            <option value="roles_removed" {{ request('event') === 'roles_removed' ? 'selected' : '' }}>Roles Removed</option>
                                        </select>
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10">
                                        <label class="form-label fs-5 fw-semibold mb-3">Auditable Type:</label>
                                        <select name="search_model" class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-audit-table-filter="auditable_type" data-dropdown-parent="#kt-toolbar-filter">
                                            <option value="">All Types</option>
                                            <option value="App\Models\Staff" {{ request('search_model') === 'App\Models\Staff' ? 'selected' : '' }}>Staff</option>
                                            <option value="App\Models\Role" {{ request('search_model') === 'App\Models\Role' ? 'selected' : '' }}>Role</option>
                                            <option value="App\Models\Permission" {{ request('search_model') === 'App\Models\Permission' ? 'selected' : '' }}>Permission</option>
                                            <option value="App\Models\Lga" {{ request('search_model') === 'App\Models\Lga' ? 'selected' : '' }}>LGA</option>
                                            <option value="App\Models\Ward" {{ request('search_model') === 'App\Models\Ward' ? 'selected' : '' }}>Ward</option>
                                            <option value="App\Models\Area" {{ request('search_model') === 'App\Models\Area' ? 'selected' : '' }}>Area</option>
                                            <option value="App\Models\Category" {{ request('search_model') === 'App\Models\Category' ? 'selected' : '' }}>Category</option>
                                            <option value="App\Models\Tariff" {{ request('search_model') === 'App\Models\Tariff' ? 'selected' : '' }}>Tariff</option>
                                        </select>
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10">
                                        <label class="form-label fs-5 fw-semibold mb-3">Date From:</label>
                                        <input type="date" name="date_from" class="form-control form-control-solid" value="{{ request('date_from') }}" />
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10">
                                        <label class="form-label fs-5 fw-semibold mb-3">Date To:</label>
                                        <input type="date" name="date_to" class="form-control form-control-solid" value="{{ request('date_to') }}" />
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Actions-->
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('staff.audits.index') }}" class="btn btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Reset</a>
                                        <button type="submit" class="btn btn-primary" data-kt-menu-dismiss="true" data-kt-audit-table-filter="filter">Apply</button>
                                    </div>
                                    <!--end::Actions-->
                                </form>
                                <!--end::Form-->
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Menu 1-->
                        <!--end::Filter-->
                    </div>
                    <!--end::Toolbar-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Table-->
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_audits_table">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-50px">ID</th>
                                <th class="min-w-125px">Event</th>
                                <th class="min-w-125px">Auditable</th>
                                <th class="min-w-125px">User</th>
                                <th class="min-w-200px">Old Values</th>
                                <th class="min-w-200px">New Values</th>
                                <th class="min-w-125px">Related</th>
                                <th class="min-w-125px">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @forelse ($audits as $audit)
                                <tr>
                                    <td>{{ $audit->id }}</td>
                                    <td>{{ Str::title(str_replace('_', ' ', $audit->event)) }}</td>
                                    <td>{{ class_basename($audit->auditable_type) }} #{{ $audit->auditable_id }}</td>
                                    <td>
                                        @if ($audit->user)
                                            {{ class_basename($audit->user_type) }}: {{ $audit->user->name ?? $audit->user->email }}
                                        @else
                                            Unknown
                                        @endif
                                    </td>
                                    <td>
                                        @if ($audit->old_values)
                                            <pre class="text-break">{{ json_encode(json_decode($audit->old_values, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($audit->new_values)
                                            <pre class="text-break">{{ json_encode(json_decode($audit->new_values, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($audit->related_type)
                                            {{ class_basename($audit->related_type) }} #{{ $audit->related_id }}
                                        @else
                                            None
                                        @endif
                                    </td>
                                    <td>{{ $audit->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No audit logs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!--end::Table-->
                <!--begin::Pagination-->
                <div class="d-flex justify-content-end mt-5">
                    {{ $audits->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
                <!--end::Pagination-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Select2 for dropdowns
        $('[data-kt-select2="true"]').select2({
            dropdownParent: $('#kt-toolbar-filter')
        });

        // Server-side search on keyup with debounce
        let searchTimeout;
        const searchInput = document.querySelector('[data-kt-audit-table-filter="search"]');
        searchInput.addEventListener('keyup', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const value = this.value;
                const url = new URL(window.location);
                if (value) {
                    url.searchParams.set('search_user', value);
                } else {
                    url.searchParams.delete('search_user');
                }
                window.location.href = url.toString();
            }, 500); // 500ms debounce
        });
    });
</script>
@endpush

<style>
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .table th, .table td {
        white-space: nowrap;
    }
    pre.text-break {
        white-space: pre-wrap;
        word-wrap: break-word;
        max-width: 100%;
        font-size: 0.85rem;
    }
</style>
@endsection