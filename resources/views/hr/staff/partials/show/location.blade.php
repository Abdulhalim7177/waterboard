<div class="d-flex flex-column gap-4">
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">State</span>
        <span class="text-dark fs-6">{{ $staff->state->name ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">LGA</span>
        <span class="text-dark fs-6">{{ $staff->lga->name ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Ward</span>
        <span class="text-dark fs-6">{{ $staff->ward->name ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Area</span>
        <span class="text-dark fs-6">{{ $staff->area->name ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Zone</span>
        <span class="text-dark fs-6">{{ $staff->zone->name ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">District</span>
        <span class="text-dark fs-6">{{ $staff->district->name ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Paypoint</span>
        <span class="text-dark fs-6">{{ $staff->paypoint->name ?? 'N/A' }}</span>
    </div>
</div>
