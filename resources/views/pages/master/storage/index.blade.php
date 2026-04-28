@extends('layouts.master-page-layout')

@section('datatable-header')
<div class="d-flex align-items-center justify-content-center gap-2">
  {{-- Date filter :begin --}}
  <div>
    <div class="input-group">
      <span class="input-group-text" id="master-storage-table-date-filter">
        <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
      </span>
      <input class="form-control master-storage-table-date-filter" aria-describedby="master-storage-table-date-filter"
        data-date-format="d-m-Y" data-provider="flatpickr" data-range-date="true" data-enable-time="true" type="text"
        placeholder="Choose date range" />
    </div>
  </div>
  {{-- Date filter :begin --}}
</div>
@endsection

@section('datatable-content')
<table class="table table-striped dt-responsive align-middle mb-0 master-storage-table" id="master-storage-table">
  <thead class="thead-sm text-uppercase fs-xxs">
    <tr>
      <th>No</th>
      <th>Name</th>
      <th>Model</th>
      <th>Serial Number</th>
      <th>Manufacture</th>
      <th>Rack Capacity</th>
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
<form class="row g-2" id="add_new_storage" autocomplete="off">
  {{-- Name --}}
  <div class="col-lg-6">
    <label class="form-label" for="name">Name
      <span class="text-danger">*</span>
    </label>
    <input autocomplete="off" class="form-control" id="name" name="name" type="text" placeholder="storage  name" />
  </div>

  {{-- Model --}}
  <div class="col-lg-6">
    <label class="form-label" for="model">Model</label>
    <input autocomplete="off" class="form-control" id="model" name="model" type="text" placeholder="storage model" />
  </div>

  {{-- Serial Number --}}
  <div class="col-lg-4">
    <label class="form-label" for="serial_number">Serial Number
      <span class="text-danger">*</span>
    </label>
    <input autocomplete="off" class="form-control" id="serial_number" name="serial_number" type="text"
      placeholder="storage SN" />
  </div>

  {{-- Manufacturer --}}
  <div class="col-lg-4">
    <label class="form-label" for="manufacturer">Manufacturer</label>
    <input autocomplete="off" class="form-control" id="manufacturer" name="manufacturer"
      placeholder="storage manufacturer" type="text" />
  </div>

  {{-- Rack Capacity --}}
  <div class="col-lg-4">
    <label class="form-label" for="rack_capacity">Rack Capacity
      <span class="text-danger">*</span>
    </label>
    <input autocomplete="off" class="form-control" id="rack_capacity" name="rack_capacity" placeholder="1 - 999"
      type="number" />
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
@include('pages.master.storage.partials.edit-data-modal')
@include('pages.master.storage.partials.delete-data-modal')
@include('pages.master.storage.partials.restore-data-modal')
@endsection

@section('custom-scripts')

@endsection