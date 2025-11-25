@extends('layouts.staff')

@section('content')
<div id="kt_content_container" class="container-xxl">
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h2 class="fw-bold">Create Customer - Personal Information</h2>
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
        <div class="card-body py-10 px-4 px-lg-17">
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
            <div id="tab-alert-container"></div>
            <div class="d-flex overflow-auto pb-2" id="tab-scroll-container">
                <ul class="nav nav-pills nav-pills-custom d-flex mt-3 flex-nowrap text-nowrap gap-1">
                    <li class="nav-item">
                        <a class="nav-link btn btn-active-light-primary active d-flex align-items-center flex-column flex-sm-row px-2 py-2" href="{{ route('staff.customers.create.personal') }}">
                            <span class="nav-text fw-semibold fs-4">Personal Info</span>
                            <span class="badge badge-{{ session('customer_creation.personal') ? 'success' : 'warning' }} ms-2">
                                {{ session('customer_creation.personal') ? 'Completed' : 'Incomplete' }}
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-active-light-primary d-flex align-items-center flex-column flex-sm-row px-2 py-2" 
                           href="{{ session('customer_creation.personal') ? route('staff.customers.create.address') : '#' }}"
                           @if(!session('customer_creation.personal')) onclick="showTabAlert('Please complete the Personal Info step first.'); return false;" @endif
                           {{ !session('customer_creation.personal') ? 'aria-disabled="true" style="opacity: 0.5;"' : '' }}>
                            <span class="nav-text fw-semibold fs-4">Address</span>
                            <span class="badge badge-{{ session('customer_creation.address') ? 'success' : 'warning' }} ms-2">
                                {{ session('customer_creation.address') ? 'Completed' : 'Incomplete' }}
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-active-light-primary d-flex align-items-center flex-column flex-sm-row px-2 py-2" 
                           href="{{ session('customer_creation.address') ? route('staff.customers.create.billing') : '#' }}"
                           @if(!session('customer_creation.personal') || !session('customer_creation.address')) onclick="showTabAlert('Please complete the Personal Info and Address steps first.'); return false;" @endif
                           {{ !session('customer_creation.personal') || !session('customer_creation.address') ? 'aria-disabled="true" style="opacity: 0.5;"' : '' }}>
                            <span class="nav-text fw-semibold fs-4">Billing</span>
                            <span class="badge badge-{{ session('customer_creation.billing') ? 'success' : 'warning' }} ms-2">
                                {{ session('customer_creation.billing') ? 'Completed' : 'Incomplete' }}
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-active-light-primary d-flex align-items-center flex-column flex-sm-row px-2 py-2" 
                           href="{{ session('customer_creation.billing') ? route('staff.customers.create.location') : '#' }}"
                           @if(!session('customer_creation.personal') || !session('customer_creation.address') || !session('customer_creation.billing')) onclick="showTabAlert('Please complete the previous steps first.'); return false;" @endif
                           {{ !session('customer_creation.personal') || !session('customer_creation.address') || !session('customer_creation.billing') ? 'aria-disabled="true" style="opacity: 0.5;"' : '' }}>
                            <span class="nav-text fw-semibold fs-4">Location</span>
                            <span class="badge badge-{{ session('customer_creation.location') ? 'success' : 'warning' }} ms-2">
                                {{ session('customer_creation.location') ? 'Completed' : 'Incomplete' }}
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const activeTab = document.querySelector('.nav-link.active');
                    if (activeTab) {
                        activeTab.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                    }
                });

                function showTabAlert(message) {
                    const container = document.getElementById('tab-alert-container');
                    if (container) {
                        container.innerHTML = `
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                ${message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `;
                        container.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            </script>
            <!--end::Tab Navigation-->

            <!--begin::Form-->
            <div class="scroll-y me-n7 pe-7" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_customer_header" data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
                <form class="p-3" action="{{ route('staff.customers.store.personal') }}" method="POST">
                    @csrf
                    <div class="row g-9 mb-7">
                        <div class="col-md-6 fv-row">
                            <label for="first_name" class="fs-6 fw-semibold mb-2 required">First Name</label>
                            <input type="text" class="form-control form-control-solid @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', session('customer_creation.personal.first_name')) }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 fv-row">
                            <label for="surname" class="fs-6 fw-semibold mb-2 required">Last Name</label>
                            <input type="text" class="form-control form-control-solid @error('surname') is-invalid @enderror" id="surname" name="surname" value="{{ old('surname', session('customer_creation.personal.surname')) }}" required>
                            @error('surname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-9 mb-7">
                        <div class="col-md-6 fv-row">
                            <label for="middle_name" class="fs-6 fw-semibold mb-2">Middle Name</label>
                            <input type="text" class="form-control form-control-solid @error('middle_name') is-invalid @enderror" id="middle_name" name="middle_name" value="{{ old('middle_name', session('customer_creation.personal.middle_name')) }}">
                            @error('middle_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 fv-row">
                            <label for="email" class="fs-6 fw-semibold mb-2 required">Email Address</label>
                            <input type="email" class="form-control form-control-solid @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', session('customer_creation.personal.email')) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-9 mb-7">
                        <div class="col-md-6 fv-row">
                            <label for="phone_number" class="fs-6 fw-semibold mb-2 required">Primary Phone Number</label>
                            <input type="text" inputmode="numeric" class="form-control form-control-solid @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number', session('customer_creation.personal.phone_number')) }}" required>
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 fv-row">
                            <label for="alternate_phone_number" class="fs-6 fw-semibold mb-2">Alternate Phone Number</label>
                            <input type="text" inputmode="numeric" class="form-control form-control-solid @error('alternate_phone_number') is-invalid @enderror" id="alternate_phone_number" name="alternate_phone_number" value="{{ old('alternate_phone_number', session('customer_creation.personal.alternate_phone_number')) }}">
                            @error('alternate_phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('staff.customers.index') }}" class="btn btn-light me-3">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Save & Continue</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
            <!--end::Scroll-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</div>
@endsection