@extends('layouts.staff')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Update Connection Task #{{ $task->id }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('staff.connection-tasks.update', $task->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-5">
                            <label for="staff_id" class="form-label">Assign To</label>
                            <select name="staff_id" id="staff_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Select Staff">
                                <option value="">Select Staff</option>
                                @foreach ($staff as $staff_member)
                                    <option value="{{ $staff_member->id }}" {{ $task->staff_id == $staff_member->id ? 'selected' : '' }}>{{ $staff_member->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-5">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" class="form-select form-select-solid" required>
                                <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="assigned" {{ $task->status == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $task->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-5">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" class="form-control form-control-solid" rows="3">{{ $task->notes }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-5">
                            <label for="pipe_path" class="form-label">Pipe Path (JSON)</label>
                            <textarea name="pipe_path" id="pipe_path" class="form-control form-control-solid" rows="5" readonly>{{ $customerPipePath ? json_encode($customerPipePath, JSON_PRETTY_PRINT) : '' }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Update Task</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-5">
        <div class="card-header">
            <h3 class="card-title">Task Details</h3>
        </div>
        <div class="card-body">
            <p><strong>Customer:</strong> {{ $task->bill->customer->first_name }} {{ $task->bill->customer->surname }}</p>
            <p><strong>Service:</strong> {{ $task->bill->tariff->name }}</p>
            <p><strong>Amount:</strong> {{ number_format($task->bill->amount, 2) }}</p>
            <p><strong>Bill Status:</strong> {{ $task->bill->status }}</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#staff_id').select2({
                placeholder: "Select Staff",
                allowClear: true
            });
        });
    </script>
@endsection
