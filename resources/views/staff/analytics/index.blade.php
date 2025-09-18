@extends('layouts.staff')
@section('content')
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        <!--begin::Alerts-->
        @if (session('success'))
            <div class="alert alert-light-success alert-dismissible fade show mb-5 shadow-sm rounded" role="alert">
                <div class="d-flex align-items-center">
                    <i class="ki-duotone ki-check-circle fs-2 text-success me-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <span class="fw-semibold">{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-light-danger alert-dismissible fade show mb-5 shadow-sm rounded" role="alert">
                <div class="d-flex align-items-center">
                    <i class="ki-duotone ki-cross-circle fs-2 text-danger me-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <span class="fw-semibold">{{ session('error') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('info'))
            <div class="alert alert-light-info alert-dismissible fade show mb-5 shadow-sm rounded" role="alert">
                <div class="d-flex align-items-center">
                    <i class="ki-duotone ki-information-5 fs-2 text-info me-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    <span class="fw-semibold">{{ session('info') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <!--end::Alerts-->

        <!--begin::Filter Form-->
        <div class="card card-flush mb-8 shadow-sm rounded">
            <div class="card-header border-0 pt-6">
                <div class="card-title w-100 d-flex align-items-center justify-content-between flex-wrap">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">Analytics Dashboard</span>
                        <span class="text-gray-400 mt-1 fw-semibold fs-6">System overview and key metrics</span>
                    </h3>
                    <form id="analytics_filter_form" method="GET" class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <div class="w-150px">
                                <label for="start_date" class="form-label fs-7 fw-bold text-gray-600">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control form-control-sm form-control-solid" value="{{ request('start_date') }}" />
                            </div>
                            <div class="w-150px">
                                <label for="end_date" class="form-label fs-7 fw-bold text-gray-600">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="form-control form-control-sm form-control-solid" value="{{ request('end_date') }}" />
                            </div>
                            <div class="w-150px">
                                <label for="status_filter" class="form-label fs-7 fw-bold text-gray-600">Status</label>
                                <select name="status_filter" id="status_filter" class="form-select form-select-sm form-select-solid" data-control="select2" data-placeholder="All Statuses">
                                    <option value="">All</option>
                                    <option value="pending" {{ request('status_filter') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status_filter') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status_filter') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="overdue" {{ request('status_filter') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                    <option value="in_progress" {{ request('status_filter') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="resolved" {{ request('status_filter') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                            <a href="{{ route('staff.analytics.index') }}" class="btn btn-sm btn-light">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--end::Filter Form-->

        <!--begin::Applied Filters-->
        @if (request()->hasAny(['start_date', 'end_date', 'status_filter']))
            <div class="alert alert-light-info mb-5 shadow-sm rounded">
                <div class="d-flex align-items-center">
                    <i class="ki-duotone ki-information-5 fs-2 text-info me-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    <div>
                        <strong class="fw-bolder text-gray-800">Applied Filters:</strong>
                        @if (request('start_date')) Start Date: {{ request('start_date') }} @endif
                        @if (request('end_date')) | End Date: {{ request('end_date') }} @endif
                        @if (request('status_filter')) | Status: {{ ucfirst(request('status_filter')) }} @endif
                    </div>
                </div>
            </div>
        @endif
        <!--end::Applied Filters-->

        <!--begin::Row-->
        <div class="row g-5 g-xl-10 mb-xl-10">
            <!--begin::Col-->
            <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
                <!--begin::Card widget - Staff Stats-->
                <div class="card card-flush h-md-50 mb-5 mb-xl-10 shadow-sm rounded bg-light-primary bg-opacity-10 hover-shadow transition">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <div class="d-flex align-items-center">
                                <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ $stats['staff']['total'] }}</span>
                                <span class="badge badge-light-primary fs-base">
                                    <i class="ki-duotone ki-user fs-5 text-primary ms-n1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>Staff
                                </span>
                            </div>
                            <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Staff Members</span>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end pt-0">
                        <div class="d-flex align-items-center flex-column mt-3 w-100">
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-bolder fs-6 text-dark">{{ $stats['staff']['approved'] }} Approved</span>
                                <span class="fw-bold fs-6 text-gray-400">{{ $stats['staff']['total'] > 0 ? number_format(($stats['staff']['approved'] / $stats['staff']['total']) * 100, 1) : 0 }}%</span>
                            </div>
                            <div class="h-8px mx-3 w-100 bg-light-primary rounded">
                                <div class="bg-primary rounded h-8px" role="progressbar" style="width: {{ $stats['staff']['total'] > 0 ? number_format(($stats['staff']['approved'] / $stats['staff']['total']) * 100, 1) : 0 }}%;" aria-valuenow="{{ $stats['staff']['total'] > 0 ? number_format(($stats['staff']['approved'] / $stats['staff']['total']) * 100, 1) : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card widget - Staff Stats-->

                <!--begin::Card widget - Customers-->
                <div class="card card-flush h-md-50 mb-xl-10 shadow-sm rounded bg-light-primary bg-opacity-10 hover-shadow transition">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <div class="d-flex align-items-center">
                                <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ $stats['customers']['total'] }}</span>
                                <span class="badge badge-light-success fs-base">
                                    <i class="ki-duotone ki-people fs-5 text-success ms-n1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>Customers
                                </span>
                            </div>
                            <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Registered</span>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-4 d-flex flex-column">
                        <div class="d-flex flex-column content-justify-center w-100">
                            <div class="d-flex fs-6 fw-semibold align-items-center">
                                <div class="bullet w-8px h-6px rounded-2 bg-success me-3"></div>
                                <div class="text-gray-500 flex-grow-1 me-4">Approved</div>
                                <div class="fw-bolder text-gray-700 text-xxl-end">{{ $stats['customers']['approved'] }}</div>
                            </div>
                            <div class="d-flex fs-6 fw-semibold align-items-center my-3">
                                <div class="bullet w-8px h-6px rounded-2 bg-warning me-3"></div>
                                <div class="text-gray-500 flex-grow-1 me-4">Pending</div>
                                <div class="fw-bolder text-gray-700 text-xxl-end">{{ $stats['customers']['pending'] }}</div>
                            </div>
                            <div class="d-flex fs-6 fw-semibold align-items-center">
                                <div class="bullet w-8px h-6px rounded-2 bg-danger me-3"></div>
                                <div class="text-gray-500 flex-grow-1 me-4">Rejected</div>
                                <div class="fw-bolder text-gray-700 text-xxl-end">{{ $stats['customers']['rejected'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card widget - Customers-->
            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
                <!--begin::Card widget - Bills-->
                <div class="card card-flush h-md-50 mb-5 mb-xl-10 shadow-sm rounded bg-light-primary bg-opacity-10 hover-shadow transition">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <div class="d-flex align-items-center">
                                <span class="fs-4 fw-semibold text-gray-400 me-1 align-self-start">₦</span>
                                <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($stats['bills']['total_amount'], 0) }}</span>
                                <span class="badge badge-light-info fs-base">
                                    <i class="ki-duotone ki-document fs-5 text-info ms-n1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>Bills
                                </span>
                            </div>
                            <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Bill Amount</span>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-4 d-flex flex-column">
                        <div class="d-flex flex-column content-justify-center w-100">
                            <div class="d-flex fs-6 fw-semibold align-items-center">
                                <div class="bullet w-8px h-6px rounded-2 bg-info me-3"></div>
                                <div class="text-gray-500 flex-grow-1 me-4">Total Bills</div>
                                <div class="fw-bolder text-gray-700 text-xxl-end">{{ $stats['bills']['total'] }}</div>
                            </div>
                            <div class="d-flex fs-6 fw-semibold align-items-center my-3">
                                <div class="bullet w-8px h-6px rounded-2 bg-warning me-3"></div>
                                <div class="text-gray-500 flex-grow-1 me-4">Pending</div>
                                <div class="fw-bolder text-gray-700 text-xxl-end">{{ $stats['bills']['pending'] }}</div>
                            </div>
                            <div class="d-flex fs-6 fw-semibold align-items-center">
                                <div class="bullet w-8px h-6px rounded-2 bg-danger me-3"></div>
                                <div class="text-gray-500 flex-grow-1 me-4">Overdue</div>
                                <div class="fw-bolder text-gray-700 text-xxl-end">{{ $stats['bills']['overdue'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card widget - Bills-->

                <!--begin::Card widget - Payments-->
                <div class="card card-flush h-md-50 mb-xl-10 shadow-sm rounded bg-light-primary bg-opacity-10 hover-shadow transition">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <div class="d-flex align-items-center">
                                <span class="fs-4 fw-semibold text-gray-400 me-1 align-self-start">₦</span>
                                <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($stats['payments']['total_amount'], 0) }}</span>
                                <span class="badge badge-light-success fs-base">
                                    <i class="ki-duotone ki-wallet fs-5 text-success ms-n1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>Payments
                                </span>
                            </div>
                            <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Received</span>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end pt-0">
                        <div class="d-flex align-items-center flex-column mt-3 w-100">
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-bolder fs-6 text-dark">{{ $stats['payments']['successful'] }} Successful</span>
                                <span class="fw-bold fs-6 text-gray-400">{{ $stats['payments']['total'] > 0 ? number_format(($stats['payments']['successful'] / $stats['payments']['total']) * 100, 1) : 0 }}%</span>
                            </div>
                            <div class="h-8px mx-3 w-100 bg-light-success rounded">
                                <div class="bg-success rounded h-8px" role="progressbar" style="width: {{ $stats['payments']['total'] > 0 ? number_format(($stats['payments']['successful'] / $stats['payments']['total']) * 100, 1) : 0 }}%;" aria-valuenow="{{ $stats['payments']['total'] > 0 ? number_format(($stats['payments']['successful'] / $stats['payments']['total']) * 100, 1) : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card widget - Payments-->
            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
                <!--begin::Card widget - Complaints-->
                <div class="card card-flush h-md-50 mb-5 mb-xl-10 shadow-sm rounded bg-light-primary bg-opacity-10 hover-shadow transition">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <div class="d-flex align-items-center">
                                <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ $stats['complaints']['total'] }}</span>
                                <span class="badge badge-light-warning fs-base">
                                    <i class="ki-duotone ki-information fs-5 text-warning ms-n1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>Issues
                                </span>
                            </div>
                            <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Complaints</span>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-4 d-flex flex-column">
                        <div class="d-flex flex-column content-justify-center w-100">
                            <div class="d-flex fs-6 fw-semibold align-items-center">
                                <div class="bullet w-8px h-6px rounded-2 bg-warning me-3"></div>
                                <div class="text-gray-500 flex-grow-1 me-4">Pending</div>
                                <div class="fw-bolder text-gray-700 text-xxl-end">{{ $stats['complaints']['pending'] }}</div>
                            </div>
                            <div class="d-flex fs-6 fw-semibold align-items-center my-3">
                                <div class="bullet w-8px h-6px rounded-2 bg-primary me-3"></div>
                                <div class="text-gray-500 flex-grow-1 me-4">In Progress</div>
                                <div class="fw-bolder text-gray-700 text-xxl-end">{{ $stats['complaints']['in_progress'] }}</div>
                            </div>
                            <div class="d-flex fs-6 fw-semibold align-items-center">
                                <div class="bullet w-8px h-6px rounded-2 bg-success me-3"></div>
                                <div class="text-gray-500 flex-grow-1 me-4">Resolved</div>
                                <div class="fw-bolder text-gray-700 text-xxl-end">{{ $stats['complaints']['resolved'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card widget - Complaints-->

                <!--begin::Card widget - Pending Updates-->
                <div class="card card-flush h-md-50 mb-xl-10 shadow-sm rounded bg-light-primary bg-opacity-10 hover-shadow transition">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ $stats['pending_updates']['total'] }}</span>
                            <span class="text-gray-400 pt-1 fw-semibold fs-6">Pending Updates</span>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end pt-0">
                        <div class="d-flex align-items-center flex-column mt-3 w-100">
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-bolder fs-6 text-dark">{{ $stats['pending_updates']['pending'] }} Require Review</span>
                                <span class="fw-bold fs-6 text-gray-400">{{ $stats['pending_updates']['total'] > 0 ? number_format(($stats['pending_updates']['pending'] / $stats['pending_updates']['total']) * 100, 1) : 0 }}%</span>
                            </div>
                            <div class="h-8px mx-3 w-100 bg-light-warning rounded">
                                <div class="bg-warning rounded h-8px" role="progressbar" style="width: {{ $stats['pending_updates']['total'] > 0 ? number_format(($stats['pending_updates']['pending'] / $stats['pending_updates']['total']) * 100, 1) : 0 }}%;" aria-valuenow="{{ $stats['pending_updates']['total'] > 0 ? number_format(($stats['pending_updates']['pending'] / $stats['pending_updates']['total']) * 100, 1) : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card widget - Pending Updates-->
            </div>
            <!--end::Col-->

            <!--begin::Col - System Stats-->
            <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
                <div class="card card-flush h-md-100 shadow-sm rounded bg-light-primary bg-opacity-10 hover-shadow transition">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-6 fw-bolder text-gray-800 d-block mb-2">System Overview</span>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-4 d-flex flex-column">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="d-flex flex-column align-items-center text-center p-3 bg-light-primary rounded">
                                    <span class="fs-1 fw-bold text-primary">{{ $stats['tariffs']['total'] }}</span>
                                    <span class="text-gray-600 fs-7 fw-semibold">Tariffs</span>
                                    <span class="text-gray-400 fs-8">{{ $stats['tariffs']['approved'] }} Approved</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex flex-column align-items-center text-center p-3 bg-light-success rounded">
                                    <span class="fs-1 fw-bold text-success">{{ $stats['categories']['total'] }}</span>
                                    <span class="text-gray-600 fs-7 fw-semibold">Categories</span>
                                    <span class="text-gray-400 fs-8">{{ $stats['categories']['approved'] }} Approved</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex flex-column align-items-center text-center p-3 bg-light-info rounded">
                                    <span class="fs-1 fw-bold text-info">{{ $stats['lgas']['total'] }}</span>
                                    <span class="text-gray-600 fs-7 fw-semibold">LGAs</span>
                                    <span class="text-gray-400 fs-8">{{ $stats['lgas']['approved'] }} Approved</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex flex-column align-items-center text-center p-3 bg-light-warning rounded">
                                    <span class="fs-1 fw-bold text-warning">{{ $stats['wards']['total'] }}</span>
                                    <span class="text-gray-600 fs-7 fw-semibold">Wards</span>
                                    <span class="text-gray-400 fs-8">{{ $stats['wards']['approved'] }} Approved</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex flex-column align-items-center text-center p-3 bg-light-secondary rounded">
                                    <span class="fs-1 fw-bold text-secondary">{{ $stats['areas']['total'] }}</span>
                                    <span class="text-gray-600 fs-7 fw-semibold">Areas</span>
                                    <span class="text-gray-400 fs-8">{{ $stats['areas']['approved'] }} Approved</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Col - System Stats-->
        </div>
        <!--end::Row-->

        <!--begin::Charts Row-->
        <div class="row g-5 g-xl-10 mb-xl-10">
            <!--begin::Chart widget - Billing Trends-->
            <div class="col-xl-6 mb-5 mb-xl-10">
                <div class="card card-flush overflow-hidden h-md-100 shadow-sm rounded bg-light-primary bg-opacity-10 hover-shadow transition">
                    <div class="card-header py-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Monthly Billing Trends</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Bill generation over time</span>
                        </h3>
                        <div class="card-toolbar">
                            <button class="btn btn-icon btn-color-gray-400 btn-active-color-primary justify-content-end" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-bs-toggle="tooltip" title="Quick Actions">
                                <i class="ki-duotone ki-dots-square fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </button>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <div class="menu-content fs-6 text-dark fw-bold px-3 py-4">Quick Actions</div>
                                </div>
                                <div class="separator mb-3 opacity-75"></div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('staff.analytics.report') }}" class="menu-link px-3">Generate Report</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('staff.analytics.export.csv') }}" class="menu-link px-3">Export to CSV</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('staff.analytics.details') }}" class="menu-link px-3">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body d-flex justify-content-between flex-column pb-1 px-0">
                        <div class="px-9 mb-5">
                            <div class="d-flex mb-2">
                                <span class="fs-4 fw-semibold text-gray-400 me-1">₦</span>
                                <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2">{{ number_format($stats['bills']['total_amount'], 0) }}</span>
                            </div>
                            <span class="fs-6 fw-semibold text-gray-400">Total Bills Generated</span>
                        </div>
                        <div class="px-4 pe-6">
                            <canvas id="billingChart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Chart widget - Billing Trends-->

            <!--begin::Chart widget - Payment Trends-->
            <div class="col-xl-6 mb-5 mb-xl-10">
                <div class="card card-flush overflow-hidden h-md-100 shadow-sm rounded bg-light-primary bg-opacity-10 hover-shadow transition">
                    <div class="card-header py-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Monthly Payment Trends</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Payment collection over time</span>
                        </h3>
                        <div class="card-toolbar">
                            <button class="btn btn-icon btn-color-gray-400 btn-active-color-primary justify-content-end" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-bs-toggle="tooltip" title="Quick Actions">
                                <i class="ki-duotone ki-dots-square fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </button>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <div class="menu-content fs-6 text-dark fw-bold px-3 py-4">Quick Actions</div>
                                </div>
                                <div class="separator mb-3 opacity-75"></div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('staff.analytics.report') }}" class="menu-link px-3">Generate Report</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('staff.analytics.export.csv') }}" class="menu-link px-3">Export to CSV</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('staff.analytics.details') }}" class="menu-link px-3">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body d-flex justify-content-between flex-column pb-1 px-0">
                        <div class="px-9 mb-5">
                            <div class="d-flex mb-2">
                                <span class="fs-4 fw-semibold text-gray-400 me-1">₦</span>
                                <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2">{{ number_format($stats['payments']['total_amount'], 0) }}</span>
                            </div>
                            <span class="fs-6 fw-semibold text-gray-400">Total Payments Received</span>
                        </div>
                        <div class="px-4 pe-6">
                            <canvas id="paymentChart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Chart widget - Payment Trends-->
        </div>
        <!--end::Charts Row-->

        <!--begin::Charts Row 2-->
        <div class="row g-5 g-xl-10 mb-xl-10">
            <!--begin::Chart widget - Complaint Trends-->
            <div class="col-xl-6 mb-5 mb-xl-10">
                <div class="card card-flush overflow-hidden h-md-100 shadow-sm rounded bg-light-primary bg-opacity-10 hover-shadow transition">
                    <div class="card-header py-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Complaint Status Trends</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Customer complaints over time</span>
                        </h3>
                        <div class="card-toolbar">
                            <button class="btn btn-icon btn-color-gray-400 btn-active-color-primary justify-content-end" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-bs-toggle="tooltip" title="Quick Actions">
                                <i class="ki-duotone ki-dots-square fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </button>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <div class="menu-content fs-6 text-dark fw-bold px-3 py-4">Quick Actions</div>
                                </div>
                                <div class="separator mb-3 opacity-75"></div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('staff.analytics.report') }}" class="menu-link px-3">Generate Report</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('staff.analytics.export.csv') }}" class="menu-link px-3">Export to CSV</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('staff.analytics.details') }}" class="menu-link px-3">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body d-flex justify-content-between flex-column pb-1 px-0">
                        <div class="px-4 pe-6">
                            <canvas id="complaintChart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Chart widget - Complaint Trends-->

            <!--begin::Chart widget - Tariffs by Category-->
            <div class="col-xl-6 mb-5 mb-xl-10">
                <div class="card card-flush overflow-hidden h-md-100 shadow-sm rounded bg-light-primary bg-opacity-10 hover-shadow transition">
                    <div class="card-header py-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Tariffs by Category</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Distribution of tariff types</span>
                        </h3>
                        <div class="card-toolbar">
                            <button class="btn btn-icon btn-color-gray-400 btn-active-color-primary justify-content-end" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-bs-toggle="tooltip" title="Quick Actions">
                                <i class="ki-duotone ki-dots-square fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </button>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <div class="menu-content fs-6 text-dark fw-bold px-3 py-4">Quick Actions</div>
                                </div>
                                <div class="separator mb-3 opacity-75"></div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('staff.analytics.report') }}" class="menu-link px-3">Generate Report</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('staff.analytics.export.csv') }}" class="menu-link px-3">Export to CSV</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('staff.analytics.details') }}" class="menu-link px-3">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body d-flex justify-content-between flex-column pb-1 px-0">
                        <div class="px-4 pe-6">
                            <canvas id="tariffCategoryChart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Chart widget - Tariffs by Category-->
        </div>
        <!--end::Charts Row 2-->

        <!--begin::Charts Row 3-->
        <div class="row g-5 g-xl-10 mb-xl-10">
            <!--begin::Chart widget - Customers by Category-->
            <div class="col-xl-6 mb-5 mb-xl-10">
                <div class="card card-flush overflow-hidden h-md-100 shadow-sm rounded bg-light-primary bg-opacity-10 hover-shadow transition">
                    <div class="card-header py-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Customers by Category</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Customer distribution by category</span>
                        </h3>
                        <div class="card-toolbar">
                            <button class="btn btn-icon btn-color-gray-400 btn-active-color-primary justify-content-end" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-bs-toggle="tooltip" title="Quick Actions">
                                <i class="ki-duotone ki-dots-square fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </button>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <div class="menu-content fs-6 text-dark fw-bold px-3 py-4">Quick Actions</div>
                                </div>
                                <div class="separator mb-3 opacity-75"></div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('staff.analytics.report') }}" class="menu-link px-3">Generate Report</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('staff.analytics.export.csv') }}" class="menu-link px-3">Export to CSV</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('staff.analytics.details') }}" class="menu-link px-3">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body d-flex justify-content-between flex-column pb-1 px-0">
                        <div class="px-4 pe-6">
                            <canvas id="customerCategoryChart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Chart widget - Customers by Category-->

            <!--begin::Chart widget - Customers by Tariff-->
            <div class="col-xl-6 mb-5 mb-xl-10">
                <div class="card card-flush overflow-hidden h-md-100 shadow-sm rounded bg-light-primary bg-opacity-10 hover-shadow transition">
                    <div class="card-header py-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Customers by Tariff</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Customer distribution by tariff</span>
                        </h3>
                        <div class="card-toolbar">
                            <button class="btn btn-icon btn-color-gray-400 btn-active-color-primary justify-content-end" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-bs-toggle="tooltip" title="Quick Actions">
                                <i class="ki-duotone ki-dots-square fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </button>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <div class="menu-content fs-6 text-dark fw-bold px-3 py-4">Quick Actions</div>
                                </div>
                                <div class="separator mb-3 opacity-75"></div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('staff.analytics.report') }}" class="menu-link px-3">Generate Report</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('staff.analytics.export.csv') }}" class="menu-link px-3">Export to CSV</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('staff.analytics.details') }}" class="menu-link px-3">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body d-flex justify-content-between flex-column pb-1 px-0">
                        <div class="px-4 pe-6">
                            <canvas id="customerTariffChart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Chart widget - Customers by Tariff-->
        </div>
        <!--end::Charts Row 3-->

        <!--begin::Charts Row 4-->
        <div class="row g-5 g-xl-10 mb-xl-10">
            <!--begin::Chart widget - Customers by LGA-->
            <div class="col-xl-12 mb-5 mb-xl-10">
                <div class="card card-flush overflow-hidden h-md-100 shadow-sm rounded bg-light-primary bg-opacity-10 hover-shadow transition">
                    <div class="card-header py-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Customers by LGA</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Geographic distribution of customers</span>
                        </h3>
                        <div class="card-toolbar">
                            <button class="btn btn-icon btn-color-gray-400 btn-active-color-primary justify-content-end" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-bs-toggle="tooltip" title="Quick Actions">
                                <i class="ki-duotone ki-dots-square fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </button>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <div class="menu-content fs-6 text-dark fw-bold px-3 py-4">Quick Actions</div>
                                </div>
                                <div class="separator mb-3 opacity-75"></div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('staff.analytics.report') }}" class="menu-link px-3">Generate Report</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('staff.analytics.export.csv') }}" class="menu-link px-3">Export to CSV</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('staff.analytics.details') }}" class="menu-link px-3">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body d-flex justify-content-between flex-column pb-1 px-0">
                        <div class="px-4 pe-6">
                            <canvas id="customerLgaChart" style="height: 400px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Chart widget - Customers by LGA-->
        </div>
        <!--end::Charts Row 4-->
    </div>
    <!--end::Container-->
