@extends('layouts.customer')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Ticket Details</h3>
    </div>
    <div class="card-body">
        <div class="mb-5">
            <h4>{{ $ticket->title }}</h4>
            <p class="text-muted">{{ $ticket->description }}</p>
        </div>
        <div class="separator separator-dashed mb-7"></div>

        <div class="row gy-5 g-5">
            <div class="col-md-6">
                <div class="fw-bold me-2">GLPI Ticket ID:</div>
                <div class="text-muted">{{ $ticket->glpi_ticket_id }}</div>
            </div>
            <div class="col-md-6">
                <div class="fw-bold me-2">Priority:</div>
                <div class="text-muted">{{ $ticket->priority }}</div>
            </div>
            <div class="col-md-6">
                <div class="fw-bold me-2">Urgency:</div>
                <div class="text-muted">{{ $ticket->urgency }}</div>
            </div>
        </div>

        <div class="separator separator-dashed my-7"></div>

        <div class="mb-5">
            <span class="fw-bold me-2">Status:</span>
            <span class="badge badge-light-{{ $ticket->status == 'open' ? 'success' : 'danger' }}">{{ ucfirst($ticket->status) }}</span>
        </div>
        <div class="mb-5">
            <span class="fw-bold me-2">Created:</span>
            <span>{{ $ticket->created_at->format('d M Y, h:i A') }}</span>
        </div>
        <div class="mb-5">
            <span class="fw-bold me-2">Last Updated:</span>
            <span>{{ $ticket->updated_at->format('d M Y, h:i A') }}</span>
        </div>

        <div class="separator separator-dashed my-7"></div>

        <h4>Follow-ups</h4>
        <div class="list-group">
            @forelse ($followups as $followup)
                <div class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">Follow-up</h5>
                        <small>{{ \Carbon\Carbon::parse($followup['date_creation'])->format('d M Y, h:i A') }}</small>
                    </div>
                    <p class="mb-1">{{ $followup['content'] }}</p>
                </div>
            @empty
                <p>No follow-ups for this ticket yet.</p>
            @endforelse
        </div>
    </div>
    <div class="card-footer d-flex justify-content-between">
        <a href="{{ route('customer.tickets.index') }}" class="btn btn-secondary">Back to Tickets</a>
        <form action="{{ route('customer.tickets.refresh', $ticket) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">Refresh Status</button>
        </form>
    </div>
</div>
@endsection
