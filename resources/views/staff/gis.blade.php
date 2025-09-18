@extends('layouts.staff')

@section('content')
    <div class="d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white position-sticky top-0 z-index-1">
                    <h2 class="card-title text-white mb-0">Customer GIS Dashboard</h2>
                    <div class="card-toolbar">
                        <a href="{{ route('staff.customers.index') }}" class="btn btn-light btn-sm">Back to Customers</a>
                    </div>
                </div>
                <div class="card-body p-6">
                    <!-- Alert Container -->
                    <div id="alertContainer" class="mb-4"></div>

                    <!-- Filters and Controls -->
                    <div class="card mb-4 border-light shadow-sm">
                        <div class="card-header bg-light" id="filtersHeading">
                            <h5 class="mb-0">
                                <button class="btn btn-link text-dark" data-bs-toggle="collapse" data-bs-target="#filtersCollapse" aria-expanded="true" aria-controls="filtersCollapse">
                                    <i class="fas fa-filter me-2"></i> Filters & Map Controls
                                </button>
                            </h5>
                        </div>
                        <div id="filtersCollapse" class="collapse show" aria-labelledby="filtersHeading">
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-md-3">
                                        <label for="centerLga" class="form-label fw-bold">Center on LGA</label>
                                        <select id="centerLga" class="form-select">
                                            <option value="">Select LGA</option>
                                            @foreach ($lgas as $lga)
                                                <option value="{{ $lga->id }}|{{ $lga->latitude }}|{{ $lga->longitude }}|12">{{ $lga->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="centerWard" class="form-label fw-bold">Center on Ward</label>
                                        <select id="centerWard" class="form-select">
                                            <option value="">Select Ward</option>
                                            @foreach ($wards as $ward)
                                                <option value="{{ $ward->id }}|{{ $ward->latitude }}|{{ $ward->longitude }}|14">{{ $ward->name }} (LGA: {{ $ward->lga->name ?? 'N/A' }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="centerArea" class="form-label fw-bold">Center on Area</label>
                                        <select id="centerArea" class="form-select">
                                            <option value="">Select Area</option>
                                            @foreach ($areas as $area)
                                                <option value="{{ $area->id }}|{{ $area->latitude }}|{{ $area->longitude }}|15">{{ $area->name }} (Ward: {{ $area->ward->name ?? 'N/A' }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="startDate" class="form-label fw-bold">Start Date</label>
                                        <input type="date" id="startDate" class="form-control" value="{{ $defaultStartDate }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="endDate" class="form-label fw-bold">End Date</label>
                                        <input type="date" id="endDate" class="form-control" value="{{ $defaultEndDate }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="paymentStatus" class="form-label fw-bold">Payment Status</label>
                                        <select id="paymentStatus" class="form-select">
                                            <option value="">All</option>
                                            <option value="paid">Paid</option>
                                            <option value="unpaid">Unpaid</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="category" class="form-label fw-bold">Category</label>
                                        <select id="category" class="form-select">
                                            <option value="">All</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="tariff" class="form-label fw-bold">Tariff</label>
                                        <select id="tariff" class="form-select">
                                            <option value="">All</option>
                                            @foreach ($tariffs as $tariff)
                                                <option value="{{ $tariff->id }}">{{ $tariff->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="search" class="form-label fw-bold">Search Customer</label>
                                        <input type="text" id="search" class="form-control" placeholder="Name or Billing ID">
                                    </div>
                                    <div class="col-md-3 align-self-end">
                                        <div class="btn-group">
                                            <button type="button" id="applyFiltersBtn" class="btn btn-primary btn-hover-scale">
                                                <i class="fas fa-check me-2"></i> Apply Filters
                                            </button>
                                            <button type="button" id="resetFiltersBtn" class="btn btn-outline-secondary btn-hover-scale">
                                                <i class="fas fa-undo me-2"></i> Reset
                                            </button>
                                            <div class="btn-group w-100 w-md-auto" role="group">
                                                <button id="exportBtn" type="button" class="btn btn-success btn-hover-scale dropdown-toggle w-100" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-download me-2"></i> Export
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#" id="exportCsvLink">CSV</a></li>
                                                    <li><a class="dropdown-item" href="#" id="exportExcelLink">Excel</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Report Section -->
                    <div class="card mb-4 border-light shadow-sm">
                        <div class="card-header bg-light">
                            <h3 class="card-title mb-0">Customer Report (Date Range: <span id="reportDateRange">{{ \Carbon\Carbon::parse($defaultStartDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($defaultEndDate)->format('M d, Y') }}</span>)</h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded">
                                        <h5>Total Customers</h5>
                                        <p class="fs-3 mb-0" id="totalCustomers">0</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded">
                                        <h5>Payment Status</h5>
                                        <div class="progress" style="height: 20px;">
                                            <div id="paidProgress" class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0</div>
                                        </div>
                                        <small>Paid: <span id="paidCustomers">0</span></small>
                                        <div class="progress mt-2" style="height: 20px;">
                                            <div id="unpaidProgress" class="progress-bar bg-danger" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0</div>
                                        </div>
                                        <small>Unpaid: <span id="unpaidCustomers">0</span></small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded">
                                        <h5>Financial Summary</h5>
                                        <p><strong>Total Billed:</strong> ₦<span id="totalBilled">0.00</span></p>
                                        <p><strong>Total Unpaid:</strong> ₦<span id="totalUnpaid">0.00</span></p>
                                    </div>
                                    <div class="p-3 bg-light rounded mt-3">
                                        <h5>Breakdowns</h5>
                                        <p><strong>Categories:</strong></p>
                                        <ul id="categoryBreakdown" class="list-unstyled"></ul>
                                        <p><strong>Tariffs:</strong></p>
                                        <ul id="tariffBreakdown" class="list-unstyled"></ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Map Controls -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <button type="button" id="getLocationBtn" class="btn btn-primary btn-hover-scale">
                                <i class="fas fa-map-marker-alt me-2"></i> Get Current Location
                            </button>
                            <button type="button" id="toggleMapBtn" class="btn btn-primary btn-hover-scale ms-2">
                                <i class="fas fa-map me-2"></i> Show Map
                            </button>
                            <button type="button" id="togglePolygonsBtn" class="btn btn-primary btn-hover-scale ms-2">
                                <i class="fas fa-draw-polygon me-2"></i> Show Polygons
                            </button>
                            <button type="button" id="togglePipePathsBtn" class="btn btn-primary btn-hover-scale ms-2">
                                <i class="fas fa-water me-2"></i> Show Pipe Paths
                            </button>
                            <button type="button" id="clearLayersBtn" class="btn btn-outline-secondary btn-hover-scale ms-2">
                                <i class="fas fa-trash me-2"></i> Clear Layers
                            </button>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div id="map" style="height: 600px; border: 1px solid #ddd; border-radius: 8px; display: none;"></div>
                            <small class="form-text text-muted">Click "Show Map" to view customer locations, "Show Polygons" to display customer polygons, or "Show Pipe Paths" to view water pipe connections. Use filters to refine data.</small>
                        </div>
                    </div>
                    @if ($error)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ $error }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <style>
        #map { width: 100%; }
        #alertContainer .alert { display: none; }
        #alertContainer .alert.show { display: block; }
        .btn-hover-scale:hover {
            transform: scale(1.05);
            transition: transform 0.2s;
        }
        .card-header.bg-primary {
            border-radius: 8px 8px 0 0;
        }
        .card.border-light {
            border: 1px solid rgba(0,0,0,0.125);
        }
        .pipe-path {
            color: #800080;
            weight: 3;
        }
    </style>
@endsection

@section('scripts')
    <script>
        console.log('GIS Dashboard script loaded');

        let map = null;
        let mapInitialized = false;
        let customerLayer = null;
        let polygonLayers = [];
        let pipePathLayers = [];
        let showPolygons = false;
        let showPipePaths = false;

        const getLocationBtn = document.getElementById('getLocationBtn');
        const toggleMapBtn = document.getElementById('toggleMapBtn');
        const togglePolygonsBtn = document.getElementById('togglePolygonsBtn');
        const togglePipePathsBtn = document.getElementById('togglePipePathsBtn');
        const clearLayersBtn = document.getElementById('clearLayersBtn');
        const centerLga = document.getElementById('centerLga');
        const centerWard = document.getElementById('centerWard');
        const centerArea = document.getElementById('centerArea');
        const startDate = document.getElementById('startDate');
        const endDate = document.getElementById('endDate');
        const paymentStatus = document.getElementById('paymentStatus');
        const category = document.getElementById('category');
        const tariff = document.getElementById('tariff');
        const search = document.getElementById('search');
        const applyFiltersBtn = document.getElementById('applyFiltersBtn');
        const resetFiltersBtn = document.getElementById('resetFiltersBtn');
        const exportCsvLink = document.getElementById('exportCsvLink');
        const exportExcelLink = document.getElementById('exportExcelLink');
        const mapContainer = document.getElementById('map');
        const alertContainer = document.getElementById('alertContainer');
        const totalCustomers = document.getElementById('totalCustomers');
        const paidCustomers = document.getElementById('paidCustomers');
        const unpaidCustomers = document.getElementById('unpaidCustomers');
        const paidProgress = document.getElementById('paidProgress');
        const unpaidProgress = document.getElementById('unpaidProgress');
        const totalBilled = document.getElementById('totalBilled');
        const totalUnpaid = document.getElementById('totalUnpaid');
        const categoryBreakdown = document.getElementById('categoryBreakdown');
        const tariffBreakdown = document.getElementById('tariffBreakdown');
        const reportDateRange = document.getElementById('reportDateRange');

        // Validate DOM elements
        const elements = [
            { id: 'getLocationBtn', element: getLocationBtn },
            { id: 'toggleMapBtn', element: toggleMapBtn },
            { id: 'togglePolygonsBtn', element: togglePolygonsBtn },
            { id: 'togglePipePathsBtn', element: togglePipePathsBtn },
            { id: 'clearLayersBtn', element: clearLayersBtn },
            { id: 'centerLga', element: centerLga },
            { id: 'centerWard', element: centerWard },
            { id: 'centerArea', element: centerArea },
            { id: 'startDate', element: startDate },
            { id: 'endDate', element: endDate },
            { id: 'paymentStatus', element: paymentStatus },
            { id: 'category', element: category },
            { id: 'tariff', element: tariff },
            { id: 'search', element: search },
            { id: 'applyFiltersBtn', element: applyFiltersBtn },
            { id: 'resetFiltersBtn', element: resetFiltersBtn },
            { id: 'exportCsvLink', element: exportCsvLink },
            { id: 'exportExcelLink', element: exportExcelLink },
            { id: 'map', element: mapContainer },
            { id: 'alertContainer', element: alertContainer },
            { id: 'totalCustomers', element: totalCustomers },
            { id: 'paidCustomers', element: paidCustomers },
            { id: 'unpaidCustomers', element: unpaidCustomers },
            { id: 'paidProgress', element: paidProgress },
            { id: 'unpaidProgress', element: unpaidProgress },
            { id: 'totalBilled', element: totalBilled },
            { id: 'totalUnpaid', element: totalUnpaid },
            { id: 'categoryBreakdown', element: categoryBreakdown },
            { id: 'tariffBreakdown', element: tariffBreakdown },
            { id: 'reportDateRange', element: reportDateRange }
        ];
        elements.forEach(({ id, element }) => {
            if (!element) console.error(`Element with ID "${id}" not found`);
        });

        // Check jQuery
        if (typeof jQuery === 'undefined') {
            console.error('jQuery not loaded');
            showAlert('danger', 'jQuery failed to load. Some features may not work.');
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

        function initializeMap() {
            console.log('Initializing Leaflet map');
            if (!L) {
                console.error('Leaflet not loaded');
                showAlert('danger', 'Leaflet failed to load. Map functionality unavailable.');
                return;
            }
            map = L.map('map').setView([11.5244, 7.3200], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            mapInitialized = true;
            if (mapContainer.style.display !== 'none') {
                map.invalidateSize();
            }
        }

        toggleMapBtn.addEventListener('click', function() {
            console.log('Toggle Map button clicked');
            if (!mapContainer) {
                showAlert('danger', 'Map container not found.');
                return;
            }
            if (mapContainer.style.display === 'none') {
                mapContainer.style.display = 'block';
                toggleMapBtn.innerHTML = '<i class="fas fa-map me-2"></i> Hide Map';
                if (!mapInitialized) {
                    initializeMap();
                    applyFilters();
                } else {
                    map.invalidateSize();
                }
                showAlert('success', 'Map is now visible. Apply filters or click to add points.');
            } else {
                mapContainer.style.display = 'none';
                toggleMapBtn.innerHTML = '<i class="fas fa-map me-2"></i> Show Map';
                showAlert('success', 'Map hidden.');
            }
        });

        togglePolygonsBtn.addEventListener('click', function() {
            console.log('Toggle Polygons button clicked');
            showPolygons = !showPolygons;
            togglePolygonsBtn.innerHTML = showPolygons ? 
                '<i class="fas fa-draw-polygon me-2"></i> Hide Polygons' : 
                '<i class="fas fa-draw-polygon me-2"></i> Show Polygons';
            updateMapLayers();
            showAlert('success', showPolygons ? 'Customer polygons displayed.' : 'Customer polygons hidden.');
        });

        togglePipePathsBtn.addEventListener('click', function() {
            console.log('Toggle Pipe Paths button clicked');
            showPipePaths = !showPipePaths;
            togglePipePathsBtn.innerHTML = showPipePaths ? 
                '<i class="fas fa-water me-2"></i> Hide Pipe Paths' : 
                '<i class="fas fa-water me-2"></i> Show Pipe Paths';
            updateMapLayers();
            showAlert('success', showPipePaths ? 'Pipe paths displayed.' : 'Pipe paths hidden.');
        });

        clearLayersBtn.addEventListener('click', function() {
            console.log('Clear Layers button clicked');
            if (customerLayer) {
                map.removeLayer(customerLayer);
                customerLayer = null;
            }
            polygonLayers.forEach(layer => map.removeLayer(layer));
            pipePathLayers.forEach(layer => map.removeLayer(layer));
            polygonLayers = [];
            pipePathLayers = [];
            showAlert('success', 'All markers, polygons, and pipe paths cleared from map.');
            applyFilters();
        });

        function applyFilters() {
            console.log('Applying filters');
            if (!jQuery) {
                showAlert('danger', 'jQuery not loaded. Cannot apply filters.');
                return;
            }
            if (startDate.value && endDate.value && new Date(startDate.value) > new Date(endDate.value)) {
                showAlert('danger', 'Start date cannot be after end date.');
                return;
            }
            const filters = {
                start_date: startDate.value,
                end_date: endDate.value,
                payment_status: paymentStatus.value,
                category_id: category.value,
                tariff_id: tariff.value,
                search: search.value
            };

            $.ajax({
                url: '{{ route("staff.gis.filter") }}',
                method: 'GET',
                data: filters,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                success: function(data) {
                    console.log('Filter response:', data);
                    if (data.error) {
                        showAlert('danger', data.error);
                        updateMap([]);
                        updateReport({ 
                            total_customers: 0, 
                            paid_customers: 0, 
                            unpaid_customers: 0, 
                            total_billed: 0, 
                            total_unpaid: 0, 
                            category_breakdown: [], 
                            tariff_breakdown: [], 
                            start_date: startDate.value, 
                            end_date: endDate.value 
                        });
                        return;
                    }
                    updateMap(data.features);
                    updateReport(data.summary);
                    showAlert('success', `Map and report updated with ${data.features.length} customers for ${new Date(data.summary.start_date).toLocaleDateString()} - ${new Date(data.summary.end_date).toLocaleDateString()}.`);
                },
                error: function(xhr) {
                    console.error('Filter request failed:', xhr.responseText);
                    showAlert('danger', 'Failed to apply filters. Check the console for details.');
                    updateMap([]);
                    updateReport({ 
                        total_customers: 0, 
                        paid_customers: 0, 
                        unpaid_customers: 0, 
                        total_billed: 0, 
                        total_unpaid: 0, 
                        category_breakdown: [], 
                        tariff_breakdown: [], 
                        start_date: startDate.value, 
                        end_date: endDate.value 
                    });
                }
            });
        }

        function updateMap(features) {
            if (!map || !mapInitialized) {
                showAlert('warning', 'Map not initialized. Please show the map first.');
                return;
            }
            if (customerLayer) {
                map.removeLayer(customerLayer);
                customerLayer = null;
            }
            customerLayer = L.geoJSON(features, {
                pointToLayer: function(feature, latlng) {
                    console.log('Rendering marker for customer:', feature.properties.name, 'at', latlng);
                    return L.circleMarker(latlng, {
                        radius: 8,
                        fillColor: feature.properties.payment_status === 'paid' ? 'green' : 'red',
                        color: '#000',
                        weight: 1,
                        opacity: 1,
                        fillOpacity: 0.8
                    });
                },
                onEachFeature: function(feature, layer) {
                    layer.bindPopup(`
                        <b>Customer:</b> ${feature.properties.name}<br>
                        <b>Billing ID:</b> ${feature.properties.billing_id}<br>
                        <b>Payment Status:</b> ${feature.properties.payment_status}<br>
                        <b>Total Billed:</b> ₦${Number(feature.properties.total_billed).toFixed(2)}<br>
                        <b>Total Unpaid:</b> ₦${Number(feature.properties.total_unpaid).toFixed(2)}<br>
                        <b>Category:</b> ${feature.properties.category}<br>
                        <b>Tariff:</b> ${feature.properties.tariff}<br>
                        <b>LGA:</b> ${feature.properties.lga}<br>
                        <b>Ward:</b> ${feature.properties.ward}<br>
                        <b>Area:</b> ${feature.properties.area}
                    `);
                    layer.on('click', function() {
                        map.flyTo(latlng, map.getZoom(), { animate: true, duration: 1 });
                        showAlert('success', `Zoomed to customer: ${feature.properties.name}`);
                    });
                }
            }).addTo(map);
            updateMapLayers();
            if (features.length > 0) {
                const bounds = customerLayer.getBounds();
                if (bounds.isValid()) {
                    map.fitBounds(bounds, { padding: [50, 50] });
                }
            }
        }

        function updateMapLayers() {
            if (!map || !mapInitialized) return;
            polygonLayers.forEach(layer => map.removeLayer(layer));
            pipePathLayers.forEach(layer => map.removeLayer(layer));
            polygonLayers = [];
            pipePathLayers = [];

            if (customerLayer) {
                customerLayer.eachLayer(function(layer) {
                    const feature = layer.feature;
                    if (showPolygons && feature.properties.polygon_coordinates && feature.properties.polygon_coordinates.length > 0) {
                        try {
                            const polygonLayer = L.polygon(feature.properties.polygon_coordinates, { color: '#007bff', weight: 2 }).addTo(map);
                            polygonLayer.bindPopup(`
                                <b>Polygon for ${feature.properties.name}</b><br>
                                <b>Billing ID:</b> ${feature.properties.billing_id}<br>
                                <b>Coordinates:</b> ${feature.properties.polygon_coordinates.map(coord => `[${coord[0].toFixed(6)}, ${coord[1].toFixed(6)}]`).join(', ')}
                            `);
                            polygonLayers.push(polygonLayer);
                        } catch (e) {
                            console.error(`Invalid polygon coordinates for ${feature.properties.name}:`, e);
                        }
                    }
                    if (showPipePaths && feature.properties.pipe_path && feature.properties.pipe_path.length > 0) {
                        try {
                            const pipePathLayer = L.polyline(feature.properties.pipe_path, { color: '#800080', weight: 3, className: 'pipe-path' }).addTo(map);
                            pipePathLayer.bindPopup(`
                                <b>Pipe Path for ${feature.properties.name}</b><br>
                                <b>Billing ID:</b> ${feature.properties.billing_id}<br>
                                <b>Coordinates:</b> ${feature.properties.pipe_path.map(coord => `[${coord[0].toFixed(6)}, ${coord[1].toFixed(6)}]`).join(', ')}
                            `);
                            pipePathLayers.push(pipePathLayer);
                        } catch (e) {
                            console.error(`Invalid pipe path coordinates for ${feature.properties.name}:`, e);
                        }
                    }
                });
            }
        }

        function updateReport(summary) {
            console.log('Updating report:', summary);
            totalCustomers.textContent = summary.total_customers || 0;
            paidCustomers.textContent = summary.paid_customers || 0;
            unpaidCustomers.textContent = summary.unpaid_customers || 0;
            totalBilled.textContent = Number(summary.total_billed || 0).toFixed(2);
            totalUnpaid.textContent = Number(summary.total_unpaid || 0).toFixed(2);
            const total = summary.total_customers || 1;
            paidProgress.style.width = `${(summary.paid_customers / total) * 100}%`;
            paidProgress.setAttribute('aria-valuenow', summary.paid_customers || 0);
            unpaidProgress.style.width = `${(summary.unpaid_customers / total) * 100}%`;
            unpaidProgress.setAttribute('aria-valuenow', summary.unpaid_customers || 0);
            categoryBreakdown.innerHTML = (summary.category_breakdown || []).map(item => 
                `<li>${item.name}: ${item.count}</li>`
            ).join('');
            tariffBreakdown.innerHTML = (summary.tariff_breakdown || []).map(item => 
                `<li>${item.name}: ${item.count}</li>`
            ).join('');
            reportDateRange.textContent = `${new Date(summary.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })} - ${new Date(summary.end_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}`;
        }

        function addPolygonPoint(lat, lng) {
            if (!map || !mapInitialized) {
                showAlert('warning', 'Map not initialized. Please show the map first.');
                return;
            }
            const point = [lat, lng];
            const tempPolygon = L.polygon([point], { color: '#007bff' }).addTo(map);
            polygonLayers.push(tempPolygon);
            showAlert('success', `Point added: [${lat.toFixed(6)}, ${lng.toFixed(6)}]`);
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
            btn.innerHTML = '<i class="fas fa-map-marker-alt me-2"></i> Fetching...';
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    console.log('Geolocation success:', position);
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;
                    if (mapInitialized && mapContainer.style.display !== 'none') {
                        map.flyTo([latitude, longitude], map.getZoom(), { animate: true, duration: 1 });
                        map.invalidateSize();
                        const marker = L.marker([latitude, longitude]).addTo(map).bindPopup('Current Location').openPopup();
                        polygonLayers.push(marker);
                    }
                    showAlert('success', `Location centered: [${latitude.toFixed(6)}, ${longitude.toFixed(6)}].`);
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-map-marker-alt me-2"></i> Get Current Location';
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
                    btn.innerHTML = '<i class="fas fa-map-marker-alt me-2"></i> Get Current Location';
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        });

        exportCsvLink.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Export CSV clicked');
            if (startDate.value && endDate.value && new Date(startDate.value) > new Date(endDate.value)) {
                showAlert('danger', 'Start date cannot be after end date.');
                return;
            }
            const filters = {
                start_date: startDate.value,
                end_date: endDate.value,
                payment_status: paymentStatus.value,
                category_id: category.value,
                tariff_id: tariff.value,
                search: search.value
            };
            const queryString = new URLSearchParams(filters).toString();
            window.location.href = '{{ route("staff.gis.export.csv") }}?' + queryString;
            showAlert('success', 'Exporting customer data as CSV.');
        });

        exportExcelLink.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Export Excel clicked');
            if (startDate.value && endDate.value && new Date(startDate.value) > new Date(endDate.value)) {
                showAlert('danger', 'Start date cannot be after end date.');
                return;
            }
            const filters = {
                start_date: startDate.value,
                end_date: endDate.value,
                payment_status: paymentStatus.value,
                category_id: category.value,
                tariff_id: tariff.value,
                search: search.value
            };
            const queryString = new URLSearchParams(filters).toString();
            window.location.href = '{{ route("staff.gis.export.excel") }}?' + queryString;
            showAlert('success', 'Exporting customer data as Excel.');
        });

        centerLga.addEventListener('change', function() { centerMap(this); });
        centerWard.addEventListener('change', function() { centerMap(this); });
        centerArea.addEventListener('change', function() { centerMap(this); });

        function centerMap(selectElement) {
            console.log('Center Map triggered:', selectElement.id, selectElement.value);
            if (selectElement.value) {
                const [id, lat, lng, zoom] = selectElement.value.split('|');
                if (lat && lng && zoom) {
                    if (mapInitialized && mapContainer.style.display !== 'none') {
                        map.flyTo([parseFloat(lat), parseFloat(lng)], parseInt(zoom), { animate: true, duration: 1 });
                        map.invalidateSize();
                        showAlert('success', `Map centered on ${selectElement.id.replace('center', '')}: ${selectElement.options[selectElement.selectedIndex].text}`);
                    } else {
                        showAlert('warning', 'Please show the map to center it.');
                    }
                }
            }
        }

        applyFiltersBtn.addEventListener('click', function() {
            console.log('Apply Filters button clicked');
            applyFilters();
        });

        resetFiltersBtn.addEventListener('click', function() {
            console.log('Reset Filters button clicked');
            startDate.value = '{{ $defaultStartDate }}';
            endDate.value = '{{ $defaultEndDate }}';
            paymentStatus.value = '';
            category.value = '';
            tariff.value = '';
            search.value = '';
            centerLga.value = '';
            centerWard.value = '';
            centerArea.value = '';
            applyFilters();
            showAlert('success', 'Filters and center selections reset.');
        });

        // Initialize map if visible
        if (mapContainer.style.display !== 'none') {
            initializeMap();
            applyFilters();
        }
    </script>
@endsection

@section('styles')
    <style>
        @media (max-width: 767.98px) {
            .dropdown-menu {
                min-width: 100%;
            }
            
            .w-100.w-md-auto {
                width: 100% !important;
            }
            
            .btn-group.w-100.w-md-auto {
                width: 100% !important;
                margin-bottom: 0.5rem;
            }
            
            .btn-group .btn {
                text-align: left;
            }
        }
        
        .btn-hover-scale:hover {
            transform: scale(1.05);
            transition: transform 0.2s ease-in-out;
        }
    </style>
@endsection