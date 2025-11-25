<div class="card">
    <div class="card-header">
        <h3 class="card-title">Billing Information</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('staff.customers.update', $customer) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="part" value="billing">
            
            <div class="row mb-6">
                <div class="col-md-6 fv-row">
                    <label for="category_id" class="required form-label">Category</label>
                    <select class="form-select form-select-solid" name="category_id" id="category_id" required>
                        <option value="">Select Category</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}" 
                                @if(old('category_id', $customer->category_id) == $category->id) selected @endif>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 fv-row">
                    <label for="tariff_id" class="required form-label">Tariff</label>
                    <select class="form-select form-select-solid" name="tariff_id" id="tariff_id" required>
                        <option value="">Select Tariff</option>
                        @foreach($tariffs ?? [] as $tariff)
                            <option value="{{ $tariff->id }}" data-category="{{ $tariff->category_id }}" 
                                @if(old('tariff_id', $customer->tariff_id) == $tariff->id) selected @endif>
                                {{ $tariff->name }} ({{ $tariff->amount }}/{{ $tariff->unit }})
                            </option>
                        @endforeach
                    </select>
                    @error('tariff_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-6">
                <div class="col-md-6 fv-row">
                    <label for="delivery_code" class="form-label">Delivery Code</label>
                    <input type="text" class="form-control form-control-solid" name="delivery_code" id="delivery_code" value="{{ old('delivery_code', $customer->delivery_code) }}">
                    @error('delivery_code')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 fv-row">
                    <label for="billing_condition" class="required form-label">Billing Condition</label>
                    <select class="form-select form-select-solid" name="billing_condition" id="billing_condition" required>
                        <option value="">Select Condition</option>
                        <option value="Metered" {{ old('billing_condition', $customer->billing_condition) == 'Metered' ? 'selected' : '' }}>Metered</option>
                        <option value="Non-Metered" {{ old('billing_condition', $customer->billing_condition) == 'Non-Metered' ? 'selected' : '' }}>Non-Metered</option>
                    </select>
                    @error('billing_condition')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-6">
                <div class="col-md-6 fv-row">
                    <label for="water_supply_status" class="required form-label">Water Supply Status</label>
                    <select class="form-select form-select-solid" name="water_supply_status" id="water_supply_status" required>
                        <option value="">Select Status</option>
                        <option value="Functional" {{ old('water_supply_status', $customer->water_supply_status) == 'Functional' ? 'selected' : '' }}>Functional</option>
                        <option value="Non-Functional" {{ old('water_supply_status', $customer->water_supply_status) == 'Non-Functional' ? 'selected' : '' }}>Non-Functional</option>
                    </select>
                    @error('water_supply_status')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Submit Changes</button>
            </div>
</div>