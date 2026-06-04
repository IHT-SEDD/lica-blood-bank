@extends('layouts.report-page-layout')

@section('datatable-header')
<div class="d-flex align-items-center justify-content-center gap-2">
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
<table class="table table-sm table-striped dt-responsive align-middle mb-0 report-blood-usage-table"
  id="report-blood-usage-table">
  <thead class="thead-sm text-uppercase fs-xxs">
    <tr>
      <th>No</th>
      <th>Room</th>
      <th>Blood</th>
      <th>Total</th>
      <th>Updated At</th>
    </tr>
  </thead>
</table>
@endsection

@section('modal-content')
@endsection

@section('custom-scripts')

@endsection