@extends('layouts.vertical', ['title' => 'Blood Stock'])

@section('content')
<div class="row py-4">
  {{-- Title :begin --}}
  <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-1">
    {{-- Title --}}
    <h1 class="fw-bold">{{ __('Blood Stock') }}</h1>
  </div>
  {{-- Title :end --}}

  {{-- Datatables of List Blood Stock :begin --}}
  <div class="col-12">
    {{-- Card :begin --}}
    <div class="card">
      {{-- Card Header :begin --}}
      <div class="card-header justify-content-between align-items-center">
        <h5 class="card-title text-capitalize mb-0">{{ __('List Data of') }} {{ __('Blood Stock') }}</h5>

        {{-- Filters Datatable :begin --}}
        <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2 m-0">
          {{-- Date Range Picker :begin --}}
          <div>
            <div class="input-group">
              <span class="input-group-text" id="blood-stock-table-date-filter">
                <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
              </span>
              <input class="form-control blood-stock-table-date-filter" aria-describedby="blood-stock-table-date-filter"
                data-date-format="d-m-Y" data-provider="flatpickr" data-range-date="true" type="text"
                placeholder="{{ __('Choose Date Range') }}" />
            </div>
          </div>
          {{-- Date Range Picker :end --}}
        </div>
        {{-- Filters Datatable :end --}}
      </div>
      {{-- Card Header :end --}}

      {{-- Card Body :begin --}}
      <div class="card-body">
        <table class="table table-sm table-striped dt-responsive align-middle mb-0 blood-stock-table"
          id="blood-stock-table">
          <thead class="thead-sm text-uppercase fs-xxs">
            <tr>
              <th>{{ __('Blood Group') }}</th>
              <th>{{ __('Blood Rhesus') }}</th>
              <th>{{ __('Blood Component') }}</th>
              <th>{{ __('Quantity') }}</th>
              <th>{{ __('Status') }}</th>
              <th>{{ __('Updated At') }}</th>
              <th>{{ __('Action') }}</th>
            </tr>
          </thead>
        </table>
      </div>
      {{-- Card Body :end --}}
    </div>
    {{-- Card :end --}}
  </div>
  {{-- Datatables of List Blood Stock :end --}}
</div>

{{-- @include('pages.inventory.sub-pages.blood-stock.partials.delete-data-modal')
@include('pages.inventory.sub-pages.blood-stock.partials.restore-data-modal') --}}
@endsection

@section('scripts')
@vite([
'resources/js/pages/inventory/blood-stock/datatable.js',
'resources/js/pages/inventory/blood-stock/index.js',
])
@endsection