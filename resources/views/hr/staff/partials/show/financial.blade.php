<div class="d-flex flex-column gap-4">
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Bank Name</span>
        <span class="text-dark fs-6">{{ $staff->bank->bank_name ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Bank Code</span>
        <span class="text-dark fs-6">{{ $staff->bank->bank_code ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Account Name</span>
        <span class="text-dark fs-6">{{ $staff->bank->account_name ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Account No</span>
        <span class="text-dark fs-6">{{ $staff->bank->account_no ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">RSA Balance</span>
        <span class="text-dark fs-6">{{ $staff->pension->rsa_balance ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">PFA Contribution Rate</span>
        <span class="text-dark fs-6">{{ $staff->pension->pfa_contribution_rate ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">Pension Administrator</span>
        <span class="text-dark fs-6">{{ $staff->pension->pension_administrator ?? 'N/A' }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted fs-7 fw-bold w-150px">RSA Pin</span>
        <span class="text-dark fs-6">{{ $staff->pension->rsa_pin ?? 'N/A' }}</span>
    </div>
</div>
