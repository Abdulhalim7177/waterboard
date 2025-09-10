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
                        // Initialize any JavaScript for the loaded section
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