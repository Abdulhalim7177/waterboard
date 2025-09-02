<div class="card-body">
    <div id="alertContainer"></div>
    <form id="edit-location-form" action="{{ route('staff.customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="part" value="location">
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
                <button type="button" id="startDrawingPolygonBtn" class="btn btn-light-primary ms-2">
                    <i class="ki-duotone ki-pencil fs-2 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Draw Polygon
                </button>
                <button type="button" id="startDrawingPipeBtn" class="btn btn-light-primary ms-2">
                    <i class="ki-duotone ki-pencil fs-2 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Draw Pipe Path
                </button>
                <button type="button" id="zoomToLocationBtn" class="btn btn-light-primary ms-2">
                    <i class="ki-duotone ki-magnifier fs-2 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Zoom to Location
                </button>
                <button type="button" id="resetPolygonBtn" class="btn btn-light-secondary ms-2">
                    <i class="ki-duotone ki-trash fs-2 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Reset Polygon
                </button>
                <button type="button" id="resetPipeBtn" class="btn btn-light-secondary ms-2">
                    <i class="ki-duotone ki-trash fs-2 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Reset Pipe Path
                </button>
                <button type="button" id="clearAllBtn" class="btn btn-light-secondary ms-2">
                    <i class="ki-duotone ki-trash fs-2 me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Clear All
                </button>
            </div>
            <div class="col-md-12 fv-row mb-6">
                <div id="map" style="height: 400px; border: 1px solid #ddd; border-radius: 4px; display: none;"></div>
                <small class="form-text text-muted">Click "Show Map" and "Start Drawing" to draw a polygon or pipe path. Drag markers to reposition, double-click a marker to remove it, or double-click the map to complete the drawing.</small>
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
                <small class="form-text text-muted">Format: [[lat, lng], [lat, lng], ...]. Use "Start Drawing" to draw a polygon or pipe path, or "Get Current Location" to add points.</small>
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
                <a href="{{ route('staff.customers.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Submit for Approval</button>
            </div>
        </div>
    </form>
</div>