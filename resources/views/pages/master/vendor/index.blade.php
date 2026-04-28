@extends('layouts.master-page-layout')

@section('datatable-header')
<div class="d-flex align-items-center justify-content-center gap-2">
  {{-- Date filter :begin --}}
  <div>
    <div class="input-group">
      <span class="input-group-text" id="master-vendor-table-date-filter">
        <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
      </span>
      <input class="form-control master-vendor-table-date-filter" aria-describedby="master-vendor-table-date-filter"
        data-date-format="d-m-Y" data-provider="flatpickr" data-range-date="true" data-enable-time="true" type="text"
        placeholder="Choose date range" />
    </div>
  </div>
  {{-- Date filter :begin --}}
</div>
@endsection

@section('datatable-content')
<table class="table table-striped dt-responsive align-middle mb-0 master-vendor-table" id="master-vendor-table">
  <thead class="thead-sm text-uppercase fs-xxs">
    <tr>
      <th>No</th>
      <th>Name</th>
      <th>Address</th>
      <th>Phone Number</th>
      <th>Telephone Number</th>
      <th>PIC Name</th>
      <th>Status</th>
      <th>Created At</th>
      <th>Updated At</th>
      <th>Deleted At</th>
      <th>Action</th>
    </tr>
  </thead>
</table>
@endsection

@section('form-content')
<form class="row g-2" id="add_new_vendor" autocomplete="off">
  {{-- Name --}}
  <div class="col-lg-12">
    <label class="form-label" for="name">Name
      <span class="text-danger">*</span>
    </label>
    <input autocomplete="off" class="form-control" id="name" name="name" type="text" placeholder="Vendor name" />
  </div>

  {{-- Address --}}
  <div class="col-lg-12">
    <label class="form-label" for="address">Address</label>
    <textarea autocomplete="off" class="form-control" id="address" name="address" rows="5"
      placeholder="Vendor address"></textarea>
  </div>

  {{-- Phone Number --}}
  <div class="col-lg-6">
    <label class="form-label" for="phone_number">Phone Number</label>
    <input autocomplete="off" class="form-control" id="phone_number" name="phone_number" type="text"
      placeholder="Vendor PIC phone number" />
  </div>

  {{-- Telephone Number --}}
  <div class="col-lg-6">
    <label class="form-label" for="telephone_number">Telephone Number</label>
    <input autocomplete="off" class="form-control" id="telephone_number" name="telephone_number" type="text"
      placeholder="Vendor telephone number" />
  </div>

  {{-- PIC Name --}}
  <div class="col-lg-12">
    <label class="form-label" for="pic_name">PIC Name</label>
    <input autocomplete="off" class="form-control" id="pic_name" name="pic_name" placeholder="Vendor PIC name"
      type="text" />
  </div>

  {{-- Is Active? --}}
  <div class="col-lg-12">
    <div>
      <div class="form-check form-check-success my-1">
        <input checked="" class="form-check-input" id="is_active" type="checkbox" name="is_active" />
        <label class="form-check-label" for="is_active">Active?</label>
      </div>
    </div>
  </div>

  {{-- Submit Button --}}
  <div class="col-lg-12">
    <button class="btn btn-primary" type="submit">Submit form</button>
  </div>
</form>
@endsection

@section('modal-content')
@include('pages.master.vendor.partials.edit-data-modal')
@include('pages.master.vendor.partials.delete-data-modal')
@include('pages.master.vendor.partials.restore-data-modal')
@endsection

@section('custom-scripts')

@endsection