<div class="d-flex flex-column gap-4">
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Name</span>
        <span class="text-dark fs-6">{{ $staff->nextOfKin->name ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Relationship</span>
        <span class="text-dark fs-6">{{ $staff->nextOfKin->relationship ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Mobile</span>
        <span class="text-dark fs-6">{{ $staff->nextOfKin->mobile_no ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Address</span>
        <span class="text-dark fs-6">{{ $staff->nextOfKin->address ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Occupation</span>
        <span class="text-dark fs-6">{{ $staff->nextOfKin->occupation ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Place of Work</span>
        <span class="text-dark fs-6">{{ $staff->nextOfKin->place_of_work ?? 'N/A' }}</span>
    </div>
</div>
