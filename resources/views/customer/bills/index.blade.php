@extends('layouts.customer')
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
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
            <div class="row g-5 mb-8">
                <div class="col-md-4">
                    <div class="card shadow-sm" style="min-height: 170px;">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                            <h5 class="text-muted fs-5 fw-bold mb-2" style="letter-spacing: 0.5px;">Wallet Balance</h5>
                            <h3 class="text-success fs-1 fw-bolder mb-0">₦{{ number_format(Auth::guard('customer')->user()->account_balance, 2) }}</h3>
                            
                            <!-- Button to trigger the payment modal -->
<a href="#" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#kt_modal_make_payment">Pay Bills & Fund Account</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm" style="min-height: 170px;">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                            <h5 class="text-muted fs-5 fw-bold mb-2" style="letter-spacing: 0.5px;">Outstanding Balance</h5>
                            <h3 class="text-danger fs-1 fw-bolder mb-0">₦{{ number_format(Auth::guard('customer')->user()->total_bill, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm" style="min-height: 170px;">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                            <h5 class="text-muted fs-5 fw-bold mb-2" style="letter-spacing: 0.5px;">Tariff</h5>
                            <h3 class="text-gray-800 fs-1 fw-bolder mb-0">{{ Auth::guard('customer')->user()->tariff->name ?? 'N/A' }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-lg-20">
                   
                    <div class="m-0">
                        <div class="fw-bold fs-3 text-gray-800 mb-8">My Payment Dashboard</div>
                        <form method="GET" action="{{ route('customer.bills') }}" id="filter-form">
                            <div class="row g-5">
                                <div class="col-md-4">
                                    <label for="start_date" class="form-label fw-semibold text-gray-600 fs-7">Start Date</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="end_date" class="form-label fw-semibold text-gray-600 fs-7">End Date</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                                </div>
                                <div class="col-md-4 align-self-end">
                                    <button type="submit" class="btn btn-primary me-2">Apply Bill Filters</button>
                                    <a href="{{ route('customer.bills') }}" class="btn btn-outline-secondary">Clear Bill Filters</a>
                                </div>
                            </div>
                        </form>
                        <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#kt_customer_details_invoices_1">Unpaid Bills ({{ $bills->where('status', '!=', 'paid')->count() }})</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#kt_customer_details_invoices_2">Paid Bills ({{ $bills->where('status', 'paid')->count() }})</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="kt_customer_details_invoices_1" class="tab-pane fade show active" role="tabpanel">
                                @if ($bills->where('status', '!=', 'paid')->isEmpty())
                                    <div class="alert alert-info">No unpaid bills available for the selected date range.</div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table align-middle table-row-dashed fs-6 fw-bold gs-0 gy-4 p-0 m-0">
                                            <thead class="border-bottom border-gray-200 fs-7 text-uppercase fw-bold">
                                                <tr class="text-start text-gray-400">
                                                    <th class="w-50px"><input type="checkbox" id="select-all" onclick="toggleSelectAll(this)"></th>
                                                    <th class="min-w-100px">Billing ID</th>
                                                    <th class="min-w-100px">Tariff</th>
                                                    <th class="min-w-100px">Amount</th>
                                                    <th class="min-w-100px">Balance</th>
                                                    <th class="min-w-100px">Status</th>
                                                    <th class="min-w-125px">Billing Date</th>
                                                    <th class="min-w-125px">Due Date</th>
                                                    <th class="w-100px">Action</th>
                                                    <th class="w-100px">Download</th>
                                                </tr>
                                            </thead>
                                            <tbody class="fs-6 fw-semibold text-gray-600">
                                                @foreach ($bills->where('status', '!=', 'paid') as $bill)
                                                    <tr>
                                                        <td><input type="checkbox" name="bill_ids[]" value="{{ $bill->id }}" class="bill-checkbox" onclick="updateTotal()"></td>
                                                        <td><a href="#" class="text-gray-600 text-hover-primary">{{ $bill->billing_id }}</a></td>
                                                        <td>{{ $bill->tariff->name ?? 'N/A' }}</td>
                                                        <td class="{{ $bill->amount >= 0 ? 'text-success' : 'text-danger' }}">₦{{ number_format($bill->amount, 2) }}</td>
                                                        <td>₦{{ number_format($bill->balance, 2) }}</td>
                                                        <td>
                                                            <span class="badge {{ $bill->status == 'overdue' ? 'badge-light-danger' : 'badge-light-warning' }}">
                                                                {{ ucfirst($bill->status) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $bill->billing_date->format('M d, Y') }}</td>
                                                        <td>{{ $bill->due_date->format('M d, Y') }}</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-light btn-active-light-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_pay_bill_{{ $bill->id }}">Pay Bill #{{ $bill->billing_id }}</button>
                                                        </td>
                                                        <td>
                                                            @if ($bill->approval_status === 'approved')
                                                                <a href="{{ route('customer.bills.download-pdf', $bill->id) }}" class="btn btn-sm btn-light btn-active-light-primary">Download PDF</a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                                @if ($bills->where('status', '!=', 'paid')->count() > 0)
                                    <a href="#" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#kt_modal_pay_bills">Pay All Unpaid Bills ({{ $bills->where('status', '!=', 'paid')->count() }} Bills, ₦{{ number_format($bills->where('status', '!=', 'paid')->sum('balance'), 2) }})</a>
                                @endif
                            </div>



                            <div id="kt_customer_details_invoices_2" class="tab-pane fade" role="tabpanel">
                                @if ($bills->where('status', 'paid')->isEmpty())
                                    <div class="alert alert-info">No paid bills available for the selected date range.</div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table align-middle table-row-dashed fs-6 fw-bold gs-0 gy-4 p-0 m-0">
                                            <thead class="border-bottom border-gray-200 fs-7 text-uppercase fw-bold">
                                                <tr class="text-start text-gray-400">
                                                    <th class="min-w-100px">Billing ID</th>
                                                    <th class="min-w-100px">Tariff</th>
                                                    <th class="min-w-100px">Amount</th>
                                                    <th class="min-w-100px">Balance</th>
                                                    <th class="min-w-100px">Status</th>
                                                    <th class="min-w-125px">Billing Date</th>
                                                    <th class="min-w-125px">Due Date</th>
                                                    <th class="w-100px">Download</th>
                                                </tr>
                                            </thead>
                                            <tbody class="fs-6 fw-semibold text-gray-600">
                                                @foreach ($bills->where('status', 'paid') as $bill)
                                                    <tr>
                                                        <td><a href="#" class="text-gray-600 text-hover-primary">{{ $bill->billing_id }}</a></td>
                                                        <td>{{ $bill->tariff->name ?? 'N/A' }}</td>
                                                        <td class="{{ $bill->amount >= 0 ? 'text-success' : 'text-danger' }}">₦{{ number_format($bill->amount, 2) }}</td>
                                                        <td>₦{{ number_format($bill->balance, 2) }}</td>
                                                        <td><span class="badge badge-light-success">Paid</span></td>
                                                        <td>{{ $bill->billing_date->format('M d, Y') }}</td>
                                                        <td>{{ $bill->due_date->format('M d, Y') }}</td>
                                                        <td>
                                                            @if ($bill->approval_status === 'approved')
                                                                <a href="{{ route('customer.bills.download-pdf', $bill->id) }}" class="btn btn-sm btn-light btn-active-light-primary">Download PDF</a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4">
                            {{ $bills->links('pagination::bootstrap-5')}} 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($bills->where('status', '!=', 'paid') as $bill)
<div class="modal fade" id="kt_modal_pay_bill_{{ $bill->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Pay Bill #{{ $bill->billing_id }}</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M6 18L18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-10 my-2">
                <form id="paymentForm_{{ $bill->id }}" action="{{ route('customer.bills.pay') }}" method="POST">
                    @csrf
                    <input type="hidden" name="bill_ids[]" value="{{ $bill->id }}">
                    <div class="mb-4">
                        <label for="amount_{{ $bill->id }}" class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                        <input type="number" name="amount" id="amount_{{ $bill->id }}" placeholder="Enter amount" step="0.01" class="form-control w-full border p-2 rounded focus:ring-2 focus:ring-blue-500" required value="{{ $bill->balance }}">
                    </div>
                    <div id="successMessage_{{ $bill->id }}" class="d-none bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                        Payment Initiated!
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-light me-3" id="cancelPayment_{{ $bill->id }}">Cancel</button>
                        <button type="submit" class="btn btn-primary bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" id="submitPayment_{{ $bill->id }}">
                            <span class="indicator-label">Pay with NABRoll</span>
                            <span class="indicator-progress">Please wait... 
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<div class="modal fade" id="kt_modal_pay_bills" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Pay All Unpaid Bills</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M6 18L18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-10 my-2">
                <form id="payAllForm" action="{{ route('customer.bills.pay') }}" method="POST">
                    @csrf
                    @foreach ($bills->where('status', '!=', 'paid') as $bill)
                        <input type="hidden" name="bill_ids[]" value="{{ $bill->id }}">
                    @endforeach
                    <div class="mb-4">
                        <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-1">Total Amount</label>
                        <input type="number" name="amount" id="total_amount" placeholder="Enter amount" step="0.01" class="form-control w-full border p-2 rounded focus:ring-2 focus:ring-blue-500" required value="{{ $bills->where('status', '!=', 'paid')->sum('balance') }}">
                    </div>
                    <div id="successMessage_all" class="d-none bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                        Payment Initiated!
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-light me-3" id="cancelPayAll">Cancel</button>
                        <button type="submit" class="btn btn-primary bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" id="submitPayAll">
                            <span class="indicator-label">Pay with NABRoll</span>
                            <span class="indicator-progress">Please wait... 
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal for entering payment amount -->
<div class="modal fade" id="kt_modal_make_payment" tabindex="-1" aria-labelledby="kt_modal_make_payment_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kt_modal_make_payment_label">Fund Wallet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('customer.bills.pay') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Funding Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0.01" required placeholder="Enter amount (e.g., 100.00)">
                    </div>
                    <p class="text-muted">The funding will be applied to your outstanding bills, starting with the oldest then the remaining wil be depsoted to the account balance.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Make Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function toggleSelectAll(checkbox) {
        const checkboxes = document.querySelectorAll('.bill-checkbox');
        checkboxes.forEach(cb => cb.checked = checkbox.checked);
        updateTotal();
    }

    function updateTotal() {
        const checkboxes = document.querySelectorAll('.bill-checkbox:checked');
        let total = 0;
        checkboxes.forEach(cb => {
            const row = cb.closest('tr');
            const balance = parseFloat(row.cells[4].textContent.replace(/[^0-9.-]+/g, '')) || 0;
            total += balance;
        });
        document.getElementById('total_amount').value = total.toFixed(2);
    }

    @foreach ($bills->where('status', '!=', 'paid') as $bill)
        const paymentForm_{{ $bill->id }} = document.getElementById('paymentForm_{{ $bill->id }}');
        const submitPayment_{{ $bill->id }} = document.getElementById('submitPayment_{{ $bill->id }}');
        const cancelPayment_{{ $bill->id }} = document.getElementById('cancelPayment_{{ $bill->id }}');
        const successMessage_{{ $bill->id }} = document.getElementById('successMessage_{{ $bill->id }}');

        paymentForm_{{ $bill->id }}.addEventListener('submit', function(e) {
            e.preventDefault();
            submitPayment_{{ $bill->id }}.querySelector('.indicator-label').classList.add('d-none');
            submitPayment_{{ $bill->id }}.querySelector('.indicator-progress').classList.remove('d-none');
            submitPayment_{{ $bill->id }}.setAttribute('disabled', 'disabled');

            setTimeout(() => {
                submitPayment_{{ $bill->id }}.querySelector('.indicator-label').classList.remove('d-none');
                submitPayment_{{ $bill->id }}.querySelector('.indicator-progress').classList.add('d-none');
                submitPayment_{{ $bill->id }}.removeAttribute('disabled');
                successMessage_{{ $bill->id }}.classList.remove('d-none');
                setTimeout(() => {
                    paymentForm_{{ $bill->id }}.submit();
                }, 1000);
            }, 1000);
        });

        cancelPayment_{{ $bill->id }}.addEventListener('click', function() {
            if (confirm('Are you sure you want to cancel?')) {
                bootstrap.Modal.getInstance(document.getElementById('kt_modal_pay_bill_{{ $bill->id }}')).hide();
            }
        });
    @endforeach

    const payAllForm = document.getElementById('payAllForm');
    const submitPayAll = document.getElementById('submitPayAll');
    const cancelPayAll = document.getElementById('cancelPayAll');
    const successMessageAll = document.getElementById('successMessage_all');

    payAllForm.addEventListener('submit', function(e) {
        e.preventDefault();
        submitPayAll.querySelector('.indicator-label').classList.add('d-none');
        submitPayAll.querySelector('.indicator-progress').classList.remove('d-none');
        submitPayAll.setAttribute('disabled', 'disabled');

        setTimeout(() => {
            submitPayAll.querySelector('.indicator-label').classList.remove('d-none');
            submitPayAll.querySelector('.indicator-progress').classList.add('d-none');
            submitPayAll.removeAttribute('disabled');
            successMessageAll.classList.remove('d-none');
            setTimeout(() => {
                payAllForm.submit();
            }, 1000);
        }, 1000);
    });

    cancelPayAll.addEventListener('click', function() {
        if (confirm('Are you sure you want to cancel?')) {
            bootstrap.Modal.getInstance(document.getElementById('kt_modal_pay_bills')).hide();
        }
    });
</script>
@endsection