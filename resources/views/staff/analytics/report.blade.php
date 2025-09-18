@extends('layouts.staff')

@section('content')
<div class="container-xxl">
    <!--begin::Toolbar-->
    @include('staff.partials.navigation')
    <!--end::Toolbar-->

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
            <div class="card-title">
                <h2>Analytics Report</h2>
                <p class="text-muted">Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
            </div>
            <!--end::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <a href="{{ route('staff.analytics.index') }}" class="btn btn-light-primary">
                    <i class="ki-duotone ki-arrow-left fs-2"></i>
                    Back to Dashboard
                </a>
                <button type="button" class="btn btn-light-primary ms-2" onclick="window.print()">
                    <i class="ki-duotone ki-printer fs-2"></i>
                    Print Report
                </button>
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Summary Statistics-->
            <div class="row g-5 mb-10">
                <!--begin::Col-->
                <div class="col-md-3">
                    <div class="card card-flush h-md-100">
                        <div class="card-body text-center">
                            <i class="ki-duotone ki-profile-user fs-3x text-primary mb-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                            <div class="fs-2hx fw-bold text-dark">{{ $data['stats']['staff']['total'] ?? 0 }}</div>
                            <div class="fs-6 fw-semibold text-muted">Total Staff</div>
                        </div>
                    </div>
                </div>
                <!--end::Col-->
                
                <!--begin::Col-->
                <div class="col-md-3">
                    <div class="card card-flush h-md-100">
                        <div class="card-body text-center">
                            <i class="ki-duotone ki-user fs-3x text-success mb-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <div class="fs-2hx fw-bold text-dark">{{ $data['stats']['customers']['total'] ?? 0 }}</div>
                            <div class="fs-6 fw-semibold text-muted">Total Customers</div>
                        </div>
                    </div>
                </div>
                <!--end::Col-->
                
                <!--begin::Col-->
                <div class="col-md-3">
                    <div class="card card-flush h-md-100">
                        <div class="card-body text-center">
                            <i class="ki-duotone ki-document fs-3x text-info mb-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <div class="fs-2hx fw-bold text-dark">{{ $data['stats']['bills']['total'] ?? 0 }}</div>
                            <div class="fs-6 fw-semibold text-muted">Total Bills</div>
                        </div>
                    </div>
                </div>
                <!--end::Col-->
                
                <!--begin::Col-->
                <div class="col-md-3">
                    <div class="card card-flush h-md-100">
                        <div class="card-body text-center">
                            <i class="ki-duotone ki-credit-cart fs-3x text-warning mb-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <div class="fs-2hx fw-bold text-dark">₦{{ number_format($data['stats']['payments']['total_amount'] ?? 0, 2) }}</div>
                            <div class="fs-6 fw-semibold text-muted">Total Payments</div>
                        </div>
                    </div>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Summary Statistics-->

            <!--begin::Detailed Statistics-->
            <div class="row g-5 mb-10">
                <!--begin::Col-->
                <div class="col-md-6">
                    <div class="card card-flush h-md-100">
                        <div class="card-header">
                            <h3 class="card-title">Staff Statistics</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-row-dashed align-middle gs-0 gy-4">
                                    <tbody>
                                        <tr>
                                            <td>Total Staff</td>
                                            <td class="text-end">{{ $data['stats']['staff']['total'] ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Approved Staff</td>
                                            <td class="text-end">{{ $data['stats']['staff']['approved'] ?? 0 }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Col-->
                
                <!--begin::Col-->
                <div class="col-md-6">
                    <div class="card card-flush h-md-100">
                        <div class="card-header">
                            <h3 class="card-title">Customer Statistics</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-row-dashed align-middle gs-0 gy-4">
                                    <tbody>
                                        <tr>
                                            <td>Total Customers</td>
                                            <td class="text-end">{{ $data['stats']['customers']['total'] ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Approved Customers</td>
                                            <td class="text-end">{{ $data['stats']['customers']['approved'] ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Pending Customers</td>
                                            <td class="text-end">{{ $data['stats']['customers']['pending'] ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Rejected Customers</td>
                                            <td class="text-end">{{ $data['stats']['customers']['rejected'] ?? 0 }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Detailed Statistics-->

            <!--begin::Financial Statistics-->
            <div class="row g-5 mb-10">
                <!--begin::Col-->
                <div class="col-md-6">
                    <div class="card card-flush h-md-100">
                        <div class="card-header">
                            <h3 class="card-title">Billing Statistics</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-row-dashed align-middle gs-0 gy-4">
                                    <tbody>
                                        <tr>
                                            <td>Total Bills</td>
                                            <td class="text-end">{{ $data['stats']['bills']['total'] ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Pending Bills</td>
                                            <td class="text-end">{{ $data['stats']['bills']['pending'] ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Overdue Bills</td>
                                            <td class="text-end">{{ $data['stats']['bills']['overdue'] ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Total Amount Billed</td>
                                            <td class="text-end">₦{{ number_format($data['stats']['bills']['total_amount'] ?? 0, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Col-->
                
                <!--begin::Col-->
                <div class="col-md-6">
                    <div class="card card-flush h-md-100">
                        <div class="card-header">
                            <h3 class="card-title">Payment Statistics</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-row-dashed align-middle gs-0 gy-4">
                                    <tbody>
                                        <tr>
                                            <td>Total Payments</td>
                                            <td class="text-end">{{ $data['stats']['payments']['total'] ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Successful Payments</td>
                                            <td class="text-end">{{ $data['stats']['payments']['successful'] ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Total Amount Paid</td>
                                            <td class="text-end">₦{{ number_format($data['stats']['payments']['total_amount'] ?? 0, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Financial Statistics-->

            <!--begin::Complaints Statistics-->
            <div class="row g-5 mb-10">
                <div class="col-md-12">
                    <div class="card card-flush h-md-100">
                        <div class="card-header">
                            <h3 class="card-title">Complaints Statistics</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-row-dashed align-middle gs-0 gy-4">
                                    <tbody>
                                        <tr>
                                            <td>Total Complaints</td>
                                            <td class="text-end">{{ $data['stats']['complaints']['total'] ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Pending Complaints</td>
                                            <td class="text-end">{{ $data['stats']['complaints']['pending'] ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <td>In Progress Complaints</td>
                                            <td class="text-end">{{ $data['stats']['complaints']['in_progress'] ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Resolved Complaints</td>
                                            <td class="text-end">{{ $data['stats']['complaints']['resolved'] ?? 0 }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Complaints Statistics-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</div>
@endsection

@section('styles')
<style>
@media print {
    .card-toolbar, 
    .btn, 
    .alert {
        display: none !important;
    }
    
    body {
        padding: 0;
        margin: 0;
    }
    
    .card {
        box-shadow: none;
        border: 1px solid #ddd;
    }
}
</style>
@endsection