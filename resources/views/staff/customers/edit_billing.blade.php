@extends('layouts.staff')

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h2>Edit Customer - Billing Information</h2>
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

                    <form method="POST" action="{{ route('staff.customers.update.billing', $customer) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="category_id" class="required form-label">Category</label>
                                <select class="form-select form-select-solid" name="category_id" id="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $customer->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="tariff_id" class="required form-label">Tariff</label>
                                <select class="form-select form-select-solid" name="tariff_id" id="tariff_id" required>
                                    <option value="">Select Tariff</option>
                                    @foreach($tariffs as $tariff)
                                        <option value="{{ $tariff->id }}" 
                                            {{ old('tariff_id', $customer->tariff_id) == $tariff->id ? 'selected' : '' }}
                                            data-category="{{ $tariff->category_id }}">
                                            {{ $tariff->name }} ({{ $tariff->amount }}/{{ $tariff->unit }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('tariff_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="delivery_code" class="form-label">Delivery Code</label>
                                <input type="text" class="form-control form-control-solid" name="delivery_code" id="delivery_code" value="{{ old('delivery_code', $customer->delivery_code) }}">
                                @error('delivery_code')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-4">
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
                        
                        <div class="mb-4">
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
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Update Billing Information</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Filter tariffs based on selected category
        document.getElementById('category_id').addEventListener('change', function() {
            const selectedCategoryId = this.value;
            const tariffSelect = document.getElementById('tariff_id');
            
            // Clear current options
            tariffSelect.innerHTML = '<option value="">Select Tariff</option>';
            
            if (selectedCategoryId) {
                // Show only tariffs that belong to the selected category
                const allTariffOptions = document.querySelectorAll('#tariff_id option[data-category]');
                allTariffOptions.forEach(option => {
                    if (option.dataset.category == selectedCategoryId) {
                        tariffSelect.appendChild(option.cloneNode(true));
                    }
                });
            }
        });
        
        // Initialize filter on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Trigger change event on load to filter based on existing values
            const categoryId = document.getElementById('category_id').value;
            if (categoryId) {
                document.getElementById('category_id').dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection