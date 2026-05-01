@extends('layouts.master-page-layout')

@section('datatable-header')
<div class="d-flex align-items-center justify-content-center gap-2">
  {{-- Select Blood Group --}}
  <div>
    <select class="form-control" id="filter-blood-group" name="filter-blood-group"
      placeholder="Filter by blood group..."></select>
  </div>

  {{-- Select Status --}}
  <div>
    <select class="form-control" id="filter-status" name="filter-status" placeholder="Filter by status..."></select>
  </div>

  {{-- Select Component --}}
  <div>
    <select class="form-control" id="filter-component" name="filter-component"
      placeholder="Filter by component..."></select>
  </div>

  {{-- Date filter :begin --}}
  <div>
    <div class="input-group">
      <span class="input-group-text" id="master-blood-pack-table-date-filter">
        <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
      </span>
      <input class="form-control master-blood-pack-table-date-filter"
        aria-describedby="master-blood-pack-table-date-filter" data-date-format="d-m-Y" data-provider="flatpickr"
        data-range-date="true" type="text" placeholder="Choose date range" />
    </div>
  </div>
  {{-- Date filter :begin --}}
</div>
@endsection

@section('datatable-content')
<table class="table table-sm table-striped dt-responsive align-middle mb-0 master-blood-pack-table" id="master-blood-pack-table">
  <thead class="thead-sm text-uppercase fs-xxs">
    <tr>
      <th>No</th>
      <th>Bag Number</th>
      <th>Bag Number LICA</th>
      <th>Group</th>
      <th>Rhesus</th>
      <th>Component</th>
      <th>Volume</th>
      <th>Status</th>
      <th>Aftap</th>
      <th>Expired</th>
      <th>HIV</th>
      <th>HCV</th>
      <th>HbsAG</th>
      <th>Syphilis</th>
      <th>Used At</th>
      <th>Created At</th>
      <th>Updated At</th>
      <th>Deleted At</th>
      <th>Action</th>
    </tr>
  </thead>
</table>
@endsection

@section('modal-content')
@include('pages.master.blood-pack.partials.delete-data-modal')
@include('pages.master.blood-pack.partials.restore-data-modal')
@endsection

@section('custom-scripts')

@endsection