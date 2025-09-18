<div class="modal fade" id="kt_staff_view_modal{{ $member->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $member->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel{{ $member->id }}">View Staff</h5>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column gap-4">
                    <div class="d-flex align-items-center">
                        <span class="text-muted fs-7 fw-bold w-150px">Full Name</span>
                        <span class="text-dark fs-6">{{ $member ? trim($member->first_name . ' ' . ($member->middle_name ?? '') . ' ' . ($member->surname ?? '')) : 'N/A' }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="text-muted fs-7 fw-bold w-150px">Email</span>
                        <span class="text-dark fs-6">{{ $member->email }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="text-muted fs-7 fw-bold w-150px">Roles</span>
                        <span class="text-dark fs-6">{{ $member->getRoleNames()->join(', ') }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="text-muted fs-7 fw-bold w-150px">Status</span>
                        <span class="badge badge-light-{{ $member->status == 'approved' ? 'success' : ($member->status == 'pending' || $member->status == 'pending_delete' ? 'warning' : 'danger') }}">{{ ucfirst(str_replace('_', ' ', $member->status)) }}</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>