<div class="card">
    <div class="card-header">
        <h3 class="card-title">Address Information</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('staff.customers.update', $customer) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="part" value="address">
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="lga_id" class="required form-label">LGA</label>
                    <select class="form-select form-select-solid" name="lga_id" id="lga_id" required>
                        <option value="">Select LGA</option>
                        @foreach($lgas ?? [] as $lga)
                            <option value="{{ $lga->id }}" 
                                @if(old('lga_id', $customer->lga_id) == $lga->id) selected @endif>
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
                        @foreach($wards ?? [] as $ward)
                            <option value="{{ $ward->id }}" data-lga="{{ $ward->lga_id }}" 
                                @if(old('ward_id', $customer->ward_id) == $ward->id) selected @endif>
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
                        @foreach($areas ?? [] as $area)
                            <option value="{{ $area->id }}" data-ward="{{ $area->ward_id }}" 
                                @if(old('area_id', $customer->area_id) == $area->id) selected @endif>
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
                <button type="submit" class="btn btn-primary">Submit Changes</button>
            </div>
</div>