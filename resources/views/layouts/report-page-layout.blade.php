@extends('layouts.vertical', ['title' => "Laporan $report"])

@section('styles')
@endsection

@section('content')
<div class="row py-4">
 {{-- Title & Subtitle--}}
 <div class="row justify-content-center pb-2">
  <div class="col-12 text-center">
   <!-- Title & Icon -->
   <span class="badge badge-default fw-normal shadow px-2 py-1 mb-2 fst-italic fs-xxs">
    <i data-lucide="file-text" class="fs-sm me-1"></i> Reports
   </span>
   <h3 class="fw-bold text-uppercase">
    LAPORAN {{ $report }}
   </h3>

   <!-- Subtitle atau deskripsi report -->
   <p class="fs-sm text-muted mb-0">
    {{ $descriptionReport }}
   </p>
  </div>
 </div>

 {{-- Pro Tips--}}
 @if(View::hasSection('pro-tip'))
 <div class="row align-items-center justify-content-center pb-2">
  <div class="col-12 d-flex flex-md-row flex-column align-items-center gap-2 justify-content-md-end justify-content-center">
   <!-- Title & Icon -->
   <span class="badge badge-default fw-normal shadow px-2 py-1 mb-0 fst-italic fs-xxs">
    <i data-lucide="lightbulb" class="fs-sm me-1"></i> Pro Tip!
   </span>
   <h5 class="fw-medium mb-0">
    @yield('pro-tip')
   </h5>
  </div>
 </div>
 @endif

 {{-- Datatable :begin --}}
 <div class="col-12 {{ View::hasSection('form-content') ? 'col-xxl-8 col-md-6' : '' }}">
  {{-- Card Datatable :begin --}}
  <div class=" card">
   {{-- Card Header :begin --}}
   <div class="card-header justify-content-between align-items-center">
    <h5 class="card-title text-capitalize mb-0">{{ __('List Data :report', ['report' => $report]) }}</h5>
    @yield('datatable-header')
   </div>
   {{-- Card Header :end --}}

   {{-- Card Body Datatable :begin --}}
   <div class="card-body">
    @yield('datatable-content')
   </div>
   {{-- Card Body Datatable :end --}}
  </div>
  {{-- Card Datatable :end --}}
 </div>
 {{-- Datatable :end --}}

 {{-- Form :begin --}}
 @if(View::hasSection('form-content'))
 <div class="col-xxl-4 col-md-6 col-12">
  {{-- Card Form :begin --}}
  <div class="card ">
   {{-- Card Header :begin --}}
   <div class="card-header justify-content-between align-items-center">
    <h5 class="card-title text-capitalize mb-0">{{ __('Add New Data for :report', ['report' => $report]) }}</h5>
    @yield('form-header')
   </div>
   {{-- Card Header :end --}}

   {{-- Card Body Form :begin --}}
   <div class="card-body">
    @yield('form-content')
   </div>
   {{-- Card Body Form :end --}}
  </div>
  {{-- Card Form :end --}}
 </div>
 @endif
 {{-- Form :end --}}
</div>

@yield('modal-content')
@endsection

@section('scripts')
@vite([
'resources/js/pages/report/' . $reportJS . '/index.js',
'resources/js/pages/report/' . $reportJS . '/datatable.js'
])
@yield('custom-scripts')
@endsection