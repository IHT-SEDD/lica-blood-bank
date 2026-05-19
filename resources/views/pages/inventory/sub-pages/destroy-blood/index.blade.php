@extends('layouts.vertical', ['title' => 'Blood Destroy'])

@section('styles')
@vite([
'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css',
'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css',
])
@endsection

@section('content')
<div class="row mt-2">
  {{-- Title :begin --}}
  <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-1">
    {{-- Title --}}
    <h1 class="fw-bold">{{ __('Blood Destroy') }}</h1>
  </div>
  {{-- Title :end --}}

  {{-- Datatable :begin --}}
  <div class="col-8">
    {{-- Card :begin --}}
    <div class="card">
      {{-- Card Header :begin --}}
      <div class="card-header justify-content-between align-items-center">
        <h5 class="card-title text-capitalize mb-0">{{ __('List Data of') }} {{ __('Destroyed Blood') }}</h5>

        {{-- Filters Datatable :begin --}}
        <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2 m-0">
          {{-- Date Range Picker :begin --}}
          <div>
            <div class="input-group">
              <span class="input-group-text" id="blood-destroy-table-date-filter">
                <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
              </span>
              <input class="form-control form-control-sm blood-destroy-table-date-filter"
                aria-describedby="blood-destroy-table-date-filter" data-date-format="d-m-Y" data-provider="flatpickr"
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
        <table class="table table-sm table-striped dt-responsive align-middle mb-0 blood-destroy-table"
          id="blood-destroy-table">
          <thead class="thead-sm text-uppercase fs-xxs">
            <tr>
              <th>{{ __('Bag Number') }}</th>
              <th>{{ __('Blood Pack') }}</th>
              <th>{{ __('Expiry Date') }}</th>
              <th>{{ __('Status') }}</th>
              <th>{{ __('Destroyed At') }}</th>
              <th>{{ __('Action') }}</th>
            </tr>
          </thead>
        </table>
      </div>
      {{-- Card Body :end --}}
    </div>
    {{-- Card :end --}}
  </div>
  {{-- Datatable :end --}}

  {{-- Form Add :begin --}}
  <div class="col-4">
    {{-- Card :begin --}}
    <div class="card">
      {{-- Card Header :begin --}}
      <div class="card-header justify-content-between align-items-center">
        <h5 class="card-title text-capitalize mb-0">{{ __('Destroy Blood') }}</h5>
      </div>
      {{-- Card Header :end --}}

      {{-- Card Body :begin --}}
      <div class="card-body">
        <form class="row g-2" id="add_new_destroy_blood" autocomplete="off">
          {{-- Manual or Scan --}}
          <div class="col-6">
            <input class="btn-check" id="method_destroy_manual" name="method_add" type="radio" value="manual" checked />
            <label class="btn btn-outline-primary w-100" for="method_destroy_manual">{{ __('Manual') }}</label>
          </div>
          <div class="col-6 mb-2">
            <input class="btn-check" id="method_destroy_scan" name="method_add" type="radio" value="scan" />
            <label class="btn btn-outline-primary w-100" for="method_destroy_scan">{{ __('By Scan') }}</label>
          </div>

          <hr />

          {{-- Reason --}}
          <div class="col-lg-12">
            <label class="form-label" for="reason">Reason</label>
            <textarea autocomplete="off" class="form-control" id="reason" name="reason" rows="5"
              placeholder="Destroy Reason"></textarea>
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
            <button class="btn btn-soft-danger w-50" type="submit">{{ __('Destroy') }}</button>
          </div>
        </form>
      </div>
      {{-- Card Body :end --}}
    </div>
    {{-- Card :end --}}
  </div>
  {{-- Form Add :end --}}
</div>

@include('utils.delete-data-modal', ['id'=> 'destroy_blood', 'title' => 'Blood Destroy'])
@include('pages.inventory.sub-pages.blood-stock.partials.edit-data-modal')
@include('utils.restore-data-modal', ['id'=> 'destroy_blood', 'title' => 'Blood Destroy'])
@include('utils.permanent-delete-data-modal', ['id'=> 'destroy_blood', 'title' => 'Blood Destroy'])
@include('utils.confirmation-data-modal', ['id'=> 'destroy_blood', 'title' => 'Undestroy Blood', 'action' =>
'Undestroy'])
@endsection

@section('scripts')
@vite([
'resources/js/pages/inventory/blood-destroy/datatable.js',
'resources/js/pages/inventory/blood-destroy/form-add.js',
])
@endsection