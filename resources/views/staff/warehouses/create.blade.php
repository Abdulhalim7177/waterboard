@extends('layouts.staff')

@section('content')
    <div class="container-xxl">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Create Warehouse</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('staff.warehouses.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="label" class="form-label">Label</label>
                        <input type="text" name="label" id="label" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="hidden" name="statut" value="0">
                        <input type="checkbox" name="statut" id="statut" class="form-check-input" value="1">
                        <label class="form-check-label" for="statut">Active</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{ route('staff.warehouses.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
