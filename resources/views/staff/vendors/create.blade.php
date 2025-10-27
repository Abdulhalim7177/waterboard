@extends('layouts.staff')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Add New Vendor</h2>
            
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
                    <form action="{{ route('staff.vendors.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="mb-3">
                            <label for="street_name" class="form-label">Street Name</label>
                            <input type="text" class="form-control" id="street_name" name="street_name" value="{{ old('street_name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="vendor_code" class="form-label">Vendor Code</label>
                            <input type="text" class="form-control" id="vendor_code" name="vendor_code" value="{{ old('vendor_code') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="lga_id" class="form-label">LGA</label>
                            <select class="form-control" id="lga_id" name="lga_id" required>
                                <option value="">Select LGA</option>
                                @foreach($lgas as $lga)
                                    <option value="{{ $lga->id }}">{{ $lga->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="ward_id" class="form-label">Ward</label>
                            <select class="form-control" id="ward_id" name="ward_id" required>
                                <option value="">Select Ward</option>
                                @foreach($wards as $ward)
                                    <option value="{{ $ward->id }}">{{ $ward->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="area_id" class="form-label">Area</label>
                            <select class="form-control" id="area_id" name="area_id" required>
                                <option value="">Select Area</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Create Vendor</button>
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
        $('#lga_id').change(function() {
            var lgaId = $(this).val();
            if (lgaId) {
                $.ajax({
                    url: getWardsUrl.replace(':id', lgaId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#ward_id').empty();
                        $('#ward_id').append('<option value="">Select Ward</option>');
                        $.each(data, function(key, value) {
                            $('#ward_id').append('<option value="' + key + '">' + value + '</option>');
                        });
                        $('#area_id').empty();
                        $('#area_id').append('<option value="">Select Area</option>');
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
        });

        $('#ward_id').change(function() {
            var wardId = $(this).val();
            if (wardId) {
                $.ajax({
                    url: getAreasUrl.replace(':id', wardId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#area_id').empty();
                        $('#area_id').append('<option value="">Select Area</option>');
                        $.each(data, function(key, value) {
                            $('#area_id').append('<option value="' + key + '">' + value + '</option>');
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
        });
    });
</script>
@endsection