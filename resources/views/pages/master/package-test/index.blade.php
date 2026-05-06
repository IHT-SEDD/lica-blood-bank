@extends('layouts.master-page-layout')

@section('datatable-header')
<div class="d-flex align-items-center justify-content-center gap-2">
  {{-- Select Role --}}
 

  {{-- Date filter :begin --}}
  <div>
    <div class="input-group">
      <span class="input-group-text" id="master-insurance-table-date-filter">
        <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
      </span>
      <input class="form-control master-insurance-table-date-filter"
        aria-describedby="master-insurance-table-date-filter" data-date-format="d-m-Y" data-provider="flatpickr"
        data-range-date="true" type="text" placeholder="Choose date range" />
    </div>
  </div>
  {{-- Date filter :begin --}}
</div>
@endsection

@section('datatable-content')
<table class="table table-striped dt-responsive align-middle mb-0 master-package-test-table" id="master-package-test-table">
  <thead class="thead-sm text-uppercase fs-xxs">
    <tr>
      <th>No</th>
      <th>Package Name</th>
      <th>Test List</th>
      <th>Deleted At</th>
      <th>Action</th>
    </tr>
  </thead>
</table>
@endsection

@section('form-content')
<form class="row g-2" id="add_new_package_test" autocomplete="off">
  {{-- Name --}}
  <div class="col-lg-12">
    <label class="form-label" for="name">Package Name
      <span class="text-danger">*</span>
    </label>
     <select class="form-control" id="select-package" name="package"
      placeholder="Select Package"></select>
  </div>
  <div class="col-lg-12">
    <label class="form-label" for="general_code">Test List
      <span class="text-danger">*</span>
    </label>
    <select class="form-control" id="select-test" name="tests[]"
      placeholder="Select Tests" multiple></select>
  </div>

  {{-- Is Active? --}}
  <div class="col-lg-3">
    <div>
      <div class="form-check form-check-info my-1">
        <input checked="" class="form-check-input" id="is_active" name="is_active" type="checkbox" />
        <label class="form-check-label" for="is_active">Active?</label>
      </div>
    </div>
  </div>

  {{-- Submit Button --}}
  <div class="col-lg-12">
    <button class="btn btn-primary" type="submit">Add New Test</button>
  </div>
</form>
@endsection

@section('modal-content')
@include('pages.master.package-test.partials.edit-data-modal')
@include('pages.master.package-test.partials.delete-data-modal')
@endsection

@section('custom-scripts')

@endsection