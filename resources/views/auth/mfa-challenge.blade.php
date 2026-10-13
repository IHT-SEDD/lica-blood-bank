@extends('layouts.base', ['title' => 'MFA Enrollment'])

@section('content')
<div class="auth-box overflow-hidden align-items-center d-flex">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xxl-4 col-md-6 col-sm-8">
        {{-- MFA Card :begin --}}
        <div class="card">
          {{-- MFA Card Body :begin --}}
          <div class="card-body">
            {{-- Header MFA :begin --}}
            <div class="auth-brand mb-4">
              {{-- MFA Logo Dark --}}
              <a class="logo-dark" href="/">
                <span class="d-flex align-items-center gap-1">
                  <img alt="Logo LICA Blood Bank" class="img-center"
                    src="{{ asset('assets/images/logos/logo-lica-bb.png') }}" style="width: 40%;" />
                </span>
              </a>

              {{-- MFA Logo Light --}}
              <a class="logo-light" href="/">
                <span class="d-flex align-items-center gap-1">
                  <img alt="Logo LICA Blood Bank" class="img-center"
                    src="{{ asset('assets/images/logos/logo-lica-bb.png') }}" style="width: 40%;" />
                </span>
              </a>

              {{-- MFA Subtitle --}}
              <p class="text-muted w-lg-75 mt-3">
                {{ __('Masukkan kode 6 digit dari aplikasi authenticator Anda.') }}
              </p>
            </div>
            {{-- Header MFA :end --}}

            <form method="POST" action="{{ route('mfa.verify') }}">
              @csrf
              <label class="form-label" for="code">{{ __('Kode MFA') }}
                <span class="text-danger">*</span>
              </label>
              <input autocomplete="one-time-code" autofocus class="form-control @error('code') is-invalid @enderror"
                id="code" inputmode="numeric" maxlength="6" name="code" pattern="[0-9]*"
                placeholder="{{ __('Masukkan kode 6 digit') }}" required type="text" />
              @error('code')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="d-grid mt-3">
                <button class="btn btn-primary fw-semibold py-2" type="submit">{{ __('Verifikasi') }}</button>
              </div>
            </form>

            {{-- Logout :begin --}}
            <form action="{{ route('logout') }}" class="text-center mt-3" method="POST">
              @csrf
              <button class="btn btn-link text-muted p-0" type="submit">{{ __('Logout') }}</button>
            </form>
            {{-- Logout :end --}}
          </div>
          {{-- MFA Card Body :end --}}
        </div>
        {{-- MFA Card :end --}}

        {{-- Copyright :begin --}}
        <p class="text-center text-muted mt-4 mb-0">
          ©
          <script>
            document.write(new Date().getFullYear())
          </script> LICA Blood Bank — by <span class="fw-semibold">ISOLA</span>
        </p>
        {{-- Copyright :end --}}
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
@endsection