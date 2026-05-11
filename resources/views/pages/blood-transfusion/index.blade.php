@extends('layouts.vertical', ['title' => __('Blood Transfusion')])

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
{{-- Analytic :begin --}}
<div class="row pt-3">
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

    {{-- Modal Add New Blood Request :begin --}}
    <x-modal-layout id="add_blood_request_modal" size="modal-lg" title="{{ __('Add New Blood Request') }}">
      @include('pages.blood-transfusion.partials.form-add-blood-request')
    </x-modal-layout>
    {{-- Modal Add New Blood Request :end --}}
  </div>
  {{-- Title, Date Range Picker & Add New Blood Request :end --}}

  {{-- Left Side :begin --}}
  <div class="col-md-6 col-12">
    {{-- List Data :begin --}}
    <div class="card card-h-100">
      {{-- List Data Header :begin --}}
      <div class="card-header justify-content-between align-items-center">
        <h5 class="card-title mb-0">{{ __('List Data of') }} {{ __('Blood Request') }}</h5>
      </div>
      {{-- List Data Header :end --}}

      {{-- List Data Body :begin --}}
      <div class="card-body">
        {{-- Nav Tabs :begin --}}
        <ul class="nav nav-tabs nav-bordered nav-bordered-primary mb-1">
          {{-- List Request --}}
          <li class="nav-item">
            <a aria-expanded="false" class="nav-link active" data-bs-toggle="tab" href="#list-request">
              {{ __('Request List') }}
            </a>
          </li>

          {{-- Archive --}}
          <li class="nav-item">
            <a aria-expanded="true" class="nav-link" data-bs-toggle="tab" href="#list-archive">
              {{ __('Archive') }}
            </a>
          </li>
        </ul>
        {{-- Nav Tabs :end --}}

        {{-- Tab Contents :begin --}}
        <div class="tab-content">
          {{-- List Request Content :begin --}}
          <div class="tab-pane show active" id="list-request">
            {{-- Table :begin --}}
            @include('pages.blood-transfusion.partials.datatables.list-request-table')
            {{-- Table :end --}}
          </div>
          {{-- List Request Content :end --}}

          {{-- List Archive Content :begin --}}
          <div class="tab-pane" id="list-archive">
            {{-- Table :begin --}}
            @include('pages.blood-transfusion.partials.datatables.list-archive-table')
            {{-- Table :end --}}
          </div>
          {{-- List Archive Content :end --}}
        </div>
        {{-- Tab Contents :end --}}
      </div>
      {{-- List Data Body :end --}}
    </div>
    {{-- List Data :end --}}
  </div>
  {{-- Left Side :end --}}

  {{-- Right Side :begin --}}
  <div class="col-md-6 col-12">
    {{-- Patient Details :begin --}}
    <div class="card">
      {{-- Patient Details Header :begin --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title text-capitalize mb-0">{{ __('Patient Details') }}</h5>
        <div class="card-action d-flex align-items-center gap-2">
          <button class="btn btn-sm btn-primary d-none" id="btn-checkin-lab" data-id="">{{ __('Check In No Lab') }}</button>
          <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
        </div>
      </div>
      {{-- Patient Details Header :end --}}

      {{-- Patient Details Body :begin --}}
      <div class="card-body">
        <div class="row">
          @include('pages.blood-transfusion.partials.patient-details')
        </div>
      </div>
      {{-- Patient Details Body :end --}}
    </div>
    {{-- Patient Details :end --}}

    {{-- Bag Request List & Test List :begin --}}
    <div class="row">
      {{-- Bag Request List :begin --}}
      <div class="col-xxl-5 col-12">
        {{-- Bag Request List :begin --}}
        <div class="card">
          {{-- Bag Request List Header :begin --}}
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title text-capitalize mb-0">{{ __('Bag Request List') }}</h5>
            <button class="btn btn-sm btn-secondary" id="btn-edit-blood-pack" data-id="" data-bs-toggle="modal" data-bs-target="#edit_blood_pack_modal">{{ __('Edit') }}</button>
            <div class="card-action">
              <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
            </div>
          </div>
          {{-- Bag Request List Header :end --}}

          {{-- Bag Request List Body :begin --}}
          <div class="card-body">
            @include('pages.blood-transfusion.partials.datatables.list-bag-request-table')
          </div>
          {{-- Bag Request List Body :end --}}
        </div>
        {{-- Bag Request List :end --}}
      </div>
      {{-- Bag Request List :end --}}

      {{-- Test List :begin --}}
      <div class="col-xxl-7 col-12">
        {{-- Test List :begin --}}
        <div class="card">
          {{-- Test List Header :begin --}}
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title text-capitalize mb-0">{{ __('Test List') }}</h5>
            <div class="card-action">
              <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
            </div>
          </div>
          {{-- Test List Header :end --}}

          {{-- Test List Body :begin --}}
          <div class="card-body">
            @include('pages.blood-transfusion.partials.datatables.list-test-table')
          </div>
          {{-- Test List Body :end --}}
        </div>
        {{-- Test List :end --}}
      </div>
      {{-- Test List :end --}}
    </div>
    {{-- Bag Request List & Test List :end --}}
  </div>
  {{-- Right Side :end --}}
</div>
{{-- Analytic :end --}}

{{-- Timeline, Blood Stock & History Test :begin --}}
<div class="row">
  {{-- Timeline :begin --}}
  <div class="col-xxl-4 col-12">
    {{-- Timeline Card :begin --}}
    <div class="card">
      {{-- Timeline Header :begin --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title text-capitalize mb-0">{{ __('Timeline') }}</h5>
        <div class="card-action">
          <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
        </div>
      </div>
      {{-- Timeline Header :end --}}

      {{-- Timeline Body :begin --}}
      <div class="card-body">
        @include('pages.blood-transfusion.partials.timeline')
      </div>
      {{-- Timeline Body :end --}}
    </div>
    {{-- Timeline Card :end --}}
  </div>
  {{-- Timeline :end --}}

  {{-- Blood Stock :begin --}}
  <div class="col-xxl-4 col-12">
    {{-- Blood Stock :begin --}}
    <div class="card">
      {{-- Blood Stock Header :begin --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title text-capitalize mb-0">{{ __('Blood Stock') }}</h5>
        <div class="card-action">
          <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
        </div>
      </div>
      {{-- Blood Stock Header :end --}}

      {{-- Blood Stock Body :begin --}}
      <div class="card-body">
        @include('pages.blood-transfusion.partials.datatables.list-blood-stock-table')
      </div>
      {{-- Blood Stock Body :end --}}
    </div>
    {{-- Blood Stock :end --}}
  </div>
  {{-- Blood Stock :end --}}

  {{-- History Test :begin --}}
  <div class="col-xxl-4 col-12">
    {{-- History Test Card :begin --}}
    <div class="card">
      {{-- History Test Header :begin --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title text-capitalize mb-0">{{ __('History Test') }}</h5>
        <div class="card-action">
          <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
        </div>
      </div>
      {{-- History Test Header :end --}}

      {{-- History Test Body :begin --}}
      <div class="card-body">
        @include('pages.blood-transfusion.partials.datatables.list-history-test-table')
      </div>
      {{-- History Test Body :end --}}
    </div>
    {{-- History Test Card :end --}}
  </div>
  {{-- History Test :end --}}
</div>
{{-- Timeline, Blood Stock & History Test :end --}}

{{-- Modal :begin --}}
@include('pages.blood-transfusion.partials.edit-data-blood-request-modal')
@include('pages.blood-transfusion.partials.delete-data-blood-request-modal')
@include('pages.blood-transfusion.partials.edit-blood-pack-modal')
{{-- Modal :end --}}
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
