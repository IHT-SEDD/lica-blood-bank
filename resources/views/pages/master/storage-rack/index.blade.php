@extends('layouts.master-page-layout')

@section('datatable-header')
<div class="d-flex align-items-center justify-content-center gap-2">
  {{-- Select Storage --}}
  <div>
    <select class="form-control" id="filter-storage" name="filter-storage" placeholder="Filter by storage..."></select>
  </div>

  {{-- Date filter :begin --}}
  <div>
    <div class="input-group">
      <span class="input-group-text" id="master-user-table-date-filter">
        <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
      </span>
      <input class="form-control master-user-table-date-filter" aria-describedby="master-user-table-date-filter"
        data-date-format="d-m-Y" data-provider="flatpickr" data-range-date="true" data-enable-time="true" type="text"
        placeholder="Choose date range" />
    </div>
  </div>
  {{-- Date filter :begin --}}
</div>
@endsection

@section('datatable-content')
<table class="table table-striped dt-responsive align-middle mb-0 master-storage-rack-table"
  id="master-storage-rack-table">
  <thead class="thead-sm text-uppercase fs-xxs">
    <tr>
      <th></th>
      <th>No</th>
      <th>Name</th>
      <th>Storage</th>
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
<form class="row g-2">
  {{-- Name --}}
  <div class="col-lg-12">
    <label class="form-label" for="name">Name
      <span class="text-danger">*</span>
    </label>
    <input class="form-control" id="name" name="name" required="" type="text" placeholder="storage rack name" />
  </div>

  {{-- Storage --}}
  <div class="col-lg-12">
    <label class="form-label" for="storage">Storage
      <span class="text-danger">*</span>
    </label>
    <select class="form-control" id="select-storage" name="storage" placeholder="Choose storage..."></select>
  </div>

  {{-- Is Active? --}}
  <div class="col-lg-3">
    <div>
      <div class="form-check form-check-success my-1">
        <input checked="" class="form-check-input" id="is_active" type="checkbox" />
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

@section('custom-scripts')

@endsection