@extends('layouts.report-page-layout')

@section('datatable-header')
<div class="d-flex align-items-center justify-content-center gap-2">
  {{-- Export to excel --}}
  <button class="btn btn-sm btn-soft-secondary" id="excel_blood_request_btn">
    <i class="ti ti-file-type-xls fs-lg align-middle flex-shrink-0 me-2"></i>
    {{ __('Excel') }}
  </button>

  {{-- Select Room --}}
  <div>
    <select class="form-control form-control-sm tomselect-sm" id="filter-blood-request-room"
      name="filter-blood-request-room" placeholder="Filter Ruangan"></select>
  </div>

  {{-- Date filter :begin --}}
  <div>
    <div class="input-group">
      <span class="input-group-text" id="report-blood-request-table-date-filter">
        <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
      </span>
      <input class="form-control report-blood-request-table-month-filter"
        aria-describedby="report-blood-request-table-month-filter" data-provider="monthpickr" type="text"
        placeholder="Pilih bulan dan tahun" />
    </div>
  </div>
  {{-- Date filter :begin --}}
</div>
@endsection

@section('datatable-content')
<table class="table table-sm table-striped dt-responsive align-middle mb-0 report-blood-request-table"
  id="report-blood-request-table">
 <thead class="thead-sm text-uppercase fs-xxs">
  <tr>
   <th>No</th>
   <th>Ruangan</th>
   <th>A (PRC)</th>
   <th>B (PRC)</th>
   <th>O (PRC)</th>
   <th>AB (PRC)</th>
   <th>A (TC)</th>
   <th>B (TC)</th>
   <th>O (TC)</th>
   <th>AB (TC)</th>
   <th>A (LP)</th>
   <th>B (LP)</th>
   <th>O (LP)</th>
   <th>AB (LP)</th>
   <th>A (WB)</th>
   <th>B (WB)</th>
   <th>O (WB)</th>
   <th>AB (WB)</th>
   <th>Total</th>
  </tr>
 </thead>
</table>
@endsection

@section('modal-content')
@endsection

@section('custom-scripts')

@endsection