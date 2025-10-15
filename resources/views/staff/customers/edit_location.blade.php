@extends('layouts.staff')

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h2>Edit Customer - Location Information</h2>
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

                    <form method="POST" action="{{ route('staff.customers.update.location', $customer) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="latitude" class="required form-label">Latitude</label>
                                <input type="number" step="any" class="form-control form-control-solid" name="latitude" id="latitude" value="{{ old('latitude', $customer->latitude) }}" required>
                                @error('latitude')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="longitude" class="required form-label">Longitude</label>
                                <input type="number" step="any" class="form-control form-control-solid" name="longitude" id="longitude" value="{{ old('longitude', $customer->longitude) }}" required>
                                @error('longitude')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="altitude" class="form-label">Altitude (meters)</label>
                                <input type="number" step="any" class="form-control form-control-solid" name="altitude" id="altitude" value="{{ old('altitude', $customer->altitude) }}">
                                @error('altitude')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="meter_reading" class="form-label">Meter Reading</label>
                                <input type="number" step="any" min="0" class="form-control form-control-solid" name="meter_reading" id="meter_reading" value="{{ old('meter_reading', $customer->meter_reading) }}">
                                @error('meter_reading')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="polygon_coordinates" class="form-label">Polygon Coordinates (JSON)</label>
                            <textarea class="form-control form-control-solid" name="polygon_coordinates" id="polygon_coordinates" rows="3" placeholder="Enter coordinates in JSON format: [[lat, lng], [lat, lng], ...]">{{ old('polygon_coordinates', $customer->polygon_coordinates) }}</textarea>
                            @error('polygon_coordinates')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="pipe_path" class="form-label">Pipe Path (JSON)</label>
                            <textarea class="form-control form-control-solid" name="pipe_path" id="pipe_path" rows="3" placeholder="Enter pipe path in JSON format: [[lat, lng], [lat, lng], ...]">{{ old('pipe_path', $customer->pipe_path) }}</textarea>
                            @error('pipe_path')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control form-control-solid" name="password" id="password" placeholder="Leave blank to keep current password">
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control form-control-solid" name="password_confirmation" id="password_confirmation" placeholder="Confirm new password">
                                @error('password_confirmation')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div id="map" style="height: 400px;" class="mb-4"></div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Update Location Information</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet for Location Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        let map, marker;

        document.addEventListener('DOMContentLoaded', function() {
            const lat = parseFloat(document.getElementById('latitude').value) || 0;
            const lng = parseFloat(document.getElementById('longitude').value) || 0;
            
            // Initialize map
            map = L.map('map').setView([lat, lng], 13);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            
            // Add marker
            marker = L.marker([lat, lng], {draggable: true}).addTo(map);
            
            // Update form fields when marker is dragged
            marker.on('dragend', function(event) {
                const position = marker.getLatLng();
                document.getElementById('latitude').value = position.lat.toFixed(6);
                document.getElementById('longitude').value = position.lng.toFixed(6);
            });
            
            // Update marker position when form fields change
            document.getElementById('latitude').addEventListener('change', updateMarkerPosition);
            document.getElementById('longitude').addEventListener('change', updateMarkerPosition);
        });

        function updateMarkerPosition() {
            const lat = parseFloat(document.getElementById('latitude').value);
            const lng = parseFloat(document.getElementById('longitude').value);
            
            if (!isNaN(lat) && !isNaN(lng)) {
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng]);
            }
        }
    </script>
@endsection