@extends('layouts.vertical', ['title' => 'Detail Blood Stock'])

@section('styles')
@endsection

@section('content')
<div class="row mt-3">
  {{-- Header :begin --}}
  <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-1 mb-2">
    {{-- Button Back to Blood Stock List --}}
    <a href="{{ route('inventory.blood-stock.index') }}" class="btn btn-soft-primary">
      {{ __('Back To') }} {{ __('Blood Stock') }}
    </a>

    {{-- Title --}}
    <h1 class="fw-bold mb-0">{{ __('Detail') }} {{ __('Blood Stock') }} <span id="blood_stock_type"></span></h1>
  </div>
  {{-- Header :end --}}

  {{-- Blood Data Table :begin --}}
  <div class="col-lg-8 col-md-7 col-12">
    <div class="card">
      {{-- Card Header :begin --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title text-capitalize mb-0">{{ __('Blood Data') }}</h5>

        {{-- Filters Datatable :begin --}}
        <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2 m-0">
          {{-- Select Status --}}
          <div>
            <select class="form-control form-control-sm tomselect-sm" id="filter-blood-status"
              name="filter-blood-status" placeholder="Filter by status..."></select>
          </div>

          {{-- Date Range Picker :begin --}}
          <div>
            <div class="input-group">
              <span class="input-group-text" id="stock-blood-data-table-date-filter">
                <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
              </span>
              <input class="form-control form-control-sm stock-blood-data-table-date-filter"
                aria-describedby="stock-blood-data-table-date-filter" data-date-format="d-m-Y" data-provider="flatpickr"
                data-range-date="true" type="text" placeholder="{{ __('Choose Date Range') }}" />
            </div>
          </div>
          {{-- Date Range Picker :end --}}
        </div>
        {{-- Filters Datatable :end --}}

        <div class="card-action">
          <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
        </div>
      </div>
      {{-- Card Header :end --}}

      {{-- Card Body :begin --}}
      <div class="card-body">
        <table class="table table-sm table-striped dt-responsive align-middle mb-0 stock-blood-data-table"
          id="stock-blood-data-table">
          <thead class="thead-sm text-uppercase fs-xxs">
            <tr>
              <th>{{ __('Bag No.') }}</th>
              <th>{{ __('Bag No. LICA') }}</th>
              <th>{{ __('Blood Pack') }}</th>
              <th>{{ __('Volume') }}</th>
              <th>{{ __('Status') }}</th>
              <th>{{ __('Expired') }}</th>
              <th>{{ __('Aftap') }}</th>
              <th>{{ __('Process') }}</th>
              <th>{{ __('Used At') }}</th>
              <th>{{ __('Ready At') }}</th>
              <th>{{ __('Deleted At') }}</th>
              <th>{{ __('Action') }}</th>
            </tr>
          </thead>
        </table>
      </div>
      {{-- Card Body :end --}}
    </div>
  </div>
  {{-- Blood Data Table :end --}}

  {{-- Log Activity :begin --}}
  <div class="col-lg-4 col-md-5 col-12">
    <div class="card">
      {{-- Card Header :begin --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title text-capitalize mb-0">{{ __('Log Activity') }}</h5>
        <div class="card-action">
          <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
        </div>
      </div>
      {{-- Card Header :end --}}

      {{-- Card Body :begin --}}
      <div class="card-body blood-stock-log-data-container">
        <div class="timeline timeline-icon-bordered timeline-blood-stock-log">
          {{-- Populate by JS --}}
        </div>
      </div>
      {{-- Card Body :end --}}
    </div>
  </div>
  {{-- Log Activity :end --}}
</div>

@include('utils.delete-data-modal', ['id'=> 'stock_blood', 'title' => 'Blood Stock'])
@include('pages.inventory.sub-pages.blood-stock.partials.edit-data-modal')
@include('utils.restore-data-modal', ['id'=> 'stock_blood', 'title' => 'Blood Stock'])
@include('utils.permanent-delete-data-modal', ['id'=> 'blood_stock', 'title' => 'Blood Stock'])
@endsection

@section('scripts')
@vite([
'resources/js/pages/inventory/blood-stock/detail-stock.js',
])
@endsection