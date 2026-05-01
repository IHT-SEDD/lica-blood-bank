@extends('layouts.base', ['title' => 'Sign In'])

@section('content')
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
                            <p class="text-muted w-lg-75 mt-3">Enter your username and password to continue.</p>
                        </div>
                        {{-- Header Login :end --}}

                        {{-- Login Form :begin --}}
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            {{-- Username Input --}}
                            <div class="mb-3">
                                <label class="form-label" for="username">Username
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input class="form-control" id="username" name="username"
                                        placeholder="Your username" required="" type="text" />
                                </div>
                            </div>

                            {{-- Password Input --}}
                            <div class="mb-3">
                                <label class="form-label" for="password">Password
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input class="form-control" id="password" name="password" placeholder="••••••••"
                                        required="" type="password" />
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                {{-- Remember Me --}}
                                <div class="form-check">
                                    <input class="form-check-input form-check-input-light fs-14" id="remember_me"
                                        type="checkbox" name="remember" />
                                    <label class="form-check-label" for="remember_me">Keep me signed in</label>
                                </div>

                                {{-- Uncomment this if need forgot password --}}
                                {{-- Forgot Password --}}
                                {{-- @if (Route::has('password.request'))
                                <a class="text-decoration-underline link-offset-3 text-muted"
                                    href="{{ route('password.request') }}">Forgot Password?</a>
                                @endif --}}
                            </div>

                            {{-- Submit Button --}}
                            <div class="d-grid">
                                <button class="btn btn-primary fw-semibold py-2" type="submit">Sign In</button>
                            </div>
                        </form>

                        {{-- Uncomment this if need register account --}}
                        {{-- <p class="text-muted text-center mt-4 mb-0">
                            New here?
                            <a class="text-decoration-underline link-offset-3 fw-semibold"
                                href="{{ route('register') }}">
                                Create an account
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