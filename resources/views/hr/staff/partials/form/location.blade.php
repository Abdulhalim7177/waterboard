<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="lga_id" class="form-label">LGA</label>
            <select class="form-select" id="lga_id" name="lga_id">
                @foreach (\App\Models\Lga::all() as $lga)
                    <option value="{{ $lga->id }}" {{ old('lga_id', $staff->lga_id ?? '') == $lga->id ? 'selected' : '' }}>{{ $lga->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="ward_id" class="form-label">Ward</label>
            <select class="form-select" id="ward_id" name="ward_id">
                @foreach (\App\Models\Ward::all() as $ward)
                    <option value="{{ $ward->id }}" {{ old('ward_id', $staff->ward_id ?? '') == $ward->id ? 'selected' : '' }}>{{ $ward->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="area_id" class="form-label">Area</label>
            <select class="form-select" id="area_id" name="area_id">
                @foreach (\App\Models\Area::all() as $area)
                    <option value="{{ $area->id }}" {{ old('area_id', $staff->area_id ?? '') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="zone_id" class="form-label">Zone</label>
            <select class="form-select" id="zone_id" name="zone_id">
                @foreach (\App\Models\Zone::all() as $zone)
                    <option value="{{ $zone->id }}" {{ old('zone_id', $staff->zone_id ?? '') == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="district_id" class="form-label">District</label>
            <select class="form-select" id="district_id" name="district_id">
                @foreach (\App\Models\District::all() as $district)
                    <option value="{{ $district->id }}" {{ old('district_id', $staff->district_id ?? '') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="paypoint_id" class="form-label">Paypoint</label>
            <select class="form-select" id="paypoint_id" name="paypoint_id">
                @foreach (\App\Models\Paypoint::all() as $paypoint)
                    <option value="{{ $paypoint->id }}" {{ old('paypoint_id', $staff->paypoint_id ?? '') == $paypoint->id ? 'selected' : '' }}>{{ $paypoint->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
