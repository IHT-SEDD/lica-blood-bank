@extends('layouts.vertical', ['title' => "Master $master"])

@section('styles')
@endsection

@section('content')
<div class="row py-4">
 {{-- Title --}}
 <div class="d-flex justify-content-between align-items-start mb-1">
  <h1 class="fw-bold text-uppercase">MASTER {{ $master }}</h1>
 </div>

 {{-- Datatable :begin --}}
 <div class="col-12 {{ View::hasSection('form-content') ? 'col-xxl-8 col-md-6' : '' }}">
  {{-- Card Datatable :begin --}}
  <div class=" card">
   {{-- Card Header :begin --}}
   <div class="card-header justify-content-between align-items-center">
    <h5 class="card-title text-capitalize mb-0">{{ __('List Data of :master', ['master' => $master]) }}</h5>
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
    <h5 class="card-title text-capitalize mb-0">{{ __('Add New Data for :master', ['master' => $master]) }}</h5>
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
'resources/js/pages/master/' . $masterJS . '/index.js',
'resources/js/pages/master/' . $masterJS . '/datatable.js'
])
@yield('custom-scripts')
@endsection