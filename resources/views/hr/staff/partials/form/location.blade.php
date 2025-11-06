<div class="d-flex flex-column gap-4">
    <div class="d-flex align-items-center">
        <label for="lga_id" class="form-label w-150px">LGA</label>
        <select class="form-select" id="lga_id" name="lga_id" required>
            <option value="">Select LGA</option>
            @foreach (\App\Models\Lga::all() as $lga)
                <option value="{{ $lga->id }}" {{ old('lga_id', $staff->lga_id ?? '') == $lga->id ? 'selected' : '' }}>{{ $lga->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="d-flex align-items-center">
        <label for="ward_id" class="form-label w-150px">Ward</label>
        <select class="form-select" id="ward_id" name="ward_id" required>
            <option value="">Select Ward</option>
            @foreach ($wards as $ward)
                <option value="{{ $ward->id }}" data-lga-id="{{ $ward->lga_id }}" {{ old('ward_id', $staff->ward_id ?? '') == $ward->id ? 'selected' : '' }}>{{ $ward->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="d-flex align-items-center">
        <label for="area_id" class="form-label w-150px">Area</label>
        <select class="form-select" id="area_id" name="area_id" required>
            <option value="">Select Area</option>
            @foreach ($areas as $area)
                <option value="{{ $area->id }}" data-ward-id="{{ $area->ward_id }}" {{ old('area_id', $staff->area_id ?? '') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="d-flex align-items-center">
        <label for="zone_id" class="form-label w-150px">Zone</label>
        <select class="form-select" id="zone_id" name="zone_id" required>
            <option value="">Select Zone</option>
            @foreach (\App\Models\Zone::all() as $zone)
                <option value="{{ $zone->id }}" {{ old('zone_id', $staff->zone_id ?? '') == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="d-flex align-items-center">
        <label for="district_id" class="form-label w-150px">District</label>
        <select class="form-select" id="district_id" name="district_id" required>
            <option value="">Select District</option>
            @foreach (\App\Models\District::all() as $district)
                <option value="{{ $district->id }}" {{ old('district_id', $staff->district_id ?? '') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="d-flex align-items-center">
        <label for="paypoint_id" class="form-label w-150px">Paypoint</label>
        <select class="form-select" id="paypoint_id" name="paypoint_id" required>
            <option value="">Select Paypoint</option>
            @foreach (\App\Models\Paypoint::all() as $paypoint)
                <option value="{{ $paypoint->id }}" {{ old('paypoint_id', $staff->paypoint_id ?? '') == $paypoint->id ? 'selected' : '' }}>{{ $paypoint->name }}</option>
            @endforeach
        </select>
    </div>
</div>
