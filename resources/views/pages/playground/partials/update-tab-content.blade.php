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
     <a href={{ route('playground.fixing.crossmatch-result.index') }} class="btn btn-soft-primary w-100" type="button">
      Masuk Halaman
     </a>
    </div>
   </div>
  </div>

  {{-- Halaman Penyesuaian Kantong Darah --}}
  <div class="col-12">
   <div class="card">
    {{-- Card Header --}}
    <div class="card-header justify-content-between align-items-center">
     <h5 class="card-title mb-0"><i class="ti ti-tool me-2 fs-4"></i> Penyesuaian Data Kantong Darah</h5>
     <div class="card-action d-flex align-items-center gap-2">
      <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
     </div>
    </div>

    {{-- Card Body --}}
    <div class="card-body">
     <a href={{ route('playground.fixing.blood-stock-data.index') }} class="btn btn-soft-primary w-100" type="button">
      Masuk Halaman
     </a>
    </div>
   </div>
  </div>
 </div>
</div>