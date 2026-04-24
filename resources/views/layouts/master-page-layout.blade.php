@extends('layouts.vertical', ['title' => 'Master User'])

@section('styles')
@vite([
'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css',
'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css',
])
@endsection

@section('content')
<div class="row py-4">
 {{-- Title --}}
 <div class="d-flex justify-content-between align-items-start mb-1">
  <h1 class="fw-bold text-uppercase">MASTER {{ $master }}</h1>
 </div>

 {{-- Datatable :begin --}}
 <div class="col-xxl-8 col-md-6 col-12">
  {{-- Card Datatable :begin --}}
  <div class="card">
   {{-- Card Header :begin --}}
   <div class="card-header justify-content-between align-items-center">
    <h5 class="card-title text-capitalize mb-0">List Data of {{ $master }}</h5>
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
 <div class="col-xxl-4 col-md-6 col-12">
  {{-- Card Form :begin --}}
  <div class="card ">
   {{-- Card Header :begin --}}
   <div class="card-header justify-content-between align-items-center">
    <h5 class="card-title text-capitalize mb-0">Add New Data for {{ $master }}</h5>
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
 {{-- Form :end --}}
</div>
@endsection

@section('scripts')
@vite([
'resources/js/pages/master/' . $master . '/index.js',
'resources/js/pages/master/' . $master . '/datatable.js'
])
@yield('custom-scripts')
@endsection