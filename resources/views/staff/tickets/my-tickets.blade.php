@extends('layouts.staff')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">My Assigned Tickets</h3>
    </div>
    <div class="card-body">
        <table class="table table-striped gy-7 gs-7">
            <thead>
                <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                    <th>Title</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Paypoint</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->title }}</td>
                        <td>{{ $ticket->customer->first_name ?? '' }} {{ $ticket->customer->surname ?? 'N/A' }}</td>
                        <td><span class="badge badge-light-{{ $ticket->status == 'open' ? 'success' : 'danger' }}">{{ ucfirst($ticket->status) }}</span></td>
                        <td>{{ $ticket->staff->name ?? 'Unassigned' }}</td>
                        <td>{{ $ticket->paypoint->name ?? 'Unassigned' }}</td>
                        <td>{{ $ticket->updated_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('staff.tickets.show', $ticket->id) }}" class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">You have no assigned support tickets.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
