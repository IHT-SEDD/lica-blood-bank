@php($mfaEnabled = \App\Models\Setting::getBool('mfa_enabled', true))

{{-- MFA Authentication card :begin --}}
<div class="card mb-0">
 {{-- MFA Authentication card header :begin --}}
 <div class="card-header justify-content-between align-items-center">
  <h5 class="card-title mb-0">
   <i class="ti ti-settings me-2 fs-4"></i>
   {{ 'Multi Faktor Autentikasi' }}
   <span class="badge {{ $mfaEnabled ? 'bg-success' : 'bg-secondary' }}">
    {{ $mfaEnabled ? 'Aktif' : 'Nonaktif' }}
   </span>
  </h5>
  <div class="card-action d-flex align-items-center gap-2">
   <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
  </div>
 </div>
 {{-- MFA Authentication card header :end --}}

 {{-- MFA Authentication card body :begin --}}
 <div class="card-body">
  <form action="{{ route('playground.setting.config.application.update-mfa') }}" method="POST" @if ($mfaEnabled)
   onsubmit="return confirm('Yakin ingin mematikan MFA untuk LICA Blood Bank?')" @endif>
   @csrf
   @method('PATCH')
   <input name="enabled" type="hidden" value="{{ $mfaEnabled ? 0 : 1 }}">
   <button class="btn btn-sm w-100 {{ $mfaEnabled ? 'btn-danger' : 'btn-primary' }}" type="submit">
    {{ $mfaEnabled ? 'Matikan' : 'Aktifkan' }}
   </button>
  </form>
 </div>
 {{-- MFA Authentication card body :end --}}

 {{-- MFA Authentication card footer :begin --}}
 <div class="card-footer">
  <small>{{ 'Aktifkan atau matikan multi faktor autentikasi pada LICA Blood Bank' }}</small>
 </div>
 {{-- MFA Authentication card footer :end --}}
</div>
{{-- MFA Authentication card :end --}}