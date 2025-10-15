@extends('layouts.staff')

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h2>Edit Customer - Address Information</h2>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ route('staff.customers.index') }}" class="btn btn-secondary">Back to Customers</a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Alerts -->
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
                    @if (session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('staff.customers.update.address', $customer) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="lga_id" class="required form-label">LGA</label>
                                <select class="form-select form-select-solid" name="lga_id" id="lga_id" required>
                                    <option value="">Select LGA</option>
                                    @foreach($lgas as $lga)
                                        <option value="{{ $lga->id }}" {{ old('lga_id', $customer->lga_id) == $lga->id ? 'selected' : '' }}>
                                            {{ $lga->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('lga_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label for="ward_id" class="required form-label">Ward</label>
                                <select class="form-select form-select-solid" name="ward_id" id="ward_id" required>
                                    <option value="">Select Ward</option>
                                    @foreach($wards as $ward)
                                        <option value="{{ $ward->id }}" {{ old('ward_id', $customer->ward_id) == $ward->id ? 'selected' : '' }} data-lga="{{ $ward->lga_id }}">
                                            {{ $ward->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ward_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label for="area_id" class="required form-label">Area</label>
                                <select class="form-select form-select-solid" name="area_id" id="area_id" required>
                                    <option value="">Select Area</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}" {{ old('area_id', $customer->area_id) == $area->id ? 'selected' : '' }} data-ward="{{ $area->ward_id }}">
                                            {{ $area->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('area_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="street_name" class="required form-label">Street Name</label>
                                <input type="text" class="form-control form-control-solid" name="street_name" id="street_name" value="{{ old('street_name', $customer->street_name) }}" required>
                                @error('street_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label for="house_number" class="required form-label">House Number</label>
                                <input type="text" class="form-control form-control-solid" name="house_number" id="house_number" value="{{ old('house_number', $customer->house_number) }}" required>
                                @error('house_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="landmark" class="required form-label">Landmark</label>
                            <input type="text" class="form-control form-control-solid" name="landmark" id="landmark" value="{{ old('landmark', $customer->landmark) }}" required>
                            @error('landmark')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Update Address Information</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Filter wards based on selected LGA
        document.getElementById('lga_id').addEventListener('change', function() {
            const selectedLgaId = this.value;
            const wardSelect = document.getElementById('ward_id');
            
            // Clear current options
            wardSelect.innerHTML = '<option value="">Select Ward</option>';
            
            if (selectedLgaId) {
                // Show only wards that belong to the selected LGA
                const allWardOptions = document.querySelectorAll('#ward_id option[data-lga]');
                allWardOptions.forEach(option => {
                    if (option.dataset.lga == selectedLgaId) {
                        wardSelect.appendChild(option.cloneNode(true));
                    }
                });
            }
        });
        
        // Filter areas based on selected ward
        document.getElementById('ward_id').addEventListener('change', function() {
            const selectedWardId = this.value;
            const areaSelect = document.getElementById('area_id');
            
            // Clear current options
            areaSelect.innerHTML = '<option value="">Select Area</option>';
            
            if (selectedWardId) {
                // Show only areas that belong to the selected ward
                const allAreaOptions = document.querySelectorAll('#area_id option[data-ward]');
                allAreaOptions.forEach(option => {
                    if (option.dataset.ward == selectedWardId) {
                        areaSelect.appendChild(option.cloneNode(true));
                    }
                });
            }
        });
        
        // Initialize filters on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Trigger change event on load to filter based on existing values
            const lgaId = document.getElementById('lga_id').value;
            if (lgaId) {
                document.getElementById('lga_id').dispatchEvent(new Event('change'));
                
                // After wards are filtered, trigger ward change to filter areas
                setTimeout(() => {
                    const wardId = document.getElementById('ward_id').value;
                    if (wardId) {
                        document.getElementById('ward_id').dispatchEvent(new Event('change'));
                    }
                }, 100);
            }
        });
    </script>
@endsection