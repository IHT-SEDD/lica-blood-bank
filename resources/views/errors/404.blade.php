@extends('layouts.base', ['title' => '404 Error'])

@section('styles')
@endsection

@section('content')
<div class="auth-box overflow-hidden align-items-center d-flex">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-4 col-md-6 col-sm-8">
                <div class="card">
                    <div class="card-body">
                        <div class="p-2 text-center">
                            <div class="text-error fw-bold fs-60">404</div>
                            <h3 class="fw-semibold" data-lang="page_not_found">Page Not Found</h3>
                            <p class="text-muted" data-lang="page_not_found_description">The page you’re looking for
                                doesn’t exist or has been moved.</p>
                            <button class="btn btn-primary mt-3 rounded-pill" onclick="window.location.href='/'"
                                data-lang="go_home_link">
                                Go Home
                            </button>
                        </div>
                    </div>
                </div>
                <p class="text-center text-muted mt-4 mb-0">
                    ©
                    <script>
                        document.write(new Date().getFullYear())
                    </script> LICA Blood Bank — by <span class="fw-semibold">ISOLA</span>
                </p>
            </div>
        </div>
    </div>
</div>
<!-- end auth-fluid-->
@endsection