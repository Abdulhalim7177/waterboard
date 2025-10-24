@extends('layouts.customer')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Create Support Ticket</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('customer.tickets.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="5" required></textarea>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="priority" class="form-label">Priority</label>
                        <select name="priority" id="priority" class="form-select">
                            <option value="1">Low</option>
                            <option value="2">Medium</option>
                            <option value="3">High</option>
                            <option value="4">Very High</option>
                            <option value="5">Critical</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="urgency" class="form-label">Urgency</label>
                        <select name="urgency" id="urgency" class="form-select">
                            <option value="1">Low</option>
                            <option value="2">Medium</option>
                            <option value="3">High</option>
                            <option value="4">Very High</option>
                            <option value="5">Critical</option>
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Create Ticket</button>
        </form>
    </div>
</div>
@endsection
