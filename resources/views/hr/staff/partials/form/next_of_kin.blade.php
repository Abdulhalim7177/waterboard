<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="next_of_kin_name" class="form-label">Name</label>
            <input type="text" class="form-control" id="next_of_kin_name" name="next_of_kin_name" value="{{ old('next_of_kin_name', $staff->nextOfKin->name ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label for="next_of_kin_relationship" class="form-label">Relationship</label>
            <input type="text" class="form-control" id="next_of_kin_relationship" name="next_of_kin_relationship" value="{{ old('next_of_kin_relationship', $staff->nextOfKin->relationship ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label for="next_of_kin_mobile_no" class="form-label">Mobile No</label>
            <input type="text" class="form-control" id="next_of_kin_mobile_no" name="next_of_kin_mobile_no" value="{{ old('next_of_kin_mobile_no', $staff->nextOfKin->mobile_no ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="next_of_kin_address" class="form-label">Address</label>
            <textarea class="form-control" id="next_of_kin_address" name="next_of_kin_address" required>{{ old('next_of_kin_address', $staff->nextOfKin->address ?? '') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="next_of_kin_occupation" class="form-label">Occupation</label>
            <input type="text" class="form-control" id="next_of_kin_occupation" name="next_of_kin_occupation" value="{{ old('next_of_kin_occupation', $staff->nextOfKin->occupation ?? '') }}">
        </div>
        <div class="mb-3">
            <label for="next_of_kin_place_of_work" class="form-label">Place of Work</label>
            <input type="text" class="form-control" id="next_of_kin_place_of_work" name="next_of_kin_place_of_work" value="{{ old('next_of_kin_place_of_work', $staff->nextOfKin->place_of_work ?? '') }}">
        </div>
    </div>
</div>
