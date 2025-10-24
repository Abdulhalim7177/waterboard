@extends('layouts.customer')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">My Support Tickets</h3>
        <div class="card-toolbar">
            <a href="{{ route('customer.tickets.create') }}" class="btn btn-primary">Create New Ticket</a>
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
        <table class="table table-striped gy-7 gs-7">
            <thead>
                <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                    <th>Title</th>
                    <th>Category</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->title }}</td>
                        <td>{{ $ticket->category }}</td>
                        <td>{{ $ticket->priority }}</td>
                        <td><span class="badge badge-light-{{ $ticket->status == 'open' ? 'success' : 'danger' }}">{{ ucfirst($ticket->status) }}</span></td>
                        <td>{{ $ticket->updated_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('customer.tickets.show', $ticket->id) }}" class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">You have no support tickets.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
