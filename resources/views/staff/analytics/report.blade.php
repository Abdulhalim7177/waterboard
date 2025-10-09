@extends('layouts.staff')

@section('page_title')
    Analytics Report
@endsection

@section('page_description')
    Generated system analytics report
@endsection

@section('content')
<div class="container-xxl">
    <!--begin::Toolbar-->
    @include('staff.analytics.partials.navigation')
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
    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <!--end::Alerts-->

    <!--begin::Print Options Modal-->
    <div class="modal fade" id="printOptionsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Print Options</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="printSummaryStats" checked>
                        <label class="form-check-label" for="printSummaryStats">
                            Summary Statistics
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="printDetailedStats" checked>
                        <label class="form-check-label" for="printDetailedStats">
                            Detailed Statistics
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="printTrendData" checked>
                        <label class="form-check-label" for="printTrendData">
                            Monthly Trends Data
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="printDistributionData" checked>
                        <label class="form-check-label" for="printDistributionData">
                            Distribution Data
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="printAdditionalData" checked>
                        <label class="form-check-label" for="printAdditionalData">
                            Additional Distribution Data
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="printSelectedSections()">Print Selected</button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Print Options Modal-->

    <!--begin::Card-->
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <h2>Analytics Report</h2>
                <p class="text-muted">Generated on {{ now()->format('F j, Y \\a\\t g:i A') }}</p>
            </div>
            <!--end::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <a href="{{ route('staff.analytics.index') }}" class="btn btn-light-primary">
                    <i class="ki-duotone ki-arrow-left fs-2"></i>
                    Back to Dashboard
                </a>
                <button type="button" class="btn btn-light-primary ms-2" data-bs-toggle="modal" data-bs-target="#printOptionsModal">
                    <i class="ki-duotone ki-printer fs-2"></i>
                    Print Report
                </button>
                <a href="{{ route('staff.analytics.export.csv') }}" class="btn btn-light-success ms-2">
                    <i class="ki-duotone ki-exit-up fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Export CSV
                </a>
                <a href="{{ route('staff.analytics.export.excel') }}" class="btn btn-light-success ms-2">
                    <i class="ki-duotone ki-exit-up fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Export Excel
                </a>
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Summary Statistics-->
            <div class="row g-5 mb-10" id="summaryStatsSection">
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

            <!--begin::Detailed Statistics Tables-->
            <div class="row g-5 mb-10" id="detailedStatsSection">
                <!--begin::Col-->
                <div class="col-md-12">
                    <div class="card card-flush h-md-100">
                        <div class="card-header">
                            <h3 class="card-title">Detailed Statistics</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-row-dashed align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th>Category</th>
                                            <th class="text-end">Total</th>
                                            <th class="text-end">Approved</th>
                                            <th class="text-end">Pending</th>
                                            <th class="text-end">Rejected</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Staff</td>
                                            <td class="text-end">{{ $data['stats']['staff']['total'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['staff']['approved'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['staff']['pending'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['staff']['rejected'] ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Customers</td>
                                            <td class="text-end">{{ $data['stats']['customers']['total'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['customers']['approved'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['customers']['pending'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['customers']['rejected'] ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Bills</td>
                                            <td class="text-end">{{ $data['stats']['bills']['total'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['bills']['approved'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['bills']['pending'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['bills']['rejected'] ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Payments</td>
                                            <td class="text-end">{{ $data['stats']['payments']['total'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['payments']['successful'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['payments']['pending'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['payments']['rejected'] ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tariffs</td>
                                            <td class="text-end">{{ $data['stats']['tariffs']['total'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['tariffs']['approved'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['tariffs']['pending'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['tariffs']['rejected'] ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Categories</td>
                                            <td class="text-end">{{ $data['stats']['categories']['total'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['categories']['approved'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['categories']['pending'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['categories']['rejected'] ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <td>LGAs</td>
                                            <td class="text-end">{{ $data['stats']['lgas']['total'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['lgas']['approved'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['lgas']['pending'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['lgas']['rejected'] ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Wards</td>
                                            <td class="text-end">{{ $data['stats']['wards']['total'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['wards']['approved'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['wards']['pending'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['wards']['rejected'] ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <td>Areas</td>
                                            <td class="text-end">{{ $data['stats']['areas']['total'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['areas']['approved'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['areas']['pending'] ?? 0 }}</td>
                                            <td class="text-end">{{ $data['stats']['areas']['rejected'] ?? 0 }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Detailed Statistics Tables-->

            <!--begin::Trend Data Tables-->
            <div class="row g-5 mb-10" id="trendDataSection">
                <!--begin::Col-->
                <div class="col-md-12">
                    <div class="card card-flush h-md-100">
                        <div class="card-header">
                            <h3 class="card-title">Monthly Trends Data</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-row-dashed align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th>Month</th>
                                            <th class="text-end">Bills (₦)</th>
                                            <th class="text-end">Payments (₦)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < count($data['months']); $i++)
                                            <tr>
                                                <td>{{ $data['months'][$i] }}</td>
                                                <td class="text-end">₦{{ number_format($data['billAmounts'][$i] ?? 0, 2) }}</td>
                                                <td class="text-end">₦{{ number_format($data['paymentAmounts'][$i] ?? 0, 2) }}</td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Trend Data Tables-->

            <!--begin::Distribution Data Tables-->
            <div class="row g-5 mb-10" id="distributionDataSection">
                <!--begin::Col-->
                <div class="col-md-6">
                    <div class="card card-flush h-md-100">
                        <div class="card-header">
                            <h3 class="card-title">Customers by Category</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-row-dashed align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th>Category</th>
                                            <th class="text-end">Customers</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['customersByCategory'] as $category => $count)
                                            <tr>
                                                <td>{{ $category }}</td>
                                                <td class="text-end">{{ $count }}</td>
                                            </tr>
                                        @endforeach
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
                            <h3 class="card-title">Customers by Tariff</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-row-dashed align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th>Tariff</th>
                                            <th class="text-end">Customers</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['customersByTariff'] as $tariff => $count)
                                            <tr>
                                                <td>{{ $tariff }}</td>
                                                <td class="text-end">{{ $count }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Distribution Data Tables-->

            <!--begin::Additional Distribution Data Tables-->
            <div class="row g-5 mb-10" id="additionalDataSection">
                <!--begin::Col-->
                <div class="col-md-6">
                    <div class="card card-flush h-md-100">
                        <div class="card-header">
                            <h3 class="card-title">Tariffs by Category</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-row-dashed align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th>Category</th>
                                            <th class="text-end">Tariffs</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['tariffByCategory'] as $category => $count)
                                            <tr>
                                                <td>{{ $category }}</td>
                                                <td class="text-end">{{ $count }}</td>
                                            </tr>
                                        @endforeach
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
                            <h3 class="card-title">Customers by LGA</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-row-dashed align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th>LGA</th>
                                            <th class="text-end">Customers</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['customersByLga'] as $lga => $count)
                                            <tr>
                                                <td>{{ $lga }}</td>
                                                <td class="text-end">{{ $count }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Additional Distribution Data Tables-->
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
    .alert,
    .modal {
        display: none !important;
    }
    
    /* Specific section visibility based on what user selects to print */
    #summaryStatsSection:not(.print-include),
    #detailedStatsSection:not(.print-include),
    #trendDataSection:not(.print-include),
    #distributionDataSection:not(.print-include),
    #additionalDataSection:not(.print-include) {
        display: none !important;
    }
    
    body {
        padding: 0;
        margin: 0;
        font-family: "Segoe UI", "Helvetica Neue", Arial, sans-serif;
    }
    
    .card {
        box-shadow: none;
        border: 1px solid #ddd;
        margin: 0;
        padding: 20px;
    }
}
</style>
@endsection

@section('scripts')
<script>
function printSelectedSections() {
    // Hide all sections by default
    document.getElementById('summaryStatsSection').classList.remove('print-include');
    document.getElementById('detailedStatsSection').classList.remove('print-include');
    document.getElementById('trendDataSection').classList.remove('print-include');
    document.getElementById('distributionDataSection').classList.remove('print-include');
    document.getElementById('additionalDataSection').classList.remove('print-include');
    
    // Show selected sections
    if (document.getElementById('printSummaryStats').checked) {
        document.getElementById('summaryStatsSection').classList.add('print-include');
    }
    if (document.getElementById('printDetailedStats').checked) {
        document.getElementById('detailedStatsSection').classList.add('print-include');
    }
    if (document.getElementById('printTrendData').checked) {
        document.getElementById('trendDataSection').classList.add('print-include');
    }
    if (document.getElementById('printDistributionData').checked) {
        document.getElementById('distributionDataSection').classList.add('print-include');
    }
    if (document.getElementById('printAdditionalData').checked) {
        document.getElementById('additionalDataSection').classList.add('print-include');
    }
    
    // Close the modal and trigger print
    var modal = bootstrap.Modal.getInstance(document.getElementById('printOptionsModal'));
    modal.hide();
    
    // Add a small delay to ensure classes are applied
    setTimeout(function() {
        window.print();
    }, 100);
}

// Close the modal when print dialog is closed (this is a fallback)
window.addEventListener('afterprint', function() {
    // Reset all sections to be visible again
    document.getElementById('summaryStatsSection').classList.remove('print-include');
    document.getElementById('detailedStatsSection').classList.remove('print-include');
    document.getElementById('trendDataSection').classList.remove('print-include');
    document.getElementById('distributionDataSection').classList.remove('print-include');
    document.getElementById('additionalDataSection').classList.remove('print-include');
});
</script>
@endsection