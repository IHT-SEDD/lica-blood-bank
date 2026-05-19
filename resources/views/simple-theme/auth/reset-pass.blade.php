@extends('layouts.base', ['title' => 'Reset Password'])

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
                            <p class="text-muted w-lg-75 mt-3">Enter your email address and we'll send you a link to
                                reset your password.</p>
                        </div>
                        <form>
                            <div class="mb-3">
                                <label class="form-label" for="userEmail">Email address <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input class="form-control" id="userEmail" placeholder="you@example.com" required=""
                                        type="email" />
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input form-check-input-light fs-14" id="termAndPolicy"
                                        type="checkbox" />
                                    <label class="form-check-label" for="termAndPolicy">Agree the Terms &amp;
                                        Policy</label>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-primary fw-semibold py-2" type="submit">Send Request</button>
                            </div>
                        </form>
                        <p class="text-muted text-center mt-4 mb-0">
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