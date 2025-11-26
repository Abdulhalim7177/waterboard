@extends('layouts.vendor')

@section('title', 'Account Funding History')
@section('page-title', 'Funding History')
@section('breadcrumb', 'Funding History')

@section('content')
<!--begin::Card-->
<div class="card card-flush mb-5">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <div class="d-flex align-items-center">
                <div class="symbol symbol-45px symbol-light-success me-4">
                    <span class="symbol-label">
                        <i class="ki-duotone ki-wallet fs-2 text-success">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </span>
                </div>
                <div>
                    <span class="card-label fw-bold fs-3 text-dark">Account Funding History</span>
                    <div class="text-muted fw-semibold fs-7">View all account funding transactions</div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body py-3">
        <!--begin::Filter Form-->
        <form method="GET" action="{{ route('vendor.payments.funding') }}" class="mb-7">
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
                                    <label class="fs-6 fw-semibold mb-2">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="">All Statuses</option>
                                        <option value="SUCCESSFUL" {{ request('status') == 'SUCCESSFUL' ? 'selected' : '' }}>Successful</option>
                                        <option value="FAILED" {{ request('status') == 'FAILED' ? 'selected' : '' }}>Failed</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="fv-row mb-5">
                                    <label class="fs-6 fw-semibold mb-2">Method</label>
                                    <select name="method" class="form-select">
                                        <option value="">All Methods</option>
                                        <option value="NABRoll" {{ request('method') == 'NABRoll' ? 'selected' : '' }}>NABRoll</option>
                                        <option value="Bank Transfer" {{ request('method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="POS" {{ request('method') == 'POS' ? 'selected' : '' }}>POS</option>
                                        <option value="Cash" {{ request('method') == 'Cash' ? 'selected' : '' }}>Cash</option>
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
                        <a href="{{ route('vendor.payments.funding') }}" class="btn btn-light me-3">Reset</a>
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="d-flex justify-content-end py-6 px-9">
            <form action="{{ route('vendor.payments.funding') }}" method="GET" class="d-flex align-items-center">
                <div class="d-flex align-items-center fw-bold">
                    <div class="text-muted fs-7 me-2">Show</div>
                    <select name="per_page" class="form-select form-select-transparent text-dark fs-7 lh-1 fw-bold py-0 ps-3 w-auto" onchange="this.form.submit()">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>All</option>
                    </select>
                </div>
            </form>
        </div>
        <!--end::Filter Form-->

        @if (isset($vendorPayments) && $vendorPayments->count() > 0)
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4 table-auto table-striped">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="min-w-150px">Date</th>
                            <th class="min-w-120px">Amount</th>
                            <th class="min-w-120px">Method</th>
                            <th class="min-w-120px">Status</th>
                            <th class="min-w-120px">Transaction Ref</th>
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
                                <td>
                                    <span class="text-dark fw-bold text-hover-primary d-block fs-6">{{ $payment->transaction_ref ?? 'N/A' }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                @if ($vendorPayments instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $vendorPayments->links() }}
                @elseif ($vendorPayments instanceof \Illuminate\Database\Eloquent\Collection)
                    <nav>
                        <ul class="pagination">
                            <li class="page-item disabled" aria-disabled="true">
                                <span class="page-link">@lang('pagination.previous')</span>
                            </li>
                            <li class="page-item disabled" aria-disabled="true">
                                <span class="page-link">@lang('pagination.next')</span>
                            </li>
                        </ul>
                    </nav>
                @endif
            </div>
        @else
            <div class="alert alert-info">
                <h4 class="alert-heading">No funding transactions found</h4>
                <p>You haven't funded your account yet.</p>
            </div>
        @endif
    </div>
</div>
<!--end::Card-->
@endsection