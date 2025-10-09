@extends('layouts.staff')

@section('content')
<div class="container-xxl flex-lg-row-fluid">
    <!--begin::Toolbar-->
    <div class="d-flex flex-wrap flex-stack my-5">
        <!--begin::Heading-->
        <h3 class="fw-bold me-5">Manage Wards in District: {{ $district->name }}</h3>
        <!--end::Heading-->
        <!--begin::Controls-->
        <div class="d-flex flex-wrap my-5 my-md-0">
            <a href="{{ route('staff.districts.index') }}" class="btn btn-light me-2">Back to Districts</a>
        </div>
        <!--end::Controls-->
    </div>
    <!--end::Toolbar-->
    
    <div class="row g-6 g-xl-9 mb-6 mb-xl-9">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="card-title">Assign Wards to District</h3>
                    </div>
                </div>
                <div class="card-body py-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Available Wards</h4>
                            <form method="POST" action="{{ route('staff.districts.assign-ward', $district->id) }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Select Ward to Assign</label>
                                    <select name="ward_id" class="form-control form-control-solid" data-control="select2" required>
                                        <option value="">Select Ward</option>
                                        @foreach($wards as $ward)
                                            @if(!$ward->district_id || $ward->district_id == $district->id)
                                                <option value="{{ $ward->id }}">{{ $ward->name }} (LGA: {{ $ward->lga->name ?? 'N/A' }})</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Assign Ward to District</button>
                            </form>
                        </div>
                        
                        <div class="col-md-6">
                            <h4>Wards in this District</h4>
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th class="min-w-150px">Ward Name</th>
                                            <th class="min-w-150px">LGA</th>
                                            <th class="min-w-100px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($assignedWards as $ward)
                                        <tr>
                                            <td>{{ $ward->name }}</td>
                                            <td>{{ $ward->lga->name ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('staff.wards.remove-from-district', $ward->id) }}" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Are you sure you want to remove this ward from the district?')">
                                                   Remove
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No wards assigned to this district</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection