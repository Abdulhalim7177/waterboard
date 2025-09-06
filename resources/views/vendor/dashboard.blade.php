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
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fundAccountModal">
                                Fund Account
                            </button>
                        </div>
                    </div>
                    <div class="d-flex fw-semibold align-items-center my-3">
                        <div class="bullet w-8px h-3px rounded-2 bg-primary me-3"></div>
                        <div class="text-gray-500 flex-grow-1 me-4">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#makePaymentModal">
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

<!-- Fund Account Modal -->
<div class="modal fade" id="fundAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Fund Account</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <form id="fundAccountForm" class="form" action="{{ route('vendor.payments.fund') }}" method="POST">
                    @csrf
                    <div class="d-flex flex-column mb-7 fv-row">
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                            <span class="required">Amount to Fund</span>
                        </label>
                        <input type="number" class="form-control form-control-solid" placeholder="Enter amount" name="amount" step="0.01" min="0.01" required />
                    </div>
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="fundAccountSubmitBtn">
                            <span class="indicator-label">Fund Account</span>
                            <span class="indicator-progress" style="display: none;">Please wait... 
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Make Payment Modal -->
<div class="modal fade" id="makePaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Make Payment</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <form id="makePaymentForm" class="form" action="{{ route('vendor.payments.initiate') }}" method="POST">
                    @csrf
                    <div class="d-flex flex-column mb-7 fv-row">
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                            <span class="required">Customer Billing ID</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" placeholder="Enter billing ID" name="billing_id" required />
                    </div>
                    <div class="d-flex flex-column mb-7 fv-row">
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                            <span class="required">Amount</span>
                        </label>
                        <input type="number" class="form-control form-control-solid" placeholder="Enter amount" name="amount" step="0.01" min="0.01" required />
                    </div>
                    <div class="d-flex flex-column mb-7 fv-row">
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                            <span class="required">Payment Method</span>
                        </label>
                        <select name="payment_type" class="form-select form-select-solid" required>
                            <option value="">Select Payment Method</option>
                            <option value="online">Pay Online (NABRoll)</option>
                            <option value="account">Pay from Account Balance</option>
                        </select>
                    </div>
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="makePaymentSubmitBtn">
                            <span class="indicator-label">Make Payment</span>
                            <span class="indicator-progress" style="display: none;">Please wait... 
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Fund Account Confirmation Modal -->
<div class="modal fade" id="fundAccountConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Confirm Account Funding</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <div class="text-center mb-10">
                    <h4 class="text-dark mb-3">Are you sure you want to fund your account?</h4>
                    <p class="text-muted">
                        You are about to fund your vendor account with <strong id="fundAmountDisplay">₦0.00</strong>.
                        You will be redirected to the payment gateway to complete this transaction.
                    </p>
                </div>
                <div class="text-center">
                    <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmFundAccountBtn">Confirm Funding</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Make Payment Confirmation Modal -->
<div class="modal fade" id="makePaymentConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Confirm Payment</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <div class="text-center mb-10">
                    <h4 class="text-dark mb-3">Are you sure you want to make this payment?</h4>
                    <p class="text-muted">
                        You are about to make a payment of <strong id="paymentAmountDisplay">₦0.00</strong> 
                        to customer with billing ID <strong id="billingIdDisplay">N/A</strong>.
                    </p>
                    <p class="text-muted">
                        Payment method: <strong id="paymentMethodDisplay">N/A</strong>
                    </p>
                </div>
                <div class="text-center">
                    <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmMakePaymentBtn">Confirm Payment</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Handle fund account form submission
    document.getElementById('fundAccountSubmitBtn').addEventListener('click', function(e) {
        e.preventDefault();
        
        const amount = document.querySelector('#fundAccountForm input[name="amount"]').value;
        
        if (!amount || amount <= 0) {
            alert('Please enter a valid amount');
            return;
        }
        
        // Display the amount in the confirmation modal
        document.getElementById('fundAmountDisplay').textContent = '₦' + parseFloat(amount).toFixed(2);
        
        // Hide the fund account modal and show confirmation modal
        const fundAccountModal = bootstrap.Modal.getInstance(document.getElementById('fundAccountModal'));
        if (fundAccountModal) {
            fundAccountModal.hide();
        }
        
        const confirmModal = new bootstrap.Modal(document.getElementById('fundAccountConfirmModal'));
        confirmModal.show();
    });
    
    // Handle confirm fund account button
    document.getElementById('confirmFundAccountBtn').addEventListener('click', function() {
        // Submit the fund account form
        document.getElementById('fundAccountForm').submit();
    });
    
    // Handle make payment form submission
    document.getElementById('makePaymentSubmitBtn').addEventListener('click', function(e) {
        e.preventDefault();
        
        const billingId = document.querySelector('#makePaymentForm input[name="billing_id"]').value;
        const amount = document.querySelector('#makePaymentForm input[name="amount"]').value;
        const paymentType = document.querySelector('#makePaymentForm select[name="payment_type"]').value;
        
        if (!billingId) {
            alert('Please enter a billing ID');
            return;
        }
        
        if (!amount || amount <= 0) {
            alert('Please enter a valid amount');
            return;
        }
        
        if (!paymentType) {
            alert('Please select a payment method');
            return;
        }
        
        // Get the text for the payment type
        const paymentTypeText = document.querySelector('#makePaymentForm select[name="payment_type"] option:checked').text;
        
        // Display the details in the confirmation modal
        document.getElementById('billingIdDisplay').textContent = billingId;
        document.getElementById('paymentAmountDisplay').textContent = '₦' + parseFloat(amount).toFixed(2);
        document.getElementById('paymentMethodDisplay').textContent = paymentTypeText;
        
        // Hide the make payment modal and show confirmation modal
        const makePaymentModal = bootstrap.Modal.getInstance(document.getElementById('makePaymentModal'));
        if (makePaymentModal) {
            makePaymentModal.hide();
        }
        
        const confirmModal = new bootstrap.Modal(document.getElementById('makePaymentConfirmModal'));
        confirmModal.show();
    });
    
    // Handle confirm make payment button
    document.getElementById('confirmMakePaymentBtn').addEventListener('click', function() {
        // Submit the make payment form
        document.getElementById('makePaymentForm').submit();
    });
    
    // Reset forms when modals are hidden
    document.getElementById('fundAccountModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('fundAccountForm').reset();
    });
    
    document.getElementById('makePaymentModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('makePaymentForm').reset();
    });
    
    // Handle form submissions to prevent double submissions
    document.getElementById('fundAccountForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('fundAccountSubmitBtn');
        const indicator = submitBtn.querySelector('.indicator-progress');
        const label = submitBtn.querySelector('.indicator-label');
        
        if (indicator && label) {
            indicator.style.display = 'inline-block';
            label.style.display = 'none';
            submitBtn.disabled = true;
        }
    });
    
    document.getElementById('makePaymentForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('makePaymentSubmitBtn');
        const indicator = submitBtn.querySelector('.indicator-progress');
        const label = submitBtn.querySelector('.indicator-label');
        
        if (indicator && label) {
            indicator.style.display = 'inline-block';
            label.style.display = 'none';
            submitBtn.disabled = true;
        }
    });
</script>
@endsection