<div class="card-body">
    <!-- Category Selection -->
    <div class="row mb-6">
        <div class="col-md-6 fv-row">
            <label for="category_id" class="form-label required">Tariff Category</label>
            <select class="form-select form-select-solid @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                <option value="">Select Category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $selectedCategoryId ?? $customer->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Main Billing Update Form -->
    <form id="edit-billing-form" action="{{ route('staff.customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="part" value="billing">
        <input type="hidden" name="category_id" id="hidden_category_id" value="{{ old('category_id', $selectedCategoryId ?? $customer->category_id) }}">
        <div class="row mb-6">
            <div class="col-md-6 fv-row">
                <label for="tariff_id" class="form-label required">Tariff</label>
                <select class="form-select form-select-solid @error('tariff_id') is-invalid @enderror" id="tariff_id" name="tariff_id" required>
                    <option value="">Select Tariff</option>
                    @foreach ($tariffs as $tariff)
                        <option value="{{ $tariff->id }}" data-category="{{ $tariff->category_id }}" {{ old('tariff_id', $customer->tariff_id) == $tariff->id ? 'selected' : '' }}>{{ $tariff->name }}</option>
                    @endforeach
                </select>
                @error('tariff_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 fv-row">
                <label for="delivery_code" class="form-label">Delivery Code</label>
                <input type="text" class="form-control form-control-solid @error('delivery_code') is-invalid @enderror" id="delivery_code" name="delivery_code" value="{{ old('delivery_code', $customer->delivery_code) }}">
                @error('delivery_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-md-6 fv-row">
                <label for="billing_condition" class="form-label required">Billing Condition</label>
                <select class="form-select form-select-solid @error('billing_condition') is-invalid @enderror" id="billing_condition" name="billing_condition" required>
                    <option value="">Select Billing Condition</option>
                    <option value="Metered" {{ old('billing_condition', $customer->billing_condition) == 'Metered' ? 'selected' : '' }}>Metered</option>
                    <option value="Non-Metered" {{ old('billing_condition', $customer->billing_condition) == 'Non-Metered' ? 'selected' : '' }}>Non-Metered</option>
                </select>
                @error('billing_condition')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 fv-row">
                <label for="water_supply_status" class="form-label required">Water Supply Status</label>
                <select class="form-select form-select-solid @error('water_supply_status') is-invalid @enderror" id="water_supply_status" name="water_supply_status" required>
                    <option value="">Select Water Supply Status</option>
                    <option value="Functional" {{ old('water_supply_status', $customer->water_supply_status) == 'Functional' ? 'selected' : '' }}>Functional</option>
                    <option value="Non-Functional" {{ old('water_supply_status', $customer->water_supply_status) == 'Non-Functional' ? 'selected' : '' }}>Non-Functional</option>
                </select>
                @error('water_supply_status')
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
        const categorySelect = document.getElementById('category_id');
        const tariffSelect = document.getElementById('tariff_id');
        const hiddenCategoryInput = document.getElementById('hidden_category_id');

        // Store all tariff options for filtering
        const allTariffs = Array.from(tariffSelect.querySelectorAll('option[data-category]'));

        // Filter tariffs based on selected category
        function filterTariffs() {
            const selectedCategoryId = categorySelect.value;
            
            // Update hidden input
            hiddenCategoryInput.value = selectedCategoryId;
            
            // Clear tariff selection
            tariffSelect.value = '';
            
            // Show/hide tariffs based on category
            allTariffs.forEach(option => {
                if (selectedCategoryId === '' || option.dataset.category === selectedCategoryId) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });
        }

        // Event listeners
        categorySelect.addEventListener('change', filterTariffs);

        // Initialize filtering on page load
        filterTariffs();
    });
</script>