@extends('layouts.staff')

@inject('glpiService', 'App\Services\GlpiService')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Ticket Details</h3>
        <div class="card-toolbar">
            @if(isset($ticket->glpiTicket->id))
                <a href="{{ config('services.glpi.api_url') }}/front/ticket.form.php?id={{ $ticket->glpiTicket->id }}" target="_blank" class="btn btn-sm btn-primary">View in GLPI</a>
            @endif
        </div>
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
        <div class="row mb-5">
            <div class="col-md-6">
                <span class="fw-bold me-2">Customer:</span>
                <span>{{ $ticket->customer->first_name ?? '' }} {{ $ticket->customer->surname ?? 'N/A' }}</span>
            </div>
            <div class="col-md-6">
                <span class="fw-bold me-2">Status:</span>
                @if(isset($ticket->glpiTicket->status))
                    <span class="badge badge-light-info">{{ $glpiService->getStatusMappings()[$ticket->glpiTicket->status] ?? 'Unknown' }}</span>
                @else
                    <span class="badge badge-light-secondary">Unknown</span>
                @endif
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-md-6">
                <span class="fw-bold me-2">Priority:</span>
                @if(isset($ticket->glpiTicket->priority))
                    <span>{{ $glpiService->getPriorityMappings()[$ticket->glpiTicket->priority] ?? 'Unknown' }}</span>
                @else
                    <span>Unset</span>
                @endif
            </div>
            <div class="col-md-6">
                <span class="fw-bold me-2">Assigned To (GLPI):</span>
                @if(isset($ticket->glpiTicket->assigned_user))
                    <span>{{ $ticket->glpiTicket->assigned_user['name'] ?? 'Unassigned' }}</span>
                @else
                    <span>Unassigned</span>
                @endif
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-md-6">
                <span class="fw-bold me-2">Created:</span>
                <span>{{ $ticket->created_at->format('d M Y, h:i A') }}</span>
            </div>
            <div class="col-md-6">
                <span class="fw-bold me-2">Last Updated:</span>
                <span>{{ $ticket->updated_at->format('d M Y, h:i A') }}</span>
            </div>
        </div>

        <hr class="my-5">

        @can('assign', $ticket)
        <h4>Assign Ticket</h4>
        <form action="{{ route('staff.tickets.assign', $ticket->id) }}" method="POST">
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
                    <button type="submit" class="btn btn-primary mt-8">Assign</button>
                </div>
            </div>
        </form>
        @endcan

        <hr class="my-5">

        <h4>Follow-ups</h4>
        <div class="mb-5">
            @if(!empty($followups))
                @foreach($followups as $followup)
                    <div class="d-flex flex-column mb-5">
                        <div class="d-flex align-items-center mb-2">
                            <span class="fw-bold me-2">{{ $followup['user_name'] ?? 'Unknown User' }}</span>
                            <span class="text-muted">{{ \Carbon\Carbon::parse($followup['date_creation'])->format('d M Y, h:i A') }}</span>
                        </div>
                        <div>{!! nl2br(e($followup['content'])) !!}</div>
                    </div>
                @endforeach
            @else
                <p>No follow-ups yet.</p>
            @endif
        </div>

        <hr class="my-5">

        <h4>Add Follow-up</h4>
        <form action="{{ route('staff.tickets.add-followup', $ticket->id) }}" method="POST" id="followupForm">
            @csrf
            <div class="mb-3">
                <textarea name="content" class="form-control" rows="3" placeholder="Enter your follow-up here..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Follow-up</button>
        </form>

    </div>
    <div class="card-footer">
        <a href="{{ route('staff.tickets.index') }}" class="btn btn-secondary">Back to Tickets</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
});
</script>
@endsection
