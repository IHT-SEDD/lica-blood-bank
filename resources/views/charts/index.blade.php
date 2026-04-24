@extends('layouts.vertical', ['title' => 'Area Charts'])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Charts &amp; Graphs',
        'subTitle' =>
            'Visualize data with interactive and responsive charts powered by Chart.js — including bar, line, pie, and more.',
        'badgeIcon' => 'chart-bar',
        'badgeTitle' => 'Chart.js Visuals',
    ])

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Basic Area</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="basic-area-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Different Dataset</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="different-dataset-area-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Stacked</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="stacked-area-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Boundaries</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="boundaries-area-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Draw Time</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="draw-time-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Radar</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="radar-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
    </div>
    <!-- end row-->
    <h4 class="mb-3 fw-bold">Bar Charts</h4>
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Basic Bar</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="basic-bar-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Border Radius</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="border-radius-bar-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Floating</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="floating-bar-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Horizontal</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="horizontal-bar-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Stacked</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="stacked-bar-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Stacked with Groups</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="stacked-groups-bar-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Vertical</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="vertical-bar-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
    </div>
    <!-- end row-->
    <h4 class="mb-3 fw-bold">Line Charts</h4>
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Basic Line</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="basic-line-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Interpolation</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="interpolation-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Multi-Axes</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="multi-axes-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Point Styling</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="point-styling-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Line Segment</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="line-segment-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Stepped</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="stepped-line-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
    </div>
    <!-- end row-->
    <h4 class="mb-3 fw-bold">Other Charts</h4>
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Bubble</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="bubble-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Combo Bar &amp; Line</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="combo-bar-line-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Stacked Bar &amp; Line</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="stacked-bar-line-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Doughnut</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="doughnut-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Multi Series Pie</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="multi-pie-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Pie</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="pie-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Polar Area</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="polar-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Scatter</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div class="mt-3" style="height: 300px;">
                            <canvas id="scatter-chart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
    </div>
    <!-- end row-->
@endsection

@section('scripts')
    @vite(['resources/js/pages/chart-area.js', 'resources/js/pages/chart-other.js', 'resources/js/pages/chart-bar.js', 'resources/js/pages/chart-line.js'])
@endsection
