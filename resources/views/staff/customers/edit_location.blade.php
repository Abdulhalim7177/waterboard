@extends('layouts.staff')

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card">
                <div class="card-header position-relative py-0 border-bottom-2">
                    <div class="card-title">
                        <h2>Edit Location: {{ $customer->first_name }} {{ $customer->surname }} ({{ $customer->billing_id ?? 'Pending' }})</h2>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ route('staff.customers.edit', $customer->id) }}" class="btn btn-secondary">Back to Edit Options</a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Single Alert Container -->
                    <div id="alertContainer"></div>

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

                    <form action="{{ route('staff.customers.update.location', $customer->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row mb-6">
                            <div class="col-md-12 fv-row mb-6">
                                <button type="button" id="getLocationBtn" class="btn btn-light-primary">
                                    <i class="ki-duotone ki-geolocation fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Get Current Location
                                </button>
                                <button type="button" id="toggleMapBtn" class="btn btn-light-primary ms-2">
                                    <i class="ki-duotone ki-map fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Show Map
                                </button>
                                <button type="button" id="clearPolygonBtn" class="btn btn-light-secondary ms-2">
                                    <i class="ki-duotone ki-trash fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Clear Polygon
                                </button>
                            </div>
                            <div class="col-md-12 fv-row mb-6">
                                <div id="map" style="height: 400px; border: 1px solid #ddd; border-radius: 4px; display: none;"></div>
                                <small class="form-text text-muted">Click "Show Map" to select polygon points or use "Get Current Location" to add your position.</small>
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="latitude" class="form-label required">Latitude</label>
                                <input type="number" step="any" class="form-control form-control-solid @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', $customer->latitude) }}" required>
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="longitude" class="form-label required">Longitude</label>
                                <input type="number" step="any" class="form-control form-control-solid @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', $customer->longitude) }}" required>
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-md-6 fv-row">
                                <label for="altitude" class="form-label">Altitude</label>
                                <input type="number" step="any" class="form-control form-control-solid @error('altitude') is-invalid @enderror" id="altitude" name="altitude" value="{{ old('altitude', $customer->altitude) }}">
                                <small class="form-text text-muted">Altitude is optional and may not be available on all devices.</small>
                                @error('altitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="pipe_path" class="form-label">Pipe Path</label>
                                <input type="text" class="form-control form-control-solid @error('pipe_path') is-invalid @enderror" id="pipe_path" name="pipe_path" value="{{ old('pipe_path', $customer->pipe_path) }}">
                                @error('pipe_path')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-md-12 fv-row">
                                <label for="polygon_coordinates" class="form-label">Polygon Coordinates</label>
                                <textarea class="form-control form-control-solid @error('polygon_coordinates') is-invalid @enderror" id="polygon_coordinates" name="polygon_coordinates" rows="4">{{ old('polygon_coordinates', $customer->polygon_coordinates) }}</textarea>
                                <small class="form-text text-muted">Format: [[lat, lng], [lat, lng], ...]. Use "Get Current Location" or show the map to add points.</small>
                                @error('polygon_coordinates')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-md-6 fv-row">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control form-control-solid @error('password') is-invalid @enderror" id="password" name="password">
                                <small class="form-text text-muted">Leave blank to keep current password.</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control form-control-solid @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation">
                                @error('password_confirmation')
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        console.log('Geolocation and Leaflet script loaded');

        let map = null;
        let mapInitialized = false;
        let polygonPoints = [];
        let polygonLayer = null;

        const getLocationBtn = document.getElementById('getLocationBtn');
        const toggleMapBtn = document.getElementById('toggleMapBtn');
        const clearPolygonBtn = document.getElementById('clearPolygonBtn');
        const polygonField = document.getElementById('polygon_coordinates');
        const mapContainer = document.getElementById('map');
        const alertContainer = document.getElementById('alertContainer');

        if (!getLocationBtn) console.error('Button with ID "getLocationBtn" not found');
        if (!toggleMapBtn) console.error('Button with ID "toggleMapBtn" not found');
        if (!clearPolygonBtn) console.error('Button with ID "clearPolygonBtn" not found');
        if (!polygonField) console.error('Textarea with ID "polygon_coordinates" not found');
        if (!mapContainer) console.error('Map container with ID "map" not found');
        if (!alertContainer) console.error('Alert container with ID "alertContainer" not found');

        // Load existing polygon coordinates from customer data
        try {
            if (polygonField.value) {
                polygonPoints = JSON.parse(polygonField.value);
                if (!Array.isArray(polygonPoints) || !polygonPoints.every(p => Array.isArray(p) && p.length === 2)) {
                    throw new Error('Invalid polygon coordinates format');
                }
            }
        } catch (e) {
            console.error('Error parsing polygon coordinates:', e.message);
            polygonPoints = [];
            polygonField.value = '';
        }

        toggleMapBtn.addEventListener('click', function() {
            console.log('Toggle Map button clicked');
            if (mapContainer.style.display === 'none') {
                mapContainer.style.display = 'block';
                toggleMapBtn.innerHTML = '<i class="ki-duotone ki-map fs-2 me-2"><span class="path1"></span><span class="path2"></span></i> Hide Map';
                if (!mapInitialized) {
                    const lat = parseFloat(document.getElementById('latitude').value) || 6.5244; // Default to Lagos if no lat
                    const lng = parseFloat(document.getElementById('longitude').value) || 3.3792;
                    map = L.map('map').setView([lat, lng], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);
                    map.on('click', function(e) {
                        console.log('Map clicked at:', e.latlng);
                        addPolygonPoint(e.latlng.lat, e.latlng.lng);
                    });
                    mapInitialized = true;
                }
                updateMapPolygon();
                showAlert('success', 'Map is now visible. Click to add polygon points.');
            } else {
                mapContainer.style.display = 'none';
                toggleMapBtn.innerHTML = '<i class="ki-duotone ki-map fs-2 me-2"><span class="path1"></span><span class="path2"></span></i> Show Map';
                showAlert('success', 'Map hidden. Use "Get Current Location" to add points.');
            }
        });

        function addPolygonPoint(lat, lng) {
            polygonPoints.push([lat, lng]);
            updatePolygonField();
            if (mapInitialized && mapContainer.style.display !== 'none') {
                updateMapPolygon();
            }
            showAlert('success', `Point added to polygon: [${lat.toFixed(6)}, ${lng.toFixed(6)}]`);
        }

        function updatePolygonField() {
            try {
                polygonField.value = JSON.stringify(polygonPoints);
            } catch (e) {
                console.error('Error stringifying polygon coordinates:', e.message);
                polygonField.value = '';
            }
        }

        function updateMapPolygon() {
            if (polygonLayer) map.removeLayer(polygonLayer);
            if (polygonPoints.length > 0) {
                polygonLayer = L.polygon(polygonPoints, { color: '#007bff' }).addTo(map);
                map.fitBounds(polygonLayer.getBounds());
            }
        }

        getLocationBtn.addEventListener('click', function() {
            console.log('Get Current Location button clicked');

            if (!navigator.geolocation) {
                showAlert('danger', 'Geolocation is not supported by your browser. Show the map to add points manually.');
                console.error('Geolocation not supported');
                return;
            }

            if (window.location.protocol !== 'https:' && window.location.hostname !== 'localhost') {
                showAlert('danger', 'Geolocation requires a secure connection (HTTPS). Please access this page via HTTPS or localhost.');
                console.error('Non-HTTPS connection detected');
                return;
            }

            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<i class="ki-duotone ki-geolocation fs-2 me-2"><span class="path1"></span><span class="path2"></span></i> Fetching...';
            console.log('Requesting geolocation...');

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    console.log('Geolocation success:', position);
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;
                    const altitude = position.coords.altitude || '';

                    document.getElementById('latitude').value = latitude.toFixed(6);
                    document.getElementById('longitude').value = longitude.toFixed(6);
                    document.getElementById('altitude').value = altitude ? altitude.toFixed(2) : '';

                    addPolygonPoint(latitude, longitude);
                    if (mapInitialized && mapContainer.style.display !== 'none') {
                        map.setView([latitude, longitude], 15);
                        L.marker([latitude, longitude]).addTo(map).bindPopup('Current Location').openPopup();
                    }

                    showAlert('success', altitude ? `Location and polygon point added: [${latitude.toFixed(6)}, ${longitude.toFixed(6)}].` : `Location and polygon point added: [${latitude.toFixed(6)}, ${longitude.toFixed(6)}]. Altitude unavailable; you can leave it blank or enter manually.`);
                    btn.disabled = false;
                    btn.innerHTML = '<i class="ki-duotone ki-geolocation fs-2 me-2"><span class="path1"></span><span class="path2"></span></i> Get Current Location';
                },
                function(error) {
                    console.error('Geolocation error:', error.message, 'Code:', error.code);
                    let message = 'Unable to fetch location. Show the map to add points manually.';
                    if (error.code === error.PERMISSION_DENIED) {
                        message = 'Location access is blocked. To enable:<br>' +
                                  '- <b>Chrome</b>: Click the lock icon in the address bar > "Site settings" > Set "Location" to "Allow".<br>' +
                                  '- <b>Firefox</b>: Click the lock icon > "Permissions" > Clear "Block" for Location or set to "Allow".<br>' +
                                  '- <b>Safari</b>: Go to Settings > Privacy > Location Services > Enable for Safari.<br>' +
                                  '- Ensure device location services are enabled (e.g., GPS or Wi-Fi).<br>' +
                                  'Refresh the page and try again.';
                    } else if (error.code === error.POSITION_UNAVAILABLE) {
                        message = 'Location information is unavailable. Ensure location services are enabled on your device (e.g., GPS or Wi-Fi).';
                    } else if (error.code === error.TIMEOUT) {
                        message = 'The request to get location timed out. Try again or ensure a strong GPS/Wi-Fi signal.';
                    }
                    showAlert('danger', message);
                    btn.disabled = false;
                    btn.innerHTML = '<i class="ki-duotone ki-geolocation fs-2 me-2"><span class="path1"></span><span class="path2"></span></i> Get Current Location';
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        });

        clearPolygonBtn.addEventListener('click', function() {
            console.log('Clear Polygon button clicked');
            polygonPoints = [];
            if (polygonLayer && mapInitialized && mapContainer.style.display !== 'none') {
                map.removeLayer(polygonLayer);
                polygonLayer = null;
            }
            polygonField.value = '';
            showAlert('success', 'Polygon coordinates cleared.');
        });

        function showAlert(type, message) {
            console.log('Showing alert:', type, message);
            let alert = alertContainer.querySelector('.alert');
            if (!alert) {
                alert = document.createElement('div');
                alert.className = `alert alert-${type} alert-dismissible fade`;
                alert.role = 'alert';
                alertContainer.appendChild(alert);
            } else {
                alert.className = alert.className.replace(/alert-\w+/, `alert-${type}`);
            }
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            alert.classList.add('show');
            setTimeout(() => {
                alert.classList.remove('show');
                setTimeout(() => alert.classList.remove('fade'), 150);
            }, 7000);
        }
    </script>
    <style>
        #map { width: 100%; }
        #alertContainer .alert { display: none; }
        #alertContainer .alert.show { display: block; }
    </style>
@endsection