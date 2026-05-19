@extends('layouts.base', ['title' => 'Sign In'])

@section('content')
{{-- Full Screen Loading Overlay :begin --}}
<div id="fullscreen_loading_overlay" style="
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0, 0, 0, 0.45);
        backdrop-filter: blur(2px);
    " class="d-flex align-items-center justify-content-center d-none">
    <div class="text-center text-white">
        <div class="spinner-border avatar-lg text-primary mb-2" role="status"></div>
        <p class="fw-semibold fs-5 mb-0">{{ __('Processing') }}</p>
    </div>
</div>
{{-- Full Screen Loading Overlay :end --}}

<div class="auth-box overflow-hidden align-items-center d-flex">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-4 col-md-6 col-sm-8">
                {{-- Login Card :begin --}}
                <div class="card">
                    {{-- Login Card Body :begin --}}
                    <div class="card-body">
                        {{-- Header Login :begin --}}
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

                            {{-- Login Subtitle --}}
                            <p class="text-muted w-lg-75 mt-3">{{ __('Enter your username and password to continue.') }}</p>
                        </div>
                        {{-- Header Login :end --}}

                        {{-- Login Form :begin --}}
                        <form autocomplete="off" id="login_user">
                            {{-- Username Input --}}
                            <div class="mb-3">
                                <label class="form-label" for="username">{{ __('Username') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input autocomplete="off" class="form-control" id="username" name="username"
                                        placeholder="Your {{ __('Username') }}" type="text" />
                                </div>
                            </div>

                            {{-- Password Input --}}
                            <div class="mb-3">
                                <label class="form-label" for="password">{{ __('Password') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group" data-password="">
                                    <input autocomplete="new-password" class="form-control form-password" id="password"
                                        name="password" placeholder="••••••••" type="password" />
                                    <button class="btn btn-primary btn-icon" type="button">
                                        <i class="ti ti-eye password-eye-on"></i>
                                        <i class="ti ti-eye-closed d-none password-eye-off"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                {{-- Remember Me --}}
                                <div class="form-check">
                                    <input class="form-check-input form-check-input-light fs-14" id="remember_me"
                                        type="checkbox" name="remember" />
                                    <label class="form-check-label" for="remember_me">
                                        {{ __('Keep me signed in') }}
                                    </label>
                                </div>

                                {{-- Uncomment this if need forgot password --}}
                                {{-- Forgot Password --}}
                                {{-- @if (Route::has('password.request'))
                                <a class="text-decoration-underline link-offset-3 text-muted"
                                    href="{{ route('password.request') }}" >
                                    {{ __('Forgot Password') }}?
                                </a>
                                @endif --}}
                            </div>

                            {{-- Submit Button --}}
                            <div class="d-grid">
                                <button class="btn btn-primary fw-semibold py-2" type="submit">
                                    {{ __('Sign In') }}
                                </button>
                            </div>
                        </form>

                        {{-- Uncomment this if need register account --}}
                        {{-- <p class="text-muted text-center mt-4 mb-0">
                            {{ __('New Here') }}?
                            <a class="text-decoration-underline link-offset-3 fw-semibold"
                                href="{{ route('register') }}" >
                                {{ __('Create an Account') }}
                            </a>
                        </p> --}}
                        {{-- Login Form :end --}}
                    </div>
                    {{-- Login Card Body :end --}}
                </div>
                {{-- Login Card :end --}}

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
@vite(['resources/js/pages/auth/sign-in.js'])
@endsection