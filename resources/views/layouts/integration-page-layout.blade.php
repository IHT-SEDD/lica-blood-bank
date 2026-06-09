@extends('layouts.vertical', ['title' => $integration])

@section('styles')
<style>
#payload-display {
    white-space: pre-wrap !important;
    font-family: monospace;
}
</style>
@endsection

@section('content')
<div class="row py-4">
 {{-- Title --}}
 <div class="d-flex justify-content-between align-items-start mb-1">
  <h1 class="fw-bold text-uppercase">{{ $integration }}</h1>
 </div>

 {{-- Datatable :begin --}}
 <div class="col-6 {{ View::hasSection('form-content') ? 'col-xxl-8 col-md-6' : '' }}">
  {{-- Card Datatable :begin --}}
  <div class=" card">
   {{-- Card Header :begin --}}
   <div class="card-header justify-content-between align-items-center">
    <h5 class="card-title text-capitalize mb-0">{{ __('List Data of Receive data') }}</h5>
    @yield('datatable-receive-data-header')
   </div>
   {{-- Card Header :end --}}

   {{-- Card Body Datatable :begin --}}
   <div class="card-body">
    @yield('datatable-receive-data')
   </div>
   {{-- Card Body Datatable :end --}}
  </div>
  {{-- Card Datatable :end --}}
 </div>
 {{-- Datatable :end --}}

  {{-- Datatable :begin --}}
 <div class="col-6 {{ View::hasSection('form-content') ? 'col-xxl-8 col-md-6' : '' }}">
  {{-- Card Datatable :begin --}}
  <div class=" card">
   {{-- Card Header :begin --}}
   <div class="card-header justify-content-between align-items-center">
    <h5 class="card-title text-capitalize mb-0">{{ __('List Data of Send Result ') }}</h5>
    @yield('datatable-send-result-header')
   </div>
   {{-- Card Header :end --}}

   {{-- Card Body Datatable :begin --}}
   <div class="card-body">
    @yield('datatable-send-result')
   </div>
   {{-- Card Body Datatable :end --}}
  </div>
  {{-- Card Datatable :end --}}
 </div>
 {{-- Datatable :end --}}


</div>

@yield('modal-content')
@endsection

@section('scripts')
@vite([
'resources/js/pages/integration/' . $integrationJS . '/index.js',
'resources/js/pages/integration/' . $integrationJS . '/datatable.js'
])
@yield('custom-scripts')
@endsection