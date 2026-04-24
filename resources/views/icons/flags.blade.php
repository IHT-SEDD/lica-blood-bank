@extends('layouts.vertical', ['title' => 'Flags'])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'SVG Flags Library',
        'subTitle' =>
            'Browse a complete collection of scalable vector flags for countries and regions — perfect for language switchers and geo features.',
        'badgeIcon' => 'flag',
        'badgeTitle' => 'Country Flags',
    ])

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                            Flags Listing (SVG)
                        </h5>
                    </div>
                    <div class="app-search">
                        <input class="form-control" id="countrySearch" placeholder="Search country..." type="search" />
                        <i class="app-search-icon text-muted" data-lucide="search"></i>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center w-100" id="flagTable">
                            <thead>
                                <tr class="fs-xxs">
                                    <th>Flag</th>
                                    <th>Country Name</th>
                                    <th>Path</th>
                                    <th>Flag</th>
                                    <th>Country Name</th>
                                    <th>Path</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->
    </div> <!-- end row-->

@endsection

@section('scripts')
    @vite(['resources/js/pages/flags-listing.js'])
@endsection
