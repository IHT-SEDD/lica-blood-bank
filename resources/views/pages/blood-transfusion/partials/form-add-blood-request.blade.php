{{-- Choose Patient --}}
<div class="col-lg-12 mb-3">
  <label class="form-label" for="select-patient">{{ __('Choose Patient or Create New') }}</label>
  <div class="input-group">
    <select class="form-control" id="select-patient" name="patient_id"
      placeholder="{{ __('Choose Patient or Create New') }}"></select>
    <button class="btn btn-soft-primary" id="add-new-patient-data" type="button">{{ __('Add New') }}</button>
  </div>
  <small class="form-text text-muted fs-6">
    {{ __('Choose patient from dropdown if patient already exists or you can click Add New button to insert new
    patient.') }}
  </small>
</div>

<hr />

{{-- Form Add New Blood Request :begin --}}
<form class="row g-2" id="add_new_blood_request" autocomplete="off">
  {{-- Patient Data :begin --}}
  <div class="col-lg-7 col-12 patient-data-border pe-lg-4">
    {{-- Title --}}
    <div style="border-bottom: 2px dashed #ccc; padding-bottom: 6px; margin-bottom: 12px;">
      <h4>{{ __('Patient Data') }} {{ __('Details') }}</h4>
    </div>

    <div class="row mb-2">
      {{-- Medrec --}}
      <div class="col-xxl-6 col-md-6 col-12 mb-0">
        <label class="form-label" for="medrec">{{ __('Medrec') }}
          <span class="text-danger">*</span>
        </label>
        <input autocomplete="off" class="form-control" id="medrec" name="medrec" type="text"
          placeholder="{{ __('Medical Record') }}" readonly />
        <small class="form-text text-muted">
          {{ __('Click inputs to generate medrec.') }}
        </small>
      </div>

      {{-- Name --}}
      <div class="col-xxl-6 col-md-6 col-12 mb-0">
        <label class="form-label" for="name">{{ __('Name') }}
          <span class="text-danger">*</span>
        </label>
        <input autocomplete="off" class="form-control" id="name" name="name" type="text"
          placeholder="{{ __('Patient Full Name') }}" />
      </div>

      {{-- Email --}}
      <div class="col-xxl-6 col-md-6 col-12 mb-0">
        <label class="col-form-label" for="email">{{ __('Email') }}</label>
        <input autocomplete="off" class="form-control" id="email" name="email" type="email"
          placeholder="{{ __('Patient Email Address') }}" />
      </div>

      {{-- Phone Number --}}
      <div class="col-xxl-6 col-md-6 col-12 mb-0">
        <label class="col-form-label" for="phone_number">{{ __('Phone Number') }}</label>
        <input autocomplete="off" class="form-control" id="phone_number" name="phone_number" type="text"
          placeholder="08**********" />
      </div>

      {{-- Blood Group --}}
      <div class="col-xxl-6 col-md-6 col-12 mt-2">
        <label class="form-label" for="select-blood-group">{{ __('Blood Group') }}</label>
        <select class="form-control" id="select-blood-group" name="blood_group"
          placeholder="{{ __('A,B,AB,O') }}"></select>
      </div>

      {{-- Blood Rhesus --}}
      <div class="col-xxl-6 col-md-6 col-12 mt-2">
        <label class="form-label" for="select-blood-rhesus">{{ __('Blood Rhesus') }}</label>
        <select class="form-control" id="select-blood-rhesus" name="blood_rhesus"
          placeholder="{{ __('+/-') }}"></select>
      </div>

      {{-- Birth Date --}}
      <div class="col-xxl-6 col-md-12 col-12 mb-0">
        <label class="col-form-label m-0" for="birth_date">
          {{ __('Birth Date') }}
          <span class="text-danger">*</span>
        </label>
        <input class="form-control patient-birth-date" aria-describedby="patient-birth-date" data-date-format="d-m-Y"
          data-provider="flatpickr" data-range-date="true" type="text" id="birth_date" name="birth_date"
          placeholder="{{ __('Choose') }} {{ __('Birth Date') }}" />
      </div>

      {{-- Gender --}}
      <div class="col-xxl-6 col-md-12 col-12 mt-2">
        <label class="form-label" for="select-role">{{ __('Gender') }}
          <span class="text-danger">*</span>
        </label>
        <div class="mt-1">
          {{-- Male --}}
          <div class="form-check form-check-inline">
            <input checked="" class="form-check-input" id="gender" name="gender" type="radio" value="M" />
            <label class="form-check-label" for="gender">{{ __('Male') }}</label>
          </div>
          {{-- Female --}}
          <div class="form-check form-check-inline">
            <input class="form-check-input" id="gender" name="gender" type="radio" value="F" />
            <label class="form-check-label" for="gender">{{ __('Female') }}</label>
          </div>
        </div>
      </div>

      {{-- Relation Name --}}
      <div class="col-xxl-6 col-md-12 col-12 mt-2">
        <label class="form-label" for="relation_name">{{ __('Relation Name') }}
          <span class="text-danger">*</span>
        </label>
        <input autocomplete="off" class="form-control" id="relation_name" name="relation_name" type="text"
          placeholder="{{ __('Patient Relation Name') }}" />
      </div>

      {{-- Relation Type --}}
      <div class="col-xxl-6 col-md-12 col-12 mt-2">
        <label class="form-label" for="relation_type">{{ __('Relation Type') }}
          <span class="text-danger">*</span>
        </label>
        <select class="form-control" id="select-relation-type" name="relation_type"
          placeholder="{{ __('Choose Relation Type') }}"></select>
      </div>
    </div>

    {{-- Title --}}
    <div style="border-bottom: 2px dashed #ccc; padding-bottom: 6px; margin-bottom: 12px;">
      <h4>{{ __('Transaction') }} {{ __('Details') }}</h4>
    </div>

    <div class="row">
      {{-- Insurance --}}
      <div class="col-xxl-6 col-md-6 col-12 mt-2">
        <label class="form-label" for="select-insurance">{{ __('Insurance') }}</label>
        <select class="form-control" id="select-insurance" name="insurance_id"
          placeholder="{{ __('Choose') }} {{ __('Insurance') }}"></select>
      </div>

      {{-- Room --}}
      <div class="col-xxl-6 col-md-6 col-12 mt-2">
        <label class="form-label" for="select-room">{{ __('Room') }}</label>
        <select class="form-control" id="select-room" name="room_id"
          placeholder="{{ __('Choose') }} {{ __('Room') }}"></select>
      </div>

      {{-- Doctor --}}
      <div class="col-xxl-12 col-md-12 col-12 mt-2">
        <label class="form-label" for="select-doctor">{{ __('Doctor') }}</label>
        <select class="form-control" id="select-doctor" name="doctor_id"
          placeholder="{{ __('Choose') }} {{ __('Doctor') }}"></select>
      </div>

      {{-- Diagnosis --}}
      <div class="col-xxl-12 col-md-12 col-12 mt-2">
        <label class="form-label" for="diagnosis">{{ __('Diagnosis') }}</label>
        <textarea class="form-control" id="diagnosis" name="diagnosis" placeholder="{{ __('Diagnosis') }}"
          rows="5"></textarea>
      </div>
    </div>
  </div>
  {{-- Patient Data :end --}}

  {{-- Blood Request Detail :begin --}}
  <div class="col-lg-5 col-12 ps-lg-4">
    {{-- Title --}}
    <div style="border-bottom: 2px dashed #ccc; padding-bottom: 6px; margin-bottom: 12px;">
      <h4>{{ __('Blood Request') }} {{ __('Details') }}</h4>
    </div>

    <button class="btn btn-sm btn-soft-secondary" id="add-new-blood-pack-request" type="button">{{ __('Add Blood Pack')
      }}</button>

    {{-- Blood Required Date --}}
    <div class="col-xxl-12 col-md-12 col-12 mt-2">
      <label class="col-form-label m-0" for="blood_required_at">
        {{ __('Required Date') }}
        <span class="text-danger">*</span>
      </label>
      <input class="form-control patient-blood-required-date" aria-describedby="patient-blood-required-date"
        data-date-format="d-m-Y" data-provider="flatpickr" type="text" id="blood_required_at" name="blood_required_at"
        placeholder="{{ __('Choose') }} {{ __('Required Date') }}" />
    </div>

    {{-- Blood Pack --}}
    <div class="col-xxl-12 col-md-12 col-12 mt-2">
      <label class="form-label" for="select-blood-pack">{{ __('Blood Pack') }}</label>
      <select class="form-control" id="select-blood-pack" name="blood_pack_id"
        placeholder="{{ __('Choose') }} {{ __('Blood Pack') }}"></select>
    </div>
  </div>
  {{-- Blood Request Detail :end --}}

  <hr />

  {{-- Submit Button --}}
  <div class="col-lg-12 mt-2">
    <button class="btn btn-primary" type="submit">{{ __('Add Blood Request') }}</button>
  </div>
</form>
{{-- Form Add New Blood Request :end --}}