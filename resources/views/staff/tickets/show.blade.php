@extends('layouts.staff')

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
        <div class="mb-5">
            <span class="fw-bold me-2">Customer:</span>
            <span>{{ $ticket->customer->name ?? 'N/A' }}</span>
        </div>
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

        <hr class="my-5">

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
                                    {{ $staffMember->name }}
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

        <hr class="my-5">

        <h4>Add Follow-up</h4>
        <form action="{{ route('staff.tickets.add-followup', $ticket->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <textarea name="content" class="form-control" rows="3" placeholder="Enter your follow-up here..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Follow-up</button>
        </form>

        <hr class="my-5">

        <h4>Update Status</h4>
        <form action="{{ route('staff.tickets.update-status', $ticket->id) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="1">New</option>
                            <option value="2">Processing (assigned)</option>
                            <option value="3">Processing (planned)</option>
                            <option value="4">Pending</option>
                            <option value="5">Solved</option>
                            <option value="6">Closed</option>
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
@endsection
