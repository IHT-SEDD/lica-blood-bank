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
  <div class="col-xxl-6 col-12">
    <div class="card">
      {{-- Card Header --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center justify-content-start gap-2">
          <h5 class="card-title text-capitalize m-0">{{ __('Test List') }}</h5>
          {{-- Finish blood request button --}}
          <button class="btn btn-sm btn-soft-success m-0" id="btn-test-done" data-id=""
            data-bs-title="Finish Crossmatch" data-bs-toggle="tooltip" data-bs-trigger="hover">
            <i class="ti ti-circle-check fs-4"></i>
          </button>

          {{-- Hold blood pack button --}}
          <button class="btn btn-sm btn-soft-warning d-none m-0" id="btn-hold-blood-pack" data-id=""
            data-bs-title="Hold This Blood Pack" data-bs-toggle="tooltip" data-bs-trigger="hover">
            <i class="ti ti-heart-pause fs-4"></i>
          </button>

          {{-- Release blood pack button --}}
          <button class="btn btn-sm btn-soft-danger d-none m-0" id="btn-release-blood-pack" data-id=""
            data-bs-title="Release This Blood Pack" data-bs-toggle="tooltip" data-bs-trigger="hover">
            <i class="ti ti-heart-up fs-4"></i>
          </button>
          {{-- Don't Release blood pack button --}}
          <button class="btn btn-sm btn-soft-danger d-none m-0" id="btn-unrelease-blood-pack" data-id=""
            data-bs-title="Don't Release This Blood Pack" data-bs-toggle="tooltip" data-bs-trigger="hover">
            <i class="ti ti-heart-x fs-4"></i>
          </button>

          {{-- Print crossmatch result incompotible blood pack button --}}
          <button class="btn btn-sm btn-soft-primary d-none m-0" id="btn-print-incompletter" data-id=""
            data-bs-title="Print Crossmatch Result Incompatible Blood Pack" data-bs-toggle="tooltip"
            data-bs-trigger="hover">
            <i class="ti ti-printer fs-4"></i>
          </button>
        </div>

        <div class="card-action">
          {{-- Accept blood pack button --}}
          <button class="btn btn-sm btn-soft-success me-2 d-none" id="btn-accept-blood-pack" data-id=""
            data-bs-title="Approve Release This Blood Pack" data-bs-toggle="tooltip" data-bs-trigger="hover">
            <i class="ti ti-droplet-check fs-4"></i>
          </button>
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
  <div class="col-xxl-6 col-12">
    <div class="card">
      {{-- Card Header --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center justify-content-start gap-2">
          <h5 class="card-title text-capitalize m-0">{{ __('Bag Request List') }}</h5>

          {{-- Edit blood pack button --}}
          <button class="btn btn-sm btn-soft-primary d-none m-0" id="btn-edit-blood-pack" data-id=""
            data-bs-toggle="modal" data-bs-target="#edit_blood_pack_modal" data-bs-title="Edit blood pack"
            data-bs-toggle="tooltip" data-bs-trigger="hover">
            <i class="ti ti-pencil fs-4"></i>
          </button>

          {{-- Release all blood button --}}
          <button class="btn btn-sm btn-soft-danger d-none m-0" id="btn-release-all-blood-pack" data-id=""
            data-bs-title="Release All Blood Pack" data-bs-toggle="tooltip" data-bs-trigger="hover">
            <i class="ti ti-heart-share fs-4"></i>
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
@include('pages.blood-transfusion.partials.accept-incompatible-blood-modal')
@include('pages.blood-transfusion.partials.edit-blood-pack-modal')
@endsection

@section('scripts')
@vite([
'resources/js/pages/blood-transfusion/index.js',
'resources/js/pages/blood-transfusion/datatable-blood-pack.js',
'resources/js/pages/blood-transfusion/form-add.js',
'resources/js/pages/blood-transfusion/form-edit.js',
'resources/js/pages/form-wizard.js'
])
@endsection