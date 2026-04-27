{{-- Choose Patient --}}
<div class="col-lg-12 mb-3">
  <label class="form-label" for="select-patient">Choose Patient or Create New</label>
  <div class="input-group">
    <select class="form-control" id="select-patient" placeholder="Choose patient or create new patient"></select>
    <button class="btn btn-soft-primary" id="add-new-patient-data" type="button">Add New</button>
  </div>
  <small class="form-text text-muted">
    Choose patient from dropdown if patient already exists or you can click Add New button to insert new patient.
  </small>
</div>

<hr />

{{-- Form Add New Blood Request :begin --}}
<form class="row g-2" id="add_new_user" autocomplete="off">
  {{-- Patient Data :begin --}}
  <div class="col-lg-7 col-12 patient-data-border pe-lg-4">
    {{-- Title --}}
    <div style="border-bottom: 2px dashed #ccc; padding-bottom: 6px; margin-bottom: 12px;">
      <h4>Patient Data Details</h4>
    </div>

    {{-- Medrec --}}
    <div class="mb-1">
      <label class="form-label" for="medrec">Medrec
        <span class="text-danger">*</span>
      </label>
      <input autocomplete="off" class="form-control" id="medrec" name="medrec" type="text" placeholder="Medical record"
        readonly />
      <small class="form-text text-muted">
        Click inputs to generate medrec.
      </small>
    </div>

    {{-- Name --}}
    <div class="mb-1">
      <label class="form-label" for="name">Full Name
        <span class="text-danger">*</span>
      </label>
      <input autocomplete="off" class="form-control" id="name" name="name" type="text"
        placeholder="Patient full name" />
    </div>

    {{-- Email --}}
    <div class="mb-1">
      <label class="col-form-label" for="email">Email</label>
      <input autocomplete="off" class="form-control" id="email" name="email" type="email" placeholder="Patient email" />
    </div>

    {{-- Phone Number --}}
    <div class="mb-1">
      <label class="col-form-label" for="phone_number">Phone Number</label>
      <input autocomplete="off" class="form-control" id="phone_number" name="phone_number" type="text"
        placeholder="Patient phone number" />
    </div>

    {{-- Birth Date --}}
    <div class="my-1">
      <label class="col-form-label m-0" for="birth_date">
        Birth Date
        <span class="text-danger">*</span>
      </label>
      <input class="form-control patient-birth-date" aria-describedby="patient-birth-date" data-date-format="d-m-Y"
        data-provider="flatpickr" data-range-date="true" type="text" id="birth_date" name="birth_date"
        placeholder="Choose birth date" />
    </div>

    {{-- Gender --}}
    <div class="my-2">
      <label class="form-label" for="select-role">Gender
        <span class="text-danger">*</span>
      </label>
      <div class="mt-1">
        <div class="form-check form-check-inline">
          <input checked="" class="form-check-input" id="gender" name="gender" type="radio" value="male" />
          <label class="form-check-label" for="gender">Male</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" id="gender" name="gender" type="radio" value="female" />
          <label class="form-check-label" for="gender">Female</label>
        </div>
      </div>
    </div>

    {{-- Blood Type & Rhesus --}}
    <div class="row g-2 my-2">
      {{-- Blood Type --}}
      <div class="col-lg-6 col-12">
        <label class="form-label" for="select-blood-type">Blood Type</label>
        <select class="form-control" id="select-blood-type" name="blood_type"
          placeholder="Choose blood type..."></select>
      </div>

      {{-- Blood Rhesus --}}
      <div class="col-lg-6 col-12">
        <label class="form-label" for="select-blood-rhesus">Blood Rhesus</label>
        <select class="form-control" id="select-blood-rhesus" name="blood_rhesus"
          placeholder="Choose blood rhesus..."></select>
      </div>
    </div>

    {{-- Patient Relation --}}
    <div class="row g-2 mb-2">
      {{-- Relation Name --}}
      <div class="col-lg-7">
        <label class="form-label" for="relation_name">Relation Name
          <span class="text-danger">*</span>
        </label>
        <input autocomplete="off" class="form-control" id="relation_name" name="relation_name" type="text"
          placeholder="Patient relation name" />
      </div>

      {{-- Relation Type --}}
      <div class="col-lg-5">
        <label class="form-label" for="relation_type">Relation Type
          <span class="text-danger">*</span>
        </label>
        <select class="form-control" id="select-relation-type" name="relation_type"
          placeholder="Choose patient relation type..."></select>
      </div>
    </div>
  </div>
  {{-- Patient Data :end --}}

  {{-- Blood Request Detail :begin --}}
  <div class="col-lg-5 col-12 ps-lg-4">
    {{-- Title --}}
    <div style="border-bottom: 2px dashed #ccc; padding-bottom: 6px; margin-bottom: 12px;">
      <h4>Blood Request Data Details</h4>
    </div>

    {{-- Insurance --}}
    <div class="mb-1">
      <label class="form-label" for="select-insurance">Insurance
        <span class="text-danger">*</span>
      </label>
      <select class="form-control" id="select-insurance" name="insurance_id"
        placeholder="Choose patient insurance"></select>
    </div>

    {{-- Room Reference --}}
    <div class="mb-1">
      <label class="form-label" for="select-room">Room Reference
        <span class="text-danger">*</span>
      </label>
      <select class="form-control" id="select-room" name="room_id" placeholder="Choose patient room reference"></select>
    </div>

    {{-- Doctor Reference --}}
    <div class="mb-1">
      <label class="form-label" for="select-doctor">Doctor Reference
        <span class="text-danger">*</span>
      </label>
      <select class="form-control" id="select-doctor" name="doctor_id"
        placeholder="Choose patient doctor reference"></select>
    </div>

    {{-- Blood Required Date --}}
    <div class="my-1">
      <label class="col-form-label m-0" for="blood_required_at">
        Blood Required Date
        <span class="text-danger">*</span>
      </label>
      <input class="form-control patient-blood-required-date" aria-describedby="patient-blood-required-date"
        data-date-format="d-m-Y" data-provider="flatpickr" data-range-date="true" type="text" id="blood_required_at"
        name="blood_required_at" placeholder="Choose blood required date" />
    </div>
  </div>
  {{-- Blood Request Detail :end --}}

  <hr />

  {{-- Submit Button --}}
  <div class="col-lg-12 mt-2">
    <button class="btn btn-primary" type="submit">Add Blood Request</button>
  </div>
</form>
{{-- Form Add New Blood Request :end --}}