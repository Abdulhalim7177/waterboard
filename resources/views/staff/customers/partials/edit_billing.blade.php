<!--begin::Alerts-->
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<!--end::Alerts-->

<!--begin::Tab Navigation-->
<ul class="nav nav-stretch nav-pills nav-pills-custom d-flex mt-3">
    <li class="nav-item p-0 ms-0 me-8">
        <a class="nav-link btn btn-color-muted px-0" href="javascript:void(0)" onclick="loadSection('personal')">
            <span class="nav-text fw-semibold fs-4 mb-3">Personal Info</span>
            <span class="badge badge-warning ms-2">Edit Mode</span>
            <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-0 bg-primary rounded"></span>
        </a>
    </li>
    <li class="nav-item p-0 ms-0 me-8">
        <a class="nav-link btn btn-color-muted px-0" href="javascript:void(0)" onclick="loadSection('address')">
            <span class="nav-text fw-semibold fs-4 mb-3">Address</span>
            <span class="badge badge-warning ms-2">Edit Mode</span>
            <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-0 bg-primary rounded"></span>
        </a>
    </li>
    <li class="nav-item p-0 ms-0 me-8">
        <a class="nav-link btn btn-color-muted active px-0" href="javascript:void(0)">
            <span class="nav-text fw-semibold fs-4 mb-3">Billing</span>
            <span class="badge badge-success ms-2">Edit Mode</span>
            <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-0 bg-primary rounded"></span>
        </a>
    </li>
    <li class="nav-item p-0 ms-0">
        <a class="nav-link btn btn-color-muted px-0" href="javascript:void(0)" onclick="loadSection('location')">
            <span class="nav-text fw-semibold fs-4 mb-3">Location</span>
            <span class="badge badge-warning ms-2">Edit Mode</span>
            <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-0 bg-primary rounded"></span>
        </a>
    </li>
</ul>
<!--end::Tab Navigation-->

<!--begin::Forms-->
<div class="scroll-y me-n7 pe-7" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_customer_header" data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
    <!--begin::Category Selection-->
    <div class="fv-row mb-7">
        <label for="category_id" class="fs-6 fw-semibold mb-2 required">Tariff Category</label>
        <select class="form-select form-select-solid @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
            <option value="">Select Category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $selectedCategoryId ?? $customer->category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }} ({{ $category->description }})
                </option>
            @endforeach
        </select>
        @error('category_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @if ($categories->isEmpty())
            <div class="alert alert-warning mt-2">
                No categories available. Please contact an administrator to add categories.
            </div>
        @endif
    </div>
    <!--end::Category Selection-->

    <!--begin::Billing Form-->
    @if ($categories->isNotEmpty())
        <form id="edit-billing-form" action="{{ route('staff.customers.update', $customer->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="part" value="billing">
            <input type="hidden" name="category_id" id="hidden_category_id" value="{{ old('category_id', $selectedCategoryId ?? $customer->category_id) }}">
            <div class="row g-9 mb-7">
                <div class="col-md-6 fv-row">
                    <label for="tariff_id" class="fs-6 fw-semibold mb-2 required">Tariff</label>
                    <select class="form-select form-select-solid @error('tariff_id') is-invalid @enderror" id="tariff_id" name="tariff_id" required>
                        <option value="">Select Tariff</option>
                        @foreach ($tariffs as $tariff)
                            <option value="{{ $tariff->id }}" data-category="{{ $tariff->category_id }}" {{ old('tariff_id', $customer->tariff_id) == $tariff->id ? 'selected' : '' }}>
                                {{ $tariff->name }} (Catcode: {{ $tariff->catcode }}, Amount: {{ $tariff->amount }})
                            </option>
                        @endforeach
                    </select>
                    @error('tariff_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if ($tariffs->isEmpty() && session('customer_creation.billing.category_id'))
                        <div class="alert alert-warning mt-2">
                            No tariffs available for the selected category. Please select another category or contact an administrator.
                        </div>
                    @endif
                </div>
                <div class="col-md-6 fv-row">
                    <label for="delivery_code" class="fs-6 fw-semibold mb-2">Delivery Code</label>
                    <input type="text" class="form-control form-control-solid @error('delivery_code') is-invalid @enderror" id="delivery_code" name="delivery_code" value="{{ old('delivery_code', $customer->delivery_code) }}">
                    @error('delivery_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row g-9 mb-7">
                <div class="col-md-6 fv-row">
                    <label for="billing_condition" class="fs-6 fw-semibold mb-2 required">Billing Condition</label>
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
                    <label for="water_supply_status" class="fs-6 fw-semibold mb-2 required">Water Supply Status</label>
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
            <div class="text-center">
                <a href="{{ route('staff.customers.index') }}" class="btn btn-light me-3">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <span class="indicator-label">Submit for Approval</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
        </form>
    @endif
    <!--end::Billing Form-->
</div>
<!--end::Scroll-->

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