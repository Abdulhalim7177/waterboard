@extends('layouts.staff')

@section('content')
<div class="container">
    <h1>Dashboard</h1>
    <p>This is the staff dashboard. Reporting and SLA information will be displayed here.</p>

    <div class="card mt-5">
        <div class="card-header">
            <h3 class="card-title">Ticket Status Overview</h3>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($statusMappings as $statusId => $statusName)
                    <div class="col-md-4 col-sm-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $statusName }}</h5>
                                <p class="card-text fs-2hx fw-bold">{{ $ticketsByStatus[$statusId] }}</p>
                                @if($totalTickets > 0)
                                    <p class="card-text text-muted">{{ round(($ticketsByStatus[$statusId] / $totalTickets) * 100, 2) }}% of total</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="card mt-5">
        <div class="card-header">
            <h3 class="card-title">SLA Status</h3>
        </div>
        <div class="card-body">
            <p>SLA information coming soon.</p>
        </div>
    </div>
</div>
@endsection