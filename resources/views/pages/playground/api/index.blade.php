@extends('layouts.vertical', ['title' => __('API Playground')])

@section('styles')
@endsection

@section('content')
<div class="row py-3">
 <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
  {{-- Title --}}
  <h1 class="fw-bold uppercase">{{ __('API Playground') }}</h1>
 </div>

 {{-- Content ::begin --}}
 <div class="row row-cols-xxl-4 row-cols-md-2 row-cols-1">
  {{-- Endpoint card --}}
  @include('pages.playground.api.partials.endpoints')
 </div>
 {{-- Content ::end --}}
</div>
@endsection

@section('scripts')
@vite('resources/js/pages/playground/api/emsifa/index.js')
@endsection