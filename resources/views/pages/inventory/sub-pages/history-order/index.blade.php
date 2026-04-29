@extends('layouts.vertical', ['title' => 'History Order'])

@section('styles')
@endsection

@section('content')
<div class="row py-4">
  {{-- Title & Add New Order :begin --}}
  <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-1 mb-2">
    {{-- Title --}}
    <h1 class="fw-bold">History Order</h1>

    {{-- Button Add New Order --}}
    <a href="{{ route('inventory.history-order.new-order') }}" class="btn btn-soft-info">
      Add New Order
    </a>
  </div>
  {{-- Title & Add New Order :end --}}

  {{-- Datatables :begin --}}
  <div class="col-12">
    {{-- Card :begin --}}
    <div class="card">
      {{-- Card Header :begin --}}
      <div class="card-header justify-content-between align-items-center">
        <h5 class="card-title text-capitalize mb-0">List Data of History Order</h5>

        {{-- Filters Datatable :begin --}}
        <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2 m-0">
          {{-- Select Status --}}
          <div>
            <select class="form-control" id="filter-order-status" name="filter-order-status"
              placeholder="Filter by status..."></select>
          </div>

          {{-- Select Vendor --}}
          <div>
            <select class="form-control" id="filter-order-vendor" name="filter-order-vendor"
              placeholder="Filter by vendor..."></select>
          </div>

          {{-- Select Blood Group --}}
          <div>
            <select class="form-control" id="filter-order-blood-group" name="filter-order-blood-group"
              placeholder="Filter by blood group..."></select>
          </div>

          {{-- Select Blood Component --}}
          <div>
            <select class="form-control" id="filter-order-blood-component" name="filter-order-blood-component"
              placeholder="Filter by blood component..."></select>
          </div>

          {{-- Date Range Picker :begin --}}
          <div>
            <div class="input-group">
              <span class="input-group-text" id="history-order-table-date-filter">
                <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
              </span>
              <input class="form-control history-order-table-date-filter"
                aria-describedby="history-order-table-date-filter" data-date-format="d-m-Y" data-provider="flatpickr"
                data-range-date="true" type="text" placeholder="Choose date range" />
            </div>
          </div>
          {{-- Date Range Picker :end --}}
        </div>
        {{-- Filters Datatable :end --}}
      </div>
      {{-- Card Header :end --}}

      {{-- Card Body :begin --}}
      <div class="card-body">
        <table class="table table-striped dt-responsive align-middle mb-0 history-order-table" id="history-order-table">
          <thead class="thead-sm text-uppercase fs-xxs">
            <tr>
              <th>PO Number</th>
              <th>Vendor</th>
              <th>Total Qty</th>
              <th>Blood Group</th>
              <th>Status</th>
              <th>Created At</th>
              <th>Updated At</th>
              <th>Deleted At</th>
              <th>Action</th>
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
'resources/js/pages/inventory/history-order/index.js',
])
@endsection