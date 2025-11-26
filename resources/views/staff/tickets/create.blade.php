@extends('layouts.staff')

@section('content')
<div class="container">
    <h1>Create New Ticket</h1>

    <form action="{{ route('staff.tickets.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="customer_id">Customer</label>
            <select name="customer_id" id="customer_id" class="form-control" data-control="select2" data-placeholder="Select a customer" required>
                <option></option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" data-email="{{ $customer->email }}" data-billing-id="{{ $customer->billing_id }}">{{ $customer->first_name }} {{ $customer->surname }} ({{ $customer->email }})</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" rows="5" required></textarea>
        </div>

        <div class="form-group">
            <label for="urgency">Urgency</label>
            <select name="urgency" id="urgency" class="form-control" required>
                <option value="">Select urgency</option>
                @foreach($urgencyMappings as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="priority">Priority</label>
            <select name="priority" id="priority" class="form-control" required>
                <option value="">Select priority</option>
                @foreach($priorityMappings as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Create Ticket</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#customer_id').select2({
        matcher: function(params, data) {
            if ($.trim(params.term) === '') {
                return data;
            }

            if (typeof data.text === 'undefined') {
                return null;
            }

            var term = params.term.toLowerCase();
            var text = data.text.toLowerCase();
            var email = $(data.element).data('email').toLowerCase();
            var billingId = $(data.element).data('billing-id').toLowerCase();

            if (text.indexOf(term) > -1 || email.indexOf(term) > -1 || billingId.indexOf(term) > -1) {
                return data;
            }

            return null;
        }
    });
});
</script>
@endsection

