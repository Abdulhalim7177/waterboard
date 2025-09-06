@extends('layouts.vendor')

@section('title', 'Vendor Transaction History')
@section('page-title', 'Transaction History')
@section('breadcrumb', 'Transaction History')

@section('content')
<!--begin::Card-->
<div class="card card-xxl-stretch mb-5">
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold fs-3 mb-1">Payment History</span>
            <span class="text-muted mt-1 fw-semibold fs-7">View all payments processed through your vendor account</span>
        </h3>
    </div>
    <div class="card-body py-3">
        <!--begin::Filter Form-->
        <form method="GET" action="{{ route('vendor.payments.index') }}" class="mb-7">
            <div class="card card-flush border rounded mb-5">
                <div class="card-header cursor-pointer py-5" data-bs-toggle="collapse" data-bs-target="#filter_collapse">
                    <h3 class="card-title fw-bold">Filters</h3>
                    <div class="card-toolbar">
                        <span class="btn btn-sm btn-icon btn-light-dark">
                            <i class="ki-duotone ki-down fs-1"></i>
                        </span>
                    </div>
                </div>
                <div id="filter_collapse" class="collapse">
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="fv-row mb-5">
                                    <label class="fs-6 fw-semibold mb-2">Start Date</label>
                                    <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="fv-row mb-5">
                                    <label class="fs-6 fw-semibold mb-2">End Date</label>
                                    <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="fv-row mb-5">
                                    <label class="fs-6 fw-semibold mb-2">Customer</label>
                                    <select name="customer_id" class="form-select" data-control="select2" data-placeholder="Select customer">
                                        <option value=""></option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->first_name }} {{ $customer->surname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="fv-row mb-5">
                                    <label class="fs-6 fw-semibold mb-2">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="">All Statuses</option>
                                        <option value="SUCCESSFUL" {{ request('status') == 'SUCCESSFUL' ? 'selected' : '' }}>Successful</option>
                                        <option value="FAILED" {{ request('status') == 'FAILED' ? 'selected' : '' }}>Failed</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="fv-row mb-5">
                                    <label class="fs-6 fw-semibold mb-2">Min Amount</label>
                                    <input type="number" step="0.01" class="form-control" name="min_amount" placeholder="0.00" value="{{ request('min_amount') }}" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="fv-row mb-5">
                                    <label class="fs-6 fw-semibold mb-2">Max Amount</label>
                                    <input type="number" step="0.01" class="form-control" name="max_amount" placeholder="0.00" value="{{ request('max_amount') }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="{{ route('vendor.payments.index') }}" class="btn btn-light me-3">Reset</a>
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                    </div>
                </div>
            </div>
        </form>
        <!--end::Filter Form-->

        @if (isset($vendorPayments) && $vendorPayments->count() > 0)
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="min-w-150px">Date</th>
                            <th class="min-w-150px">Customer</th>
                            <th class="min-w-120px">Billing ID</th>
                            <th class="min-w-120px">Amount</th>
                            <th class="min-w-120px">Method</th>
                            <th class="min-w-120px">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vendorPayments as $payment)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex justify-content-start flex-column">
                                            <span class="text-dark fw-bold text-hover-primary fs-6">{{ $payment->payment_date ? $payment->payment_date->format('Y-m-d') : $payment->created_at->format('Y-m-d') }}</span>
                                            <span class="text-muted fw-semibold text-muted d-block fs-7">{{ $payment->payment_date ? $payment->payment_date->format('H:i') : $payment->created_at->format('H:i') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex justify-content-start flex-column">
                                            <span class="text-dark fw-bold text-hover-primary fs-6">{{ $payment->customer->first_name ?? '' }} {{ $payment->customer->surname ?? '' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-dark fw-bold text-hover-primary d-block fs-6">{{ $payment->billing_id ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="text-dark fw-bold d-block fs-6">₦{{ number_format($payment->amount, 2) }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-light-primary fs-7 fw-semibold">{{ $payment->method ?? 'NABRoll' }}</span>
                                </td>
                                <td>
                                    @if($payment->payment_status === 'SUCCESSFUL')
                                        <span class="badge badge-light-success fs-7 fw-semibold">{{ $payment->payment_status }}</span>
                                    @elseif($payment->payment_status === 'FAILED')
                                        <span class="badge badge-light-danger fs-7 fw-semibold">{{ $payment->payment_status }}</span>
                                    @else
                                        <span class="badge badge-light-warning fs-7 fw-semibold">{{ $payment->payment_status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $vendorPayments->links() }}
            </div>
        @else
            <div class="alert alert-info">
                <h4 class="alert-heading">No payments found</h4>
                <p>You haven't processed any payments yet.</p>
            </div>
        @endif
    </div>
</div>
<!--end::Card-->

<!-- Initialize Select2 -->
@section('scripts')
<script>
    $(document).ready(function() {
        $('[data-control="select2"]').each(function() {
            $(this).select2({
                placeholder: $(this).attr('data-placeholder') || 'Select an option',
                allowClear: true
            });
        });
    });
</script>
@endsection
@endsection