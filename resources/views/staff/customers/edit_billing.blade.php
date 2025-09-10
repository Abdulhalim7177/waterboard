@extends('layouts.staff')

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h2>Edit Billing: {{ $customer->first_name }} {{ $customer->surname }} ({{ $customer->billing_id ?? 'Pending' }})</h2>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ route('staff.customers.edit', $customer->id) }}" class="btn btn-secondary">Back to Edit Options</a>
                    </div>
                </div>
                <div class="card-body">
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

                    <!-- Category Selection Form -->
                    <form action="{{ route('staff.customers.filter.tariffs') }}" method="POST" class="mb-6">
                        @csrf
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                        <div class="row">
                            <div class="col-md-6 fv-row">
                                <label for="category_id" class="form-label required">Category</label>
                                <select class="form-select form-select-solid @error('category_id') is-invalid @enderror" id="category_id" name="category_id" onchange="this.form.submit()" required>
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
                    </form>

                    <!-- Main Billing Update Form -->
                    <form action="{{ route('staff.customers.update.billing', $customer->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="category_id" value="{{ old('category_id', $selectedCategoryId ?? $customer->category_id) }}">
                        <div class="row mb-6">
                            <div class="col-md-6 fv-row">
                                <label for="tariff_id" class="form-label required">Tariff</label>
                                <select class="form-select form-select-solid @error('tariff_id') is-invalid @enderror" id="tariff_id" name="tariff_id" required>
                                    <option value="">Select Tariff</option>
                                    @foreach ($tariffs as $tariff)
                                        <option value="{{ $tariff->id }}" {{ old('tariff_id', $customer->tariff_id) == $tariff->id ? 'selected' : '' }}>{{ $tariff->name }}</option>
                                    @endforeach
                                </select>
                                @error('tariff_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="delivery_code" class="form-label required">Delivery Code</label>
                                <input type="text" class="form-control form-control-solid @error('delivery_code') is-invalid @enderror" id="delivery_code" name="delivery_code" value="{{ old('delivery_code', $customer->delivery_code) }}" required>
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
                                <a href="{{ route('staff.customers.edit', $customer->id) }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Submit for Approval</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection