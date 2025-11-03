<div class="d-flex flex-column gap-4">
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Staff ID</span>
        <span class="text-dark fs-6">{{ $staff->staff_id }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Full Name</span>
        <span class="text-dark fs-6">{{ $staff->fullName }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Email</span>
        <span class="text-dark fs-6">{{ $staff->email }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Mobile</span>
        <span class="text-dark fs-6">{{ $staff->mobile_no }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Date of Birth</span>
        <span class="text-dark fs-6">{{ $staff->date_of_birth ? $staff->date_of_birth->format('F j, Y') : 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Gender</span>
        <span class="text-dark fs-6">{{ ucfirst($staff->gender ?? 'N/A') }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Nationality</span>
        <span class="text-dark fs-6">{{ $staff->nationality ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">NIN</span>
        <span class="text-dark fs-6">{{ $staff->nin ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Address</span>
        <span class="text-dark fs-6">{{ $staff->address ?? 'N/A' }}</span>
    </div>
    @if($staff->photo_path)
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Photo</span>
        <img src="{{ asset('storage/' . $staff->photo_path) }}" alt="{{ $staff->fullName }}" class="w-100px h-100px rounded" />
    </div>
    @endif
</div>
