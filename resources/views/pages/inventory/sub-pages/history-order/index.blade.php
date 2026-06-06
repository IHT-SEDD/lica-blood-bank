@extends('layouts.vertical', ['title' => 'Permintaan Darah'])

@section('styles')
@endsection

@section('content')
<div class="row mt-2">
  {{-- Title & Add New Order :begin --}}
  <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-1 mb-2">
    {{-- Title --}}
    <h1 class="fw-bold">{{ __('Riwayat Permintaan Darah') }}</h1>

    {{-- Button Add New Order --}}
    <a href="{{ route('inventory.history-order.new-order') }}" class="btn btn-soft-info">
      {{ __('Buat Permintaan Baru') }}
    </a>
  </div>
  {{-- Title & Add New Order :end --}}

  {{-- Datatables :begin --}}
  <div class="col-12">
    {{-- Card :begin --}}
    <div class="card">
      {{-- Card Header :begin --}}
      <div class="card-header justify-content-between align-items-center">
        {{-- Filters Datatable :begin --}}
        <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2 m-0">
          {{-- Export to excel --}}
          <button class="btn btn-sm btn-soft-secondary" id="excel_order_btn">
            <i class="ti ti-file-type-xls fs-lg align-middle flex-shrink-0 me-2"></i>
            {{ __('Excel') }}
          </button>

          {{-- Select Status --}}
          <div>
            <select class="form-control form-control-sm tomselect-sm" id="filter-order-status"
              name="filter-order-status" placeholder="Filter status"></select>
          </div>

          {{-- Select Vendor --}}
          <div>
            <select class="form-control form-control-sm tomselect-sm" id="filter-order-vendor"
              name="filter-order-vendor" placeholder="Filter PMI"></select>
          </div>

          {{-- Date Range Picker :begin --}}
          <div>
            <div class="input-group">
              <span class="input-group-text" id="history-order-table-date-filter">
                <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
              </span>
              <input class="form-control form-control-sm history-order-table-date-filter"
                aria-describedby="history-order-table-date-filter" data-date-format="d-m-Y" data-provider="flatpickr"
                data-range-date="true" type="text" placeholder="Filter rentang tanggal" />
            </div>
          </div>
          {{-- Date Range Picker :end --}}
        </div>
        {{-- Filters Datatable :end --}}
      </div>
      {{-- Card Header :end --}}

      {{-- Card Body :begin --}}
      <div class="card-body">
        <table class="table table-sm table-striped dt-responsive align-middle mb-0 history-order-table"
          id="history-order-table">
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
            </tr>
          </thead>
        </table>
      </div>
      {{-- Card Body :end --}}
    </div>
    {{-- Card :end --}}
  </div>
  {{-- Datatables :end --}}
</div>

@include('pages.inventory.sub-pages.history-order.partials.delete-data-modal')
@include('pages.inventory.sub-pages.history-order.partials.restore-data-modal')
@endsection

@section('scripts')
@vite([
'resources/js/pages/inventory/history-order/datatable.js',
])
@endsection