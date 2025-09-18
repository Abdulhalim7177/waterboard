@if (auth('staff')->user()->hasAnyRole(['super-admin', 'manager']))
    <div class="modal fade" id="kt_staff_remove_roles_modal{{ $member->id }}" tabindex="-1" aria-labelledby="removeRolesModalLabel{{ $member->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeRolesModalLabel{{ $member->id }}">Remove Roles from {{ $member ? trim($member->first_name . ' ' . ($member->middle_name ?? '') . ' ' . ($member->surname ?? '')) : 'N/A' }}</h5>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <form action="{{ route('staff.staff.remove-roles', $member->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="remove_roles" class="form-label">Select Roles to Remove</label>
                            <select name="roles[]" id="remove_roles" class="form-control form-control-solid" data-control="select2" multiple required>
                                @foreach ($member->roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
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
                        <button type="submit" class="btn btn-danger">Remove Roles</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif