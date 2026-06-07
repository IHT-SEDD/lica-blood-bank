@extends('layouts.vertical', ['title' => __('Print Test')])

@section('styles')
@endsection

@section('content')
<div class="row py-3">
 <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
  <a href="{{ route('playground.index') }}" class="btn btn-sm btn-soft-primary">
   <i class="ti ti-arrow-left-dashed fs-lg align-middle flex-shrink-0 me-1"></i>
   {{ __('Keluar') }}
  </a>

  {{-- Title --}}
  <h1 class="fw-bold uppercase">{{ __('Uji Coba Print') }}</h1>
 </div>

 {{-- Incompatible Letter --}}
 <div class="col-xxl-3 col-md-4 col-6">
  <div class="card">
   {{-- Card Header --}}
   <div class="card-header justify-content-between align-items-center">
    <h5 class="card-title mb-0">Incompatible Letter Preview</h5>
    <div class="card-action d-flex align-items-center gap-2">
     <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
    </div>
   </div>

   {{-- Card Body --}}
   <div class="card-body">
    <button class="btn btn-soft-secondary w-100" type="button" id="print_preview_btn" data-print="incompatible-letter"
     data-preview-url="{{ route('playground.print.preview', ':print') }}">
     Print Preview
    </button>
   </div>
  </div>
 </div>

 {{-- Crossmatch Result --}}
 <div class="col-xxl-3 col-md-4 col-6">
  <div class="card">
   {{-- Card Header --}}
   <div class="card-header justify-content-between align-items-center">
    <h5 class="card-title mb-0">Crossmatch Result Preview</h5>
    <div class="card-action d-flex align-items-center gap-2">
     <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
    </div>
   </div>

   {{-- Card Body --}}
   <div class="card-body">
    <button class="btn btn-soft-secondary w-100" type="button" id="print_preview_btn" data-print="crossmatch-result"
     data-preview-url="{{ route('playground.print.preview', ':print') }}">
     Print Preview
    </button>
   </div>
  </div>
 </div>

 {{-- Blood Patient Card --}}
 <div class="col-xxl-3 col-md-4 col-6">
  <div class="card">
   {{-- Card Header --}}
   <div class="card-header justify-content-between align-items-center">
    <h5 class="card-title mb-0">Blood Patient Card Preview</h5>
    <div class="card-action d-flex align-items-center gap-2">
     <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
    </div>
   </div>

   {{-- Card Body --}}
   <div class="card-body">
    <button class="btn btn-soft-secondary w-100" type="button" id="print_preview_btn" data-print="blood-patient-card"
     data-preview-url="{{ route('playground.print.preview', ':print') }}">
     Print Preview
    </button>
   </div>
  </div>
 </div>

 {{-- Nota --}}
 <div class="col-xxl-3 col-md-4 col-6">
  <div class="card">
   {{-- Card Header --}}
   <div class="card-header justify-content-between align-items-center">
    <h5 class="card-title mb-0">Nota Transaction</h5>
    <div class="card-action d-flex align-items-center gap-2">
     <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
    </div>
   </div>

   {{-- Card Body --}}
   <div class="card-body">
    <button class="btn btn-soft-secondary w-100" type="button" id="print_preview_btn" data-print="nota-transaction"
     data-preview-url="{{ route('playground.print.preview', ':print') }}">
     Print Preview
    </button>
   </div>
  </div>
 </div>
</div>
@endsection

@section('scripts')
@vite([
'resources/js/pages/playground/print-test/index.js',
])
@endsection