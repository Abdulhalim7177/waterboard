@extends('layouts.staff')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Vendor Management</h2>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Vendors List</h5>
                    <a href="{{ route('staff.vendors.create') }}" class="btn btn-primary">Add New Vendor</a>
                </div>
                <div class="card-body">
                    @if($vendors->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vendors as $vendor)
                                        <tr>
                                            <td>{{ $vendor->id }}</td>
                                            <td>{{ $vendor->name }}</td>
                                            <td>{{ $vendor->email }}</td>
                                            <td>
                                                @if($vendor->approved)
                                                    <span class="badge bg-success">Approved</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>{{ $vendor->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <a href="{{ route('staff.vendors.show', $vendor) }}" class="btn btn-info btn-sm">View</a>
                                                <a href="{{ route('staff.vendors.edit', $vendor) }}" class="btn btn-warning btn-sm">Edit</a>
                                                
                                                @if(!$vendor->approved)
                                                    <form action="{{ route('staff.vendors.approve', $vendor) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                                    </form>
                                                @endif
                                                
                                                <form action="{{ route('staff.vendors.destroy', $vendor) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this vendor?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            {{ $vendors->links() }}
                        </div>
                    @else
                        <p>No vendors found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection