@extends('layouts.vertical', ['title' => 'Child Row Datatables'])

@section('styles')
@vite(['node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'])
@endsection

@section('content')
@include('layouts.shared.page-title', [
'title' => 'Child Rows',
'subTitle' =>
'Display additional row details with expandable child rows in DataTables for a cleaner and more informative layout.',
'badgeIcon' => 'layout-list',
'badgeTitle' => 'Expandable Rows',
])


<div class="row justify-content-center">
    <div class="col-xxl-12">
        <div class="card">
            <div class="card-header justify-content-between">
                <h5 class="card-title"> Example </h5>
                <a class="icon-link icon-link-hover link-primary fw-semibold"
                    href="https://datatables.net/examples/api/row_details.html" target="_blank">View Docs <i
                        class="ti ti-arrow-right bi align-middle fs-lg"></i></a>
            </div>
            <div class="card-body">
                <table class="table table-striped dt-responsive align-middle mb-0" id="child-rows-data">
                    <thead class="thead-sm text-uppercase fs-xxs">
                        <tr>
                            <th></th>
                            <th>Company</th>
                            <th>Symbol</th>
                            <th>Price</th>
                            <th>Change</th>
                            <th>Volume</th>
                            <th>Market Cap</th>
                        </tr>
                    </thead>
                </table>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div>
</div>
@endsection

@section('scripts')
@vite(['resources/js/pages/datatables-child-rows.js'])
@endsection