@extends('layouts.staff')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Create New Connection</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('staff.connections.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-5">
                            <label for="customer_id" class="form-label">Customer</label>
                            <select name="customer_id" id="customer_id" class="form-select form-select-solid no-padding" required data-control="select2" data-placeholder="Select Customer">
                                <option value=""></option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->first_name }} {{ $customer->surname }} ({{ $customer->billing_id }}) - {{ $customer->email }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-5">
                            <label for="tariff_id" class="form-label">Service Type</label>
                            <select name="tariff_id" id="tariff_id" class="form-select form-select-solid no-padding" required data-control="select2" data-placeholder="Select Service">
                                <option value=""></option>
                                @foreach ($tariffs as $tariff)
                                    <option value="{{ $tariff->id }}">{{ $tariff->name }} (₦{{ number_format($tariff->amount, 2) }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Create Bill</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Select2 for customer and tariff dropdowns
            $('#customer_id').select2({
                placeholder: "Select Customer",
                allowClear: true
            });
            $('#tariff_id').select2({
                placeholder: "Select Service",
                allowClear: true
            });
        });
    </script>
    <style>
        .select2-container .select2-selection--single .select2-selection__rendered {
            padding: 0 !important;
        }
    </style>
@endsection
