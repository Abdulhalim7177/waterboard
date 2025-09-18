<!--begin::Stats Widget-->
<div class="row g-5 mb-8">
    <!--begin::Col-->
    <div class="col-md-3">
        <div class="card card-flush h-md-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <div class="d-flex flex-column">
                    <h4 class="fw-bold text-dark mb-3">Total Staff</h4>
                    <div class="d-flex align-items-center">
                        <span class="fs-1 fw-bold text-primary">{{ $stats['staff']['total'] ?? 0 }}</span>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-5">
                    <i class="ki-duotone ki-profile-user fs-2x text-primary me-3">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                    </i>
                    <span class="text-muted fs-7">All registered staff members</span>
                </div>
            </div>
        </div>
    </div>
    <!--end::Col-->

    <!--begin::Col-->
    <div class="col-md-3">
        <div class="card card-flush h-md-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <div class="d-flex flex-column">
                    <h4 class="fw-bold text-dark mb-3">Active Staff</h4>
                    <div class="d-flex align-items-center">
                        <span class="fs-1 fw-bold text-success">{{ $stats['staff']['approved'] ?? 0 }}</span>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-5">
                    <i class="ki-duotone ki-user-tick fs-2x text-success me-3">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <span class="text-muted fs-7">Currently active employees</span>
                </div>
            </div>
        </div>
    </div>
    <!--end::Col-->

    <!--begin::Col-->
    <div class="col-md-3">
        <div class="card card-flush h-md-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <div class="d-flex flex-column">
                    <h4 class="fw-bold text-dark mb-3">Total Customers</h4>
                    <div class="d-flex align-items-center">
                        <span class="fs-1 fw-bold text-info">{{ $stats['customers']['total'] ?? 0 }}</span>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-5">
                    <i class="ki-duotone ki-people fs-2x text-info me-3">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                        <span class="path5"></span>
                    </i>
                    <span class="text-muted fs-7">All registered customers</span>
                </div>
            </div>
        </div>
    </div>
    <!--end::Col-->

    <!--begin::Col-->
    <div class="col-md-3">
        <div class="card card-flush h-md-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <div class="d-flex flex-column">
                    <h4 class="fw-bold text-dark mb-3">Pending Changes</h4>
                    <div class="d-flex align-items-center">
                        <span class="fs-1 fw-bold text-warning">{{ $stats['pending_updates']['total'] ?? 0 }}</span>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-5">
                    <i class="ki-duotone ki-exclamation fs-2x text-warning me-3">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <span class="text-muted fs-7">Awaiting approval</span>
                </div>
            </div>
        </div>
    </div>
    <!--end::Col-->
</div>
<!--end::Stats Widget-->