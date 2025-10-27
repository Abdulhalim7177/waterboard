@extends('layouts.staff')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Vendor Details</h2>
            
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Vendor Information</h5>
                    <div>
                        <a href="{{ route('staff.vendors.edit', $vendor) }}" class="btn btn-warning">Edit</a>
                        
                        @if(!$vendor->approved)
                            <form action="{{ route('staff.vendors.approve', $vendor) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">Approve</button>
                            </form>
                        @endif
                        
                        <form action="{{ route('staff.vendors.destroy', $vendor) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this vendor?')">Delete</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>ID</th>
                            <td>{{ $vendor->id }}</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{ $vendor->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $vendor->email }}</td>
                        </tr>
                        <tr>
                            <th>Street Name</th>
                            <td>{{ $vendor->street_name }}</td>
                        </tr>
                        <tr>
                            <th>Vendor Code</th>
                            <td>{{ $vendor->vendor_code }}</td>
                        </tr>
                        <tr>
                            <th>LGA</th>
                            <td>{{ $vendor->lga->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Ward</th>
                            <td>{{ $vendor->ward->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Area</th>
                            <td>{{ $vendor->area->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($vendor->approved)
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-warning">Pending Approval</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $vendor->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $vendor->updated_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <a href="{{ route('staff.vendors.index') }}" class="btn btn-secondary mt-3">Back to Vendors</a>
        </div>
    </div>
</div>
@endsection