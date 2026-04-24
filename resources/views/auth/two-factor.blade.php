@extends('layouts.vertical', ['title' => 'Two Factor Authentication'])

@section('styles')
@endsection

@section('content')
<div class="auth-box overflow-hidden align-items-center d-flex">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-4 col-md-6 col-sm-8">
                <div class="card">
                    <div class="card-body">
                        <div class="auth-brand mb-4">
                            <a class="logo-dark" href="/">
                                <span class="d-flex align-items-center gap-1">
                                    <span class="avatar avatar-xs rounded-circle text-bg-dark">
                                        <span class="avatar-title">
                                            <i class="fs-md" data-lucide="sparkles"></i>
                                        </span>
                                    </span>
                                    <span class="logo-text text-body fw-bold fs-xl">Simple</span>
                                </span>
                            </a>
                            <a class="logo-light" href="/">
                                <span class="d-flex align-items-center gap-1">
                                    <span class="avatar avatar-xs rounded-circle text-bg-dark">
                                        <span class="avatar-title">
                                            <i class="fs-md" data-lucide="sparkles"></i>
                                        </span>
                                    </span>
                                    <span class="logo-text text-white fw-bold fs-xl">Simple</span>
                                </span>
                            </a>
                            <p class="text-muted w-lg-75 mt-3">We've emailed you a 6-digit verification code we sent to
                            </p>
                        </div>
                        <div class="text-center mb-4">
                            <div class="fw-bold fs-4">+ (12) ******6789</div>
                        </div>
                        <form>
                            <label class="form-label">Enter your 6-digit code <span class="text-danger">*</span></label>
                            <div class="d-flex gap-2 mb-3 two-factor">
                                <input class="form-control text-center" required="" type="text" />
                                <input class="form-control text-center" required="" type="text" />
                                <input class="form-control text-center" required="" type="text" />
                                <input class="form-control text-center" required="" type="text" />
                                <input class="form-control text-center" required="" type="text" />
                                <input class="form-control text-center" required="" type="text" />
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-primary fw-semibold py-2" type="submit">Confirm</button>
                            </div>
                        </form>
                        <p class="mt-4 text-muted text-center mb-4">Don’t have a code? <a
                                class="text-decoration-underline link-offset-2 fw-semibold" href="#">Resend</a> or
                            <a class="text-decoration-underline link-offset-2 fw-semibold" href="#">Call Us</a>
                        </p>
                        <p class="text-muted text-center mb-0">
                            Return to <a class="text-decoration-underline link-offset-3 fw-semibold"
                                href="auth-sign-in.html">Sign in</a>
                        </p>
                    </div>
                </div>
                <p class="text-center text-muted mt-4 mb-0">
                    ©
                    <script>
                        document.write(new Date().getFullYear())
                    </script> Simple — by <span class="fw-semibold">Coderthemes</span>
                </p>
            </div>
        </div>
    </div>
</div>
<!-- end auth-fluid-->
@endsection

@section('scripts')
@vite(['resources/js/pages/auth-two-factor.js'])
@endsection