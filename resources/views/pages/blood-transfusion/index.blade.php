@extends('layouts.blood-transfusion-layout', ['title' => __('Blood Transfusion')])

@section('styles')
@vite([
'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css',
'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css',
])
<style>
  @media (min-width: 992px) {
    .patient-data-border {
      border-right: 1px solid #e5e7eb;
    }
  }

  #tableSelector {
    table-layout: fixed !important;
    width: 100% !important;
  }
</style>
@endsection

@section('content')
<div class="row py-3">
  {{-- Title, Date Range Picker & Add New Blood Request :begin --}}
  <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
    {{-- Title --}}
    <h1 class="fw-bold uppercase">{{ __('Transfusion') }}</h1>

    {{-- Date Range Picker & Add New Blood Request Button :begin --}}
    <div class="d-flex align-items-center justify-content-center gap-2">
      {{-- Date Range Picker --}}
      <div>
        <div class="input-group">
          <span class="input-group-text" id="blood-transfusion-date-filter">
            <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
          </span>
          <input class="form-control blood-transfusion-date-filter" aria-describedby="blood-transfusion-date-filter"
            data-date-format="d-m-Y" data-provider="flatpickr" data-range-date="true" type="text"
            placeholder="{{ __('Choose Date Range') }}" />
        </div>
      </div>

      {{-- Button Add New Blood Request --}}
      <button class="btn btn-info" data-bs-target="#add_blood_request_modal" data-bs-toggle="modal" type="button">
        {{ __('Add Blood Request') }}
      </button>
    </div>
    {{-- Date Range Picker & Add New Blood Request Button :end --}}

    {{-- Modal Add New Blood Request --}}
    <x-modal-layout id="add_blood_request_modal" size="modal-lg" title="{{ __('Add New Blood Request') }}">
      @include('pages.blood-transfusion.partials.form-add-blood-request')
    </x-modal-layout>
  </div>
  {{-- Title, Date Range Picker & Add New Blood Request :end --}}

  {{-- List Data :begin --}}
  <div class="col-xxl-8 col-12">
    <div class="card">
      {{-- Card Header --}}
      <div class="card-header justify-content-between align-items-center">
        <h5 class="card-title mb-0">{{ __('List Data of') }} {{ __('Blood Request') }}</h5>
        <div class="card-action d-flex align-items-center gap-2">
          <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
        </div>
      </div>

      {{-- Card Body --}}
      <div class="card-body">
        @include('pages.blood-transfusion.partials.datatables.list-request-table')
      </div>
    </div>
  </div>
  {{-- List Data :end --}}

  {{-- Patient Details :begin --}}
  <div class="col-xxl-4 col-12">
    <div class="card">
      {{-- Card Header --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title text-capitalize mb-0">{{ __('Patient Details') }}</h5>
        <div class="card-action d-flex align-items-center gap-2">
          <button class="btn btn-sm btn-soft-secondary d-none" id="btn-checkin-lab" data-id="">
            {{ __('Check In') }}
          </button>
          <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
        </div>
      </div>

      {{-- Card Body --}}
      <div class="card-body">
        <div class="row">
          @include('pages.blood-transfusion.partials.patient-details')
        </div>
      </div>
    </div>
  </div>
  {{-- Patient Details :end --}}

  {{-- Test List :begin --}}
  <div class="col-xxl-8 col-12">
    <div class="card">
      {{-- Card Header --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center justify-content-start gap-2">
          <h5 class="card-title text-capitalize m-0">{{ __('Test List') }}</h5>
          <button class="btn btn-sm btn-soft-success m-0" id="btn-test-done" data-id="">{{ __('Done') }}</button>
        </div>

        <div class="card-action">
          <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
        </div>
      </div>

      {{-- Card Body --}}
      <div class="card-body">
        @include('pages.blood-transfusion.partials.datatables.list-test-table')
      </div>
    </div>
  </div>
  {{-- Test List :end --}}

  {{-- Bag Request List :begin --}}
  <div class="col-xxl-4 col-12">
    <div class="card">
      {{-- Card Header --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center justify-content-start gap-2">
          <h5 class="card-title text-capitalize m-0">{{ __('Bag Request List') }}</h5>
          <button class="btn btn-sm btn-soft-primary m-0" id="btn-edit-blood-pack" data-id="" data-bs-toggle="modal"
            data-bs-target="#edit_blood_pack_modal">
            <i class="ti ti-pencil"></i>
          </button>
        </div>

        <div class="card-action">
          <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
        </div>
      </div>

      {{-- Card Body --}}
      <div class="card-body">
        @include('pages.blood-transfusion.partials.datatables.list-bag-request-table')
      </div>
    </div>
  </div>
  {{-- Bag Request List :end --}}

  {{-- Timeline :begin --}}
  <div class="col-xxl-6 col-12 d-none">
    <div class="card">
      {{-- Card Header --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title text-capitalize mb-0">{{ __('Timeline') }}</h5>
        <div class="card-action">
          <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
        </div>
      </div>

      {{-- Card Body --}}
      <div class="card-body">
        @include('pages.blood-transfusion.partials.timeline')
      </div>
    </div>
  </div>
  {{-- Timeline :end --}}

  {{-- History Test :begin --}}
  <div class="col-xxl-6 col-12 d-none">
    <div class="card">
      {{-- Card Header --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title text-capitalize mb-0">{{ __('History Test') }}</h5>
        <div class="card-action">
          <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
        </div>
      </div>

      {{-- Card Body --}}
      <div class="card-body">
        @include('pages.blood-transfusion.partials.datatables.list-history-test-table')
      </div>
    </div>
  </div>
  {{-- History Test :end --}}
</div>

@include('pages.blood-transfusion.partials.edit-data-blood-request-modal')
@include('pages.blood-transfusion.partials.delete-data-blood-request-modal')
@include('pages.blood-transfusion.partials.edit-blood-pack-modal')
@endsection

@section('scripts')
@vite([
'resources/js/pages/blood-transfusion/index.js',
'resources/js/pages/blood-transfusion/datatable-blood-pack.js',
'resources/js/pages/blood-transfusion/analytic/datatables-helper.js',
'resources/js/pages/blood-transfusion/form-add.js',
'resources/js/pages/blood-transfusion/form-edit.js',
'resources/js/pages/form-wizard.js'
])
@endsection