<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.shared.title-meta')
    @yield('styles')
    @include('layouts.shared/head-css')
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">
        @include('layouts.shared/menu')

        <!-- ============================================================== -->
        <!-- Start Main Content -->
        <!-- ============================================================== -->
        <div class="content-page">
            <div class="container-fluid">
                {{-- Full Screen Loading Overlay :begin --}}
                <div id="fullscreen_loading_overlay" style="position: fixed; inset: 0; z-index: 9999; background: rgba(0, 0, 0, 0.45); backdrop-filter: blur(2px);" class="d-flex align-items-center justify-content-center d-none">
                    <div class="text-center text-white">
                        <div class="spinner-border avatar-lg text-primary mb-2" role="status"></div>
                        <p class="fw-semibold fs-5 mb-0">{{ __('Processing') }}...</p>
                    </div>
                </div>
                {{-- Full Screen Loading Overlay :end --}}
                
                @yield('content')
            </div>
            @include('layouts.shared/footer')
        </div>
    </div>

    @include('layouts.shared/customizer')
    @include('layouts.shared/footer-scripts')
</body>

</html>