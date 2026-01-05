@extends('layouts.staff')

@section('content')
<div id="kt_content_container" class="container-xxl">
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h2 class="fw-bold">Create Customer - Address Details</h2>
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
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
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
                    <a class="nav-link btn btn-color-muted px-0 {{ !session('customer_creation.personal') ? 'active' : '' }}" href="{{ route('staff.customers.create.personal') }}">
                        <span class="nav-text fw-semibold fs-4 mb-3">Personal Info</span>
                        <span class="badge badge-{{ session('customer_creation.personal') ? 'success' : 'warning' }} ms-2">
                            {{ session('customer_creation.personal') ? 'Completed' : 'Incomplete' }}
                        </span>
                        <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-0 bg-primary rounded"></span>
                    </a>
                </li>
                <li class="nav-item p-0 ms-0 me-8">
                    <a class="nav-link btn btn-color-muted px-0 {{ session('customer_creation.personal') ? 'active' : '' }}" href="{{ route('staff.customers.create.address') }}">
                        <span class="nav-text fw-semibold fs-4 mb-3">Address</span>
                        <span class="badge badge-{{ session('customer_creation.address') ? 'success' : 'warning' }} ms-2">
                            {{ session('customer_creation.address') ? 'Completed' : 'Incomplete' }}
                        </span>
                        <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-0 bg-primary rounded"></span>
                    </a>
                </li>
                <li class="nav-item p-0 ms-0 me-8">
                    <a class="nav-link btn btn-color-muted px-0 {{ session('customer_creation.address') ? '' : 'disabled' }}" href="{{ session('customer_creation.address') ? route('staff.customers.create.billing') : '#' }}" {{ !session('customer_creation.address') ? 'aria-disabled="true" style="pointer-events: none; opacity: 0.5;"' : '' }}>
                        <span class="nav-text fw-semibold fs-4 mb-3">Billing</span>
                        <span class="badge badge-{{ session('customer_creation.billing') ? 'success' : 'warning' }} ms-2">
                            {{ session('customer_creation.billing') ? 'Completed' : 'Incomplete' }}
                        </span>
                        <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-0 bg-primary rounded"></span>
                    </a>
                </li>
                <li class="nav-item p-0 ms-0">
                    <a class="nav-link btn btn-color-muted px-0 {{ session('customer_creation.billing') ? '' : 'disabled' }}" href="{{ session('customer_creation.billing') ? route('staff.customers.create.location') : '#' }}" {{ !session('customer_creation.billing') ? 'aria-disabled="true" style="pointer-events: none; opacity: 0.5;"' : '' }}>
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
                <!--begin::LGA Selection-->
                <div class="fv-row mb-7">
                    <label for="lga_id" class="fs-6 fw-semibold mb-2 required">Local Government Area</label>
                    <select name="lga_id" id="lga_id" class="form-select form-select-solid @error('lga_id') is-invalid @enderror" required>
                        <option value="">Select LGA</option>
                        @foreach ($lgas as $lga)
                            <option value="{{ $lga->id }}" {{ old('lga_id', session('customer_creation.address.lga_id')) == $lga->id ? 'selected' : '' }}>{{ $lga->name }}</option>
                        @endforeach
                    </select>
                    @error('lga_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!--end::LGA Selection-->

                <!--begin::Ward Selection-->
                <div class="fv-row mb-7">
                    <label for="ward_id" class="fs-6 fw-semibold mb-2 required">Ward</label>
                    <select name="ward_id" id="ward_id" class="form-select form-select-solid @error('ward_id') is-invalid @enderror" required>
                        <option value="">Select Ward</option>
                        @foreach ($wards as $ward)
                            <option value="{{ $ward->id }}" data-lga="{{ $ward->lga_id }}" {{ old('ward_id', session('customer_creation.address.ward_id')) == $ward->id ? 'selected' : '' }}>{{ $ward->name }}</option>
                        @endforeach
                    </select>
                    @error('ward_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!--end::Ward Selection-->

                <!--begin::Address Form-->
                <form action="{{ route('staff.customers.store.address') }}" method="POST">
                    @csrf
                    <input type="hidden" name="lga_id" id="hidden_lga_id" value="{{ old('lga_id', session('customer_creation.address.lga_id')) }}">
                    <input type="hidden" name="ward_id" id="hidden_ward_id" value="{{ old('ward_id', session('customer_creation.address.ward_id')) }}">
                    <div class="fv-row mb-7">
                        <label for="area_id" class="fs-6 fw-semibold mb-2 required">Area</label>
                        <select name="area_id" id="area_id" class="form-select form-select-solid @error('area_id') is-invalid @enderror" required>
                            <option value="">Select Area</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}" data-ward="{{ $area->ward_id }}" {{ old('area_id', session('customer_creation.address.area_id')) == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                            @endforeach
                        </select>
                        @error('area_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="fv-row mb-7">
                        <label for="street_name" class="fs-6 fw-semibold mb-2">Street Name</label>
                        <input type="text" name="street_name" id="street_name" class="form-control form-control-solid @error('street_name') is-invalid @enderror" value="{{ old('street_name', session('customer_creation.address.street_name')) }}">
                        @error('street_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="fv-row mb-7">
                        <label for="house_number" class="fs-6 fw-semibold mb-2">House Number</label>
                        <input type="text" name="house_number" id="house_number" class="form-control form-control-solid @error('house_number') is-invalid @enderror" value="{{ old('house_number', session('customer_creation.address.house_number')) }}">
                        @error('house_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="fv-row mb-7">
                        <label for="landmark" class="fs-6 fw-semibold mb-2 required">Landmark</label>
                        <input type="text" name="landmark" id="landmark" class="form-control form-control-solid @error('landmark') is-invalid @enderror" value="{{ old('landmark', session('customer_creation.address.landmark')) }}" required>
                        @error('landmark')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="text-center">
                        <a href="{{ route('staff.customers.index') }}" class="btn btn-light me-3">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Submit</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
                <!--end::Address Form-->
            </div>
            <!--end::Scroll-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get DOM elements
        const lgaSelect = document.getElementById('lga_id');
        const wardSelect = document.getElementById('ward_id');
        const areaSelect = document.getElementById('area_id');
        const hiddenLgaInput = document.getElementById('hidden_lga_id');
        const hiddenWardInput = document.getElementById('hidden_ward_id');

        // Store all options for filtering
        const allWards = Array.from(wardSelect.querySelectorAll('option[data-lga]'));
        const allAreas = Array.from(areaSelect.querySelectorAll('option[data-ward]'));

        // Filter wards based on selected LGA
        function filterWards() {
            const selectedLgaId = lgaSelect.value;
            
            // Update hidden input
            hiddenLgaInput.value = selectedLgaId;
            
            // Clear ward selection
            wardSelect.value = '';
            filterAreas(); // Also clear areas
            
            // Show/hide wards based on LGA
            allWards.forEach(option => {
                if (selectedLgaId === '' || option.dataset.lga === selectedLgaId) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });
        }

        // Filter areas based on selected ward
        function filterAreas() {
            const selectedWardId = wardSelect.value;
            
            // Update hidden input
            hiddenWardInput.value = selectedWardId;
            
            // Clear area selection
            areaSelect.value = '';
            
            // Show/hide areas based on ward
            allAreas.forEach(option => {
                if (selectedWardId === '' || option.dataset.ward === selectedWardId) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });
        }

        // Event listeners
        lgaSelect.addEventListener('change', filterWards);
        wardSelect.addEventListener('change', filterAreas);

        // Initialize filtering on page load
        filterWards();
    });
</script>
@endsection