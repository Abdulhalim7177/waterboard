@extends('layouts.customer')

@section('title', 'Complaint Details - ' . ($complaint['name'] ?? 'Ticket Details'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Complaint Details</h2>
                <a href="{{ route('customer.complaints.index') }}" class="btn btn-secondary">Back to Complaints</a>
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
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Submitted:</strong></td>
                                    <td>
                                        {{ isset($complaint['date']) ? \Carbon\Carbon::parse($complaint['date'])->format('M d, Y \a\t h:i A') : 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>
                                        {{ isset($complaint['date_mod']) ? \Carbon\Carbon::parse($complaint['date_mod'])->format('M d, Y \a\t h:i A') : 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Assigned To:</strong></td>
                                    <td>
                                        @if(isset($complaint['users_id_assign']) && $complaint['users_id_assign'])
                                            <span class="text-muted">Staff ID: {{ $complaint['users_id_assign'] }}</span>
                                        @else
                                            <span class="text-muted">Not assigned yet</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Resolved:</strong></td>
                                    <td>
                                        @if(isset($complaint['solvedate']) && $complaint['solvedate'])
                                            {{ \Carbon\Carbon::parse($complaint['solvedate'])->format('M d, Y \a\t h:i A') }}
                                        @else
                                            <span class="text-muted">Not resolved yet</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Description</h5>
                        <p class="border p-3 rounded bg-light">{{ $complaint['content'] ?? 'N/A' }}</p>
                    </div>
                    
                    @if(isset($complaint['solution']) && $complaint['solution'])
                        <div class="mb-4">
                            <h5>Resolution Notes</h5>
                            <p class="border p-3 rounded bg-light">{{ $complaint['solution'] ?? 'N/A' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection