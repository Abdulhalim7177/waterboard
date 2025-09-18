@if (auth('staff')->user()->hasAnyRole(['super-admin', 'manager']))
    <div class="modal fade" id="kt_staff_assign_roles_modal{{ $member->id }}" tabindex="-1" aria-labelledby="assignRolesModalLabel{{ $member->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignRolesModalLabel{{ $member->id }}">Assign Roles to {{ $member ? trim($member->first_name . ' ' . ($member->middle_name ?? '') . ' ' . ($member->surname ?? '')) : 'N/A' }}</h5>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <form action="{{ route('staff.staff.assign-roles', $member->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="roles" class="form-label">Roles</label>
                            <select name="roles[]" id="roles" class="form-control form-control-solid" data-control="select2" multiple required>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" {{ $member->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if (auth('staff')->user()->hasRole('manager'))
                            <div class="alert alert-info">
                                This action requires Super Admin approval.
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Assign Roles</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif