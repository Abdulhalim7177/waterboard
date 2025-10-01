<!--begin::Alerts-->
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
<!--end::Alerts-->

<!--begin::Tab Navigation-->
<ul class="nav nav-stretch nav-pills nav-pills-custom d-flex mt-3">
    <li class="nav-item p-0 ms-0 me-8">
        <a class="nav-link btn btn-color-muted px-0" href="javascript:void(0)" onclick="loadSection('personal')">
            <span class="nav-text fw-semibold fs-4 mb-3">Personal Info</span>
            <span class="badge badge-warning ms-2">Edit Mode</span>
            <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-0 bg-primary rounded"></span>
        </a>
    </li>
    <li class="nav-item p-0 ms-0 me-8">
        <a class="nav-link btn btn-color-muted px-0" href="javascript:void(0)" onclick="loadSection('address')">
            <span class="nav-text fw-semibold fs-4 mb-3">Address</span>
            <span class="badge badge-warning ms-2">Edit Mode</span>
            <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-0 bg-primary rounded"></span>
        </a>
    </li>
    <li class="nav-item p-0 ms-0 me-8">
        <a class="nav-link btn btn-color-muted px-0" href="javascript:void(0)" onclick="loadSection('billing')">
            <span class="nav-text fw-semibold fs-4 mb-3">Billing</span>
            <span class="badge badge-warning ms-2">Edit Mode</span>
            <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-0 bg-primary rounded"></span>
        </a>
    </li>
    <li class="nav-item p-0 ms-0">
        <a class="nav-link btn btn-color-muted active px-0" href="javascript:void(0)">
            <span class="nav-text fw-semibold fs-4 mb-3">Location</span>
            <span class="badge badge-success ms-2">Edit Mode</span>
            <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-0 bg-primary rounded"></span>
        </a>
    </li>
</ul>
<!--end::Tab Navigation-->

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
            <a href="{{ route('staff.customers.index') }}" class="btn btn-light me-3">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <span class="indicator-label">Submit for Approval</span>
                <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>
    </div>
</form>