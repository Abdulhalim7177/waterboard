@extends('layouts.staff')

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h2>Edit Customer: {{ $customer->first_name }} {{ $customer->surname }} ({{ $customer->billing_id ?? 'Pending' }})</h2>
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
                    <div id="alertContainer"></div>

                    <!-- Section Selection -->
                    <div class="row mb-6">
                        <div class="col-md-6 fv-row">
                            <label for="part" class="form-label required">Select Section to Edit</label>
                            <select class="form-select form-select-solid" id="part" name="part" required>
                                <option value="">Select Section</option>
                                <option value="personal">Personal Information</option>
                                <option value="address">Address Information</option>
                                <option value="billing">Billing Information</option>
                                <option value="location">Location Information</option>
                            </select>
                        </div>
                    </div>

                    <!-- Form Container -->
                    <div id="section-form" class="mt-6"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet for Location Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const partSelect = document.getElementById('part');
            const sectionForm = document.getElementById('section-form');
            const alertContainer = document.getElementById('alertContainer');
            const customerId = "{{ $customer->id }}";
            const csrfToken = "{{ csrf_token() }}";

            // Function to show alerts
            function showAlert(type, message) {
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

            // Function to clear form errors
            function clearFormErrors(form) {
                form.querySelectorAll('.is-invalid').forEach(input => {
                    input.classList.remove('is-invalid');
                    const errorDiv = input.nextElementSibling;
                    if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                        errorDiv.remove();
                    }
                });
            }

            // Function to load section form
            function loadSection(part, lgaId = null, wardId = null, categoryId = null) {
                const payload = { part };
                if (lgaId) payload.lga_id = lgaId;
                if (wardId) payload.ward_id = wardId;
                if (categoryId) payload.category_id = categoryId;

                fetch("{{ route('staff.customers.edit.section', $customer->id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload),
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.error || `HTTP error! Status: ${response.status}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        showAlert('danger', data.error);
                        sectionForm.innerHTML = '';
                    } else {
                        sectionForm.innerHTML = data.html;
                        initializeFormEventListeners();
                        if (part === 'location') {
                            initializeMap();
                        }
                    }
                })
                .catch(error => {
                    showAlert('danger', `Failed to load section: ${error.message}`);
                    console.error('Error:', error);
                });
            }

            // Initialize form event listeners
            function initializeFormEventListeners() {
                const lgaForm = document.getElementById('filter-lga-form');
                const wardForm = document.getElementById('filter-ward-form');
                const categoryForm = document.getElementById('filter-category-form');
                const editForms = document.querySelectorAll('#edit-personal-form, #edit-address-form, #edit-billing-form, #edit-location-form');

                // Prevent native form submission for filter forms
                [lgaForm, wardForm, categoryForm].forEach(form => {
                    if (form) {
                        form.querySelectorAll('select').forEach(select => {
                            select.removeAttribute('onchange');
                        });
                    }
                });

                if (lgaForm) {
                    const lgaSelect = lgaForm.querySelector('#lga_id');
                    lgaSelect.addEventListener('change', function() {
                        const lgaId = this.value;
                        if (lgaId) {
                            loadSection('address', lgaId);
                        } else {
                            sectionForm.innerHTML = '';
                        }
                    });
                }

                if (wardForm) {
                    const wardSelect = wardForm.querySelector('#ward_id');
                    wardSelect.addEventListener('change', function() {
                        const wardId = this.value;
                        const lgaId = wardForm.querySelector('[name="lga_id"]').value;
                        if (wardId) {
                            loadSection('address', lgaId, wardId);
                        } else {
                            loadSection('address', lgaId);
                        }
                    });
                }

                if (categoryForm) {
                    const categorySelect = categoryForm.querySelector('#category_id');
                    categorySelect.addEventListener('change', function() {
                        const categoryId = this.value;
                        if (categoryId) {
                            loadSection('billing', null, null, categoryId);
                        } else {
                            sectionForm.innerHTML = '';
                        }
                    });
                }

                editForms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        clearFormErrors(form);

                        const formData = new FormData(form);
                        fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            body: formData,
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(data => {
                                    throw data;
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            showAlert(data.status, data.message);
                            sectionForm.innerHTML = '';
                            partSelect.value = '';
                        })
                        .catch(error => {
                            if (error.errors) {
                                Object.entries(error.errors).forEach(([field, messages]) => {
                                    const input = form.querySelector(`[name="${field}"]`);
                                    if (input) {
                                        input.classList.add('is-invalid');
                                        let errorDiv = input.nextElementSibling;
                                        if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                                            errorDiv = document.createElement('div');
                                            errorDiv.className = 'invalid-feedback';
                                            input.parentNode.appendChild(errorDiv);
                                        }
                                        errorDiv.textContent = messages[0];
                                    }
                                });
                                showAlert('danger', 'Please correct the errors in the form.');
                            } else {
                                showAlert('danger', error.error || 'An error occurred while submitting the form.');
                                console.error('Error:', error);
                            }
                        });
                    });
                });
            }

            // Initialize map for location section
            function initializeMap() {
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

                function initializeMapInstance(lat = 6.5244, lng = 3.3792, zoom = 13) {
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
                        marker.on('drag', function(e) {
                            const position = marker.getLatLng();
                            latitudeField.value = position.lat.toFixed(6);
                            longitudeField.value = position.lng.toFixed(6);
                        });
                        marker.on('dragend', function() {
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
                            m.on('dblclick', function() {
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
                            m.on('dblclick', function() {
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
                    marker.on('drag', function(e) {
                        const position = marker.getLatLng();
                        latitudeField.value = position.lat.toFixed(6);
                        longitudeField.value = position.lng.toFixed(6);
                    });
                    marker.on('dragend', function() {
                        map.panTo(marker.getLatLng(), { animate: true });
                    });
                }

                function handleMapClick(e) {
                    if (!isDrawingPolygon && !isDrawingPipe) {
                        if (!marker) {
                            marker = L.marker([e.latlng.lat, e.latlng.lng], { draggable: true }).addTo(map);
                            marker.on('drag', function(e) {
                                const position = marker.getLatLng();
                                latitudeField.value = position.lat.toFixed(6);
                                longitudeField.value = position.lng.toFixed(6);
                            });
                            marker.on('dragend', function() {
                                map.panTo(marker.getLatLng(), { animate: true });
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
                        m.on('dblclick', function() {
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
                        m.on('dblclick', function() {
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

                toggleMapBtn.addEventListener('click', function() {
                    console.log('Toggle Map button clicked');
                    if (mapContainer.style.display === 'none') {
                        mapContainer.style.display = 'block';
                        toggleMapBtn.innerHTML = '<i class="ki-duotone ki-map fs-2 me-2"><span class="path1"></span><span class="path2"></span></i> Hide Map';
                        if (!mapInitialized) {
                            const lat = parseFloat(latitudeField.value) || 6.5244;
                            const lng = parseFloat(longitudeField.value) || 3.3792;
                            const zoom = lat && lng && !isNaN(lat) && !isNaN(lng) ? 15 : 13;
                            initializeMapInstance(lat, lng, zoom);
                        }
                        showAlert('success', 'Map is now visible. Click "Draw Polygon" or "Draw Pipe Path" to start.');
                    } else {
                        mapContainer.style.display = 'none';
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

                startDrawingPolygonBtn.addEventListener('click', function() {
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

                startDrawingPipeBtn.addEventListener('click', function() {
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

                zoomToLocationBtn.addEventListener('click', function() {
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

                resetPolygonBtn.addEventListener('click', function() {
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

                resetPipeBtn.addEventListener('click', function() {
                    console.log('Reset Pipe Path button clicked');
                    pipePoints = [];
                    pipeMarkers.forEach(m => map.removeLayer(m));
                    pipeMarkers = [];
                    if (pipeLayer) map.removeLayer(pipeLayer);
                    pipeLayer = null;
                    pipeField.value = '';
                    showAlert('success', 'Pipe path coordinates and markers cleared.');
                });

                clearAllBtn.addEventListener('click', function() {
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

                getLocationBtn.addEventListener('click', function() {
                    if (!navigator.geolocation) {
                        showAlert('danger', 'Geolocation is not supported by your browser. Show the map to add points manually.');
                        return;
                    }
                    if (window.location.protocol !== 'https:' && window.location.hostname !== 'localhost') {
                        showAlert('danger', 'Geolocation requires a secure connection (HTTPS). Please access this page via HTTPS or localhost.');
                        return;
                    }
                    const btn = this;
                    btn.disabled = true;
                    btn.innerHTML = '<i class="ki-duotone ki-geolocation fs-2 me-2"><span class="path1"></span><span class="path2"></span></i> Fetching...';
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const latitude = position.coords.latitude;
                            const longitude = position.coords.longitude;
                            const altitude = position.coords.altitude || '';
                            latitudeField.value = latitude.toFixed(6);
                            longitudeField.value = longitude.toFixed(6);
                            document.getElementById('altitude').value = altitude ? altitude.toFixed(2) : '';
                            if (!mapInitialized) {
                                initializeMapInstance(latitude, longitude, 15);
                            } else if (mapContainer.style.display !== 'none') {
                                if (marker) map.removeLayer(marker);
                                marker = L.marker([latitude, longitude], { draggable: true }).addTo(map);
                                marker.on('drag', function(e) {
                                    const position = marker.getLatLng();
                                    latitudeField.value = position.lat.toFixed(6);
                                    longitudeField.value = position.lng.toFixed(6);
                                });
                                marker.on('dragend', function() {
                                    map.panTo(marker.getLatLng(), { animate: true });
                                });
                                map.flyTo([latitude, longitude], 15, { animate: true, duration: 1 });
                            }
                            showAlert('success', altitude ? `Location set: [${latitude.toFixed(6)}, ${longitude.toFixed(6)}].` : `Location set: [${latitude.toFixed(6)}, ${longitude.toFixed(6)}]. Altitude unavailable; you can leave it blank or enter manually.`);
                            btn.disabled = false;
                            btn.innerHTML = '<i class="ki-duotone ki-geolocation fs-2 me-2"><span class="path1"></span><span class="path2"></span></i> Get Current Location';
                        },
                        function(error) {
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

                latitudeField.addEventListener('change', function() {
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

                longitudeField.addEventListener('change', function() {
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

                polygonField.addEventListener('change', function() {
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
                                m.on('dblclick', function() {
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

                pipeField.addEventListener('change', function() {
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
                                m.on('dblclick', function() {
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
            }

            // Load section when dropdown changes
            partSelect.addEventListener('change', function() {
                const part = this.value;
                if (part) {
                    loadSection(part);
                } else {
                    sectionForm.innerHTML = '';
                }
            });
        });
    </script>
    <style>
        #map { width: 100%; }
        #alertContainer .alert { display: none; }
        #alertContainer .alert.show { display: block; }
    </style>
@endsection