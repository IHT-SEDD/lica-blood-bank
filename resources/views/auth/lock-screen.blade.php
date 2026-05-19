@extends('layouts.base', ['title' => 'Lock Screen'])

@section('content')
{{-- Lock Screen :begin --}}
<div class="auth-box overflow-hidden align-items-center d-flex">
    {{-- Container :begin --}}
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-4 col-md-6 col-sm-8">
                {{-- Card Form :begin --}}
                <div class="card">
                    {{-- Card Form Body :begin --}}
                    <div class="card-body">
                        {{-- Logo & Subtitle :begin --}}
                        <div class="auth-brand mb-4">
                            {{-- Login Logo Dark --}}
                            <a class="logo-dark" href="/">
                                <span class="d-flex align-items-center gap-1">
                                    <img alt="Logo LICA Blood Bank" class="img-center"
                                        src="{{ asset('assets/images/logos/logo-lica-bb.png') }}" style="width: 40%;" />
                                </span>
                            </a>

                            {{-- Login Logo Light --}}
                            <a class="logo-light" href="/">
                                <span class="d-flex align-items-center gap-1">
                                    <img alt="Logo LICA Blood Bank" class="img-center"
                                        src="{{ asset('assets/images/logos/logo-lica-bb.png') }}" style="width: 40%;" />
                                </span>
                            </a>

                            <p class="text-muted w-lg-75 mt-3">
                                {{ __('This screen is locked. Enter your password to continue') }}
                            </p>
                        </div>
                        {{-- Logo & Subtitle :end --}}

                        {{-- User name & Role :begin --}}
                        <div class="text-center mb-4">
                            <img alt="Profile Icon" class="mb-2" src="{{ asset('assets/images/profile.png') }}"
                                width="20%" />
                            <span>
                                <h5 class="my-0 text-uppercase fw-semibold">{{ Auth::user()->name }}</h5>
                                <h6 class="my-0 text-capitalize text-muted">{{ Auth::user()->getRoleNames()->first() }}
                                </h6>
                            </span>
                        </div>
                        {{-- User name & Role :end --}}

                        {{-- Form :begin --}}
                        <form method="POST" action="{{ route('unlock') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="password">{{ __('Password') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input class="form-control" id="password" name="password" placeholder="••••••••"
                                        required="" type="password" />
                                </div>
                                @error('password')
                                <small class="text-danger fw-medium my-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button class="btn btn-primary fw-semibold py-2" type="submit">
                                    {{ __('Unlock') }}
                                </button>
                            </div>
                        </form>
                        {{-- Form :end --}}

                        {{-- Login Button --}}
                        <p class="text-muted text-center mt-4 mb-0" data-lang="lock_screen_login_link_label">
                            {{ __('Not You') }}? {{ __('Return To') }}
                            <a class="text-decoration-underline link-offset-3 fw-semibold" href="{{ route('login') }}">
                                {{ __('Login') }}
                            </a>
                        </p>
                    </div>
                    {{-- Card Form Body :end --}}
                </div>
                {{-- Card Form :end --}}

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
    {{-- Container :end --}}
</div>
{{-- Lock Screen :end --}}
@endsection