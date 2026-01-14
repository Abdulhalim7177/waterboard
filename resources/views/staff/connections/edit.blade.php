@extends('layouts.staff')

@section('title', 'Edit Connection')

@section('content')
<div class="post d-flex flex-column-fluid" id="kt_post">
    <div id="kt_content_container" class="container-xxl">
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <h3>Edit Connection</h3>
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('staff.connections.index') }}" class="btn btn-light-primary">
                            <i class="ki-duotone ki-arrow-left fs-2"></i>Back to Connections
                        </a>
                        <a href="{{ route('staff.connection-fees.index') }}" class="btn btn-light-primary">
                            <i class="ki-duotone ki-switch fs-2"></i>Manage Fees
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <form action="{{ route('staff.connections.update', $connection) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="fv-row mb-7">
                                <label class="required form-label">Customer</label>
                                <select name="customer_id" id="customer_id" class="form-select form-select-solid @error('customer_id') is-invalid @enderror" data-control="select2" data-placeholder="Select Customer" required>
                                    <option value=""></option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id', $connection->customer_id) == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->first_name }} {{ $customer->surname }} ({{ $customer->billing_id }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="fv-row mb-7">
                                <label class="required form-label">Connection Type</label>
                                <select name="connection_type_id" id="connection_type_id" class="form-select form-select-solid @error('connection_type_id') is-invalid @enderror" data-control="select2" data-placeholder="Select Connection Type" required>
                                    <option value=""></option>
                                    @foreach($connectionTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('connection_type_id', $connection->connection_type_id) == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('connection_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="fv-row mb-7">
                                <label class="form-label">Connection Size</label>
                                <select name="connection_size_id" id="connection_size_id" class="form-select form-select-solid @error('connection_size_id') is-invalid @enderror" data-control="select2" data-placeholder="Select Connection Size (optional)">
                                    <option value="">Select Connection Size (optional)</option>
                                    @foreach($connectionSizes as $size)
                                        <option value="{{ $size->id }}" {{ old('connection_size_id', $connection->connection_size_id) == $size->id ? 'selected' : '' }}>
                                            {{ $size->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('connection_size_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Note: Legalisation and Reconnection fees don't require a size</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="fv-row mb-7">
                                <label class="form-label">Status</label>
                                <select name="status" id="status" class="form-select form-select-solid @error('status') is-invalid @enderror" data-control="select2" data-placeholder="Select Status">
                                    <option value="pending" {{ old('status', $connection->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ old('status', $connection->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ old('status', $connection->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="active" {{ old('status', $connection->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $connection->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="fv-row mb-7">
                                <label class="form-label">Installation Date</label>
                                <input type="date" name="installation_date" id="installation_date"
                                       class="form-control form-control-solid @error('installation_date') is-invalid @enderror"
                                       value="{{ old('installation_date', $connection->installation_date ? $connection->installation_date->format('Y-m-d') : '') }}">
                                @error('installation_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" id="notes" rows="3" class="form-control form-control-solid @error('notes') is-invalid @enderror">{{ old('notes', $connection->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('staff.connections.index') }}" class="btn btn-light me-3">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Connection</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const connectionTypeId = document.getElementById('connection_type_id');
    const connectionSizeId = document.getElementById('connection_size_id');

    // Store all connection sizes for reference
    const allConnectionSizes = [];
    for (let i = 0; i < connectionSizeId.options.length; i++) {
        allConnectionSizes.push({
            value: connectionSizeId.options[i].value,
            text: connectionSizeId.options[i].text
        });
    }

    // Function to update connection size visibility based on connection type
    function updateConnectionSizeVisibility() {
        if (!connectionTypeId || !connectionSizeId) return;

        const selectedTypeText = connectionTypeId.options[connectionTypeId.selectedIndex].text.toLowerCase();

        // Reset the connection size dropdown
        connectionSizeId.innerHTML = '<option value="">Select Connection Size (optional)</option>';

        // Hide/disable size selection for legalisation and reconnection fees
        if(selectedTypeText.includes('legalisation') || selectedTypeText.includes('reconnection')) {
            connectionSizeId.disabled = true;
            connectionSizeId.style.display = 'none';
            const sizeRow = document.querySelector('[for="connection_size_id"]').closest('.fv-row');
            if (sizeRow) sizeRow.style.display = 'none';
        } else {
            connectionSizeId.disabled = false;
            connectionSizeId.style.display = 'block';
            const sizeRow = document.querySelector('[for="connection_size_id"]').closest('.fv-row');
            if (sizeRow) sizeRow.style.display = 'block';

            // Show all sizes for both private and commercial connections
            allConnectionSizes.forEach(size => {
                if(size.value !== "") { // Skip the empty option
                    const option = document.createElement('option');
                    option.value = size.value;
                    option.text = size.text;
                    connectionSizeId.appendChild(option);
                }
            });
        }
    }

    // Initialize on page load
    updateConnectionSizeVisibility();

    // Add event listener for connection type changes
    if(connectionTypeId) {
        connectionTypeId.addEventListener('change', updateConnectionSizeVisibility);
    }

    // Update status based on connection type
    if(connectionTypeId) {
        connectionTypeId.addEventListener('change', function() {
            const selectedTypeText = this.options[this.selectedIndex].text.toLowerCase();
            const statusSelect = document.getElementById('status');

            // Automatically set status to pending for legalisation and reconnection
            if(selectedTypeText.includes('legalisation') || selectedTypeText.includes('reconnection')) {
                if(statusSelect) {
                    statusSelect.value = 'pending';
                }
            }
        });
    }
});
</script>
@endsection