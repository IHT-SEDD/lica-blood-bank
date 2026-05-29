@extends('layouts.blood-transfusion-layout', ['title' => __('Blood Transfusion')])

@section('styles')
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
  <div class="col-xxl-7 col-12">
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
  <div class="col-xxl-5 col-12">
    <div class="card">
      {{-- Card Header --}}
      <div class="card-header d-flex justify-content-between align-items-center border-dashed">
        <h5 class="card-title text-capitalize mb-0">{{ __('Patient Details') }}</h5>

        <div class="card-action d-flex align-items-center justify-content-center gap-2">
          {{-- Check In button --}}
          <button class="btn btn-sm btn-soft-secondary d-none fs-6 fw-semibold" id="btn-checkin-lab" data-id=""
            data-bs-title="Check In Patient" data-bs-toggle="tooltip" data-bs-trigger="hover">
            <i class="ti ti-file-check fs-4 me-2"></i> Check In
          </button>

          {{-- Print barcode button --}}
          <button class="btn btn-sm btn-soft-info d-none fs-6 fw-semibold" id="btn-print-barcode" data-id=""
            data-bs-title="Print Barcode" data-bs-toggle="tooltip" data-bs-trigger="hover">
            <i class="ti ti-printer fs-4 me-2"></i> Barcode
          </button>

          {{-- Complete Transaction button --}}
          <button data-id="" class="btn btn-sm btn-success d-none fw-medium" style="font-size: 11.9px;"
            id="btn-complete-transaction" data-bs-title="Complete This Transaction" data-bs-toggle="tooltip"
            data-bs-trigger="hover">
            <i class="ti ti-flag-check fs-lg me-1"></i> Complete
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

  {{-- Bag Request List :begin --}}
  <div class="col-xxl-7 col-12">
    <div class="card">
      {{-- Card Header --}}
      <div class="card-header d-flex justify-content-between align-items-center border-dashed">
        <h5 class="card-title text-capitalize mb-0">{{ __('Bag Request List') }}</h5>

        <div class="d-flex align-items-center justify-content-center gap-2">
          {{-- Edit blood pack button --}}
          <button class="btn btn-sm btn-soft-primary d-none fw-medium" style="font-size: 11.9px;"
            id="btn-edit-blood-pack" data-id="" data-bs-toggle="modal" data-bs-target="#edit_blood_pack_modal"
            data-bs-title="Edit blood pack" data-bs-toggle="tooltip" data-bs-trigger="hover">
            <i class="ti ti-pencil fs-lg"></i>
          </button>

          {{-- Release all blood button --}}
          <button class="btn btn-sm btn-soft-danger d-none fw-medium" style="font-size: 11.9px;"
            id="btn-release-all-blood-pack" data-id="" data-bs-title="Release All Blood Pack" data-bs-toggle="tooltip"
            data-bs-trigger="hover">
            <i class="ti ti-heart-share fs-lg me-1"></i> Release All
          </button>

          {{-- Print crossmatch result button --}}
          <button data-id="" class="btn btn-sm btn-soft-info d-none fw-medium" style="font-size: 11.9px;"
            id="btn-print-result" data-bs-title="Print Crossmatch Result" data-bs-toggle="tooltip"
            data-bs-trigger="hover">
            <i class="ti ti-printer fs-lg me-1"></i> Result
          </button>

          {{-- Print incompatible letter button --}}
          <button data-id="" class="btn btn-sm btn-soft-primary d-none fw-medium" style="font-size: 11.9px;"
            id="btn-print-incompletter" data-bs-title="Print Crossmatch Result Incompatible Blood Pack"
            data-bs-toggle="tooltip" data-bs-trigger="hover">
            <i class="ti ti-printer fs-4 me-1"></i> Incompatible Letter
          </button>
        </div>
      </div>

      {{-- Card Body --}}
      <div class="card-body">
        @include('pages.blood-transfusion.partials.datatables.list-bag-request-table')
      </div>
    </div>
  </div>
  {{-- Bag Request List :end --}}

  {{-- Test List :begin --}}
  <div class="col-xxl-5 col-12">
    <div class="card">
      {{-- Card Header --}}
      <div class="card-header d-flex justify-content-between align-items-center border-dashed">
        <h5 class="card-title text-capitalize mb-0">{{ __('Test List') }}</h5>

        <div class="d-flex justify-content-center align-items-center gap-2">
          {{-- Finish blood request button --}}
          <button data-id="" class="btn btn-sm btn-soft-success fw-medium" style="font-size: 11.9px;" id="btn-test-done"
            data-bs-title="Finish Crossmatch" data-bs-toggle="tooltip" data-bs-trigger="hover">
            <i class="ti ti-circle-check fs-4 me-1"></i> Finish
          </button>

          {{-- Hold blood pack button --}}
          <button data-id="" class="btn btn-sm btn-soft-warning d-none fw-medium" style="font-size: 11.9px;"
            id="btn-hold-blood-pack" data-bs-title="Hold This Blood Pack" data-bs-toggle="tooltip"
            data-bs-trigger="hover">
            <i class="ti ti-heart-pause fs-4 me-1"></i> Hold
          </button>

          {{-- Release blood pack button --}}
          <button data-id="" class="btn btn-sm btn-soft-danger d-none fw-medium" style="font-size: 11.9px;"
            id="btn-release-blood-pack" data-bs-title="Release This Blood Pack" data-bs-toggle="tooltip"
            data-bs-trigger="hover">
            <i class="ti ti-heart-up fs-4 me-1"></i> Release
          </button>

          {{-- Don't Release blood pack button --}}
          <button data-id="" class="btn btn-sm btn-soft-danger d-none fw-medium" style="font-size: 11.9px;"
            id="btn-unrelease-blood-pack" data-bs-title="Don't Release This Blood Pack" data-bs-toggle="tooltip"
            data-bs-trigger="hover">
            <i class="ti ti-heart-x fs-4 me-1"></i> Don't Release
          </button>

          {{-- Approve incompatible button --}}
          <button data-id="" class="btn btn-sm btn-soft-success d-none fw-medium" style="font-size: 11.9px;"
            id="btn-accept-blood-pack" data-bs-title="Approve Release This Blood Pack" data-bs-toggle="tooltip"
            data-bs-trigger="hover">
            <i class="ti ti-droplet-check fs-4 me-1"></i> Approve Incompatible
          </button>
        </div>
      </div>

      {{-- Card Body --}}
      <div class="card-body">
        @include('pages.blood-transfusion.partials.datatables.list-test-table')
      </div>
    </div>
  </div>
  {{-- Test List :end --}}

  {{-- Timeline :begin --}}
  <div class="col-xxl-6 col-12">
    <div class="card">
      {{-- Card Header --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title text-capitalize mb-0">{{ __('Timeline') }}</h5>
        <div class="card-action">
          <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
        </div>
      </div>

      {{-- Card Body :begin --}}
      <div class="card-body blood-transfusion-log-data-container">
        <div class="timeline timeline-icon-bordered timeline-blood-transfusion-log">
          {{-- Populate by JS --}}
        </div>
      </div>
      {{-- Card Body :end --}}
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
@include('pages.blood-transfusion.partials.accept-incompatible-blood-modal')
@include('pages.blood-transfusion.partials.edit-blood-pack-modal')
@endsection

@section('scripts')
@vite([
'resources/js/pages/blood-transfusion/index.js',
'resources/js/pages/blood-transfusion/datatable-blood-pack.js',
'resources/js/pages/blood-transfusion/form-add.js',
// 'resources/js/pages/blood-transfusion/form-edit.js',
'resources/js/pages/form-wizard.js'
])
@endsection