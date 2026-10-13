@extends('layouts.donor-layout', ['title' => __('Donor')])

@section('styles')
<style>
 #tableSelector {
  table-layout: fixed !important;
  width: 100% !important;
 }

 .input-group .flatpickr-wrapper {
  flex: 1 1 auto;
  width: 1%;
  min-width: 0;
 }

 .input-group .flatpickr-wrapper .form-control {
  width: 100%;
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
 }
</style>
@endsection

@section('content')
<div class="row py-3">
 {{-- Header :begin --}}
 <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
  {{-- Title --}}
  <h1 class="fw-bold uppercase">{{ __('Donor') }}</h1>

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
      placeholder="{{ __('Filter rentang tanggal') }}" />
    </div>
   </div>

   {{-- Button tambah donor darah --}}
   <button class="btn btn-info" data-bs-target="#add_donor_request_modal" data-bs-toggle="modal" type="button">
    {{ __('Tambah Donor Darah') }}
   </button>
  </div>
  {{-- Date Range Picker & Add New Blood Request Button :end --}}

  {{-- Modal tambah pendonor --}}
  <x-modal-layout id="add_donor_request_modal" size="modal-lg" title="{{ __('Tambah Donor Darah') }}">
   @include('pages.donor.partials.form-add-donor-request')
  </x-modal-layout>
 </div>
 {{-- Header :end --}}
</div>
@endsection

@section('scripts')
<script src="{{ asset('vendor/qz/rsvp.min.js')}}"></script>
<script src="{{ asset('vendor/qz/qz-tray.js')}}"></script>
<script>
 window.clientConfig = @json(['blood_transfusion' => \App\Config\ClientConfig::module('blood_transfusion')]);
</script>
{{-- @vite([
'resources/js/pages/blood-transfusion/index.js',
'resources/js/pages/blood-transfusion/datatable-blood-pack.js',
'resources/js/pages/blood-transfusion/form-add.js',
'resources/js/pages/form-wizard.js'
]) --}}
@endsection