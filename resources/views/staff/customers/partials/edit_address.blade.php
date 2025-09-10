<div class="card-body">
    <!-- LGA Selection -->
    <div class="row mb-6">
        <div class="col-md-6 fv-row">
            <label for="lga_id" class="form-label required">Local Government Area</label>
            <select class="form-select form-select-solid @error('lga_id') is-invalid @enderror" id="lga_id" name="lga_id" required>
                <option value="">Select LGA</option>
                @foreach ($lgas as $lga)
                    <option value="{{ $lga->id }}" {{ old('lga_id', $selectedLgaId ?? $customer->lga_id) == $lga->id ? 'selected' : '' }}>{{ $lga->name }}</option>
                @endforeach
            </select>
            @error('lga_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Ward Selection -->
    <div class="row mb-6">
        <div class="col-md-6 fv-row">
            <label for="ward_id" class="form-label required">Ward</label>
            <select class="form-select form-select-solid @error('ward_id') is-invalid @enderror" id="ward_id" name="ward_id" required>
                <option value="">Select Ward</option>
                @foreach ($wards as $ward)
                    <option value="{{ $ward->id }}" data-lga="{{ $ward->lga_id }}" {{ old('ward_id', $selectedWardId ?? $customer->ward_id) == $ward->id ? 'selected' : '' }}>{{ $ward->name }}</option>
                @endforeach
            </select>
            @error('ward_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Main Address Update Form -->
    <form id="edit-address-form" action="{{ route('staff.customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="part" value="address">
        <input type="hidden" name="lga_id" id="hidden_lga_id" value="{{ old('lga_id', $selectedLgaId ?? $customer->lga_id) }}">
        <input type="hidden" name="ward_id" id="hidden_ward_id" value="{{ old('ward_id', $selectedWardId ?? $customer->ward_id) }}">
        <div class="row mb-6">
            <div class="col-md-6 fv-row">
                <label for="area_id" class="form-label required">Area</label>
                <select class="form-select form-select-solid @error('area_id') is-invalid @enderror" id="area_id" name="area_id" required>
                    <option value="">Select Area</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}" data-ward="{{ $area->ward_id }}" {{ old('area_id', $customer->area_id) == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                    @endforeach
                </select>
                @error('area_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 fv-row">
                <label for="street_name" class="form-label required">Street Name</label>
                <input type="text" class="form-control form-control-solid @error('street_name') is-invalid @enderror" id="street_name" name="street_name" value="{{ old('street_name', $customer->street_name) }}" required>
                @error('street_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-md-6 fv-row">
                <label for="house_number" class="form-label required">House Number</label>
                <input type="text" class="form-control form-control-solid @error('house_number') is-invalid @enderror" id="house_number" name="house_number" value="{{ old('house_number', $customer->house_number) }}" required>
                @error('house_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 fv-row">
                <label for="landmark" class="form-label required">Landmark</label>
                <input type="text" class="form-control form-control-solid @error('landmark') is-invalid @enderror" id="landmark" name="landmark" value="{{ old('landmark', $customer->landmark) }}" required>
                @error('landmark')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-end">
                <a href="{{ route('staff.customers.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Submit for Approval</button>
            </div>
        </div>
    </form>
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