@extends('layouts.report-page-layout')

@section('pro-tip')
Export data laporan ini ke file excel untuk melihat detail!
@endsection

@section('datatable-header')
<div class="d-flex align-items-center justify-content-center gap-2">
  {{-- Export to excel --}}
  <button class="btn btn-sm btn-soft-secondary" id="excel_blood_expire_btn">
    <i class="ti ti-file-type-xls fs-lg align-middle flex-shrink-0 me-2"></i>
    {{ __('Excel') }}
  </button>

  {{-- Select Blood Component --}}
  <div>
    <select class="form-control form-control-sm tomselect-sm" id="filter-blood-expire-blood-component"
      name="filter-blood-expire-blood-component" placeholder="Filter Komponen Darah"></select>
  </div>

  {{-- Date filter :begin --}}
  <div>
    <div class="input-group">
      <span class="input-group-text" id="report-blood-expire-table-date-filter">
        <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
      </span>
      <input class="form-control report-blood-expire-table-month-filter"
        aria-describedby="report-blood-expire-table-month-filter" data-provider="monthpickr" type="text"
        placeholder="Pilih bulan dan tahun" />
    </div>
  </div>
  {{-- Date filter :begin --}}
</div>
@endsection

@section('datatable-content')
<table class="table table-sm table-striped dt-responsive align-middle mb-0 report-blood-expire-table"
  id="report-blood-expire-table">
  <thead class="thead-sm text-uppercase fs-xxs">
    <tr>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
    </tr>
  </thead>
</table>
@endsection

@section('modal-content')
@endsection

@section('custom-scripts')

@endsection