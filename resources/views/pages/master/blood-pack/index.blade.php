@extends('layouts.master-page-layout')

@section('datatable-header')
<div class="d-flex align-items-center justify-content-center gap-2">
  {{-- Select Blood Group --}}
  <div>
    <select class="form-control" id="filter-blood-group" name="filter-blood-group"
      placeholder="Filter by blood group..."></select>
  </div>

  {{-- Select Component --}}
  <div>
    <select class="form-control" id="filter-component" name="filter-component"
      placeholder="Filter by component..."></select>
  </div>

  {{-- Date filter :begin --}}
  <div>
    <div class="input-group">
      <span class="input-group-text" id="master-blood-pack-table-date-filter">
        <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
      </span>
      <input class="form-control master-blood-pack-table-date-filter"
        aria-describedby="master-blood-pack-table-date-filter" data-date-format="d-m-Y" data-provider="flatpickr"
        data-range-date="true" type="text" placeholder="Choose date range" />
    </div>
  </div>
  {{-- Date filter :begin --}}
</div>
@endsection

@section('datatable-content')
<table class="table table-sm table-striped dt-responsive align-middle mb-0 master-blood-pack-table"
  id="master-blood-pack-table">
  <thead class="thead-sm text-uppercase fs-xxs">
    <tr>
      <th>No</th>
      <th>Blood Group</th>
      <th>Blood Rhesus</th>
      <th>Blood Component</th>
      <th>Warning Qty</th>
      <th>Danger Qty</th>
      <th>Created At</th>
      <th>Updated At</th>
      <th>Deleted At</th>
      <th>Action</th>
    </tr>
  </thead>
</table>
@endsection

@section('form-content')
<form class="row g-2" id="add_new_blood_pack" autocomplete="off">
  {{-- Blood Group --}}
  <div class="col-lg-6">
    <label class="form-label" for="select-blood-group">{{ __('Blood Group') }}
      <span class="text-danger">*</span>
    </label>
    <select class="form-control" id="select-blood-group" name="blood_group"
      placeholder="{{ __('Choose') }} {{ __('Blood Group') }}..."></select>
  </div>

  {{-- Blood Rhesus --}}
  <div class="col-lg-6">
    <label class="form-label" for="select-blood-rhesus">{{ __('Blood Rhesus') }}
      <span class="text-danger">*</span>
    </label>
    <select class="form-control" id="select-blood-rhesus" name="blood_rhesus"
      placeholder="{{ __('Choose') }} {{ __('Blood Rhesus') }}..."></select>
  </div>

  {{-- Blood Component --}}
  <div class="col-lg-12">
    <label class="form-label" for="select-blood-component">{{ __('Blood Component') }}
      <span class="text-danger">*</span>
    </label>
    <select class="form-control" id="select-blood-component" name="blood_component"
      placeholder="{{ __('Choose') }} {{ __('Blood Component') }}..."></select>
  </div>

  {{-- Warning QTY --}}
  <div class="col-lg-6">
    <label class="form-label" for="warning_quantity">{{ __('Warning') }} {{ __('Quantity') }}
      <span class="text-danger">*</span>
    </label>
    <input autocomplete="off" class="form-control" id="warning_quantity" name="warning_quantity" type="number" />
  </div>

  {{-- Danger QTY --}}
  <div class="col-lg-6">
    <label class="form-label" for="danger_quantity">{{ __('Danger') }} {{ __('Quantity') }}
      <span class="text-danger">*</span>
    </label>
    <input autocomplete="off" class="form-control" id="danger_quantity" name="danger_quantity" type="number" />
  </div>

  {{-- Is Active? --}}
  <div class="col-lg-12">
    <div>
      <div class="form-check form-check-success my-1">
        <input checked="" class="form-check-input" id="is_active" type="checkbox" name="is_active" />
        <label class="form-check-label" for="is_active">{{ __('Active') }}?</label>
      </div>
    </div>
  </div>

  {{-- Submit Button --}}
  <div class="col-lg-12">
    <button class="btn btn-primary" type="submit">{{ __('Submit') }} {{ __('Data') }}</button>
  </div>
</form>
@endsection

@section('modal-content')
@include('pages.master.blood-pack.partials.edit-data-modal')
@include('pages.master.blood-pack.partials.delete-data-modal')
@include('pages.master.blood-pack.partials.restore-data-modal')
@endsection

@section('custom-scripts')

@endsection