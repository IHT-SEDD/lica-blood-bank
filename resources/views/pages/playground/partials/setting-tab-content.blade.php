<div class="col-12">
 <div class="row">
  {{-- Konfigurasi api/integrasi :begin --}}
  <div class="col-xxl-4 col-md-5 col-6">
   {{-- Konfigurasi api/integrasi card :begin --}}
   <div class="card">
    {{-- Konfigurasi api/integrasi card header :begin --}}
    <div class="card-header justify-content-between align-items-center">
     <h5 class="card-title mb-0"><i class="ti ti-tool me-2 fs-4"></i> Konfigurasi API/Integrasi</h5>
     <div class="card-action d-flex align-items-center gap-2">
      <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
     </div>
    </div>
    {{-- Konfigurasi api/integrasi card header :end --}}

    {{-- Konfigurasi api/integrasi card body :begin --}}
    <div class="card-body">
     {{-- Konfigurasi halaman :begin --}}
     @include('pages.playground.config.api-integrations')
     {{-- Konfigurasi halaman :end --}}
    </div>
    {{-- Konfigurasi api/integrasi card body :end --}}
   </div>
   {{-- Konfigurasi api/integrasi card :end --}}
  </div>
  {{-- Konfigurasi api/integrasi :end --}}

  {{-- Konfigurasi LICA Blood Bank :begin --}}
  <div class="col-xxl-4 col-md-5 col-6">
   {{-- Konfigurasi LICA Blood Bank card :begin --}}
   <div class="card">
    {{-- Konfigurasi LICA Blood Bank card header :begin --}}
    <div class="card-header justify-content-between align-items-center">
     <h5 class="card-title mb-0"><i class="ti ti-settings me-2 fs-4"></i> Konfigurasi LICA Blood Bank</h5>
     <div class="card-action d-flex align-items-center gap-2">
      <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
     </div>
    </div>
    {{-- Konfigurasi LICA Blood Bank card header :end --}}

    {{-- Konfigurasi LICA Blood Bank card body :begin --}}
    <div class="card-body">
     {{-- Konfigurasi halaman :begin --}}
     @include('pages.playground.config.lica-blood-bank')
     {{-- Konfigurasi halaman :end --}}
    </div>
    {{-- Konfigurasi LICA Blood Bank card body :end --}}
   </div>
   {{-- Konfigurasi LICA Blood Bank card :end --}}
  </div>
  {{-- Konfigurasi LICA Blood Bank :end --}}
 </div>
</div>