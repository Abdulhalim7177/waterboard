<div class="d-flex flex-column gap-4">
    <div class="d-flex align-items-center">
        <label for="bank_name" class="form-label w-150px">Bank Name</label>
        <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ old('bank_name', $staff->bank->bank_name ?? '') }}" required>
    </div>
    <div class="d-flex align-items-center">
        <label for="bank_code" class="form-label w-150px">Bank Code</label>
        <input type="text" class="form-control" id="bank_code" name="bank_code" value="{{ old('bank_code', $staff->bank->bank_code ?? '') }}" required>
    </div>
    <div class="d-flex align-items-center">
        <label for="account_name" class="form-label w-150px">Account Name</label>
        <input type="text" class="form-control" id="account_name" name="account_name" value="{{ old('account_name', $staff->bank->account_name ?? '') }}" required>
    </div>
    <div class="d-flex align-items-center">
        <label for="account_no" class="form-label w-150px">Account Number</label>
        <input type="text" class="form-control" id="account_no" name="account_no" value="{{ old('account_no', $staff->bank->account_no ?? '') }}" required>
    </div>
    <div class="d-flex align-items-center">
        <label for="pension_administrator" class="form-label w-150px">Pension Administrator</label>
        <input type="text" class="form-control" id="pension_administrator" name="pension_administrator" value="{{ old('pension_administrator', $staff->pension->pension_administrator ?? '') }}">
    </div>
    <div class="d-flex align-items-center">
        <label for="rsa_pin" class="form-label w-150px">RSA Pin</label>
        <input type="text" class="form-control" id="rsa_pin" name="rsa_pin" value="{{ old('rsa_pin', $staff->pension->rsa_pin ?? '') }}">
    </div>
</div>
