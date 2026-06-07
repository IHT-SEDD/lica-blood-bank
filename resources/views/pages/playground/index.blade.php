@extends('layouts.vertical', ['title' => __('Dev Playground')])

@section('styles')
@endsection

@section('content')
<div class="row py-3">
 <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
  {{-- Title --}}
  <h1 class="fw-bold uppercase">{{ __('Developer Playground') }}</h1>
 </div>

 {{-- Halaman Uji Coba Print --}}
 <div class="col-xxl-3 col-md-4 col-6">
  <div class="card">
   {{-- Card Header --}}
   <div class="card-header justify-content-between align-items-center">
    <h5 class="card-title mb-0">Uji Coba Print</h5>
    <div class="card-action d-flex align-items-center gap-2">
     <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
    </div>
   </div>

   {{-- Card Body --}}
   <div class="card-body">
    <a href={{ route('playground.print.index') }} class="btn btn-soft-secondary w-100" type="button">
     Masuk Halaman
    </a>
   </div>
  </div>
 </div>
</div>
@endsection

@section('scripts')
@endsection