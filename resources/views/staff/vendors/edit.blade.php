@extends('layouts.staff')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Edit Vendor</h2>
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Vendor Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.vendors.update', $vendor) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $vendor->name) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $vendor->email) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password (Leave blank to keep current password)</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>

                        <div class="mb-3">
                            <label for="street_name" class="form-label">Street Name</label>
                            <input type="text" class="form-control" id="street_name" name="street_name" value="{{ old('street_name', $vendor->street_name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="vendor_code" class="form-label">Vendor Code</label>
                            <input type="text" class="form-control" id="vendor_code" name="vendor_code" value="{{ old('vendor_code', $vendor->vendor_code) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="lga_id" class="form-label">LGA</label>
                            <select class="form-control" id="lga_id" name="lga_id" required>
                                <option value="">Select LGA</option>
                                @foreach($lgas as $lga)
                                    <option value="{{ $lga->id }}" {{ old('lga_id', $vendor->lga_id) == $lga->id ? 'selected' : '' }}>{{ $lga->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="ward_id" class="form-label">Ward</label>
                            <select class="form-control" id="ward_id" name="ward_id" required>
                                <option value="">Select Ward</option>
                                @foreach($wards as $ward)
                                    <option value="{{ $ward->id }}" {{ old('ward_id', $vendor->ward_id) == $ward->id ? 'selected' : '' }}>{{ $ward->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="area_id" class="form-label">Area</label>
                            <select class="form-control" id="area_id" name="area_id" required>
                                <option value="">Select Area</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}" {{ old('area_id', $vendor->area_id) == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update Vendor</button>
                        <a href="{{ route('staff.vendors.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Define URLs using Laravel route helper
    var getWardsUrl = '{{ route("staff.get.wards", ["lga" => ":id"]) }}';
    var getAreasUrl = '{{ route("staff.get.areas", ["ward" => ":id"]) }}';
    
    $(document).ready(function() {
        function loadWards(lgaId, selectedWardId) {
            if (lgaId) {
                $.ajax({
                    url: getWardsUrl.replace(':id', lgaId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#ward_id').empty();
                        $('#ward_id').append('<option value="">Select Ward</option>');
                        $.each(data, function(key, value) {
                            $('#ward_id').append('<option value="' + key + '"' + (key == selectedWardId ? ' selected' : '') + '>' + value + '</option>');
                        });
                        $('#ward_id').trigger('change'); // Trigger change to load areas
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading wards:', error);
                        alert('Error loading wards. Please try again.');
                    }
                });
            } else {
                $('#ward_id').empty();
                $('#ward_id').append('<option value="">Select Ward</option>');
                $('#area_id').empty();
                $('#area_id').append('<option value="">Select Area</option>');
            }
        }

        function loadAreas(wardId, selectedAreaId) {
            if (wardId) {
                $.ajax({
                    url: getAreasUrl.replace(':id', wardId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#area_id').empty();
                        $('#area_id').append('<option value="">Select Area</option>');
                        $.each(data, function(key, value) {
                            $('#area_id').append('<option value="' + key + '"' + (key == selectedAreaId ? ' selected' : '') + '>' + value + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading areas:', error);
                        alert('Error loading areas. Please try again.');
                    }
                });
            } else {
                $('#area_id').empty();
                $('#area_id').append('<option value="">Select Area</option>');
            }
        }

        // Initial load for wards and areas if LGA and Ward are already selected
        var initialLgaId = $('#lga_id').val();
        var initialWardId = '{{ old('ward_id', $vendor->ward_id) }}';
        var initialAreaId = '{{ old('area_id', $vendor->area_id) }}';

        if (initialLgaId) {
            loadWards(initialLgaId, initialWardId);
        }

        // Event listeners
        $('#lga_id').change(function() {
            loadWards($(this).val(), null);
        });

        $('#ward_id').change(function() {
            loadAreas($(this).val(), null);
        });
    });
</script>
@endsection