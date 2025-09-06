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
@endsection