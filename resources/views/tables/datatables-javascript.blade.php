@extends('layouts.vertical', ['title' => 'Javascript Source Datatables'])

@section('styles')
    @vite(['node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'JavaScript DataTables',
        'subTitle' =>
            'Populate DataTables directly from JavaScript arrays or objects for full client-side control and flexibility.',
        'badgeIcon' => 'code',
        'badgeTitle' => 'Client-Side Data',
    ])

    <div class="row justify-content-center">
        <div class="col-xxl-12">
            <div class="card">
                <div class="card-header justify-content-between">
                    <h5 class="card-title"> Example </h5>
                    <a class="icon-link icon-link-hover link-primary fw-semibold"
                        href="https://datatables.net/examples/data_sources/js_array.html" target="_blank">View
                        Docs <i class="ti ti-arrow-right bi align-middle fs-lg"></i></a>
                </div>
                <div class="card-body">
                    <table class="table table-striped dt-responsive align-middle mb-0" id="datatables-javascript-source">
                        <thead class="thead-sm text-uppercase fs-xxs">
                            <tr>
                                <th>Company</th>
                                <th>Symbol</th>
                                <th>Price</th>
                                <th>Change</th>
                                <th>Volume</th>
                                <th>Market Cap</th>
                                <th>Rating</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    </table>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->
    </div> <!-- end row-->
@endsection

@section('scripts')
    @vite(['resources/js/pages/datatables-javascript-source.js'])
@endsection
