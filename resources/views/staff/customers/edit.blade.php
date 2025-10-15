@extends('layouts.staff')

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h2>Edit Customer - Personal Information</h2>
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
            let allLgas, allWards, allAreas, allCategories, allTariffs;

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
            function loadSection(part) {
                fetch("{{ route('staff.customers.edit.section', $customer->id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ part }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        showAlert('danger', data.error);
                        sectionForm.innerHTML = '';
                    } else {
                        sectionForm.innerHTML = data.html;
                        allLgas = data.lgas;
                        allWards = data.wards;
                        allAreas = data.areas;
                        allCategories = data.categories;
                        allTariffs = data.tariffs;

                        // Initialize any JavaScript for the loaded section
                        if (part === 'location') {
                            initializeMap();
                        } else if (part === 'address') {
                            initAddressForm();
                        } else if (part === 'billing') {
                            initBillingForm();
                        } else if (part === 'personal') {
                            initPersonalForm();
                        }
                        
                        // Add event listener to the form to handle submissions via AJAX
                        const loadedForm = sectionForm.querySelector('form');
                        if (loadedForm) {
                            const submitBtn = loadedForm.querySelector('button[type="submit"]');
                            if (submitBtn) {
                                const originalText = submitBtn.innerHTML;
                                submitBtn.setAttribute('data-original-text', originalText);
                            }
                            
                            loadedForm.addEventListener('submit', function(e) {
                                e.preventDefault();
                                
                                const submitBtn = loadedForm.querySelector('button[type="submit"]');
                                if (submitBtn) {
                                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Submitting...';
                                    submitBtn.disabled = true;
                                }
                                
                                fetch(loadedForm.action, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded',
                                        'X-CSRF-TOKEN': csrfToken,
                                        'X-HTTP-Method-Override': 'PUT'
                                    },
                                    body: new URLSearchParams(new FormData(loadedForm))
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status === 'success') {
                                        showAlert('success', data.message);
                                        setTimeout(() => {
                                            loadSection(part);
                                        }, 1000);
                                    } else if (data.status === 'error') {
                                        showAlert('danger', data.error || 'An error occurred');
                                    } else if (data.status === 'info') {
                                        showAlert('info', data.message);
                                    } else {
                                        showAlert('danger', 'Please check the form for errors');
                                        console.log(data.errors);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    showAlert('danger', 'An unexpected error occurred');
                                })
                                .finally(() => {
                                    if (submitBtn) {
                                        submitBtn.innerHTML = submitBtn.getAttribute('data-original-text');
                                        submitBtn.disabled = false;
                                    }
                                });
                            });
                        }
                    }
                })
                .catch(error => {
                    showAlert('danger', `Failed to load section: ${error.message}`);
                    console.error('Error:', error);
                });
            }

            function initPersonalForm() {
                // No special initialization needed
            }

            function initAddressForm() {
                const lgaSelect = document.getElementById('lga_id');
                const wardSelect = document.getElementById('ward_id');
                const areaSelect = document.getElementById('area_id');

                function filterWards() {
                    const selectedLgaId = lgaSelect.value;
                    const currentWardId = '{{ old("ward_id", $customer->ward_id) }}';
                    wardSelect.innerHTML = '<option value="">Select Ward</option>';
                    allWards.forEach(ward => {
                        if (ward.lga_id == selectedLgaId) {
                            const option = new Option(ward.name, ward.id);
                            if (ward.id == currentWardId) {
                                option.selected = true;
                            }
                            wardSelect.add(option);
                        }
                    });
                    filterAreas();
                }

                function filterAreas() {
                    const selectedWardId = wardSelect.value;
                    const currentAreaId = '{{ old("area_id", $customer->area_id) }}';
                    areaSelect.innerHTML = '<option value="">Select Area</option>';
                    allAreas.forEach(area => {
                        if (area.ward_id == selectedWardId) {
                            const option = new Option(area.name, area.id);
                            if (area.id == currentAreaId) {
                                option.selected = true;
                            }
                            areaSelect.add(option);
                        }
                    });
                }

                lgaSelect.addEventListener('change', filterWards);
                wardSelect.addEventListener('change', filterAreas);

                // Initial population
                filterWards();
            }

            function initBillingForm() {
                const categorySelect = document.getElementById('category_id');
                const tariffSelect = document.getElementById('tariff_id');

                function filterTariffs() {
                    const selectedCategoryId = categorySelect.value;
                    const currentTariffId = '{{ old("tariff_id", $customer->tariff_id) }}';
                    tariffSelect.innerHTML = '<option value="">Select Tariff</option>';
                    allTariffs.forEach(tariff => {
                        if (tariff.category_id == selectedCategoryId) {
                            const option = new Option(`${tariff.name} (${tariff.amount}/${tariff.unit})`, tariff.id);
                            if (tariff.id == currentTariffId) {
                                option.selected = true;
                            }
                            tariffSelect.add(option);
                        }
                    });
                }

                categorySelect.addEventListener('change', filterTariffs);

                // Initial population
                filterTariffs();
            }

            function initializeMap() {
                // Map initialization logic from edit_location.blade.php
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

                // Load existing coordinates
                try {
                    if (polygonField.value) {
                        polygonPoints = JSON.parse(polygonField.value);
                    }
                } catch (e) {
                    polygonPoints = [];
                }
                try {
                    if (pipeField.value) {
                        pipePoints = JSON.parse(pipeField.value);
                    }
                } catch (e) {
                    pipePoints = [];
                }

                function initializeMapInternal(lat = 6.5244, lng = 3.3792, zoom = 13) {
                    map = L.map('map').setView([lat, lng], zoom);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);
                    map.on('click', handleMapClick);
                    map.on('dblclick', completeDrawing);

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

                toggleMapBtn.addEventListener('click', function () {
                    if (mapContainer.style.display === 'none') {
                        mapContainer.style.display = 'block';
                        toggleMapBtn.innerHTML = '<i class="ki-duotone ki-map fs-2 me-2"><span class="path1"></span><span class="path2"></span></i> Hide Map';
                        if (!mapInitialized) {
                            const lat = parseFloat(latitudeField.value) || 6.5244;
                            const lng = parseFloat(longitudeField.value) || 3.3792;
                            const zoom = lat && lng && !isNaN(lat) && !isNaN(lng) ? 15 : 13;
                            initializeMapInternal(lat, lng, zoom);
                        }
                    } else {
                        mapContainer.style.display = 'none';
                        toggleMapBtn.innerHTML = '<i class="ki-duotone ki-map fs-2 me-2"><span class="path1"></span><span class="path2"></span></i> Show Map';
                        if (map) map.remove();
                        mapInitialized = false;
                    }
                });

                startDrawingPolygonBtn.addEventListener('click', function () {
                    isDrawingPolygon = !isDrawingPolygon;
                    isDrawingPipe = false;
                    startDrawingPipeBtn.classList.remove('active');
                    this.classList.toggle('active', isDrawingPolygon);
                });

                startDrawingPipeBtn.addEventListener('click', function () {
                    isDrawingPipe = !isDrawingPipe;
                    isDrawingPolygon = false;
                    startDrawingPolygonBtn.classList.remove('active');
                    this.classList.toggle('active', isDrawingPipe);
                });

                zoomToLocationBtn.addEventListener('click', function () {
                    const lat = parseFloat(latitudeField.value);
                    const lng = parseFloat(longitudeField.value);
                    if (!isNaN(lat) && !isNaN(lng)) {
                        map.flyTo([lat, lng], 15, { animate: true, duration: 1 });
                    }
                });

                resetPolygonBtn.addEventListener('click', function () {
                    polygonPoints = [];
                    polygonMarkers.forEach(m => map.removeLayer(m));
                    polygonMarkers = [];
                    if (polygonLayer) map.removeLayer(polygonLayer);
                    polygonLayer = null;
                    polygonField.value = '';
                    updatePolygonCenter();
                });

                resetPipeBtn.addEventListener('click', function () {
                    pipePoints = [];
                    pipeMarkers.forEach(m => map.removeLayer(m));
                    pipeMarkers = [];
                    if (pipeLayer) map.removeLayer(pipeLayer);
                    pipeLayer = null;
                    pipeField.value = '';
                });

                clearAllBtn.addEventListener('click', function () {
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
                });

                latitudeField.addEventListener('change', function () {
                    if (map && mapInitialized && marker) {
                        const lat = parseFloat(this.value);
                        const lng = parseFloat(longitudeField.value);
                        if (!isNaN(lat) && !isNaN(lng)) {
                            marker.setLatLng([lat, lng]);
                            map.flyTo([lat, lng], 15, { animate: true, duration: 1 });
                        }
                    }
                });

                longitudeField.addEventListener('change', function () {
                    if (map && mapInitialized && marker) {
                        const lat = parseFloat(latitudeField.value);
                        const lng = parseFloat(this.value);
                        if (!isNaN(lat) && !isNaN(lng)) {
                            marker.setLatLng([lat, lng]);
                            map.flyTo([lat, lng], 15, { animate: true, duration: 1 });
                        }
                    }
                });

                polygonField.addEventListener('change', function () {
                    if (!map || !mapInitialized) return;
                    try {
                        const coords = JSON.parse(this.value || '[]');
                        if (Array.isArray(coords) && coords.length >= 3) {
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
                                });
                                polygonMarkers.push(m);
                            });
                            map.fitBounds(polygonLayer.getBounds(), { padding: [50, 50] });
                            updatePolygonCenter();
                        } else {
                            polygonField.value = '';
                        }
                    } catch (e) {
                        polygonField.value = '';
                    }
                });

                pipeField.addEventListener('change', function () {
                    if (!map || !mapInitialized) return;
                    try {
                        const coords = JSON.parse(this.value || '[]');
                        if (Array.isArray(coords) && coords.length >= 2) {
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
                                });
                                pipeMarkers.push(m);
                            });
                            map.fitBounds(pipeLayer.getBounds(), { padding: [50, 50] });
                        } else {
                            pipeField.value = '';
                        }
                    } catch (e) {
                        pipeField.value = '';
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