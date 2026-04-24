@extends('layouts.vertical', ['title' => 'Ajax Datatables'])

@section('styles')
@vite(['node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'])
@endsection

@section('content')
@include('layouts.shared.page-title', [
'title' => 'Ajax DataTables',
'subTitle' =>
'Load table data asynchronously using Ajax with DataTables for faster performance and seamless server-side
integration.',
'badgeIcon' => 'database',
'badgeTitle' => 'Dynamic Loading',
])


<div class="row justify-content-center">
    <div class="col-xxl-12">
        <div class="card">
            <div class="card-header justify-content-between">
                <h5 class="card-title"> Example </h5>
                <a class="icon-link icon-link-hover link-primary fw-semibold"
                    href="https://datatables.net/examples/ajax/" target="_blank">View Docs <i
                        class="ti ti-arrow-right bi align-middle fs-lg"></i></a>
            </div>
            <div class="card-body">
                <table class="table table-striped dt-responsive align-middle mb-0" id="datatables-ajax">
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
    </div>
</div>
@endsection

@section('scripts')
@vite(['resources/js/pages/datatables-ajax.js'])
@endsection