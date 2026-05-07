@extends('layouts.vertical', ['title' => 'Inventory'])

@section('content')
{{-- Statistic :begin --}}
<div class="row py-4">
  {{-- Title & Date Range Picker --}}
  <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
    {{-- Title --}}
    <h1 class="fw-bold">{{ __('DASHBOARD') }}</h1>
  </div>

  {{-- Pie Chart :begin--}}
  <div class="col-xxl-4 col-md-6 col-12">
    {{-- Pie Chart Card :begin --}}
    <div class="card card-h-100">
      <div class="card-body">
        {{-- Title & Icon --}}
        <div class="d-flex justify-content-between align-items-start mb-4">
          <h5 class="card-title mb-0">{{ __('Blood Stocks Chart') }}</h5>
          <div>
            <i class="fs-24 svg-sw-10 text-danger fill-danger mb-0" data-lucide="heart-pulse"></i>
          </div>
        </div>

        {{-- Chart --}}
        <div class="mb-0">
          <canvas id="bloodPackStatsChart" style="max-height: 170px; width: 100%;"></canvas>
        </div>
      </div>
    </div>
    {{-- Pie Chart Card :end --}}
  </div>
  {{-- Pie Chart :end --}}

  {{-- Stats per Blood Group :begin --}}
  <div class="col-xxl-8 col-md-6 col-12">
    @include('pages.inventory.sub-pages.dashboard.blood-group-stats')
  </div>
  {{-- Stats per Blood Group :end --}}

  {{-- Datatables :begin --}}
  <div class="col-xxl-12">
    {{-- Card :begin --}}
    <div class="card card-h-100">
      {{-- Card Body :begin --}}
      <div class="card-body">
        {{-- Nav Tabs :begin --}}
        <ul class="nav nav-tabs nav-bordered nav-bordered-primary mb-3">
          {{-- List Stock Tab --}}
          <li class="nav-item">
            <a aria-expanded="false" class="nav-link active" data-bs-toggle="tab" href="#list-stock">
              {{ __('List Blood Stock') }}
            </a>
          </li>
        </ul>
        {{-- Nav Tabs :end --}}

        {{-- Tab Content :begin --}}
        <div class="tab-content">
          {{-- List Stock Tab Content --}}
          <div class="tab-pane show active" id="list-stock">
            {{-- Subtitle :begin --}}
            <h4 class="my-4">
              List of available unit for blood type
              <span class="fw-semibold text-danger" id="blood-group-list-stock">A+</span>
            </h4>
            {{-- Subtitle :end --}}

            {{-- Table :begin --}}
            @include('pages.inventory.sub-pages.dashboard.datatables.list-stock-table')
            {{-- Table :end --}}
          </div>
        </div>
        {{-- Tab Content :end --}}
      </div>
      {{-- Card Body :end --}}
    </div>
    {{-- Card :end --}}
  </div>
  {{-- Datatables :end --}}
</div>
{{-- Statistic :end --}}
@endsection

@section('scripts')
@vite(['resources/js/pages/inventory/dashboard/index.js', 'resources/js/pages/inventory/dashboard/datatables.js'])
@endsection