<div class="card">
    <div class="card-header">
        <h3 class="card-title">Location Information</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('staff.customers.update', $customer) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="part" value="location">
            
            <div class="row mb-6">
                <div class="col-md-12 fv-row mb-6">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <button type="button" id="getLocationBtn" class="btn btn-light-primary">
                            <i class="ki-duotone ki-geolocation fs-2 me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Get Current Location
                        </button>
                        <button type="button" id="toggleMapBtn" class="btn btn-light-primary">
                            <i class="ki-duotone ki-map fs-2 me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Show Map
                        </button>
                    </div>
                    
                    <div id="mapTools" class="d-none flex-wrap gap-2">
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" id="startDrawingPolygonBtn" class="btn btn-light-primary">
                                <i class="ki-duotone ki-pencil fs-2 me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Draw Polygon
                            </button>
                            <button type="button" id="startDrawingPipeBtn" class="btn btn-light-primary">
                                <i class="ki-duotone ki-pencil fs-2 me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Draw Pipe Path
                            </button>
                            <button type="button" id="zoomToLocationBtn" class="btn btn-light-primary">
                                <i class="ki-duotone ki-magnifier fs-2 me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Zoom to Location
                            </button>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mt-2 mt-md-0">
                            <button type="button" id="resetPolygonBtn" class="btn btn-light-secondary">
                                <i class="ki-duotone ki-trash fs-2 me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Reset Polygon
                            </button>
                            <button type="button" id="resetPipeBtn" class="btn btn-light-secondary">
                                <i class="ki-duotone ki-trash fs-2 me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Reset Pipe Path
                            </button>
                            <button type="button" id="clearAllBtn" class="btn btn-light-secondary">
                                <i class="ki-duotone ki-trash fs-2 me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Clear All
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 fv-row mb-6">
                    <div id="map" style="height: 400px; border: 1px solid #ddd; border-radius: 4px; display: none;"></div>
                    <small class="form-text text-muted">Click "Show Map" and "Start Drawing" to draw a polygon. Drag markers to reposition, double-click a marker to remove it, or double-click the map to complete the polygon.</small>
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
                    <small class="form-text text-muted">Format: [[lat, lng], [lat, lng], ...]. Use "Start Drawing" to draw a polygon or "Get Current Location" to add points.</small>
                    @error('polygon_coordinates')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row mb-6">
                <div class="col-md-6 fv-row">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control form-control-solid @error('password') is-invalid @enderror" id="password" name="password" value="">
                    <small class="form-text text-muted">Leave blank to keep the current password.</small>
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
                    <button type="submit" class="btn btn-primary">
                        <span class="indicator-label">Submit Changes</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<style>
    #map { width: 100%; }
    #alertContainer .alert { display: none; }
    #alertContainer .alert.show { display: block; }
</style>

