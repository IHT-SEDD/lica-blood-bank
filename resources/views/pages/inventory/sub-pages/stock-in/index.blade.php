@extends('layouts.vertical', ['title' => 'Stock In'])

@section('content')
<div class="row mt-2">
  {{-- Title :begin --}}
  <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-1">
    {{-- Title --}}
    <h1 class="fw-bold">{{ __('Darah Masuk') }}</h1>

    {{-- Button Add New Stock --}}
    <a href="{{ route('inventory.stock-in.new-incoming-stock') }}" class="btn btn-soft-info">
      {{ __('Daftarkan Darah Yang Akan Masuk') }}
    </a>
  </div>
  {{-- Title :end --}}

  {{-- Datatables of List Stock In :begin --}}
  <div class="col-12">
    {{-- Card :begin --}}
    <div class="card">
      {{-- Card Header :begin --}}
      <div class="card-header justify-content-between align-items-center">
        <h5 class="card-title text-capitalize mb-0">{{ __('List Darah Masuk Terdaftar') }}</h5>

        {{-- Filters Datatable :begin --}}
        <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2 m-0">
          {{-- Export to excel --}}
          <button class="btn btn-sm btn-soft-secondary" id="excel_incoming_btn">
            <i class="ti ti-file-type-xls fs-lg align-middle flex-shrink-0 me-2"></i>
            {{ __('Excel') }}
          </button>

          {{-- Select Status --}}
          <div>
            <select class="form-control form-control-sm tomselect-sm" id="filter-stockin-status"
              name="filter-stockin-status" placeholder="{{ __('Filter status') }}..."></select>
          </div>

          {{-- Select Vendor --}}
          <div>
            <select class="form-control form-control-sm tomselect-sm" id="filter-stockin-vendor"
              name="filter-stockin-vendor" placeholder="{{ __('Filter PMI') }}..."></select>
          </div>

          {{-- Date Range Picker :begin --}}
          <div>
            <div class="input-group">
              <span class="input-group-text" id="stockin-table-date-filter">
                <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
              </span>
              <input class="form-control form-control-sm stockin-table-date-filter"
                aria-describedby="stockin-table-date-filter" data-date-format="d-m-Y" data-provider="flatpickr"
                data-range-date="true" type="text" placeholder="{{ __('Filter rentang tanggal') }}" />
            </div>
          </div>
          {{-- Date Range Picker :end --}}
        </div>
        {{-- Filters Datatable :end --}}
      </div>
      {{-- Card Header :end --}}

      {{-- Card Body :begin --}}
      <div class="card-body">
        <table class="table table-sm table-striped dt-responsive align-middle mb-0 stockin-table" id="stockin-table">
          <thead class="thead-sm text-uppercase fs-xxs">
            <tr>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
            </tr>
          </thead>
        </table>
      </div>
      {{-- Card Body :end --}}
    </div>
    {{-- Card :end --}}
  </div>
  {{-- Datatables of List Stock In :end --}}
</div>

@include('pages.inventory.sub-pages.stock-in.partials.delete-data-modal')
@include('pages.inventory.sub-pages.stock-in.partials.restore-data-modal')
@endsection

@section('scripts')
@vite([
'resources/js/pages/inventory/stock-in/datatable.js',
])
@endsection