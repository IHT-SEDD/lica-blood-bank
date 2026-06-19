@extends('layouts.blood-transfusion-layout', ['title' => __('Archive Blood Transfusion')])

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
  {{-- Header :begin --}}
  <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
    <a href="{{ route('blood-transfusion.index') }}" class="btn btn-sm btn-soft-primary">
      <i class="ti ti-arrow-left-dashed fs-lg align-middle flex-shrink-0 me-1"></i>
      {{ __('Keluar') }}
    </a>

    <h1 class="fw-bold uppercase">{{ __('Arsip Transaksi') }}</h1>

    <div class="d-flex align-items-center justify-content-center gap-2">
      <div>
        <div class="input-group">
          <span class="input-group-text" id="archive-blood-transfusion-date-filter">
            <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
          </span>
          <input class="form-control archive-blood-transfusion-date-filter"
            aria-describedby="archive-blood-transfusion-date-filter" data-date-format="d-m-Y" data-provider="flatpickr"
            data-range-date="true" type="text" placeholder="{{ __('Filter rentang tanggal') }}" />
        </div>
      </div>
    </div>
  </div>
  {{-- Header :end --}}

  {{-- List Data :begin --}}
  <div class="col-xxl-7 col-12">
    <div class="card">
      <div class="card-header justify-content-between align-items-center">
        <h5 class="card-title mb-0">{{ __('Data Arsip Permintaan Darah') }}</h5>
        <div class="card-action d-flex align-items-center gap-2">
          <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
        </div>
      </div>
      <div class="card-body">
        @include('pages.blood-transfusion.partials.datatables.archive.list-archive-table')
      </div>
    </div>
  </div>
  {{-- List Data :end --}}

  {{-- Patient Details :begin --}}
  <div class="col-xxl-5 col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center border-dashed">
        <h5 class="card-title text-capitalize mb-0">{{ __('Data Detail Pasien') }}</h5>
        <div class="card-action d-flex align-items-center justify-content-center gap-2">
          <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
        </div>
      </div>
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
      <div class="card-header d-flex justify-content-between align-items-center border-dashed">
        <h5 class="card-title text-capitalize mb-0">{{ __('List Labu Darah') }}</h5>
        <div class="d-flex align-items-center justify-content-center gap-2">
          <button data-id="" class="btn btn-sm btn-soft-info d-none fw-medium" style="font-size: 11.9px;"
            id="archive-btn-print-result" data-bs-title="Print Hasil Crossmatch" data-bs-toggle="tooltip"
            data-bs-trigger="hover">
            <i class="ti ti-printer fs-lg me-1"></i> Hasil Crossmatch
          </button>
          <button data-id="" class="btn btn-sm btn-soft-primary d-none fw-medium" style="font-size: 11.9px;"
            id="archive-btn-print-incompletter" data-bs-title="Print Surat Incompatible" data-bs-toggle="tooltip"
            data-bs-trigger="hover">
            <i class="ti ti-printer fs-4 me-1"></i> Surat Incompatible
          </button>
        </div>
      </div>
      <div class="card-body">
        @include('pages.blood-transfusion.partials.datatables.archive.list-bag-request-table')
      </div>
    </div>
  </div>
  {{-- Bag Request List :end --}}

  {{-- Test List :begin --}}
  <div class="col-xxl-5 col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center border-dashed">
        <h5 class="card-title text-capitalize mb-0">{{ __('List Pemeriksaan') }}</h5>
      </div>
      <div class="card-body">
        @include('pages.blood-transfusion.partials.datatables.archive.list-test-table')
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
      <div class="card-body archive-blood-transfusion-log-data-container overflow-auto" style="max-height: 500px;">
        <div class="timeline timeline-icon-bordered timeline-archive-blood-transfusion-log">
          {{-- Populate by JS --}}
        </div>
      </div>
      {{-- Card Body :end --}}
    </div>
  </div>
  {{-- Timeline :end --}}
</div>
@endsection

@section('scripts')
@vite([
'resources/js/pages/blood-transfusion/archive-index.js',
])
@endsection