<script>
    console.log('Geolocation and Leaflet script loaded');
    let map = null;
    let mapInitialized = false;
    let polygonPoints = [], pipePoints = [];
    let polygonLayer = null, pipeLayer = null, marker = null;
    let polygonMarkers = [], pipeMarkers = [];
    let isDrawingPolygon = false, isDrawingPipe = false;

    const getLocationBtn = document.getElementById('getLocationBtn');
    const toggleMapBtn = document.getElementById('toggleMapBtn');
    const startDrawingPolygonBtn = document.getElementById('startDrawingPolygonBtn');
    const startDrawingPipeBtn = document.getElementById('startDrawingPipeBtn');
    const zoomToLocationBtn = document.getElementById('zoomToLocationBtn');
    const resetPolygonBtn = document.getElementById('resetPolygonBtn');
    const resetPipeBtn = document.getElementById('resetPipeBtn');
    const clearAllBtn = document.getElementById('clearAllBtn');
    const polygonField = document.getElementById('polygon_coordinates');
    const pipeField = document.getElementById('pipe_path');
    const mapContainer = document.getElementById('map');
    const alertContainer = document.getElementById('alertContainer');
    const latitudeField = document.getElementById('latitude');
    const longitudeField = document.getElementById('longitude');

    // Element existence checks
    if (!getLocationBtn) console.error('Button with ID "getLocationBtn" not found');
    if (!toggleMapBtn) console.error('Button with ID "toggleMapBtn" not found');
    if (!startDrawingPolygonBtn) console.error('Button with ID "startDrawingPolygonBtn" not found');
    if (!startDrawingPipeBtn) console.error('Button with ID "startDrawingPipeBtn" not found');
    if (!zoomToLocationBtn) console.error('Button with ID "zoomToLocationBtn" not found');
    if (!resetPolygonBtn) console.error('Button with ID "resetPolygonBtn" not found');
    if (!resetPipeBtn) console.error('Button with ID "resetPipeBtn" not found');
    if (!clearAllBtn) console.error('Button with ID "clearAllBtn" not found');
    if (!polygonField) console.error('Textarea with ID "polygon_coordinates" not found');
    if (!pipeField) console.error('Input with ID "pipe_path" not found');
    if (!mapContainer) console.error('Map container with ID "map" not found');
    if (!alertContainer) console.error('Alert container with ID "alertContainer" not found');
    if (!latitudeField) console.error('Input with ID "latitude" not found');
    if (!longitudeField) console.error('Input with ID "longitude" not found');

    // Load existing coordinates
    try {
        if (polygonField.value) {
            polygonPoints = JSON.parse(polygonField.value);
            if (!Array.isArray(polygonPoints) || !polygonPoints.every(p => Array.isArray(p) && p.length === 2 && !isNaN(p[0]) && !isNaN(p[1]))) {
                throw new Error('Invalid polygon coordinates format');
            }
        }
    } catch (e) {
        console.error('Error parsing polygon coordinates:', e.message);
        polygonPoints = [];
        polygonField.value = '';
    }
    try {
        if (pipeField.value) {
            pipePoints = JSON.parse(pipeField.value);
            if (!Array.isArray(pipePoints) || !pipePoints.every(p => Array.isArray(p) && p.length === 2 && !isNaN(p[0]) && !isNaN(p[1]))) {
                throw new Error('Invalid pipe path coordinates format');
            }
        }
    } catch (e) {
        console.error('Error parsing pipe path coordinates:', e.message);
        pipePoints = [];
        pipeField.value = '';
    }

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

    function initializeMap(lat = 6.5244, lng = 3.3792, zoom = 13) {
        map = L.map('map').setView([lat, lng], zoom);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        map.on('click', handleMapClick);
        map.on('dblclick', completeDrawing);

        // Initialize marker if latitude and longitude are provided
        const latValue = parseFloat(latitudeField.value);
        const lngValue = parseFloat(longitudeField.value);
        if (!isNaN(latValue) && !isNaN(lngValue)) {
            marker = L.marker([latValue, lngValue], { draggable: true }).addTo(map);
            marker.on('drag', function (e) {
                const position = marker.getLatLng();
                latitudeField.value = position.lat.toFixed(6);
                longitudeField.value = position.lng.toFixed(6);
            });
            marker.on('dragend', function () {
                map.panTo(marker.getLatLng(), { animate: true });
            });
            map.flyTo([latValue, lngValue], 15, { animate: true, duration: 1 });
        }

        // Initialize polygon if coordinates exist
        if (polygonPoints.length >= 3) {
            polygonLayer = L.polygon(polygonPoints, { color: 'red', weight: 2 }).addTo(map);
            polygonPoints.forEach(coord => {
                const m = L.marker(coord, { draggable: true }).addTo(map);
                m.on('dragend', updatePolygon);
                m.on('dblclick', function () {
                    map.removeLayer(m);
                    polygonMarkers = polygonMarkers.filter(mark => mark !== m);
                    updatePolygon();
                });
                polygonMarkers.push(m);
            });
            map.fitBounds(polygonLayer.getBounds(), { padding: [50, 50] });
        }

        // Initialize pipe path if coordinates exist
        if (pipePoints.length >= 2) {
            pipeLayer = L.polyline(pipePoints, { color: 'blue', weight: 3 }).addTo(map);
            pipePoints.forEach(coord => {
                const m = L.marker(coord, { draggable: true, icon: L.divIcon({ className: 'pipe-marker', html: '<div style="background-color:blue;width:8px;height:8px;border-radius:50%;"></div>' }) }).addTo(map);
                m.on('dragend', updatePipePath);
                m.on('dblclick', function () {
                    map.removeLayer(m);
                    pipeMarkers = pipeMarkers.filter(mark => mark !== m);
                    updatePipePath();
                });
                pipeMarkers.push(m);
            });
            map.fitBounds(pipeLayer.getBounds(), { padding: [50, 50] });
        }
        mapInitialized = true;
    }

    function updatePolygon() {
        polygonPoints = polygonMarkers.map(m => [m.getLatLng().lat, m.getLatLng().lng]);
        polygonField.value = polygonPoints.length >= 3 ? JSON.stringify(polygonPoints) : '';
        if (polygonLayer) map.removeLayer(polygonLayer);
        if (polygonPoints.length >= 3) {
            polygonLayer = L.polygon(polygonPoints, { color: 'red', weight: 2 }).addTo(map);
            map.fitBounds(polygonLayer.getBounds(), { padding: [50, 50] });
        }
        updatePolygonCenter();
    }

    function updatePipePath() {
        pipePoints = pipeMarkers.map(m => [m.getLatLng().lat, m.getLatLng().lng]);
        pipeField.value = pipePoints.length >= 2 ? JSON.stringify(pipePoints) : '';
        if (pipeLayer) map.removeLayer(pipeLayer);
        if (pipePoints.length >= 2) {
            pipeLayer = L.polyline(pipePoints, { color: 'blue', weight: 3 }).addTo(map);
            map.fitBounds(pipeLayer.getBounds(), { padding: [50, 50] });
        }
    }

    function updatePolygonCenter() {
        if (polygonPoints.length === 0) {
            if (!pipePoints.length && !marker) {
                latitudeField.value = '';
                longitudeField.value = '';
            }
            return;
        }
        const bounds = L.polygon(polygonPoints).getBounds();
        const center = bounds.getCenter();
        latitudeField.value = center.lat.toFixed(6);
        longitudeField.value = center.lng.toFixed(6);
        if (marker) map.removeLayer(marker);
        marker = L.marker([center.lat, center.lng], { draggable: true }).addTo(map);
        marker.on('drag', function (e) {
            const position = marker.getLatLng();
            latitudeField.value = position.lat.toFixed(6);
            longitudeField.value = position.lng.toFixed(6);
        });
        marker.on('dragend', function () {
            map.panTo(marker.getLatLng(), { animate: true });
        });
    }

    function handleMapClick(e) {
        if (!isDrawingPolygon && !isDrawingPipe) {
            if (!marker) {
                marker = L.marker([e.latlng.lat, e.latlng.lng], { draggable: true }).addTo(map);
                marker.on('drag', function (e) {
                    const position = marker.getLatLng();
                    latitudeField.value = position.lat.toFixed(6);
                    longitudeField.value = position.lng.toFixed(6);
                });
                marker.on('dragend', function () {
                    map.panTo([e.latlng.lat, e.latlng.lng], { animate: true });
                });
                latitudeField.value = e.latlng.lat.toFixed(6);
                longitudeField.value = e.latlng.lng.toFixed(6);
                map.panTo([e.latlng.lat, e.latlng.lng], { animate: true });
                showAlert('success', `Marker added: [${e.latlng.lat.toFixed(6)}, ${e.latlng.lng.toFixed(6)}]`);
            }
            return;
        }
        if (isDrawingPolygon) {
            const m = L.marker([e.latlng.lat, e.latlng.lng], { draggable: true }).addTo(map);
            m.on('dragend', updatePolygon);
            m.on('dblclick', function () {
                map.removeLayer(m);
                polygonMarkers = polygonMarkers.filter(mark => mark !== m);
                updatePolygon();
                showAlert('success', 'Polygon point removed.');
            });
            polygonMarkers.push(m);
            updatePolygon();
            map.panTo([e.latlng.lat, e.latlng.lng], { animate: true });
            showAlert('success', `Polygon point added: [${e.latlng.lat.toFixed(6)}, ${e.latlng.lng.toFixed(6)}]`);
        } else if (isDrawingPipe) {
            const m = L.marker([e.latlng.lat, e.latlng.lng], { draggable: true, icon: L.divIcon({ className: 'pipe-marker', html: '<div style="background-color:blue;width:8px;height:8px;border-radius:50%;"></div>' }) }).addTo(map);
            m.on('dragend', updatePipePath);
            m.on('dblclick', function () {
                map.removeLayer(m);
                pipeMarkers = pipeMarkers.filter(mark => mark !== m);
                updatePipePath();
                showAlert('success', 'Pipe path point removed.');
            });
            pipeMarkers.push(m);
            updatePipePath();
            map.panTo([e.latlng.lat, e.latlng.lng], { animate: true });
            showAlert('success', `Pipe path point added: [${e.latlng.lat.toFixed(6)}, ${e.latlng.lng.toFixed(6)}]`);
        }
    }

    function completeDrawing() {
        if (isDrawingPolygon && polygonPoints.length >= 3) {
            isDrawingPolygon = false;
            startDrawingPolygonBtn.classList.remove('active');
            showAlert('success', 'Polygon drawing completed.');
        } else if (isDrawingPipe && pipePoints.length >= 2) {
            isDrawingPipe = false;
            startDrawingPipeBtn.classList.remove('active');
            showAlert('success', 'Pipe path drawing completed.');
        } else {
            showAlert('warning', isDrawingPolygon ? 'At least 3 points required for polygon.' : 'At least 2 points required for pipe path.');
        }
    }

    const mapTools = document.getElementById('mapTools');

    toggleMapBtn.addEventListener('click', function () {
        console.log('Toggle Map button clicked');
        if (mapContainer.style.display === 'none') {
            mapContainer.style.display = 'block';
            mapTools.classList.remove('d-none');
            mapTools.classList.add('d-flex');
            toggleMapBtn.innerHTML = '<i class="ki-duotone ki-map fs-2 me-2"><span class="path1"></span><span class="path2"></span></i> Hide Map';
            if (!mapInitialized) {
                const lat = parseFloat(latitudeField.value) || 6.5244;
                const lng = parseFloat(longitudeField.value) || 3.3792;
                const zoom = lat && lng && !isNaN(lat) && !isNaN(lng) ? 15 : 13;
                initializeMap(lat, lng, zoom);
            }
            showAlert('success', 'Map is now visible. Click "Draw Polygon" or "Draw Pipe Path" to start.');
        } else {
            mapContainer.style.display = 'none';
            mapTools.classList.add('d-none');
            mapTools.classList.remove('d-flex');
            toggleMapBtn.innerHTML = '<i class="ki-duotone ki-map fs-2 me-2"><span class="path1"></span><span class="path2"></span></i> Show Map';
            if (map) map.remove();
            mapInitialized = false;
            isDrawingPolygon = false;
            isDrawingPipe = false;
            startDrawingPolygonBtn.classList.remove('active');
            startDrawingPipeBtn.classList.remove('active');
            showAlert('success', 'Map hidden. Use "Get Current Location" to add points.');
        }
    });

    startDrawingPolygonBtn.addEventListener('click', function () {
        console.log('Draw Polygon button clicked');
        if (mapContainer.style.display === 'none') {
            showAlert('warning', 'Please show the map first to start drawing.');
            return;
        }
        isDrawingPolygon = !isDrawingPolygon;
        isDrawingPipe = false;
        startDrawingPipeBtn.classList.remove('active');
        this.classList.toggle('active', isDrawingPolygon);
        showAlert(isDrawingPolygon ? 'info' : 'success', isDrawingPolygon ? 'Drawing polygon. Click on the map to add points. Double-click to complete.' : 'Polygon drawing stopped.');
    });

    startDrawingPipeBtn.addEventListener('click', function () {
        console.log('Draw Pipe Path button clicked');
        if (mapContainer.style.display === 'none') {
            showAlert('warning', 'Please show the map first to start drawing.');
            return;
        }
        isDrawingPipe = !isDrawingPipe;
        isDrawingPolygon = false;
        startDrawingPolygonBtn.classList.remove('active');
        this.classList.toggle('active', isDrawingPipe);
        showAlert(isDrawingPipe ? 'info' : 'success', isDrawingPipe ? 'Drawing pipe path. Click on the map to add points. Double-click to complete.' : 'Pipe path drawing stopped.');
    });

    zoomToLocationBtn.addEventListener('click', function () {
        console.log('Zoom to Location button clicked');
        if (mapContainer.style.display === 'none') {
            showAlert('warning', 'Please show the map first.');
            return;
        }
        const lat = parseFloat(latitudeField.value);
        const lng = parseFloat(longitudeField.value);
        if (!isNaN(lat) && !isNaN(lng)) {
            map.flyTo([lat, lng], 15, { animate: true, duration: 1 });
            showAlert('success', 'Zoomed to location.');
        } else {
            showAlert('danger', 'Please enter valid latitude and longitude values.');
        }
    });

    resetPolygonBtn.addEventListener('click', function () {
        console.log('Reset Polygon button clicked');
        polygonPoints = [];
        polygonMarkers.forEach(m => map.removeLayer(m));
        polygonMarkers = [];
        if (polygonLayer) map.removeLayer(polygonLayer);
        polygonLayer = null;
        polygonField.value = '';
        updatePolygonCenter();
        showAlert('success', 'Polygon coordinates and markers cleared.');
    });

    resetPipeBtn.addEventListener('click', function () {
        console.log('Reset Pipe Path button clicked');
        pipePoints = [];
        pipeMarkers.forEach(m => map.removeLayer(m));
        pipeMarkers = [];
        if (pipeLayer) map.removeLayer(pipeLayer);
        pipeLayer = null;
        pipeField.value = '';
        showAlert('success', 'Pipe path coordinates and markers cleared.');
    });

    clearAllBtn.addEventListener('click', function () {
        console.log('Clear All button clicked');
        polygonPoints = [];
        pipePoints = [];
        polygonMarkers.forEach(m => map.removeLayer(m));
        pipeMarkers.forEach(m => map.removeLayer(m));
        polygonMarkers = [];
        pipeMarkers = [];
        if (polygonLayer) map.removeLayer(polygonLayer);
        if (pipeLayer) map.removeLayer(pipeLayer);
        if (marker) map.removeLayer(marker);
        polygonLayer = null;
        pipeLayer = null;
        marker = null;
        polygonField.value = '';
        pipeField.value = '';
        latitudeField.value = '';
        longitudeField.value = '';
        showAlert('success', 'All coordinates and markers cleared.');
    });

    latitudeField.addEventListener('change', function () {
        if (map && mapInitialized && mapContainer.style.display !== 'none' && marker) {
            const lat = parseFloat(this.value);
            const lng = parseFloat(longitudeField.value);
            if (!isNaN(lat) && !isNaN(lng)) {
                marker.setLatLng([lat, lng]);
                map.flyTo([lat, lng], 15, { animate: true, duration: 1 });
            } else {
                showAlert('danger', 'Invalid latitude value.');
            }
        }
    });

    longitudeField.addEventListener('change', function () {
        if (map && mapInitialized && mapContainer.style.display !== 'none' && marker) {
            const lat = parseFloat(latitudeField.value);
            const lng = parseFloat(this.value);
            if (!isNaN(lat) && !isNaN(lng)) {
                marker.setLatLng([lat, lng]);
                map.flyTo([lat, lng], 15, { animate: true, duration: 1 });
            } else {
                showAlert('danger', 'Invalid longitude value.');
            }
        }
    });

    polygonField.addEventListener('change', function () {
        if (!map || !mapInitialized) return;
        try {
            const coords = JSON.parse(this.value || '[]');
            if (Array.isArray(coords) && coords.length >= 3 && coords.every(c => Array.isArray(c) && c.length === 2 && !isNaN(c[0]) && !isNaN(c[1]))) {
                polygonPoints = coords;
                polygonMarkers.forEach(m => map.removeLayer(m));
                polygonMarkers = [];
                if (polygonLayer) map.removeLayer(polygonLayer);
                polygonLayer = L.polygon(polygonPoints, { color: 'red', weight: 2 }).addTo(map);
                polygonPoints.forEach(coord => {
                    const m = L.marker(coord, { draggable: true }).addTo(map);
                    m.on('dragend', updatePolygon);
                    m.on('dblclick', function () {
                        map.removeLayer(m);
                        polygonMarkers = polygonMarkers.filter(mark => mark !== m);
                        updatePolygon();
                        showAlert('success', 'Polygon point removed.');
                    });
                    polygonMarkers.push(m);
                });
                map.fitBounds(polygonLayer.getBounds(), { padding: [50, 50] });
                updatePolygonCenter();
            } else {
                polygonField.value = '';
                polygonPoints = [];
                if (polygonLayer) map.removeLayer(polygonLayer);
                polygonMarkers.forEach(m => map.removeLayer(m));
                polygonMarkers = [];
                showAlert('danger', 'Invalid polygon coordinates: must be an array of [lat, lng] pairs with at least 3 points.');
            }
        } catch (e) {
            polygonField.value = '';
            showAlert('danger', 'Invalid polygon coordinates format: ' + e.message);
        }
    });

    pipeField.addEventListener('change', function () {
        if (!map || !mapInitialized) return;
        try {
            const coords = JSON.parse(this.value || '[]');
            if (Array.isArray(coords) && coords.length >= 2 && coords.every(c => Array.isArray(c) && c.length === 2 && !isNaN(c[0]) && !isNaN(c[1]))) {
                pipePoints = coords;
                pipeMarkers.forEach(m => map.removeLayer(m));
                pipeMarkers = [];
                if (pipeLayer) map.removeLayer(pipeLayer);
                pipeLayer = L.polyline(pipePoints, { color: 'blue', weight: 3 }).addTo(map);
                pipePoints.forEach(coord => {
                    const m = L.marker(coord, { draggable: true, icon: L.divIcon({ className: 'pipe-marker', html: '<div style="background-color:blue;width:8px;height:8px;border-radius:50%;"></div>' }) }).addTo(map);
                    m.on('dragend', updatePipePath);
                    m.on('dblclick', function () {
                        map.removeLayer(m);
                        pipeMarkers = pipeMarkers.filter(mark => mark !== m);
                        updatePipePath();
                        showAlert('success', 'Pipe path point removed.');
                    });
                    pipeMarkers.push(m);
                });
                map.fitBounds(pipeLayer.getBounds(), { padding: [50, 50] });
            } else {
                pipeField.value = '';
                pipePoints = [];
                if (pipeLayer) map.removeLayer(pipeLayer);
                pipeMarkers.forEach(m => map.removeLayer(m));
                pipeMarkers = [];
                showAlert('danger', 'Invalid pipe path coordinates: must be an array of [lat, lng] pairs with at least 2 points.');
            }
        } catch (e) {
            pipeField.value = '';
            showAlert('danger', 'Invalid pipe path coordinates format: ' + e.message);
        }
    });
</script>