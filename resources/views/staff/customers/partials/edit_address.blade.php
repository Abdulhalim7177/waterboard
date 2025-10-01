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
        <a class="nav-link btn btn-color-muted px-0" href="javascript:void(0)" onclick="loadSection('personal')">
            <span class="nav-text fw-semibold fs-4 mb-3">Personal Info</span>
            <span class="badge badge-warning ms-2">Edit Mode</span>
            <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-0 bg-primary rounded"></span>
        </a>
    </li>
    <li class="nav-item p-0 ms-0 me-8">
        <a class="nav-link btn btn-color-muted active px-0" href="javascript:void(0)">
            <span class="nav-text fw-semibold fs-4 mb-3">Address</span>
            <span class="badge badge-success ms-2">Edit Mode</span>
            <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-0 bg-primary rounded"></span>
        </a>
    </li>
    <li class="nav-item p-0 ms-0 me-8">
        <a class="nav-link btn btn-color-muted px-0" href="javascript:void(0)" onclick="loadSection('billing')">
            <span class="nav-text fw-semibold fs-4 mb-3">Billing</span>
            <span class="badge badge-warning ms-2">Edit Mode</span>
            <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-0 bg-primary rounded"></span>
        </a>
    </li>
    <li class="nav-item p-0 ms-0">
        <a class="nav-link btn btn-color-muted px-0" href="javascript:void(0)" onclick="loadSection('location')">
            <span class="nav-text fw-semibold fs-4 mb-3">Location</span>
            <span class="badge badge-warning ms-2">Edit Mode</span>
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
                <option value="{{ $lga->id }}" {{ old('lga_id', $selectedLgaId ?? $customer->lga_id) == $lga->id ? 'selected' : '' }}>{{ $lga->name }}</option>
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
                <option value="{{ $ward->id }}" data-lga="{{ $ward->lga_id }}" {{ old('ward_id', $selectedWardId ?? $customer->ward_id) == $ward->id ? 'selected' : '' }}>{{ $ward->name }}</option>
            @endforeach
        </select>
        @error('ward_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <!--end::Ward Selection-->

    <!--begin::Address Form-->
    <form id="edit-address-form" action="{{ route('staff.customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="part" value="address">
        <input type="hidden" name="lga_id" id="hidden_lga_id" value="{{ old('lga_id', $selectedLgaId ?? $customer->lga_id) }}">
        <input type="hidden" name="ward_id" id="hidden_ward_id" value="{{ old('ward_id', $selectedWardId ?? $customer->ward_id) }}">
        <div class="fv-row mb-7">
            <label for="area_id" class="fs-6 fw-semibold mb-2 required">Area</label>
            <select name="area_id" id="area_id" class="form-select form-select-solid @error('area_id') is-invalid @enderror" required>
                <option value="">Select Area</option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}" data-ward="{{ $area->ward_id }}" {{ old('area_id', $customer->area_id) == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                @endforeach
                <option value="add_new_area">+ Add New Area</option>
            </select>
            @error('area_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- New Area Form (Hidden by default) -->
        <div id="new-area-form" class="fv-row mb-7" style="display: none;">
            <div class="card card-flush">
                <div class="card-header">
                    <h3 class="card-title">Add New Area</h3>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-sm btn-light-danger" id="cancel-new-area-btn">
                            <i class="ki-duotone ki-cross fs-2"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="fv-row mb-5">
                        <label for="new_area_name" class="fs-6 fw-semibold mb-2 required">New Area Name</label>
                        <input type="text" id="new_area_name" class="form-control form-control-solid" placeholder="Enter area name">
                        <div class="invalid-feedback" id="new_area_name_error"></div>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-primary" id="create-new-area-btn">
                            <span class="indicator-label">Create Area</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="fv-row mb-7">
            <label for="street_name" class="fs-6 fw-semibold mb-2 required">Street Name</label>
            <input type="text" name="street_name" id="street_name" class="form-control form-control-solid @error('street_name') is-invalid @enderror" value="{{ old('street_name', $customer->street_name) }}" required>
            @error('street_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="fv-row mb-7">
            <label for="house_number" class="fs-6 fw-semibold mb-2 required">House Number</label>
            <input type="text" name="house_number" id="house_number" class="form-control form-control-solid @error('house_number') is-invalid @enderror" value="{{ old('house_number', $customer->house_number) }}" required>
            @error('house_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="fv-row mb-7">
            <label for="landmark" class="fs-6 fw-semibold mb-2 required">Landmark</label>
            <input type="text" name="landmark" id="landmark" class="form-control form-control-solid @error('landmark') is-invalid @enderror" value="{{ old('landmark', $customer->landmark) }}" required>
            @error('landmark')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="text-center">
            <a href="{{ route('staff.customers.index') }}" class="btn btn-light me-3">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <span class="indicator-label">Submit for Approval</span>
                <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>
    </form>
    <!--end::Address Form-->
</div>
<!--end::Scroll-->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get DOM elements
        const lgaSelect = document.getElementById('lga_id');
        const wardSelect = document.getElementById('ward_id');
        const areaSelect = document.getElementById('area_id');
        const hiddenLgaInput = document.getElementById('hidden_lga_id');
        const hiddenWardInput = document.getElementById('hidden_ward_id');
        const newAreaForm = document.getElementById('new-area-form');
        const cancelNewAreaBtn = document.getElementById('cancel-new-area-btn');
        const createNewAreaBtn = document.getElementById('create-new-area-btn');

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

        // Toggle new area form
        function toggleNewAreaForm(show = true) {
            if (show) {
                newAreaForm.style.display = 'block';
            } else {
                newAreaForm.style.display = 'none';
            }
        }

        // Create new area
        async function createNewArea() {
            const areaName = document.getElementById('new_area_name').value;
            const selectedWardId = wardSelect.value;

            if (!selectedWardId) {
                alert('Please select a ward first before creating an area.');
                return;
            }

            // Clear previous errors
            document.getElementById('new_area_name_error').textContent = '';

            createNewAreaBtn.disabled = true;
            createNewAreaBtn.querySelector('.indicator-label').style.display = 'none';
            createNewAreaBtn.querySelector('.indicator-progress').style.display = 'inline';

            try {
                const response = await fetch('{{ route("staff.areas.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        name: areaName,
                        ward_id: selectedWardId
                    })
                });

                // Check if response is JSON before parsing
                const contentType = response.headers.get('content-type');
                let data = {};

                if (contentType && contentType.includes('application/json')) {
                    data = await response.json();
                } else {
                    // If it's not JSON, we have a different kind of error
                    alert('Server returned an unexpected response format. Status: ' + response.status);
                    return;
                }

                if (response.ok) {
                    // Add the new area to the dropdown
                    const newOption = document.createElement('option');
                    newOption.value = data.area.id;
                    newOption.textContent = data.area.name;
                    newOption.setAttribute('data-ward', data.area.ward_id);
                    areaSelect.appendChild(newOption);

                    // Select the new area
                    areaSelect.value = data.area.id;

                    // Reset and hide the form
                    document.getElementById('new_area_name').value = '';
                    toggleNewAreaForm();

                    // Show success message
                    alert('New area created successfully and pending approval.');
                } else {
                    // Handle different types of errors
                    if (data && data.errors) {
                        // Validation errors
                        if (data.errors.name) {
                            document.getElementById('new_area_name_error').textContent = data.errors.name[0];
                        }
                        if (data.errors.ward_id) {
                            alert(data.errors.ward_id[0]);
                        }
                    } else if (data && data.message) {
                        // Error message from server
                        alert(data.message);
                    } else {
                        // General error
                        alert('An error occurred while creating the area. Status: ' + response.status);
                    }
                }
            } catch (error) {
                console.error('Error creating area:', error);
                alert('An error occurred while creating the area.');
            } finally {
                createNewAreaBtn.disabled = false;
                createNewAreaBtn.querySelector('.indicator-label').style.display = 'inline';
                createNewAreaBtn.querySelector('.indicator-progress').style.display = 'none';
            }
        }

        // Handle area selection change
        areaSelect.addEventListener('change', function() {
            if (areaSelect.value === 'add_new_area') {
                // Reset the selection to empty so user has to re-select after creating
                areaSelect.value = '';
                toggleNewAreaForm(true);
            }
        });

        // Event listeners for new area functionality
        document.getElementById('cancel-new-area-btn').addEventListener('click', function() {
            toggleNewAreaForm(false);
            // Reset form fields
            document.getElementById('new_area_name').value = '';
            document.getElementById('new_area_name_error').textContent = '';
        });
        document.getElementById('create-new-area-btn').addEventListener('click', createNewArea);
    });
</script>