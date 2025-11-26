@extends('layouts.vendor')

@section('title', 'Vendor Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<!--begin::Row - Dashboard Stats-->
<div class="row g-4 g-xl-8 mb-6 mb-xl-8">
    <!--begin::Col - Account Balance Card-->
    <div class="col-12 col-sm-6 col-lg-6 col-xl-4 col-xxl-4 mb-4 mb-xl-6">
        <div class="card card-flush h-100 bg-gradient-start">
            <div class="card-header border-0 pt-6 pb-4">
                <div class="card-title">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="symbol symbol-50px symbol-light-primary me-4">
                                <span class="symbol-label">
                                    <i class="ki-duotone ki-wallet fs-2 text-primary">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="fs-3x fw-bold text-gray-800 d-block">₦{{ number_format(Auth::guard('vendor')->user()->account_balance, 2) }}</span>
                            <span class="text-gray-500 fw-semibold fs-6">Available Balance</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="d-flex align-items-center">
                    <div class="bullet w-8px h-3px rounded-2 bg-success me-3"></div>
                    <div class="text-gray-600 fs-7">Ready for transactions</div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Col-->

    <!--begin::Col - Quick Actions Card-->
    <div class="col-12 col-sm-6 col-lg-6 col-xl-4 col-xxl-4 mb-4 mb-xl-6">
        <div class="card card-flush h-100 bg-gradient-end">
            <div class="card-header border-0 pt-6 pb-4">
                <div class="card-title">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="symbol symbol-50px symbol-light-success me-4">
                                <span class="symbol-label">
                                    <i class="ki-duotone ki-abstract-23 fs-2 text-success">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="fs-3x fw-bold text-gray-800 d-block">Actions</span>
                            <span class="text-gray-500 fw-semibold fs-6">Quick Tasks</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="d-flex flex-column gap-2">
                    <button type="button" class="btn btn-primary btn-sm w-100" id="showFundAccountBtn">
                        <i class="ki-duotone ki-plus-circle fs-4 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Fund Account
                    </button>
                    <button type="button" class="btn btn-success btn-sm w-100" id="showPaymentBtn">
                        <i class="ki-duotone ki-dollar fs-4 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        Make Payment
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Col-->

    <!--begin::Col - Status Card-->
    <div class="col-12 col-sm-12 col-lg-12 col-xl-4 col-xxl-4 mb-4 mb-xl-6">
        <div class="card card-flush h-100 bg-gradient-middle">
            <div class="card-header border-0 pt-6 pb-4">
                <div class="card-title">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="symbol symbol-50px symbol-light-info me-4">
                                <span class="symbol-label">
                                    <i class="ki-duotone ki-clipboard-check fs-2 text-info">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </span>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="fs-3x fw-bold text-gray-800 d-block">Active</span>
                            <span class="text-gray-500 fw-semibold fs-6">Account Status</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="d-flex align-items-center">
                    <div class="bullet w-8px h-3px rounded-2 bg-info me-3"></div>
                    <div class="text-gray-600 fs-7">Fully operational</div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Col-->
</div>
<!--end::Row-->
<!--end::Row-->

<!--begin::Row - Alerts Section-->
<div class="row g-4 mb-6">
    <div class="col-12">
        <!-- Alerts for messages -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="ki-duotone ki-information-5 fs-2 me-3">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>
                <div class="flex-grow-1">
                    <strong>Error!</strong> Please review the following:
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="ki-duotone ki-check-circle fs-2 me-3">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <div class="flex-grow-1">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="ki-duotone ki-cross-circle fs-2 me-3">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <div class="flex-grow-1">
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
</div>
<!--end::Row-->
<!--end::Row-->

<!-- Fund Account Form (Hidden by default) -->
<div class="row g-4 mb-6" id="fundAccountSection" style="display: none;">
    <div class="col-12">
        <div class="card card-flush border border-primary border-dashed">
            <div class="card-header bg-light-primary border-0 py-4">
                <div class="card-title d-flex align-items-center">
                    <div class="symbol symbol-40px bg-primary me-3">
                        <span class="symbol-label">
                            <i class="ki-duotone ki-wallet text-white fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                    </div>
                    <div>
                        <span class="card-label fw-bold fs-4 text-dark">Fund Account</span>
                        <div class="text-muted fw-semibold fs-7">Add funds to your vendor account</div>
                    </div>
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-light-danger" id="hideFundAccountBtn">
                        <i class="ki-duotone ki-cross fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Close
                    </button>
                </div>
            </div>
            <div class="card-body py-6">
                <form method="POST" action="{{ route('vendor.payments.fund') }}" class="form">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="fv-row mb-6">
                                <label class="form-label fw-semibold fs-6">Amount to Fund</label>
                                <div class="input-group">
                                    <span class="input-group-text">₦</span>
                                    <input type="number" name="amount" class="form-control form-control-solid" step="0.01" min="0.01" placeholder="0.00" required>
                                </div>
                                <div class="form-text">Enter amount to add to your account</div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column flex-sm-row gap-3">
                        <button type="submit" class="btn btn-primary flex-fill flex-sm-grow-0">
                            <i class="ki-duotone ki-plus-circle fs-3 me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Fund Account
                        </button>
                        <button type="button" class="btn btn-light" id="hideFundAccountBtn2">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Make Payment Form (Hidden by default) -->
<div class="row g-4 mb-6" id="paymentSection" style="display: none;">
    <div class="col-12">
        <div class="card card-flush border border-success border-dashed">
            <div class="card-header bg-light-success border-0 py-4">
                <div class="card-title d-flex align-items-center">
                    <div class="symbol symbol-40px bg-success me-3">
                        <span class="symbol-label">
                            <i class="ki-duotone ki-dollar text-white fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                    </div>
                    <div>
                        <span class="card-label fw-bold fs-4 text-dark">Process Payment</span>
                        <div class="text-muted fw-semibold fs-7">Make payment for customer using billing ID</div>
                    </div>
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-light-danger" id="hidePaymentBtn">
                        <i class="ki-duotone ki-cross fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Close
                    </button>
                </div>
            </div>
            <div class="card-body py-6">
                <div class="row">
                    <div class="col-12 col-lg-7">
                        <form method="POST" action="{{ route('vendor.payments.initiate') }}" id="paymentForm" class="form">
                            @csrf
                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="fv-row">
                                        <label class="form-label fw-semibold fs-6">Customer Billing ID</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="ki-duotone ki-identification fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                            </span>
                                            <input type="text" name="billing_id" id="billing_id" class="form-control form-control-solid" placeholder="Enter billing ID" value="{{ old('billing_id') }}" required>
                                        </div>
                                        <div class="form-text">Enter the customer's billing ID to verify their details</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="fv-row">
                                        <label class="form-label fw-semibold fs-6">Amount</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₦</span>
                                            <input type="number" name="amount" class="form-control form-control-solid" step="0.01" min="0.01" placeholder="0.00" value="{{ old('amount') }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="fv-row">
                                        <label class="form-label fw-semibold fs-6">Payment Method</label>
                                        <select name="payment_type" class="form-select form-select-solid" required>
                                            <option value="">Select Method</option>
                                            <option value="online" {{ old('payment_type') == 'online' ? 'selected' : '' }}>
                                                <i class="ki-duotone ki-credit-card"></i> Pay Online (NABRoll)
                                            </option>
                                            <option value="account" {{ old('payment_type') == 'account' ? 'selected' : '' }}>
                                                <i class="ki-duotone ki-wallet"></i> Pay from Account Balance
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-column flex-sm-row gap-3 mt-6">
                                <button type="submit" class="btn btn-success flex-fill flex-sm-grow-0">
                                    <i class="ki-duotone ki-credit-card fs-3 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                    Process Payment
                                </button>
                                <button type="button" class="btn btn-light" id="hidePaymentBtn2">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-12 col-lg-5 mt-4 mt-lg-0">
                        <div class="bg-light-primary rounded p-4 p-lg-6" id="customerInfoSection" style="display: none;">
                            <div class="d-flex align-items-center mb-4">
                                <div class="symbol symbol-40px bg-primary me-3">
                                    <span class="symbol-label">
                                        <i class="ki-duotone ki-user text-white fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                </div>
                                <div>
                                    <h4 class="mb-0">Customer Information</h4>
                                    <div class="text-muted fs-7">Verified details will appear here</div>
                                </div>
                            </div>
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
        // Scroll to the section with offset for mobile
        const element = document.getElementById('fundAccountSection');
        const offset = 80; // Header offset
        const elementPosition = element.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - offset;

        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        });
    });

    // Hide fund account section (both buttons)
    document.getElementById('hideFundAccountBtn').addEventListener('click', function() {
        document.getElementById('fundAccountSection').style.display = 'none';
    });

    if (document.getElementById('hideFundAccountBtn2')) {
        document.getElementById('hideFundAccountBtn2').addEventListener('click', function() {
            document.getElementById('fundAccountSection').style.display = 'none';
        });
    }

    // Show/hide payment section
    document.getElementById('showPaymentBtn').addEventListener('click', function() {
        document.getElementById('paymentSection').style.display = 'block';
        // Scroll to the section with offset for mobile
        const element = document.getElementById('paymentSection');
        const offset = 80; // Header offset
        const elementPosition = element.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - offset;

        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        });
    });

    // Hide payment section (both buttons)
    document.getElementById('hidePaymentBtn').addEventListener('click', function() {
        document.getElementById('paymentSection').style.display = 'none';
    });

    if (document.getElementById('hidePaymentBtn2')) {
        document.getElementById('hidePaymentBtn2').addEventListener('click', function() {
            document.getElementById('paymentSection').style.display = 'none';
        });
    }
    
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
                            <div class="space-y-3">
                                <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-user fs-3 text-primary me-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                    <div>
                                        <div class="text-muted fs-8">Customer Name</div>
                                        <div class="fw-bold fs-6">${data.data.first_name} ${data.data.surname}</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-abstract-26 fs-3 text-info me-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                    <div>
                                        <div class="text-muted fs-8">Tariff Plan</div>
                                        <div class="fw-bold fs-6">${data.data.tariff}</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="ki-duotone ki-category fs-3 text-success me-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                    <div>
                                        <div class="text-muted fs-8">Category</div>
                                        <div class="fw-bold fs-6">${data.data.category}</div>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        customerInfoContent.innerHTML = `
                            <div class="alert alert-warning d-flex align-items-center">
                                <i class="ki-duotone ki-information-5 fs-3 me-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                <div>${data.message || 'Customer not found'}</div>
                            </div>
                        `;
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