@endsection

@section('styles')
    <style>
        .hover-shadow {
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }
        .hover-shadow:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
            transform: translateY(-2px);
        }
        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        .btn-light-primary {
            background-color: #e6f0ff;
            color: #0d6efd;
        }
        .btn-light-primary:hover {
            background-color: #d0e4ff;
        }
        .transition {
            transition: all 0.3s ease;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@2.0.1/dist/chartjs-plugin-zoom.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Debug: Check if Chart.js is loaded
            if (typeof Chart === 'undefined') {
                console.error('Chart.js not loaded. Please check the CDN.');
                alert('Error: Chart.js library failed to load. Please refresh the page or check your internet connection.');
                return;
            }

            // Initialize Select2 for status filter
            $('#status_filter').select2({
                minimumResultsForSearch: 10,
                placeholder: 'All Statuses',
                dropdownCssClass: 'custom-select2-dropdown'
            });

            // Submit form on filter change with debounce
            let filterTimeout;
            $('#start_date, #end_date, #status_filter').on('change keyup', function() {
                clearTimeout(filterTimeout);
                filterTimeout = setTimeout(function() {
                    $('#analytics_filter_form').submit();
                }, 500);
            });

            // Chart data
            const months = @json($months);
            const billAmounts = @json($billAmounts);
            const paymentAmounts = @json($paymentAmounts);
            const complaintCounts = @json($complaintCounts);
            const tariffByCategory = @json($tariffByCategory);
            const customersByCategory = @json($customersByCategory);
            const customersByTariff = @json($customersByTariff);
            const customersByLga = @json($customersByLga);

            // Debug: Log chart data
            console.log('Chart Data:', {
                months: months,
                billAmounts: billAmounts,
                paymentAmounts: paymentAmounts,
                complaintCounts: complaintCounts,
                tariffByCategory: tariffByCategory,
                customersByCategory: customersByCategory,
                customersByTariff: customersByTariff,
                customersByLga: customersByLga
            });

            // Validate data
            if (!months || !Array.isArray(billAmounts) || !Array.isArray(paymentAmounts) || !Array.isArray(complaintCounts)) {
                console.error('Invalid chart data. Ensure controller is passing valid arrays.');
                alert('Error: Invalid data for charts. Please check filters or contact support.');
                return;
            }

            // Color palette
            const colors = [
                '#0d6efd', '#28a745', '#ffc107', '#dc3545', '#6f42c1',
                '#17a2b8', '#fd7e14', '#6610f2', '#20c997', '#e83e8c'
            ];

            // Chart.js default options
            Chart.defaults.font.family = 'Inter, Helvetica, "sans-serif"';
            Chart.defaults.color = '#7e8299';
            Chart.defaults.borderColor = '#eff2f5';

            // Common zoom options
            const zoomOptions = {
                zoom: {
                    wheel: {
                        enabled: true
                    },
                    pinch: {
                        enabled: true
                    },
                    drag: {
                        enabled: true,
                        backgroundColor: 'rgba(13, 110, 253, 0.3)',
                        borderColor: '#0d6efd',
                        borderWidth: 1
                    },
                    mode: 'xy',
                    sensitivity: 3
                },
                pan: {
                    enabled: true,
                    mode: 'xy'
                }
            };

            // Initialize Billing Chart (Line)
            const billingCtx = document.getElementById('billingChart').getContext('2d');
            if (months.length > 0) {
                new Chart(billingCtx, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [{
                            label: 'Billing Amount (₦)',
                            data: billAmounts,
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13, 110, 253, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#0d6efd',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                title: { display: true, text: 'Amount (₦)' },
                                grid: { color: '#eff2f5', drawBorder: false }
                            },
                            x: { 
                                title: { display: true, text: 'Month' },
                                grid: { display: false }
                            }
                        },
                        plugins: { 
                            legend: { 
                                position: 'top',
                                labels: { usePointStyle: true, padding: 20 }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                cornerRadius: 8,
                                padding: 12
                            },
                            zoom: zoomOptions.zoom,
                            pan: zoomOptions.pan
                        }
                    }
                });
            } else {
                billingCtx.canvas.parentNode.innerHTML = '<div class="text-center text-muted p-10"><i class="ki-duotone ki-chart-line fs-3x text-gray-300 mb-4"><span class="path1"></span><span class="path2"></span></i><br/>No billing data available. Try adjusting filters or adding bill records.</div>';
            }

            // Initialize Payment Chart (Line)
            const paymentCtx = document.getElementById('paymentChart').getContext('2d');
            if (months.length > 0) {
                new Chart(paymentCtx, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [{
                            label: 'Payment Amount (₦)',
                            data: paymentAmounts,
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40, 167, 69, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#28a745',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                title: { display: true, text: 'Amount (₦)' },
                                grid: { color: '#eff2f5', drawBorder: false }
                            },
                            x: { 
                                title: { display: true, text: 'Month' },
                                grid: { display: false }
                            }
                        },
                        plugins: { 
                            legend: { 
                                position: 'top',
                                labels: { usePointStyle: true, padding: 20 }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                cornerRadius: 8,
                                padding: 12
                            },
                            zoom: zoomOptions.zoom,
                            pan: zoomOptions.pan
                        }
                    }
                });
            } else {
                paymentCtx.canvas.parentNode.innerHTML = '<div class="text-center text-muted p-10"><i class="ki-duotone ki-wallet fs-3x text-gray-300 mb-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i><br/>No payment data available. Try adjusting filters or adding payment records.</div>';
            }

            // Initialize Complaint Chart (Bar)
            const complaintCtx = document.getElementById('complaintChart').getContext('2d');
            if (months.length > 0) {
                new Chart(complaintCtx, {
                    type: 'bar',
                    data: {
                        labels: months,
                        datasets: [{
                            label: 'Complaints',
                            data: complaintCounts,
                            backgroundColor: 'rgba(255, 193, 7, 0.8)',
                            borderColor: '#ffc107',
                            borderWidth: 0,
                            borderRadius: 4,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                title: { display: true, text: 'Number of Complaints' },
                                grid: { color: '#eff2f5', drawBorder: false }
                            },
                            x: { 
                                title: { display: true, text: 'Month' },
                                grid: { display: false }
                            }
                        },
                        plugins: { 
                            legend: { 
                                position: 'top',
                                labels: { usePointStyle: true, padding: 20 }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                cornerRadius: 8,
                                padding: 12
                            },
                            zoom: zoomOptions.zoom,
                            pan: zoomOptions.pan
                        }
                    }
                });
            } else {
                complaintCtx.canvas.parentNode.innerHTML = '<div class="text-center text-muted p-10"><i class="ki-duotone ki-information fs-3x text-gray-300 mb-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i><br/>No complaint data available. Try adjusting filters or adding complaint records.</div>';
            }

            // Initialize Tariff by Category Chart (Doughnut)
            const tariffCategoryCtx = document.getElementById('tariffCategoryChart').getContext('2d');
            if (Object.keys(tariffByCategory).length) {
                new Chart(tariffCategoryCtx, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(tariffByCategory),
                        datasets: [{
                            data: Object.values(tariffByCategory),
                            backgroundColor: colors,
                            borderColor: '#ffffff',
                            borderWidth: 3,
                            hoverBorderWidth: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '60%',
                        plugins: {
                            legend: { 
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 15,
                                    generateLabels: function(chart) {
                                        const data = chart.data;
                                        if (data.labels.length && data.datasets.length) {
                                            return data.labels.map(function(label, i) {
                                                const ds = data.datasets[0];
                                                return {
                                                    text: label + ' (' + ds.data[i] + ')',
                                                    fillStyle: ds.backgroundColor[i],
                                                    pointStyle: 'circle',
                                                    hidden: false,
                                                    index: i
                                                };
                                            });
                                        }
                                        return [];
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                cornerRadius: 8,
                                padding: 12
                            }
                        }
                    }
                });
            } else {
                tariffCategoryCtx.canvas.parentNode.innerHTML = '<div class="text-center text-muted p-10"><i class="ki-duotone ki-chart-pie fs-3x text-gray-300 mb-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i><br/>No tariff data available. Try adjusting filters or adding tariff records.</div>';
            }

            // Initialize Customers by Category Chart (Pie)
            const customerCategoryCtx = document.getElementById('customerCategoryChart').getContext('2d');
            if (Object.keys(customersByCategory).length) {
                new Chart(customerCategoryCtx, {
                    type: 'pie',
                    data: {
                        labels: Object.keys(customersByCategory),
                        datasets: [{
                            data: Object.values(customersByCategory),
                            backgroundColor: colors,
                            borderColor: '#ffffff',
                            borderWidth: 2,
                            hoverBorderWidth: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { 
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 15,
                                    generateLabels: function(chart) {
                                        const data = chart.data;
                                        if (data.labels.length && data.datasets.length) {
                                            return data.labels.map(function(label, i) {
                                                const ds = data.datasets[0];
                                                return {
                                                    text: label + ' (' + ds.data[i] + ')',
                                                    fillStyle: ds.backgroundColor[i],
                                                    pointStyle: 'circle',
                                                    hidden: false,
                                                    index: i
                                                };
                                            });
                                        }
                                        return [];
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                cornerRadius: 8,
                                padding: 12
                            }
                        }
                    }
                });
            } else {
                customerCategoryCtx.canvas.parentNode.innerHTML = '<div class="text-center text-muted p-10"><i class="ki-duotone ki-chart-pie fs-3x text-gray-300 mb-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i><br/>No customer category data available. Try adjusting filters or adding customer records.</div>';
            }

            // Initialize Customers by Tariff Chart (Doughnut)
            const customerTariffCtx = document.getElementById('customerTariffChart').getContext('2d');
            if (Object.keys(customersByTariff).length) {
                new Chart(customerTariffCtx, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(customersByTariff),
                        datasets: [{
                            data: Object.values(customersByTariff),
                            backgroundColor: colors,
                            borderColor: '#ffffff',
                            borderWidth: 3,
                            hoverBorderWidth: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '60%',
                        plugins: {
                            legend: { 
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 15,
                                    generateLabels: function(chart) {
                                        const data = chart.data;
                                        if (data.labels.length && data.datasets.length) {
                                            return data.labels.map(function(label, i) {
                                                const ds = data.datasets[0];
                                                return {
                                                    text: label + ' (' + ds.data[i] + ')',
                                                    fillStyle: ds.backgroundColor[i],
                                                    pointStyle: 'circle',
                                                    hidden: false,
                                                    index: i
                                                };
                                            });
                                        }
                                        return [];
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                cornerRadius: 8,
                                padding: 12
                            }
                        }
                    }
                });
            } else {
                customerTariffCtx.canvas.parentNode.innerHTML = '<div class="text-center text-muted p-10"><i class="ki-duotone ki-chart-pie fs-3x text-gray-300 mb-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i><br/>No customer tariff data available. Try adjusting filters or adding customer records.</div>';
            }

            // Initialize Customers by LGA Chart (Bar)
            const customerLgaCtx = document.getElementById('customerLgaChart').getContext('2d');
            if (Object.keys(customersByLga).length) {
                new Chart(customerLgaCtx, {
                    type: 'bar',
                    data: {
                        labels: Object.keys(customersByLga),
                        datasets: [{
                            label: 'Customers',
                            data: Object.values(customersByLga),
                            backgroundColor: 'rgba(13, 110, 253, 0.8)',
                            borderColor: '#0d6efd',
                            borderWidth: 0,
                            borderRadius: 4,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                title: { display: true, text: 'Number of Customers' },
                                grid: { color: '#eff2f5', drawBorder: false }
                            },
                            x: { 
                                title: { display: true, text: 'LGA' },
                                grid: { display: false }
                            }
                        },
                        plugins: { 
                            legend: { 
                                position: 'top',
                                labels: { usePointStyle: true, padding: 20 }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                cornerRadius: 8,
                                padding: 12
                            },
                            zoom: zoomOptions.zoom,
                            pan: zoomOptions.pan
                        }
                    }
                });
            } else {
                customerLgaCtx.canvas.parentNode.innerHTML = '<div class="text-center text-muted p-10"><i class="ki-duotone ki-chart-bar fs-3x text-gray-300 mb-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i><br/>No customer LGA data available. Try adjusting filters or adding customer records.</div>';
            }
        });
    </script>
@endsection