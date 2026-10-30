@extends('layouts.report-page-layout')

@section('pro-tip')
Export data laporan ini ke file excel untuk melihat detail!
@endsection

@section('datatable-header')
<div class="d-flex align-items-center justify-content-center gap-2">
 {{-- Export to excel --}}
 <button class="btn btn-sm btn-soft-secondary" id="excel_blood_release_btn">
  <i class="ti ti-file-type-xls fs-lg align-middle flex-shrink-0 me-2"></i>
  {{ __('Excel') }}
 </button>

 {{-- Select Blood Component --}}
 <div>
  <select class="form-control form-control-sm tomselect-sm" id="filter-blood-release-blood-component"
   name="filter-blood-release-blood-component" placeholder="Filter Komponen Darah"></select>
 </div>

 {{-- Date filter :begin --}}
 <div>
  <div class="input-group">
   <span class="input-group-text" id="report-blood-release-table-date-filter">
    <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
   </span>
   <input class="form-control report-blood-release-table-date-filter"
    aria-describedby="report-blood-release-table-date-filter" data-date-format="d-m-Y" data-provider="flatpickr"
    data-range-date="true" type="text" placeholder="Pilih rentang tanggal" />
  </div>
 </div>
 {{-- Date filter :begin --}}
</div>
@endsection

@section('datatable-content')
<table class="table table-sm table-striped dt-responsive align-middle mb-0 report-blood-release-table"
 id="report-blood-release-table">
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