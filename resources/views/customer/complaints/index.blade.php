@extends('layouts.customer')

@section('title', 'My Complaints')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>My Complaints</h2>
                <a href="{{ route('customer.complaints.create') }}" class="btn btn-primary">Submit New Complaint</a>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="card">
                <div class="card-body">
                    @if($complaints->isEmpty())
                        <div class="text-center py-5">
                            <h5 class="text-muted">No complaints found</h5>
                            <p class="text-muted">You haven't submitted any complaints yet.</p>
                            <a href="{{ route('customer.complaints.create') }}" class="btn btn-primary">Submit Your First Complaint</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Ticket ID</th>
                                        <th>Subject</th>
                                        <th>Category</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($complaints as $complaint)
                                    <tr>
                                        <td>{{ $complaint['id'] ?? 'N/A' }}</td>
                                        <td>{{ Str::limit($complaint['name'] ?? 'N/A', 30) }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $complaint['itilcategories_id'] ? 'Category ID: ' . $complaint['itilcategories_id'] : 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                @php
                                                    $urgency = $complaint['urgency'] ?? 3;
                                                    echo ($urgency <= 2) ? 'bg-success' : (($urgency == 3) ? 'bg-info' : (($urgency == 4) ? 'bg-warning' : 'bg-danger'));
                                                @endphp
                                            ">
                                                @php
                                                    $urgencyLabels = ['1' => 'Very Low', '2' => 'Low', '3' => 'Medium', '4' => 'High', '5' => 'Urgent'];
                                                    echo $urgencyLabels[$complaint['urgency'] ?? 3] ?? 'Medium';
                                                @endphp
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                @php
                                                    $status = $complaint['status'] ?? 1;
                                                    echo ($status == 1) ? 'bg-primary' : (($status == 2) ? 'bg-info' : (($status == 5) ? 'bg-success' : 'bg-secondary'));
                                                @endphp
                                            ">
                                                @php
                                                    $statusLabels = ['1' => 'New', '2' => 'Assigned', '3' => 'Planned', '4' => 'Waiting', '5' => 'Solved', '6' => 'Closed'];
                                                    echo $statusLabels[$complaint['status'] ?? 1] ?? 'New';
                                                @endphp
                                            </span>
                                        </td>
                                        <td>{{ isset($complaint['date']) ? \Carbon\Carbon::parse($complaint['date'])->format('M d, Y') : 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('customer.complaints.show', $complaint['id']) }}" 
                                               class="btn btn-sm btn-outline-primary">View</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            {{ $complaints->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection