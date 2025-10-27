@extends('layouts.staff')

@section('content')
        <div class="card w-100" style="max-width: 800px;">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-shop fs-1 me-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <h2 class="fw-bold">Add New Vendor</h2>
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <div class="d-flex align-items-center gap-2 gap-lg-3">
                            <a href="{{ route('staff.vendors.index') }}" class="btn btn-light">
                                <i class="ki-duotone ki-arrow-left fs-2"></i>
                                Back to Vendors
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body py-8">
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h4 class="alert-heading">Please fix the following errors:</h4>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <form action="{{ route('staff.vendors.store') }}" method="POST" class="form">
                    @csrf

                    <div class="row mb-6">
                        <!-- Personal Information Section -->
                        <div class="col-md-6 fv-row">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span class="required">Name</span>
                            </label>
                            <input type="text" class="form-control form-control-solid" placeholder="Enter vendor name" name="name" value="{{ old('name') }}" required />
                        </div>

                        <div class="col-md-6 fv-row">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span class="required">Email</span>
                            </label>
                            <input type="email" class="form-control form-control-solid" placeholder="vendor@example.com" name="email" value="{{ old('email') }}" required />
                        </div>
                    </div>

                    <div class="row mb-6">
                        <!-- Password Section -->
                        <div class="col-md-6 fv-row">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span class="required">Password</span>
                            </label>
                            <input type="password" class="form-control form-control-solid" placeholder="Enter password" name="password" required />
                        </div>

                        <div class="col-md-6 fv-row">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span class="required">Confirm Password</span>
                            </label>
                            <input type="password" class="form-control form-control-solid" placeholder="Confirm password" name="password_confirmation" required />
                        </div>
                    </div>

                    <div class="row mb-6">
                        <!-- Vendor Details Section -->
                        <div class="col-md-6 fv-row">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span class="required">Street Name</span>
                            </label>
                            <input type="text" class="form-control form-control-solid" placeholder="Enter street name" name="street_name" value="{{ old('street_name') }}" required />
                        </div>

                        <div class="col-md-6 fv-row">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span class="required">Vendor Code</span>
                            </label>
                            <input type="text" class="form-control form-control-solid" placeholder="Enter vendor code" name="vendor_code" value="{{ old('vendor_code') }}" required />
                        </div>
                    </div>

                    <div class="row mb-6">
                        <!-- Location Section -->
                        <div class="col-md-4 fv-row">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span class="required">LGA</span>
                            </label>
                            <select class="form-select form-select-solid" data-control="select2" data-placeholder="Select LGA" name="lga_id" id="lga_id" required>
                                <option value="">Select LGA</option>
                                @foreach($lgas as $lga)
                                    <option value="{{ $lga->id }}" {{ old('lga_id') == $lga->id ? 'selected' : '' }}>{{ $lga->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 fv-row">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span class="required">Ward</span>
                            </label>
                            <select class="form-select form-select-solid" data-control="select2" data-placeholder="Select Ward" name="ward_id" id="ward_id" required>
                                <option value="">Select Ward</option>
                                @foreach($wards as $ward)
                                    <option value="{{ $ward->id }}" {{ old('ward_id') == $ward->id ? 'selected' : '' }}>{{ $ward->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 fv-row">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span class="required">Area</span>
                            </label>
                            <select class="form-select form-select-solid" data-control="select2" data-placeholder="Select Area" name="area_id" id="area_id" required>
                                <option value="">Select Area</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="separator mb-8"></div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('staff.vendors.index') }}" class="btn btn-light me-3">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ki-duotone ki-check fs-2 me-2"></i>
                            Create Vendor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    // Store all ward and area data for client-side filtering
    const allWards = @json($wards->toArray());
    const allAreas = @json($areas->toArray());

    document.addEventListener('DOMContentLoaded', function() {
        const lgaSelect = document.getElementById('lga_id');
        const wardSelect = document.getElementById('ward_id');
        const areaSelect = document.getElementById('area_id');

        function filterWards() {
            const selectedLgaId = lgaSelect.value;
            wardSelect.innerHTML = '<option value="">Select Ward</option>';

            if (selectedLgaId) {
                allWards.forEach(ward => {
                    if (ward.lga_id == selectedLgaId) {
                        const option = document.createElement('option');
                        option.value = ward.id;
                        option.textContent = ward.name;
                        wardSelect.appendChild(option);
                    }
                });
            }

            // Clear areas when LGA changes
            areaSelect.innerHTML = '<option value="">Select Area</option>';
        }

        function filterAreas() {
            const selectedWardId = wardSelect.value;
            areaSelect.innerHTML = '<option value="">Select Area</option>';

            if (selectedWardId) {
                allAreas.forEach(area => {
                    if (area.ward_id == selectedWardId) {
                        const option = document.createElement('option');
                        option.value = area.id;
                        option.textContent = area.name;
                        areaSelect.appendChild(option);
                    }
                });
            }
        }

        lgaSelect.addEventListener('change', function() {
            wardSelect.value = '';
            areaSelect.value = '';
            filterWards();
        });

        wardSelect.addEventListener('change', function() {
            areaSelect.value = '';
            filterAreas();
        });
    });
</script>
@endsection