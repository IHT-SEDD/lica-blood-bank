@extends('layouts.vertical', ['title' => 'History Order'])

@section('styles')
<style>
  @media (min-width: 992px) {
    .order-data-border {
      border-right: 1px solid #e5e7eb;
    }
  }
</style>
@endsection

@section('content')
{{-- Header :begin --}}
<div class="row align-items-center pt-4 pb-2">
  {{-- Title & Add New Order :begin --}}
  <div class="col-lg-4 col-12">
    <div class="row align-items-center">
      {{-- Title --}}
      <div class="col-lg-6 col-8">
        <h1 class="fw-bold mb-0">History Order</h1>
      </div>

      {{-- Button Add New Order --}}
      <div class="col-lg-6 col-4">
        <button class="btn btn-sm btn-soft-info" data-bs-target="#add_new_order_modal" data-bs-toggle="modal" type="button">
          Add New Order
        </button>
      </div>
    </div>
  </div>
  {{-- Title & Add New Order :end --}}

  {{-- Tools :begin --}}
  <div class="col-lg-8 col-12">
    <div class="row align-items-center">
      {{-- Select Vendor --}}
      <div class="col-lg-3 col-4">
        <select class="form-control" id="filter-order-vendor" name="filter-order-vendor"
          placeholder="Filter by vendor..."></select>
      </div>

      {{-- Select Blood Group --}}
      <div class="col-lg-3 col-4">
        <select class="form-control" id="filter-order-blood-group" name="filter-order-blood-group"
          placeholder="Filter by blood group..."></select>
      </div>

      {{-- Select Blood Component --}}
      <div class="col-lg-3 col-4">
        <select class="form-control" id="filter-order-blood-component" name="filter-order-blood-component"
          placeholder="Filter by blood component..."></select>
      </div>

      {{-- Date Range Picker :begin --}}
      <div class="col-lg-3 col-12 mt-lg-0 mt-2">
        <div class="input-group">
          <span class="input-group-text" id="history-order-date-filter">
            <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
          </span>
          <input class="form-control history-order-date-filter" aria-describedby="history-order-date-filter"
            data-date-format="d-m-Y" data-provider="flatpickr" data-range-date="true" data-enable-time="true"
            type="text" placeholder="Choose date range" />
        </div>
      </div>
      {{-- Date Range Picker :end --}}
    </div>
  </div>
  {{-- Tools :end --}}
</div>
{{-- Header :end --}}

{{-- Datatables :begin --}}
<div class="row">
  <div class="col-xxl-12">
    {{-- Card :begin --}}
    <div class="card card-h-100">
      {{-- Card Body :begin --}}
      <div class="card-body">
      </div>
      {{-- Card Body :end --}}
    </div>
    {{-- Card :end --}}
  </div>
</div>
{{-- Datatables :end --}}

{{-- Modal Add New Order :begin --}}
<x-modal-layout id="add_new_order_modal" size="modal-lg" title="Add New Order">
  @include('pages.inventory.sub-pages.history-order.partials.form-add-new-order')
</x-modal-layout>
{{-- Modal Add New Order :end --}}
@endsection

@section('scripts')
@vite(['resources/js/pages/inventory/index.js', 'resources/js/pages/inventory/history-order/form-add.js'])
@endsection