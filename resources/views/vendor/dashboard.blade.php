@extends('layouts.vendor')

@section('title', 'Vendor Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<!--begin::Row-->
<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
    <!--begin::Col-->
    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">₦{{ number_format(Auth::guard('vendor')->user()->account_balance, 2) }}</span>
                    </div>
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Account Balance</span>
                </div>
            </div>
            <div class="card-body pt-2 pb-4 d-flex flex-wrap align-items-center">
                <div class="d-flex flex-center me-5 pt-2">
                    <div id="kt_card_widget_17_chart" style="min-width: 70px; min-height: 70px" data-kt-size="70" data-kt-line="11"></div>
                </div>
                <div class="d-flex flex-column content-justify-center flex-row-fluid">
                    <div class="d-flex fw-semibold align-items-center">
                        <div class="bullet w-8px h-3px rounded-2 bg-success me-3"></div>
                        <div class="text-gray-500 flex-grow-1 me-4">Available funds</div>
                    </div>
                    <div class="d-flex fw-semibold align-items-center my-3">
                        <div class="bullet w-8px h-3px rounded-2 bg-primary me-3"></div>
                        <div class="text-gray-500 flex-grow-1 me-4">For customer payments</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Col-->
    
    <!--begin::Col-->
    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">Actions</span>
                    </div>
                    <span class="text-gray-400 pt-1 fw-semibold fs-6">Manage Account</span>
                </div>
            </div>
            <div class="card-body pt-2 pb-4 d-flex flex-wrap align-items-center">
                <div class="d-flex flex-center me-5 pt-2">
                    <div id="kt_card_widget_18_chart" style="min-width: 70px; min-height: 70px" data-kt-size="70" data-kt-line="11"></div>
                </div>
                <div class="d-flex flex-column content-justify-center flex-row-fluid">
                    <div class="d-flex fw-semibold align-items-center">
                        <div class="bullet w-8px h-3px rounded-2 bg-success me-3"></div>
                        <div class="text-gray-500 flex-grow-1 me-4">
                            <button type="button" class="btn btn-primary" id="showFundAccountBtn">
                                Fund Account
                            </button>
                        </div>
                    </div>
                    <div class="d-flex fw-semibold align-items-center my-3">
                        <div class="bullet w-8px h-3px rounded-2 bg-primary me-3"></div>
                        <div class="text-gray-500 flex-grow-1 me-4">
                            <button type="button" class="btn btn-success" id="showPaymentBtn">
                                Make Payment
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Col-->
</div>
<!--end::Row-->

<!--begin::Row-->
<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
    <div class="col-xxl-12">
        <!-- Alerts for messages -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </div>
</div>
<!--end::Row-->

<!-- Fund Account Form (Hidden by default) -->
<div class="row g-5 g-xl-10 mb-5 mb-xl-10" id="fundAccountSection" style="display: none;">
    <div class="col-xxl-12">
        <div class="card card-xxl-stretch mb-5 mb-xl-10">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Fund Account</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Add funds to your vendor account</span>
                </h3>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-light" id="hideFundAccountBtn">Hide</button>
                </div>
            </div>
            <div class="card-body py-3">
                <form method="POST" action="{{ route('vendor.payments.fund') }}">
                    @csrf
                    <div class="form-group mb-5">
                        <label class="form-label">Amount to Fund</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="0.01" placeholder="Enter amount" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Fund Account</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Make Payment Form (Hidden by default) -->
<div class="row g-5 g-xl-10 mb-5 mb-xl-10" id="paymentSection" style="display: none;">
    <div class="col-xxl-12">
        <div class="card card-xxl-stretch mb-5 mb-xl-10">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Process Payment</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Make payment for customer using billing ID</span>
                </h3>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-light" id="hidePaymentBtn">Hide</button>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="row">
                    <div class="col-lg-6">
                        <form method="POST" action="{{ route('vendor.payments.initiate') }}" id="paymentForm">
                            @csrf
                            <div class="form-group mb-5">
                                <label class="form-label">Customer Billing ID</label>
                                <input type="text" name="billing_id" id="billing_id" class="form-control" placeholder="Enter billing ID" value="{{ old('billing_id') }}" required>
                                <div class="form-text">Enter the customer's billing ID to verify their details</div>
                            </div>
                            <div class="form-group mb-5">
                                <label class="form-label">Amount</label>
                                <input type="number" name="amount" class="form-control" step="0.01" min="0.01" placeholder="Enter amount" value="{{ old('amount') }}" required>
                            </div>
                            <div class="form-group mb-5">
                                <label class="form-label">Payment Method</label>
                                <select name="payment_type" class="form-select" required>
                                    <option value="">Select Payment Method</option>
                                    <option value="online" {{ old('payment_type') == 'online' ? 'selected' : '' }}>Pay Online (NABRoll)</option>
                                    <option value="account" {{ old('payment_type') == 'account' ? 'selected' : '' }}>Pay from Account Balance</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Process Payment</button>
                        </form>
                    </div>
                    <div class="col-lg-6">
                        <div class="bg-light rounded p-5" id="customerInfoSection" style="display: none;">
                            <h4>Customer Information</h4>
                            <div id="customerInfoContent">
                                <!-- Customer info will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Show/hide fund account section
    document.getElementById('showFundAccountBtn').addEventListener('click', function() {
        document.getElementById('fundAccountSection').style.display = 'block';
        // Scroll to the section
        document.getElementById('fundAccountSection').scrollIntoView({behavior: 'smooth'});
    });
    
    document.getElementById('hideFundAccountBtn').addEventListener('click', function() {
        document.getElementById('fundAccountSection').style.display = 'none';
    });
    
    // Show/hide payment section
    document.getElementById('showPaymentBtn').addEventListener('click', function() {
        document.getElementById('paymentSection').style.display = 'block';
        // Scroll to the section
        document.getElementById('paymentSection').scrollIntoView({behavior: 'smooth'});
    });
    
    document.getElementById('hidePaymentBtn').addEventListener('click', function() {
        document.getElementById('paymentSection').style.display = 'none';
    });
    
    // Fetch customer information when billing ID is entered
    document.getElementById('billing_id').addEventListener('blur', function() {
        const billingId = this.value.trim();
        
        if (billingId.length > 0) {
            // Show loading indicator
            const customerInfoSection = document.getElementById('customerInfoSection');
            const customerInfoContent = document.getElementById('customerInfoContent');
            
            customerInfoContent.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
            customerInfoSection.style.display = 'block';
            
            // Fetch customer information
            const url = '{{ route("vendor.customer.info", ["billingId" => "_BILLING_ID_"]) }}'.replace('_BILLING_ID_', encodeURIComponent(billingId));
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        customerInfoContent.innerHTML = `
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>${data.data.first_name} ${data.data.surname}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tariff:</strong></td>
                                    <td>${data.data.tariff}</td>
                                </tr>
                                <tr>
                                    <td><strong>Category:</strong></td>
                                    <td>${data.data.category}</td>
                                </tr>
                            </table>
                        `;
                    } else {
                        customerInfoContent.innerHTML = `<div class="alert alert-warning">${data.message || 'Customer not found'}</div>`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    customerInfoContent.innerHTML = '<div class="alert alert-danger">Error loading customer information: ' + error.message + '</div>';
                });
        } else {
            document.getElementById('customerInfoSection').style.display = 'none';
        }
    });
</script>
@endsection