@extends('layouts.staff')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Ticket Details</h3>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning" role="alert">
                {{ session('warning') }}
            </div>
        @endif
        <div class="mb-5">
            <h4>{{ $ticket->title }}</h4>
            <p class="text-muted">{{ $ticket->description }}</p>
        </div>
        <div class="mb-5">
            <span class="fw-bold me-2">Customer:</span>
            <span>{{ $ticket->customer->first_name ?? '' }} {{ $ticket->customer->surname ?? 'N/A' }}</span>
        </div>
        <div class="mb-5">
            <span class="fw-bold me-2">Status:</span>
            <span class="badge badge-light-{{ $ticket->status_color }}">{{ $ticket->status_name }}</span>
        </div>
        <div class="mb-5">
            <span class="fw-bold me-2">Created:</span>
            <span>{{ $ticket->created_at->format('d M Y, h:i A') }}</span>
        </div>
        <div class="mb-5">
            <span class="fw-bold me-2">Last Updated:</span>
            <span>{{ $ticket->updated_at->format('d M Y, h:i A') }}</span>
        </div>

        <hr class="my-5">

        @can('assign', $ticket)
        <h4>Assign Ticket</h4>
        <form action="{{ route('staff.tickets.assign', $ticket->id) }}" method="POST" id="assignForm">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="staff_id" class="form-label">Assign to Staff</label>
                        <select name="staff_id" id="staff_id" class="form-select">
                            <option value="">Unassigned</option>
                            @foreach ($staff as $staffMember)
                                <option value="{{ $staffMember->id }}" {{ $ticket->staff_id == $staffMember->id ? 'selected' : '' }}>
                                    {{ $staffMember->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="paypoint_id" class="form-label">Assign to Paypoint</label>
                        <select name="paypoint_id" id="paypoint_id" class="form-select">
                            <option value="">Unassigned</option>
                            @foreach ($paypoints as $paypoint)
                                <option value="{{ $paypoint->id }}" {{ $ticket->paypoint_id == $paypoint->id ? 'selected' : '' }}>
                                    {{ $paypoint->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Assign</button>
        </form>
        @endcan

        <hr class="my-5">

        <h4>Add Follow-up</h4>
        <form action="{{ route('staff.tickets.add-followup', $ticket->id) }}" method="POST" id="followupForm">
            @csrf
            <div class="mb-3">
                <textarea name="content" class="form-control" rows="3" placeholder="Enter your follow-up here..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Follow-up</button>
        </form>

        <hr class="my-5">

        <h4>Update Status</h4>
        <form action="{{ route('staff.tickets.update-status', $ticket->id) }}" method="POST" id="statusForm">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="1" {{ $ticket->status == 1 ? 'selected' : '' }}>New</option>
                            <option value="2" {{ $ticket->status == 2 ? 'selected' : '' }}>Processing (assigned)</option>
                            <option value="3" {{ $ticket->status == 3 ? 'selected' : '' }}>Processing (planned)</option>
                            <option value="4" {{ $ticket->status == 4 ? 'selected' : '' }}>Pending</option>
                            <option value="5" {{ $ticket->status == 5 ? 'selected' : '' }}>Solved</option>
                            <option value="6" {{ $ticket->status == 6 ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update Status</button>
        </form>
    </div>
    <div class="card-footer">
        <a href="{{ route('staff.tickets.index') }}" class="btn btn-secondary">Back to Tickets</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Alert when staff/paypoint assignment form is submitted
    const assignForm = document.getElementById('assignForm');
    if (assignForm) {
        assignForm.addEventListener('submit', function() {
            const staffSelect = document.getElementById('staff_id');
            const paypointSelect = document.getElementById('paypoint_id');
            
            let alertMessage = 'Ticket assignment in progress: ';
            let hasAssignment = false;
            
            if (staffSelect.value) {
                const selectedStaff = staffSelect.options[staffSelect.selectedIndex].text;
                alertMessage += 'Staff: ' + selectedStaff + ', ';
                hasAssignment = true;
            }
            
            if (paypointSelect.value) {
                const selectedPaypoint = paypointSelect.options[paypointSelect.selectedIndex].text;
                alertMessage += 'Paypoint: ' + selectedPaypoint;
                hasAssignment = true;
            }
            
            if (hasAssignment) {
                alert(alertMessage);
            }
        });
    }
    
    // Alert when follow-up form is submitted
    const followupForm = document.querySelector('form[action$="/add-followup"]');
    if (followupForm) {
        followupForm.addEventListener('submit', function() {
            const contentTextarea = followupForm.querySelector('textarea[name="content"]');
            if (contentTextarea && contentTextarea.value.trim() !== '') {
                alert('Follow-up will be added: ' + contentTextarea.value.substring(0, 50) + (contentTextarea.value.length > 50 ? '...' : ''));
            }
        });
    }
    
    // Alert when status update form is submitted
    const statusForm = document.querySelector('form[action$="/update-status"]');
    if (statusForm) {
        statusForm.addEventListener('submit', function() {
            const statusSelect = document.getElementById('status');
            if (statusSelect) {
                const selectedStatus = statusSelect.options[statusSelect.selectedIndex].text;
                alert('Ticket status will be updated to: ' + selectedStatus);
            }
        });
    }
});
</script>
@endsection
