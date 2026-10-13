{{-- Komponen darah card :begin --}}
<div class="card mb-0">
 {{-- Komponen darah card header :begin --}}
 <div class="card-header justify-content-between align-items-center">
  <h5 class="card-title mb-0">
   <i class="ti ti-tool me-2 fs-4"></i>
   {{ 'Komponen Darah' }}
  </h5>
  <div class="card-action d-flex align-items-center gap-2">
   <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
  </div>
 </div>
 {{-- Komponen darah card header :end --}}

 {{-- Komponen darah card body :begin --}}
 <div class="card-body">
  <a href={{ route('playground.setting.config.blood-component.index') }} class="btn btn-sm btn-primary" type="button">
   Masuk Halaman
  </a>
 </div>
 {{-- Komponen darah card body :end --}}

 {{-- Komponen darah card footer :begin --}}
 <div class="card-footer">
  <small>{{ 'Konfigurasi komponen darah untuk integrasi API' }}</small>
 </div>
 {{-- Komponen darah card footer :end --}}
</div>
{{-- Komponen darah card :end --}}