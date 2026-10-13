{{-- EMSIFA ::begin --}}
<div class="col">
  {{-- Card ::begin --}}
  <div class="card card-hovered" id="emsifa-card" data-url="{{ url('api/v1/emsifa/check-connection') }}">
    {{-- Card body ::begin --}}
    <div class="card-body">
      {{-- Header ::begin --}}
      <div class="d-flex align-items-center mb-3">
        {{-- Icon ::begin --}}
        <div class="me-3 position-relative">
          <div class="avatar-lg mx-auto">
            <span class="avatar-title text-bg-primary rounded-circle"><i class="ti ti-map-2 fs-3"></i></span>
          </div>
        </div>
        {{-- Icon ::end --}}

        <div>
          {{-- Title ::begin --}}
          <h5 class="mb-1 d-flex align-items-center">
            <a class="link-reset fw-medium fs-md" href="#!">EMSIFA</a>
          </h5>
          {{-- Title ::end --}}
          {{-- Description --}}
          <p class="text-muted mb-1">API Statis Wilayah Indonesia</p>
          {{-- Status --}}
          <span class="badge bg-secondary-subtle text-secondary fw-semibold fs-7"
            id="connection_status">Checking...</span>
        </div>
      </div>
      {{-- Header ::end --}}

      {{-- Description api ::begin --}}
      <p class="text-muted mb-2">
        EMSIFA adalah developer yang menyediakan API statis untuk data wilayah Indonesia, termasuk provinsi,
        kabupaten/kota,
        kecamatan, dan desa. API ini memungkinkan pengembang untuk mengakses data wilayah secara mudah dan cepat.
      </p>
      {{-- Description api ::end --}}

      {{-- CTA ::begin --}}
      <a class="icon-link icon-link-hover link-reset fw-medium" href="#!">
        View Detail <i class="bi fs-16 ti ti-arrow-narrow-right"></i>
      </a>
      {{-- CTA ::end --}}
    </div>
    {{-- Card body ::end --}}

    {{-- Card footer ::begin --}}
    <div class="card-footer">
      <div class="row text-center">
        <div class="col-3">
          <div class="text-muted fs-xs mb-1">Endpoints</div>
          <div class="fw-bold" id="emsifa_total_endpoints">-</div>
        </div>
        <div class="col-3">
          <div class="text-muted fs-xs mb-1">Latency</div>
          <div class="fw-bold" id="emsifa_latency">-</div>
        </div>
        <div class="col-3">
          <div class="text-muted fs-xs mb-1">Success Rate</div>
          <div class="fw-bold" id="emsifa_success_rate">-</div>
        </div>
        <div class="col-3">
          <div class="text-muted fs-xs mb-1">Provinsi</div>
          <div class="fw-bold" id="emsifa_total_provinces">-</div>
        </div>
      </div>
    </div>
    {{-- Card footer ::end --}}
  </div>
  {{-- Card ::end --}}
</div>
{{-- EMSIFA ::end --}}