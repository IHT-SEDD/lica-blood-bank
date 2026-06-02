@extends('layouts.vertical', ['title' => 'Blood Stock'])

@section('styles')
@endsection

@section('content')
<div class="row mt-2">
  {{-- Title :begin --}}
  <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-1">
    {{-- Title --}}
    <h1 class="fw-bold">{{ __('Blood Stock') }}</h1>
  </div>
  {{-- Title :end --}}

  {{-- Datatables of List Blood Stock :begin --}}
  <div class="col-8">
    {{-- Card :begin --}}
    <div class="card">
      {{-- Card Header :begin --}}
      <div class="card-header justify-content-between align-items-center">
        <h5 class="card-title text-capitalize mb-0">{{ __('List Data of') }} {{ __('Blood Stock') }}</h5>

        {{-- Filters Datatable :begin --}}
        <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2 m-0">
          {{-- Export to excel --}}
          <button class="btn btn-sm btn-soft-secondary" id="excel_blood_stock_btn">
            <i class="ti ti-file-type-xls fs-lg align-middle flex-shrink-0 me-2"></i>
            {{ __('Excel') }}
          </button>

          {{-- Date Range Picker :begin --}}
          <div>
            <div class="input-group">
              <span class="input-group-text" id="blood-stock-table-date-filter">
                <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
              </span>
              <input class="form-control form-control-sm blood-stock-table-date-filter"
                aria-describedby="blood-stock-table-date-filter" data-date-format="d-m-Y" data-provider="flatpickr"
                data-range-date="true" type="text" placeholder="{{ __('Choose Date Range') }}" />
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
              {{-- <th class="fs-sm" style="width: 1%;"></th> --}}
              <th>{{ __('Blood Pack') }}</th>
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

  {{-- Form Add New Stock :begin --}}
  <div class="col-4">
    {{-- Card :begin --}}
    <div class="card">
      {{-- Card Header :begin --}}
      <div class="card-header justify-content-between align-items-center">
        <h5 class="card-title text-capitalize mb-0">{{ __('Add New') }} {{ __('Blood Stock') }}</h5>
      </div>
      {{-- Card Header :end --}}

      {{-- Card Body :begin --}}
      <div class="card-body">
        <form class="row g-2" id="add_new_blood_stock" autocomplete="off">
          {{-- Manual or Scan --}}
          <div class="col-6">
            <input class="btn-check" id="method_add_manual" name="method_add" type="radio" value="manual" checked />
            <label class="btn btn-outline-primary w-100" for="method_add_manual">{{ __('Manual') }}</label>
          </div>
          <div class="col-6 mb-2">
            <input class="btn-check" id="method_add_scan" name="method_add" type="radio" value="scan" />
            <label class="btn btn-outline-primary w-100" for="method_add_scan">{{ __('By Scan') }}</label>
          </div>

          <hr />

          {{-- Choose Purchase Order --}}
          <div class="col-12">
            <label class="form-label" for="select-purchase-order">{{ __('Choose') }} {{ __('Purchase Order') }}
              <span class="text-danger">*</span>
            </label>
            <select class="form-control" id="select-purchase-order" name="po_number"
              placeholder="Choose purchase order"></select>
          </div>

          {{-- Note --}}
          <div class="col-lg-12">
            <label class="form-label" for="note">Note</label>
            <textarea autocomplete="off" class="form-control" id="note" name="note" rows="5"
              placeholder="Blood stock note"></textarea>
          </div>

          {{-- Choose Bag Number --}}
          <div class="col-12" id="wrap-select-bag-number">
            <label class="form-label" for="select-bag-number">{{ __('Choose') }} {{ __('Bag Number') }}
              <span class="text-danger">*</span>
            </label>
            <select class="form-control" id="select-bag-number" name="bag_numbers[]" multiple
              placeholder="Choose bag number"></select>
          </div>

          {{-- Bag Number by Scan --}}
          <div class="col-lg-12" id="wrap-textarea-bag-numbers">
            <label class="form-label" for="bag_numbers">{{ __('Bag Number List') }}
              <span class="text-danger">*</span>
            </label>
            <textarea autocomplete="off" class="form-control" id="bag_numbers" name="bag_numbers" rows="8"
              placeholder="Bag number will appear here"></textarea>
          </div>

          {{-- Submit Button --}}
          <div class="col-lg-12">
            <button class="btn btn-soft-success" type="submit">{{ __('Add Data') }}</button>
          </div>
        </form>
      </div>
      {{-- Card Body :end --}}
    </div>
    {{-- Card :end --}}
  </div>
  {{-- Form Add New Stock :end --}}
</div>

@endsection

@section('scripts')
@vite([
'resources/js/pages/inventory/blood-stock/datatable.js',
'resources/js/pages/inventory/blood-stock/form-add.js',
])
@endsection