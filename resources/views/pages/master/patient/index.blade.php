@extends('layouts.master-page-layout')

@section('datatable-header')
<div class="d-flex align-items-center justify-content-center gap-2">
  {{-- Date filter :begin --}}
  <div>
    <div class="input-group">
      <span class="input-group-text" id="master-patient-table-date-filter">
        <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
      </span>
      <input readonly class="form-control master-patient-table-date-filter" aria-describedby="master-patient-table-date-filter"
        data-date-format="d-m-Y" data-provider="flatpickr" data-range-date="true" type="text"
        placeholder="Choose birthdate range" />
    </div>
  </div>
  {{-- Date filter :end --}}
</div>
@endsection

@section('datatable-content')
<table class="table table-striped dt-responsive align-middle mb-0 master-patient-table" id="master-patient-table">
  <thead class="thead-sm text-uppercase fs-xxs">
    <tr>
      <th>No</th>
      <th>Name</th>
      <th>Medical Record</th>
      <th>Gender</th>
      <th>Birthdate</th>
      <th>Phone</th>
      <th>Email</th>
      <th>Address</th>
      <th>Deleted At</th>
      <th>Action</th>
    </tr>
  </thead>
</table>
@endsection

@section('form-content')
<form class="row g-2" id="add_new_patient" autocomplete="off">
  {{-- Name --}}
  <div class="col-lg-6">
    <label class="form-label" for="name">Name
      <span class="text-danger">*</span>
    </label>
    <input autocomplete="off" class="form-control" id="name" name="name" type="text" placeholder="Patient full name" />
  </div>

  {{-- Medical Record --}}
  {{-- <div class="col-lg-6">
    <label class="form-label" for="medrec">Medical Record
      <span class="text-danger">*</span>
    </label>
    <input autocomplete="off" value="{{ generateMedrec() }}" class="form-control" id="medrec" name="medrec" type="text" placeholder="Medical record number" />
  </div> --}}

  {{-- Gender --}}
  <div class="col-lg-6">
    <label class="form-label" for="gender">Gender
      <span class="text-danger">*</span>
    </label>
    <select class="form-control" id="gender" name="gender">
      <option value="">Choose gender</option>
      <option value="M">Male</option>
      <option value="F">Female</option>
    </select>
  </div>

  {{-- Birthdate --}}
  <div class="col-lg-6">
    <label class="form-label" for="birthdate">Birthdate
      <span class="text-danger">*</span>
    </label>
    <input autocomplete="off" class="form-control birthdate" id="birthdate" name="birthdate" type="text"
      data-date-format="d-m-Y" data-provider="flatpickr" placeholder="Select birthdate" readonly />
  </div>

  {{-- Phone --}}
  <div class="col-lg-6">
    <label class="col-form-label" for="phone">Phone</label>
    <input autocomplete="off" class="form-control" id="phone" name="phone" type="text" placeholder="Patient phone number" />
  </div>

  {{-- Email --}}
  <div class="col-lg-6">
    <label class="col-form-label" for="email">Email</label>
    <input autocomplete="off" class="form-control" id="email" name="email" type="email" placeholder="youremail@mail.com" />
  </div>

  {{-- Address --}}
  <div class="col-lg-12">
    <label class="col-form-label" for="address">Address</label>
    <textarea class="form-control" id="address" name="address" rows="2" placeholder="Patient address"></textarea>
  </div>

  {{-- Is Active? --}}
  <div class="col-lg-12">
    <div class="form-check form-check-info my-1">
      <input checked class="form-check-input" id="is_active" name="is_active" type="checkbox" />
      <label class="form-check-label" for="is_active">Active?</label>
    </div>
  </div>

  {{-- Submit Button --}}
  <div class="col-lg-12">
    <button class="btn btn-primary" type="submit">Add New Patient</button>
  </div>
</form>
@endsection

@section('modal-content')
@include('pages.master.patient.partials.edit-data-modal')
@include('pages.master.patient.partials.delete-data-modal')
@include('pages.master.patient.partials.restore-data-modal')
@endsection

@section('custom-scripts')

@endsection
