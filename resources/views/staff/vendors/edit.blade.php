@extends('layouts.staff')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Edit Vendor</h2>
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Vendor Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.vendors.update', $vendor) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $vendor->name) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $vendor->email) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password (Leave blank to keep current password)</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>

                        <div class="mb-3">
                            <label for="street_name" class="form-label">Street Name</label>
                            <input type="text" class="form-control" id="street_name" name="street_name" value="{{ old('street_name', $vendor->street_name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="vendor_code" class="form-label">Vendor Code</label>
                            <input type="text" class="form-control" id="vendor_code" name="vendor_code" value="{{ old('vendor_code', $vendor->vendor_code) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="lga_id" class="form-label">LGA</label>
                            <select class="form-control" id="lga_id" name="lga_id" required>
                                <option value="">Select LGA</option>
                                @foreach($lgas as $lga)
                                    <option value="{{ $lga->id }}" {{ old('lga_id', $vendor->lga_id) == $lga->id ? 'selected' : '' }}>{{ $lga->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="ward_id" class="form-label">Ward</label>
                            <select class="form-control" id="ward_id" name="ward_id" required>
                                <option value="">Select Ward</option>
                                @foreach($wards as $ward)
                                    <option value="{{ $ward->id }}" {{ old('ward_id', $vendor->ward_id) == $ward->id ? 'selected' : '' }}>{{ $ward->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="area_id" class="form-label">Area</label>
                            <select class="form-control" id="area_id" name="area_id" required>
                                <option value="">Select Area</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}" {{ old('area_id', $vendor->area_id) == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update Vendor</button>
                        <a href="{{ route('staff.vendors.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
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
            const currentWardId = '{{ old('ward_id', $vendor->ward_id) }}';
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

                // Set selected value if it's in old input or the vendor's existing ward
                if (currentWardId && allWards.some(w => w.id == currentWardId && w.lga_id == selectedLgaId)) {
                    wardSelect.value = currentWardId;
                }
            }

            // Clear areas when LGA changes
            areaSelect.innerHTML = '<option value="">Select Area</option>';
        }

        function filterAreas() {
            const selectedWardId = wardSelect.value;
            const currentAreaId = '{{ old('area_id', $vendor->area_id) }}';
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

                // Set selected value if it's in old input or the vendor's existing area
                if (currentAreaId && allAreas.some(a => a.id == currentAreaId && a.ward_id == selectedWardId)) {
                    areaSelect.value = currentAreaId;
                }
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

        // Get initial values
        const initialWardId = '{{ old('ward_id', $vendor->ward_id) }}';
        const initialAreaId = '{{ old('area_id', $vendor->area_id) }}';

        // Initialize filtering on page load
        filterWards();
        if (initialWardId) {
            wardSelect.value = initialWardId;
            filterAreas();
            if (initialAreaId) {
                areaSelect.value = initialAreaId;
            }
        }
    });
</script>
@endsection