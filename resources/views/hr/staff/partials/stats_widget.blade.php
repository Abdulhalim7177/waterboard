<!--begin::HR Staff Stats Widget-->
@if(isset($stats))
<div class="row g-5 mb-8">
    <div class="col-md-2">
        <div class="card card-flush h-md-100">
            <div class="card-body text-center">
                <h5 class="card-title">Total Staff</h5>
                <div class="display-6 fw-bold text-primary">{{ $stats['total'] ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-flush h-md-100">
            <div class="card-body text-center">
                <h5 class="card-title">Active</h5>
                <div class="display-6 fw-bold text-success">{{ $stats['active'] ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-flush h-md-100">
            <div class="card-body text-center">
                <h5 class="card-title">On Leave</h5>
                <div class="display-6 fw-bold text-warning">{{ $stats['on_leave'] ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-flush h-md-100">
            <div class="card-body text-center">
                <h5 class="card-title">Pending</h5>
                <div class="display-6 fw-bold text-info">{{ $stats['pending'] ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-flush h-md-100">
            <div class="card-body text-center">
                <h5 class="card-title">Suspended</h5>
                <div class="display-6 fw-bold text-danger">{{ $stats['suspended'] ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-flush h-md-100">
            <div class="card-body text-center">
                <h5 class="card-title">Terminated</h5>
                <div class="display-6 fw-bold text-secondary">{{ $stats['terminated'] ?? 0 }}</div>
            </div>
        </div>
    </div>
</div>
@endif
<!--end::HR Staff Stats Widget-->