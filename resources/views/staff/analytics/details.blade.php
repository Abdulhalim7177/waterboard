@extends('layouts.staff')

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

    <!--begin::Card-->
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <h2>Analytics Details</h2>
                <p class="text-muted">Detailed breakdown of system analytics</p>
            </div>
            <!--end::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <a href="{{ route('staff.analytics.index') }}" class="btn btn-light-primary">
                    <i class="ki-duotone ki-arrow-left fs-2"></i>
                    Back to Dashboard
                </a>
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
            <!--begin::Tabs-->
            <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1">Summary Statistics</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2">Trend Data</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_3">Distribution Charts</a>
                </li>
            </ul>
            <!--end::Tabs-->

            <!--begin::Tab content-->
            <div class="tab-content" id="myTabContent">
                <!--begin::Tab pane - Summary Statistics-->
                <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
                    <!--begin::Row-->
                    <div class="row g-5 mb-10">
                        <!--begin::Col-->
                        <div class="col-md-12">
                            <div class="card card-flush h-md-100">
                                <div class="card-header">
                                    <h3 class="card-title">System Summary Statistics</h3>
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
                                                    <td class="text-end">{{ $data['stats']['payments']['failed'] ?? 0 }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Complaints</td>
                                                    <td class="text-end">{{ $data['stats']['complaints']['total'] ?? 0 }}</td>
                                                    <td class="text-end">{{ $data['stats']['complaints']['resolved'] ?? 0 }}</td>
                                                    <td class="text-end">{{ $data['stats']['complaints']['pending'] ?? 0 }}</td>
                                                    <td class="text-end">{{ $data['stats']['complaints']['rejected'] ?? 0 }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Tab pane - Summary Statistics-->

                <!--begin::Tab pane - Trend Data-->
                <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
                    <!--begin::Row-->
                    <div class="row g-5 mb-10">
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
                                                    <th class="text-end">Complaints</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @for ($i = 0; $i < count($data['months']); $i++)
                                                    <tr>
                                                        <td>{{ $data['months'][$i] }}</td>
                                                        <td class="text-end">₦{{ number_format($data['billAmounts'][$i] ?? 0, 2) }}</td>
                                                        <td class="text-end">₦{{ number_format($data['paymentAmounts'][$i] ?? 0, 2) }}</td>
                                                        <td class="text-end">{{ $data['complaintCounts'][$i] ?? 0 }}</td>
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
                    <!--end::Row-->
                </div>
                <!--end::Tab pane - Trend Data-->

                <!--begin::Tab pane - Distribution Charts-->
                <div class="tab-pane fade" id="kt_tab_pane_3" role="tabpanel">
                    <!--begin::Row-->
                    <div class="row g-5 mb-10">
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
                    <!--end::Row-->
                    
                    <!--begin::Row-->
                    <div class="row g-5 mb-10">
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
                    <!--end::Row-->
                </div>
                <!--end::Tab pane - Distribution Charts-->
            </div>
            <!--end::Tab content-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</div>
@endsection