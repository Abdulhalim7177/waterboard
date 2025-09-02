@extends('layouts.staff')

@section('content')
<div id="kt_content_container" class="container-xxl">
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h2 class="fw-bold">Create Customer - Billing Information</h2>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('staff.customers.index') }}" class="btn btn-light btn-sm">
                    <i class="ki-duotone ki-arrow-left fs-2 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i> Back to Customers
                </a>
            </div>
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body py-10 px-lg-17">
            <!--begin::Alerts-->
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <!--end::Alerts-->

            <!--begin::Tab Navigation-->
            <ul class="nav nav-stretch nav-pills nav-pills-custom d-flex mt-3">
                <li class="nav-item p-0 ms-0 me-8">
                    <a class="nav-link btn btn-color-muted px-0" href="{{ route('staff.customers.create.personal') }}">
                        <span class="nav-text fw-semibold fs-4 mb-3">Personal Info</span>
                        <span class="badge badge-{{ session('customer_creation.personal') ? 'success' : 'warning' }} ms-2">
                            {{ session('customer_creation.personal') ? 'Completed' : 'Incomplete' }}
                        </span>
                        <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-0 bg-primary rounded"></span>
                    </a>
                </li>
                <li class="nav-item p-0 ms-0 me-8">
                    <a class="nav-link btn btn-color-muted px-0" href="{{ route('staff.customers.create.address') }}">
                        <span class="nav-text fw-semibold fs-4 mb-3">Address</span>
                        <span class="badge badge-{{ session('customer_creation.address') ? 'success' : 'warning' }} ms-2">
                            {{ session('customer_creation.address') ? 'Completed' : 'Incomplete' }}
                        </span>
                        <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-0 bg-primary rounded"></span>
                    </a>
                </li>
                <li class="nav-item p-0 ms-0 me-8">
                    <a class="nav-link btn btn-color-muted active px-0" href="{{ route('staff.customers.create.billing') }}">
                        <span class="nav-text fw-semibold fs-4 mb-3">Billing</span>
                        <span class="badge badge-{{ session('customer_creation.billing') ? 'success' : 'warning' }} ms-2">
                            {{ session('customer_creation.billing') ? 'Completed' : 'Incomplete' }}
                        </span>
                        <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-0 bg-primary rounded"></span>
                    </a>
                </li>
                <li class="nav-item p-0 ms-0">
                    <a class="nav-link btn btn-color-muted px-0" href="{{ route('staff.customers.create.location') }}" {{ !session('customer_creation.personal') || !session('customer_creation.address') || !session('customer_creation.billing') ? 'aria-disabled="true" style="pointer-events: none; opacity: 0.5;"' : '' }}>
                        <span class="nav-text fw-semibold fs-4 mb-3">Location</span>
                        <span class="badge badge-{{ session('customer_creation.location') ? 'success' : 'warning' }} ms-2">
                            {{ session('customer_creation.location') ? 'Completed' : 'Incomplete' }}
                        </span>
                        <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-0 bg-primary rounded"></span>
                    </a>
                </li>
            </ul>
            <!--end::Tab Navigation-->

            <!--begin::Forms-->
            <div class="scroll-y me-n7 pe-7" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_customer_header" data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
                <!--begin::Category Filter Form-->
                <form action="{{ route('staff.customers.create.filter.tariffs') }}" method="POST" class="mb-7">
                    @csrf
                    <div class="fv-row mb-7">
                        <label for="category_id" class="fs-6 fw-semibold mb-2 required">Tariff Category</label>
                        <select class="form-select form-select-solid @error('category_id') is-invalid @enderror" id="category_id" name="category_id" onchange="this.form.submit()" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', session('customer_creation.billing.category_id')) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ $category->description }})
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if ($categories->isEmpty())
                            <div class="alert alert-warning mt-2">
                                No categories available. Please contact an administrator to add categories.
                            </div>
                        @endif
                    </div>
                </form>
                <!--end::Category Filter Form-->

                <!--begin::Billing Form-->
                @if ($categories->isNotEmpty())
                    <form action="{{ route('staff.customers.store.billing') }}" method="POST">
                        @csrf
                        <input type="hidden" name="category_id" value="{{ old('category_id', session('customer_creation.billing.category_id')) }}">
                        <div class="row g-9 mb-7">
                            <div class="col-md-6 fv-row">
                                <label for="tariff_id" class="fs-6 fw-semibold mb-2 required">Tariff</label>
                                <select class="form-select form-select-solid @error('tariff_id') is-invalid @enderror" id="tariff_id" name="tariff_id" required>
                                    <option value="">Select Tariff</option>
                                    @foreach ($tariffs as $tariff)
                                        <option value="{{ $tariff->id }}" {{ old('tariff_id', session('customer_creation.billing.tariff_id')) == $tariff->id ? 'selected' : '' }}>
                                            {{ $tariff->name }} (Catcode: {{ $tariff->catcode }}, Amount: {{ $tariff->amount }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('tariff_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if ($tariffs->isEmpty() && session('customer_creation.billing.category_id'))
                                    <div class="alert alert-warning mt-2">
                                        No tariffs available for the selected category. Please select another category or contact an administrator.
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="delivery_code" class="fs-6 fw-semibold mb-2">Delivery Code</label>
                                <input type="text" class="form-control form-control-solid @error('delivery_code') is-invalid @enderror" id="delivery_code" name="delivery_code" value="{{ old('delivery_code', session('customer_creation.billing.delivery_code')) }}">
                                @error('delivery_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-9 mb-7">
                            <div class="col-md-6 fv-row">
                                <label for="billing_condition" class="fs-6 fw-semibold mb-2 required">Billing Condition</label>
                                <select class="form-select form-select-solid @error('billing_condition') is-invalid @enderror" id="billing_condition" name="billing_condition" required>
                                    <option value="">Select Billing Condition</option>
                                    <option value="Metered" {{ old('billing_condition', session('customer_creation.billing.billing_condition')) == 'Metered' ? 'selected' : '' }}>Metered</option>
                                    <option value="Non-Metered" {{ old('billing_condition', session('customer_creation.billing.billing_condition')) == 'Non-Metered' ? 'selected' : '' }}>Non-Metered</option>
                                </select>
                                @error('billing_condition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="water_supply_status" class="fs-6 fw-semibold mb-2 required">Water Supply Status</label>
                                <select class="form-select form-select-solid @error('water_supply_status') is-invalid @enderror" id="water_supply_status" name="water_supply_status" required>
                                    <option value="">Select Water Supply Status</option>
                                    <option value="Functional" {{ old('water_supply_status', session('customer_creation.billing.water_supply_status')) == 'Functional' ? 'selected' : '' }}>Functional</option>
                                    <option value="Non-Functional" {{ old('water_supply_status', session('customer_creation.billing.water_supply_status')) == 'Non-Functional' ? 'selected' : '' }}>Non-Functional</option>
                                </select>
                                @error('water_supply_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('staff.customers.create.address') }}" class="btn btn-light me-3">Previous</a>
                            <button type="submit" class="btn btn-primary" @if ($tariffs->isEmpty()) disabled @endif>
                                <span class="indicator-label">Next: Location</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                            <a href="{{ route('staff.customers.index') }}" class="btn btn-light me-3">Cancel</a>
                        </div>
                    </form>
                @endif
                <!--end::Billing Form-->
            </div>
            <!--end::Scroll-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</div>
@endsection