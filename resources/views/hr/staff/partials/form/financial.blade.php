<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="bank_name" class="form-label">Bank Name</label>
            <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ old('bank_name', $staff->bank->bank_name ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label for="bank_code" class="form-label">Bank Code</label>
            <input type="text" class="form-control" id="bank_code" name="bank_code" value="{{ old('bank_code', $staff->bank->bank_code ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label for="account_name" class="form-label">Account Name</label>
            <input type="text" class="form-control" id="account_name" name="account_name" value="{{ old('account_name', $staff->bank->account_name ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label for="account_no" class="form-label">Account Number</label>
            <input type="text" class="form-control" id="account_no" name="account_no" value="{{ old('account_no', $staff->bank->account_no ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="pension_administrator" class="form-label">Pension Administrator</label>
            <input type="text" class="form-control" id="pension_administrator" name="pension_administrator" value="{{ old('pension_administrator', $staff->pension->pension_administrator ?? '') }}">
        </div>
        <div class="mb-3">
            <label for="rsa_pin" class="form-label">RSA Pin</label>
            <input type="text" class="form-control" id="rsa_pin" name="rsa_pin" value="{{ old('rsa_pin', $staff->pension->rsa_pin ?? '') }}">
        </div>
    </div>
</div>
