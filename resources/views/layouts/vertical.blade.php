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
                @yield('content')
            </div>
            @include('layouts.shared/footer')
        </div>
    </div>

    @include('layouts.shared/customizer')
    @include('layouts.shared/footer-scripts')
</body>

</html>