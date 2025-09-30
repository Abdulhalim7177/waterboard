<div class="card-body">
    <form id="edit-location-form" action="{{ url('mngr-secure-9374/hr/staff/' . $staff->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="part" value="location">
        <div class="row mb-7">
            <div class="col-lg-3">
                <div class="d-flex flex-column align-items-center text-center mb-7">
                    <div class="mb-7">
                        <div class="symbol symbol-125px symbol-circle">
                            <div class="symbol-label fs-1 bg-light-primary text-primary">L</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="fw-bolder fs-3">Location Info</div>
                        <div class="text-muted fw-bold mt-1">Geographic details</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="row mb-5">
                    <div class="col-md-3 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2">Paypoint</label>
                        <select name="paypoint_id" class="form-control form-control-solid @error('paypoint_id') is-invalid @enderror" data-control="select2">
                            <option value="">Select Paypoint</option>
                            @foreach ($paypoints as $paypoint)
                                <option value="{{ $paypoint->id }}" {{ old('paypoint_id', $staff->paypoint_id) == $paypoint->id ? 'selected' : '' }}>{{ $paypoint->name }}</option>
                            @endforeach
                        </select>
                        @error('paypoint_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2">Zone</label>
                        <select name="zone_id" class="form-control form-control-solid @error('zone_id') is-invalid @enderror" data-control="select2">
                            <option value="">Select Zone</option>
                            @foreach ($zones as $zone)
                                <option value="{{ $zone->id }}" {{ old('zone_id', $staff->zone_id) == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
                            @endforeach
                        </select>
                        @error('zone_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2">District</label>
                        <select name="district_id" class="form-control form-control-solid @error('district_id') is-invalid @enderror" data-control="select2">
                            <option value="">Select District</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}" {{ old('district_id', $staff->district_id) == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                            @endforeach
                        </select>
                        @error('district_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2">LGA</label>
                        <select name="lga_id" class="form-control form-control-solid @error('lga_id') is-invalid @enderror" data-control="select2">
                            <option value="">Select LGA</option>
                            @foreach ($lgas as $lga)
                                <option value="{{ $lga->id }}" {{ old('lga_id', $staff->lga_id) == $lga->id ? 'selected' : '' }}>{{ $lga->name }}</option>
                            @endforeach
                        </select>
                        @error('lga_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-6 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2">Ward</label>
                        <select name="ward_id" class="form-control form-control-solid @error('ward_id') is-invalid @enderror" data-control="select2">
                            <option value="">Select Ward</option>
                            @foreach ($wards as $ward)
                                <option value="{{ $ward->id }}" {{ old('ward_id', $staff->ward_id) == $ward->id ? 'selected' : '' }}>{{ $ward->name }}</option>
                            @endforeach
                        </select>
                        @error('ward_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 fv-row">
                        <label class="fs-6 fw-semibold form-label mb-2">Area</label>
                        <select name="area_id" class="form-control form-control-solid @error('area_id') is-invalid @enderror" data-control="select2">
                            <option value="">Select Area</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}" {{ old('area_id', $staff->area_id) == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                            @endforeach
                        </select>
                        @error('area_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 text-end">
                        <a href="{{ route('staff.hr.staff.show', $staff->id) }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Location Information</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle form submission via AJAX
    const form = document.getElementById('edit-location-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
            submitBtn.disabled = true;
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'An error occurred');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    // Show success message
                    alert(data.message);
                    // Optionally reload the section
                    // loadSection('location');
                } else {
                    alert('Error: ' + (data.message || 'An error occurred'));
                }
            })
            .catch(error => {
                alert('Error: ' + error.message);
            })
            .finally(() => {
                // Restore button state
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
            });
        });
    }
    
    // Initialize Select2
    $('select[data-control="select2"]').select2({
        placeholder: "Select an option",
        allowClear: true
    });
});
</script>