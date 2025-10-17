@extends('layouts.staff')

@section('content')
    <div class="container-xxl">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Warehouse</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('staff.warehouses.update', $warehouse['id']) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="label" class="form-label">Label</label>
                        <input type="text" name="label" id="label" class="form-control" value="{{ $warehouse['label'] ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control">{{ $warehouse['description'] ?? '' }}</textarea>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="hidden" name="statut" value="0">
                        <input type="checkbox" name="statut" id="statut" class="form-check-input" value="1" {{ ($warehouse['statut'] ?? 0) == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="statut">Active</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('staff.warehouses.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
