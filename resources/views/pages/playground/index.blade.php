@extends('layouts.vertical', ['title' => __('Dev Playground')])

@section('styles')
@endsection

@section('content')
<div class="row py-3">
 <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
  {{-- Title --}}
  <h1 class="fw-bold uppercase">{{ __('Developer Playground') }}</h1>
 </div>

 {{-- Kolom Uji Coba--}}
 <div class="col-xxl-3 col-md-4 col-6">
  <div class="row">
   {{-- Halaman Uji Coba Print --}}
   <div class="col-12">
    <div class="card">
     {{-- Card Header --}}
     <div class="card-header justify-content-between align-items-center">
      <h5 class="card-title mb-0"><i class="ti ti-flask me-2 fs-4"></i> Uji Coba Print</h5>
      <div class="card-action d-flex align-items-center gap-2">
       <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
      </div>
     </div>

     {{-- Card Body --}}
     <div class="card-body">
      <a href={{ route('playground.print.index') }} class="btn btn-primary w-100" type="button">
       Masuk Halaman
      </a>
     </div>
    </div>
   </div>

   {{-- Halaman Uji Coba API --}}
   <div class="col-12">
    <div class="card">
     {{-- Card Header --}}
     <div class="card-header justify-content-between align-items-center">
      <h5 class="card-title mb-0"><i class="ti ti-flask me-2 fs-4"></i> Testing API (Send Result)</h5>
      <div class="card-action d-flex align-items-center gap-2">
       <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
      </div>
     </div>

     {{-- Card Body --}}
     <div class="card-body">
      <form action="{{ url('api/v1/blood-transfusion/send-result') }}" method="post">
       @csrf
       <label class="form-label mb-2" for="order-number">{{ __('Order Number') }}</label>
       <input autocomplete="off" class="form-control mb-2" id="order-number" name="order_number" type="text"
        placeholder="Order Number" />
       <button class="btn btn-primary w-100" type="submit">Send Result</button>
      </form>
     </div>
    </div>
   </div>
  </div>

 </div>

 {{-- Kolom Perbaikan --}}
 <div class="col-xxl-3 col-md-4 col-6">
  <div class="row">
   {{-- Halaman Perbaikan Hasil Crossmatch --}}
   <div class="col-12">
    <div class="card">
     {{-- Card Header --}}
     <div class="card-header justify-content-between align-items-center">
      <h5 class="card-title mb-0"><i class="ti ti-tool me-2 fs-4"></i> Perbaikan Crossmatch Result</h5>
      <div class="card-action d-flex align-items-center gap-2">
       <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
      </div>
     </div>

     {{-- Card Body --}}
     <div class="card-body">
      <a href={{ route('playground.fixing.crossmatch-result.index') }} class="btn btn-soft-info w-100" type="button">
       Masuk Halaman
      </a>
     </div>
    </div>
   </div>
  </div>
 </div>
</div>
@endsection

@section('scripts')
@endsection