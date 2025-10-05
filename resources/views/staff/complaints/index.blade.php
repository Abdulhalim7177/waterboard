@extends('layouts.staff')

@section('title', 'Complaints Management')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Customer Complaints</h2>
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
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Ticket ID</th>
                                    <th>Customer</th>
                                    <th>Subject</th>
                                    <th>Category</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th>Assigned To</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($complaints as $complaint)
                                <tr>
                                    <td>{{ $complaint['id'] ?? 'N/A' }}</td>
                                    <td>
                                        @if(isset($complaint['users_id_requester']))
                                            Customer ID: {{ $complaint['users_id_requester'] }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($complaint['name'] ?? 'N/A', 30) }}</td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $complaint['itilcategories_id'] ? 'Cat ID: ' . $complaint['itilcategories_id'] : 'N/A' }}
                                        </span>
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
                                    <td>
                                        {{ isset($complaint['date']) ? \Carbon\Carbon::parse($complaint['date'])->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td>
                                        @if(isset($complaint['users_id_assign']) && $complaint['users_id_assign'])
                                            Staff ID: {{ $complaint['users_id_assign'] }}
                                        @else
                                            <span class="text-muted">Unassigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('staff.complaints.show', $complaint['id']) }}" 
                                           class="btn btn-sm btn-outline-primary">View</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="ki-duotone ki-message-question fs-3x text-muted mb-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <div class="text-muted fs-6">No complaints found</div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $complaints->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection