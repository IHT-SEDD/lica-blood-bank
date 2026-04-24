@extends('layouts.base', ['title' => 'Sign Up'])

@section('content')
<div class="auth-box overflow-hidden align-items-center d-flex">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-4 col-md-6 col-sm-8">
                {{-- Register Card :begin --}}
                <div class="card">
                    {{-- Register Card Body :begin --}}
                    <div class="card-body">
                        {{-- Header Register :begin --}}
                        <div class="auth-brand mb-4">
                            {{-- Register Logo Dark --}}
                            <a class="logo-dark" href="/">
                                <span class="d-flex align-items-center gap-1">
                                    <img alt="Logo LICA Blood Bank" class="img-center"
                                        src="{{ asset('assets/images/logos/logo-lica-bb.png') }}" style="width: 40%;" />
                                </span>
                            </a>

                            {{-- Register Logo Light --}}
                            <a class="logo-light" href="/">
                                <span class="d-flex align-items-center gap-1">
                                    <img alt="Logo LICA Blood Bank" class="img-center"
                                        src="{{ asset('assets/images/logos/logo-lica-bb.png') }}" style="width: 40%;" />
                                </span>
                            </a>

                            {{-- Register Subtitle --}}
                            <p class="text-muted w-lg-75 mt-3">Create your account by entering your details below.</p>
                        </div>
                        {{-- Header Register :end --}}

                        {{-- Register Form :begin --}}
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            {{-- Name Input --}}
                            <div class="mb-3">
                                <label class="form-label" for="name">Name
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input class="form-control" id="name" name="name" placeholder="Your full name"
                                        required="" type="text" />
                                </div>
                            </div>

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

                            {{-- Email Input --}}
                            <div class="mb-3">
                                <label class="form-label" for="email">Email Address</label>
                                <div class="input-group">
                                    <input class="form-control" id="email" name="email" placeholder="your@mail.com"
                                        type="email" />
                                </div>
                            </div>

                            {{-- Password Input --}}
                            <div class="mb-3" data-password="bar">
                                <label class="form-label" for="password">Password
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input class="form-control" id="password" name="password" placeholder="••••••••"
                                        required="" type="password" />
                                </div>
                                <div class="password-bar my-2"></div>
                                <p class="text-muted fs-xs mb-0">
                                    Use 8+ characters with letters, numbers &amp; symbols.
                                </p>
                            </div>

                            {{-- Password Confirmation Input --}}
                            <div class="mb-3">
                                <label class="form-label" for="password_confirmation">Password Confirmation
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input class="form-control" id="password_confirmation" name="password_confirmation"
                                        placeholder="••••••••" required="" type="password" />
                                </div>
                                <p class="text-muted fs-xs my-2">
                                    Re-input your password for confirmation.
                                </p>
                            </div>

                            {{-- Submit Button --}}
                            <div class="d-grid">
                                <button class="btn btn-primary fw-semibold py-2" type="submit">Create Account</button>
                            </div>
                        </form>
                        {{-- Register Form :end --}}

                        {{-- Already have and account? --}}
                        <p class="text-muted text-center mt-4 mb-0">
                            Already have an account?
                            <a class="text-decoration-underline link-offset-3 fw-semibold" href="{{ route('login') }}">
                                Login
                            </a>
                        </p>
                    </div>
                    {{-- Register Card Body :end --}}
                </div>
                {{-- Register Card :end --}}

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
<!-- end auth-fluid-->
@endsection

@section('scripts')
@vite(['resources/js/pages/auth-password.js'])
@endsection