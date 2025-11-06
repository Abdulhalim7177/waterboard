<div class="d-flex flex-column gap-4">
    <div class="d-flex align-items-center">
        <label for="next_of_kin_name" class="form-label w-150px">Name</label>
        <input type="text" class="form-control" id="next_of_kin_name" name="next_of_kin_name" value="{{ old('next_of_kin_name', $staff->nextOfKin->name ?? '') }}" required>
    </div>
    <div class="d-flex align-items-center">
        <label for="next_of_kin_relationship" class="form-label w-150px">Relationship</label>
        <input type="text" class="form-control" id="next_of_kin_relationship" name="next_of_kin_relationship" value="{{ old('next_of_kin_relationship', $staff->nextOfKin->relationship ?? '') }}" required>
    </div>
    <div class="d-flex align-items-center">
        <label for="next_of_kin_mobile_no" class="form-label w-150px">Mobile No</label>
        <input type="text" class="form-control" id="next_of_kin_mobile_no" name="next_of_kin_mobile_no" value="{{ old('next_of_kin_mobile_no', $staff->nextOfKin->mobile_no ?? '') }}" required>
    </div>
    <div class="d-flex align-items-center">
        <label for="next_of_kin_address" class="form-label w-150px">Address</label>
        <textarea class="form-control" id="next_of_kin_address" name="next_of_kin_address" required>{{ old('next_of_kin_address', $staff->nextOfKin->address ?? '') }}</textarea>
    </div>
    <div class="d-flex align-items-center">
        <label for="next_of_kin_occupation" class="form-label w-150px">Occupation</label>
        <input type="text" class="form-control" id="next_of_kin_occupation" name="next_of_kin_occupation" value="{{ old('next_of_kin_occupation', $staff->nextOfKin->occupation ?? '') }}">
    </div>
    <div class="d-flex align-items-center">
        <label for="next_of_kin_place_of_work" class="form-label w-150px">Place of Work</label>
        <input type="text" class="form-control" id="next_of_kin_place_of_work" name="next_of_kin_place_of_work" value="{{ old('next_of_kin_place_of_work', $staff->nextOfKin->place_of_work ?? '') }}">
    </div>
</div>
