@extends('layouts.vertical', ['title' => 'Data Rendering Datatables'])

@section('styles')
    @vite(['node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Data Rendering',
        'subTitle' =>
            'Customize how data is displayed in tables using render functions, templates, and conditional formatting with DataTables.',
        'badgeIcon' => 'layout-template',
        'badgeTitle' => 'Custom Output',
    ])

    <div class="row justify-content-center">
        <div class="col-xxl-12">
            <div class="card">
                <div class="card-header justify-content-between">
                    <h5 class="card-title"> Example </h5>
                    <a class="icon-link icon-link-hover link-primary fw-semibold"
                        href="https://datatables.net/examples/basic_init/data_rendering.html" target="_blank">View Docs <i
                            class="ti ti-arrow-right bi align-middle fs-lg"></i></a>
                </div>
                <div class="card-body">
                    <table class="table table-striped dt-responsive align-middle mb-0" id="datatable-rendering">
                        <thead class="thead-sm text-uppercase fs-xxs">
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Office</th>
                                <th>Progress</th>
                                <th>Start date</th>
                                <th>Salary</th>
                            </tr>
                        </thead>
                    </table>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/pages/datatables-rendering.js'])
@endsection
