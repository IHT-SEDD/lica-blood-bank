<x-modal-layout id="edit_data_master_patient_modal" data-bs-focus="false" size="" title="Edit Data Patient">
 {{-- Form Edit :begin --}}
 <form class="row g-2" id="edit_data_patient" autocomplete="off">
  {{-- Name --}}
  <div class="col-lg-6">
   <label class="form-label" for="edit_data_patient_name">Name
    <span class="text-danger">*</span>
   </label>
   <input autocomplete="off" class="form-control" id="edit_data_patient_name" name="name" type="text"
    placeholder="Patient full name" />
  </div>

  {{-- Medical Record --}}
  {{-- <div class="col-lg-6">
   <label class="form-label" for="edit_data_patient_medrec">Medical Record
    <span class="text-danger">*</span>
   </label>
   <input autocomplete="off" class="form-control" id="edit_data_patient_medrec" name="medrec" type="text"
    placeholder="Medical record number" />
  </div> --}}

  {{-- Gender --}}
  <div class="col-lg-6">
   <label class="form-label" for="edit_data_patient_gender">Gender
    <span class="text-danger">*</span>
   </label>
   <select class="form-control" id="edit_data_patient_gender" name="gender">
    <option value="">Choose gender</option>
    <option value="M">Male</option>
    <option value="F">Female</option>
   </select>
  </div>

  {{-- Birthdate --}}
  <div class="col-lg-6">
   <label class="form-label" for="edit_data_patient_birthdate">Birthdate
    <span class="text-danger">*</span>
   </label>
   <input autocomplete="off" class="form-control edit_data_patient_birthdate" id="edit_data_patient_birthdate" name="birthdate" type="text"
    data-date-format="d-m-Y" data-provider="flatpickr" placeholder="Select birthdate" />
  </div>

  {{-- Phone --}}
  <div class="col-lg-6">
   <label class="col-form-label" for="edit_data_patient_phone">Phone</label>
   <input autocomplete="off" class="form-control" id="edit_data_patient_phone" name="phone" type="text"
    placeholder="Patient phone number" />
  </div>

  {{-- Email --}}
  <div class="col-lg-6">
   <label class="col-form-label" for="edit_data_patient_email">Email</label>
   <input autocomplete="off" class="form-control" id="edit_data_patient_email" name="email" type="email"
    placeholder="youremail@mail.com" />
  </div>

  {{-- Address --}}
  <div class="col-lg-12">
   <label class="col-form-label" for="edit_data_patient_address">Address</label>
   <textarea class="form-control" id="edit_data_patient_address" name="address" rows="2" placeholder="Patient address"></textarea>
  </div>

  {{-- Is Active? --}}
  <div class="col-lg-12">
   <div>
    <div class="form-check form-check-info my-1">
     <input checked class="form-check-input" id="edit_data_patient_is_active" name="is_active" type="checkbox" />
     <label class="form-check-label" for="edit_data_patient_is_active">Active?</label>
    </div>
   </div>
  </div>

  <hr />

  {{-- Submit Button --}}
  <div class="col-lg-12 mt-2">
   <button class="btn btn-primary" type="submit">Update Data</button>
  </div>
 </form>
 {{-- Form Edit :end --}}
</x-modal-layout>
