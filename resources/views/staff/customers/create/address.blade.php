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
        <div class="card-body py-10 px-4 px-lg-17">
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
            <div id="tab-alert-container"></div>
            <div class="d-flex overflow-auto pb-2" id="tab-scroll-container">
                <ul class="nav nav-pills nav-pills-custom d-flex mt-3 flex-nowrap text-nowrap gap-1">
                    <li class="nav-item">
                        <a class="nav-link btn btn-active-light-primary d-flex align-items-center flex-column flex-sm-row px-2 py-2" href="{{ route('staff.customers.create.personal') }}">
                            <span class="nav-text fw-semibold fs-4">Personal Info</span>
                            <span class="badge badge-{{ session('customer_creation.personal') ? 'success' : 'warning' }} ms-2">
                                {{ session('customer_creation.personal') ? 'Completed' : 'Incomplete' }}
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-active-light-primary active d-flex align-items-center flex-column flex-sm-row px-2 py-2" href="{{ route('staff.customers.create.address') }}">
                            <span class="nav-text fw-semibold fs-4">Address</span>
                            <span class="badge badge-{{ session('customer_creation.address') ? 'success' : 'warning' }} ms-2">
                                {{ session('customer_creation.address') ? 'Completed' : 'Incomplete' }}
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-active-light-primary d-flex align-items-center flex-column flex-sm-row px-2 py-2" 
                           href="{{ session('customer_creation.address') ? route('staff.customers.create.billing') : '#' }}"
                           @if(!session('customer_creation.address')) onclick="showTabAlert('Please complete the Address step first.'); return false;" @endif
                           {{ !session('customer_creation.address') ? 'aria-disabled="true" style="opacity: 0.5;"' : '' }}>
                            <span class="nav-text fw-semibold fs-4">Billing</span>
                            <span class="badge badge-{{ session('customer_creation.billing') ? 'success' : 'warning' }} ms-2">
                                {{ session('customer_creation.billing') ? 'Completed' : 'Incomplete' }}
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-active-light-primary d-flex align-items-center flex-column flex-sm-row px-2 py-2" 
                           href="{{ session('customer_creation.billing') ? route('staff.customers.create.location') : '#' }}"
                           @if(!session('customer_creation.address') || !session('customer_creation.billing')) onclick="showTabAlert('Please complete the previous steps first.'); return false;" @endif
                           {{ !session('customer_creation.address') || !session('customer_creation.billing') ? 'aria-disabled="true" style="opacity: 0.5;"' : '' }}>
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
                                <div id="new-area-alert-container"></div>
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
                        <a href="{{ route('staff.customers.create.personal') }}" class="btn btn-light me-3">
                            <i class="ki-duotone ki-arrow-left fs-2 me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i> Back to Customers
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Save & Continue</span>
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
        const newAreaForm = document.getElementById('new-area-form');
        const addNewAreaBtn = document.getElementById('add-new-area-btn');
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
            
            // Clear area selection
            areaSelect.value = '';
            
            // Show/hide wards based on LGA
            allWards.forEach(option => {
                if (selectedLgaId === '' || option.dataset.lga === selectedLgaId) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });
            
            // Also filter areas based on the selected ward
            filterAreas();
        }

        // Filter areas based on selected ward via AJAX
        function filterAreas() {
            const selectedWardId = wardSelect.value;

            // Update hidden input
            hiddenWardInput.value = selectedWardId;

            // Clear area selection
            areaSelect.innerHTML = '<option value="">Select Area</option>';

            if (selectedWardId) {
                // Make AJAX call to get areas for this ward
                const formData = new FormData();
                formData.append('ward_id', selectedWardId);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                const xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route("staff.customers.filter.areas.customer") }}');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                xhr.onload = function() {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.options_html) {
                                areaSelect.innerHTML = response.options_html;
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                            areaSelect.innerHTML = '<option value="">Select Area</option><option value="">Error loading areas</option>';
                        }
                    } else {
                        areaSelect.innerHTML = '<option value="">Select Area</option><option value="">Error loading areas</option>';
                    }
                };

                xhr.onerror = function() {
                    areaSelect.innerHTML = '<option value="">Select Area</option><option value="">Error loading areas</option>';
                };

                xhr.send(formData);
            }
        }

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

            // Create form data
            const formData = new FormData();
            formData.append('name', areaName);
            formData.append('ward_id', selectedWardId);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            // Create and send request
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route("staff.areas.store") }}');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.onload = function() {
                createNewAreaBtn.disabled = false;
                createNewAreaBtn.querySelector('.indicator-label').style.display = 'inline';
                createNewAreaBtn.querySelector('.indicator-progress').style.display = 'none';

                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        const response = JSON.parse(xhr.responseText);

                        if (response.area && response.area.id) {
                            // Add the new area to the dropdown
                            const newOption = document.createElement('option');
                            newOption.value = response.area.id;
                            newOption.textContent = response.area.name;
                            newOption.setAttribute('data-ward', response.area.ward_id);
                            areaSelect.appendChild(newOption);

                            // Select the new area
                            areaSelect.value = response.area.id;

                            // Reset and hide the form
                            document.getElementById('new_area_name').value = '';
                            toggleNewAreaForm();

                            // Show success message using Bootstrap alert
                            showAlert('New area created successfully and pending approval.', 'success');
                        } else {
                            showAlert('Unexpected response from server.', 'danger');
                        }
                    } catch (e) {
                        showAlert('Unexpected response format from server.', 'danger');
                    }
                } else {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.errors && response.errors.name) {
                            document.getElementById('new_area_name_error').textContent = response.errors.name[0];
                        } else if (response.message) {
                            showAlert(response.message, 'danger');
                        } else {
                            showAlert('An error occurred while creating the area. Status: ' + xhr.status, 'danger');
                        }
                    } catch (e) {
                        showAlert('An error occurred while creating the area. Status: ' + xhr.status, 'danger');
                    }
                }
            };

            xhr.onerror = function() {
                createNewAreaBtn.disabled = false;
                createNewAreaBtn.querySelector('.indicator-label').style.display = 'inline';
                createNewAreaBtn.querySelector('.indicator-progress').style.display = 'none';
                showAlert('An error occurred while communicating with the server.', 'danger');
            };

            xhr.send(formData);
        }

        // Handle area selection change
        areaSelect.addEventListener('change', function() {
            if (areaSelect.value === 'add_new_area') {
                // Reset the selection to empty so user has to re-select after creating
                areaSelect.value = '';
                toggleNewAreaForm(true);
            }
        });

        // Event listeners
        lgaSelect.addEventListener('change', filterWards);
        wardSelect.addEventListener('change', filterAreas);
        cancelNewAreaBtn.addEventListener('click', function() {
            toggleNewAreaForm(false);
            // Reset form fields
            document.getElementById('new_area_name').value = '';
            document.getElementById('new_area_name_error').textContent = '';
        });
        createNewAreaBtn.addEventListener('click', createNewArea);

        // Initialize filtering on page load
        filterWards();

        // Function to show Bootstrap alerts
        function showAlert(message, type) {
            const alertContainer = document.getElementById('new-area-alert-container');

            // Remove any existing alerts
            alertContainer.innerHTML = '';

            // Create alert element
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} d-flex align-items-center fade show`;
            alertDiv.setAttribute('role', 'alert');
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} fs-4 me-3"></i>
                <span>${message}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;

            // Add alert to container
            alertContainer.appendChild(alertDiv);

            // Automatically hide success alerts after 5 seconds
            if (type === 'success') {
                setTimeout(function() {
                    if (alertDiv.parentNode) {
                        alertDiv.classList.remove('show');
                        alertDiv.classList.add('hide');
                        setTimeout(function() {
                            if (alertDiv.parentNode) {
                                alertDiv.parentNode.removeChild(alertDiv);
                            }
                        }, 150); // Match Bootstrap transition duration
                    }
                }, 5000);
            }
        }
    });
</script>
@endsection