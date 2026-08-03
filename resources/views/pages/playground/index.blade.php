@extends('layouts.vertical', ['title' => __('Dev Playground')])

@section('styles')
@endsection

@section('content')
<div class="row py-3">
 <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
  {{-- Title --}}
  <h1 class="fw-bold uppercase">{{ __('Developer Playground') }}</h1>
 </div>

 <div class="col-12">
  <div class="row">
   <!-- Navs -->
   <div class="col-sm-2 mt-2 mt-sm-0">
    <div aria-orientation="vertical" class="nav flex-column nav-pills nav-pills-primary" id="nav-tab-dev-playground"
     role="tablist">
     <!-- Testing -->
     <a aria-controls="nav-pills-testing" aria-selected="true" class="nav-link fw-semibold active show"
      data-bs-toggle="pill" href="#nav-pills-testing-left" id="nav-pills-testing-tab-left" role="tab">
      Uji Coba / Testing
     </a>
     <!-- Update -->
     <a aria-controls="nav-pills-update" aria-selected="false" class="nav-link fw-semibold" data-bs-toggle="pill"
      href="#nav-pills-update-left" id="nav-pills-update-tab-left" role="tab">
      Penyesuaian / Updates
     </a>
     <!-- Setting -->
     <a aria-controls="nav-pills-setting" aria-selected="false" class="nav-link fw-semibold" data-bs-toggle="pill"
      href="#nav-pills-setting-left" id="nav-pills-setting-tab-left" role="tab">
      Pengaturan / Settings
     </a>
    </div>
   </div>
   <!-- Contents -->
   <div class="col-sm-10">
    <div class="tab-content" id="nav-tab-dev-playground-content">
     <!-- Testing Tab -->
     <div aria-labelledby="nav-pills-testing-tab" class="tab-pane fade active show" id="nav-pills-testing-left"
      role="tabpanel">
      @include('pages.playground.partials.testing-tab-content')
     </div>
     <!-- Update Tab -->
     <div aria-labelledby="nav-pills-update-tab" class="tab-pane fade" id="nav-pills-update-left" role="tabpanel">
      @include('pages.playground.partials.update-tab-content')
     </div>
     <!-- Settings Tab -->
     <div aria-labelledby="nav-pills-setting-tab" class="tab-pane fade" id="nav-pills-setting-left" role="tabpanel">
      @include('pages.playground.partials.setting-tab-content')
     </div>
    </div>
   </div>
  </div>
 </div>
</div>
@endsection

@section('scripts')
@endsection