<!--begin::Distribution Charts Widget-->
<div class="row g-5 mb-10">
    <!--begin::Col-->
    <div class="col-xl-6 mb-5 mb-xl-10">
        <div class="card card-flush h-md-100">
            <div class="card-header pt-7">
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
            <div class="card-body pt-0">
                <div class="pt-5">
                    <canvas id="complaintChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!--end::Col-->

    <!--begin::Col-->
    <div class="col-xl-6 mb-5 mb-xl-10">
        <div class="card card-flush h-md-100">
            <div class="card-header pt-7">
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
            <div class="card-body pt-0">
                <div class="pt-5">
                    <canvas id="tariffCategoryChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!--end::Col-->
</div>
<!--end::Distribution Charts Widget-->