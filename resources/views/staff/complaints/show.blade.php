@extends('layouts.staff')

@section('title', 'Complaint Details - ' . ($complaint['name'] ?? 'Ticket Details'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Complaint Details</h2>
                <a href="{{ route('staff.complaints.index') }}" class="btn btn-secondary">Back to Complaints</a>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ $complaint['name'] ?? 'N/A' }}</h4>
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
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Ticket ID:</strong></td>
                                    <td>{{ $complaint['id'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Customer:</strong></td>
                                    <td>
                                        @if(isset($complaint['users_id_requester']))
                                            Customer ID: {{ $complaint['users_id_requester'] }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Category:</strong></td>
                                    <td>{{ $complaint['itilcategories_id'] ? 'Category ID: ' . $complaint['itilcategories_id'] : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Priority:</strong></td>
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
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @php
                                            $statusLabels = ['1' => 'New', '2' => 'Assigned', '3' => 'Planned', '4' => 'Waiting', '5' => 'Solved', '6' => 'Closed'];
                                            echo $statusLabels[$complaint['status'] ?? 1] ?? 'New';
                                        @endphp
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Submitted:</strong></td>
                                    <td>
                                        {{ isset($complaint['date']) ? \Carbon\Carbon::parse($complaint['date'])->format('M d, Y \a\t h:i A') : 'N/A' }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Description</h5>
                        <p class="border p-3 rounded bg-light">{{ $complaint['content'] ?? 'N/A' }}</p>
                    </div>
                    
                    <!-- Assignment Form -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Assign Complaint</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('staff.complaints.assign', $complaint['id']) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-8">
                                        <select class="form-control" name="assigned_to" required>
                                            <option value="">Select staff member</option>
                                            @foreach(\App\Models\Staff::all() as $staff)
                                                <option value="{{ $staff->id }}">
                                                    {{ $staff->user->name ?? 'N/A' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary w-100">Assign</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Status Update Form -->
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">Update Status</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('staff.complaints.updateStatus', $complaint['id']) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row mb-3">
                                    <div class="col-md-8">
                                        <select class="form-control" name="status" required>
                                            <option value="open" {{ ($complaint['status'] ?? 1) == 1 ? 'selected' : '' }}>Open</option>
                                            <option value="in_progress" {{ in_array($complaint['status'] ?? 1, [2, 3, 4]) ? 'selected' : '' }}>In Progress</option>
                                            <option value="resolved" {{ ($complaint['status'] ?? 1) == 5 ? 'selected' : '' }}>Resolved</option>
                                            <option value="closed" {{ ($complaint['status'] ?? 1) == 6 ? 'selected' : '' }}>Closed</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-info w-100">Update Status</button>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="resolution_notes" class="form-label">Resolution Notes (Optional)</label>
                                    <textarea class="form-control" id="resolution_notes" name="resolution_notes" rows="3" 
                                              placeholder="Add notes about the complaint resolution...">{{ old('resolution_notes') }}</textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    @if(isset($complaint['users_id_assign']) && $complaint['users_id_assign'])
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Assigned Staff Details</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Staff ID:</strong> {{ $complaint['users_id_assign'] }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if(isset($complaint['solution']) && $complaint['solution'])
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Resolution Notes</h5>
                        </div>
                        <div class="card-body">
                            <p>{{ $complaint['solution'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection