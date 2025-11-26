@extends('layouts.staff')

@inject('glpiService', 'App\Services\GlpiService')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Support Tickets</h3>
        @can('create', App\Models\Ticket::class)
            <div class="card-toolbar">
                <a href="{{ route('staff.tickets.create') }}" class="btn btn-sm btn-primary">Create New Ticket</a>
            </div>
        @endcan
    </div>
    <div class="card-body">
        <table class="table table-striped gy-7 gs-7">
            <thead>
                <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                    <th>Title</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Priority</th>
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
                        <td>
                            @if(isset($ticket->glpiTicket->status))
                                <span class="badge badge-light-info">{{ $glpiService->getStatusMappings()[$ticket->glpiTicket->status] ?? 'Unknown' }}</span>
                            @else
                                <span class="badge badge-light-secondary">Unknown</span>
                            @endif
                        </td>
                        <td>
                            @if(isset($ticket->glpiTicket->priority))
                                {{ $glpiService->getPriorityMappings()[$ticket->glpiTicket->priority] ?? 'Unknown' }}
                            @else
                                Unset
                            @endif
                        </td>
                        <td>
                            @if(isset($ticket->glpiTicket->assigned_user))
                                {{ $ticket->glpiTicket->assigned_user['name'] ?? 'Unassigned' }}
                            @else
                                {{ $ticket->staff->full_name ?? 'Unassigned' }}
                            @endif
                        </td>
                        <td>{{ $ticket->paypoint->name ?? 'Unassigned' }}</td>
                        <td>{{ $ticket->updated_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('staff.tickets.show', $ticket->id) }}" class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">There are no support tickets.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection