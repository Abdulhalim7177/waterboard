@extends('layouts.staff')

@section('title', 'Create Connection Fee')

@section('content')
<div class="post d-flex flex-column-fluid" id="kt_post">
    <div id="kt_content_container" class="container-xxl">
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <h3>Create New Connection Fee</h3>
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('staff.connection-fees.index') }}" class="btn btn-light-primary">
                            <i class="ki-duotone ki-arrow-left fs-2"></i>Back to Fees
                        </a>
                        <a href="{{ route('staff.connections.index') }}" class="btn btn-light-primary">
                            <i class="ki-duotone ki-switch fs-2"></i>Manage Connections
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <form action="{{ route('staff.connection-fees.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="fv-row mb-7">
                                <label class="required form-label">Connection Type</label>
                                <select name="connection_type_id" id="connection_type_id" class="form-select form-select-solid @error('connection_type_id') is-invalid @enderror" data-control="select2" data-placeholder="Select Connection Type" required>
                                    <option value=""></option>
                                    @foreach($connectionTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('connection_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('connection_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="fv-row mb-7">
                                <label class="form-label">Connection Size</label>
                                <select name="connection_size_id" id="connection_size_id" class="form-select form-select-solid @error('connection_size_id') is-invalid @enderror" data-control="select2" data-placeholder="Select Connection Size (optional)">
                                    <option value=""></option>
                                    @foreach($connectionSizes as $size)
                                        <option value="{{ $size->id }}" {{ old('connection_size_id') == $size->id ? 'selected' : '' }}>
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
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="fv-row mb-7">
                                <label class="required form-label">Fee Amount (₦)</label>
                                <input type="number" name="fee_amount" id="fee_amount" class="form-control form-control-solid @error('fee_amount') is-invalid @enderror"
                                       value="{{ old('fee_amount') }}" step="0.01" min="0" required>
                                @error('fee_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('staff.connection-fees.index') }}" class="btn btn-light me-3">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Fee</button>
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

    connectionTypeId.addEventListener('change', function() {
        const selectedType = this.options[this.selectedIndex].text.toLowerCase();

        // Disable size selection for legalisation and reconnection fees
        if(selectedType.includes('legalisation') || selectedType.includes('reconnection')) {
            connectionSizeId.disabled = true;
            connectionSizeId.value = '';
        } else {
            connectionSizeId.disabled = false;
        }
    });
});
</script>
@endsection