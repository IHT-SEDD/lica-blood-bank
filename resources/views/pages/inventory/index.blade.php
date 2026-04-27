@extends('layouts.vertical', ['title' => 'Inventory'])

@section('content')
{{-- Statistic :begin --}}
<div class="row py-4">
  {{-- Title & Date Range Picker --}}
  <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
    {{-- Title --}}
    <h1 class="fw-bold">DASHBOARD</h1>

    {{-- Date Range Picker --}}
    <div>
      <div class="input-group">
        <span class="input-group-text" id="blood-stats-date-filter">
          <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
        </span>
        <input class="form-control blood-stats-date-filter" aria-describedby="blood-stats-date-filter"
          data-date-format="d-m-Y" data-provider="flatpickr" data-range-date="true" data-enable-time="true" type="text"
          placeholder="Choose date range" />
      </div>
    </div>
  </div>

  {{-- Pie Chart :begin--}}
  <div class="col-xxl-4 col-md-6 col-12">
    {{-- Pie Chart Card :begin --}}
    <div class="card card-h-100">
      <div class="card-body">
        {{-- Title & Icon --}}
        <div class="d-flex justify-content-between align-items-start mb-4">
          <h5 class="card-title mb-0">Blood Packs Chart</h5>
          <div>
            <i class="fs-24 svg-sw-10 text-danger fill-danger mb-0" data-lucide="heart-pulse"></i>
          </div>
        </div>

        {{-- Chart --}}
        <div class="d-flex align-items-center justify-content-center mb-0">
          <canvas id="bloodPackStatsChart" style="max-height: 250px;"></canvas>
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
</div>
{{-- Statistic :end --}}

{{-- Datatables :begin --}}
<div class="row">
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
              List Stock
            </a>
          </li>

          {{-- List Expired Tab --}}
          <li class="nav-item">
            <a aria-expanded="true" class="nav-link" data-bs-toggle="tab" href="#list-expired">
              List Expired
            </a>
          </li>

          {{-- History Order Tab --}}
          <li class="nav-item">
            <a aria-expanded="false" class="nav-link" data-bs-toggle="tab" href="#history-order">
              History Order
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

          {{-- List Expired Tab Content --}}
          <div class="tab-pane" id="list-expired">
            {{-- Subtitle :begin --}}
            <h4 class="my-4">
              List of expired units for blood type
              <span class="fw-semibold text-danger" id="blood-group-list-stock">A+</span>
            </h4>
            {{-- Subtitle :end --}}

            {{-- Table :begin --}}
            @include('pages.inventory.sub-pages.dashboard.datatables.list-expired-table')
            {{-- Table :end --}}
          </div>

          {{-- History Order Tab Content --}}
          <div class="tab-pane" id="history-order">
            {{-- Subtitle :begin --}}
            <h4 class="my-4">
              List of order history for blood type
              <span class="fw-semibold text-danger" id="blood-group-list-stock">A+</span>
            </h4>
            {{-- Subtitle :end --}}

            {{-- Table :begin --}}
            @include('pages.inventory.sub-pages.dashboard.datatables.history-order-table')
            {{-- Table :end --}}
          </div>
        </div>
        {{-- Tab Content :end --}}
      </div>
      {{-- Card Body :end --}}
    </div>
    {{-- Card :end --}}
  </div>
</div>
{{-- Datatables :end --}}
@endsection

@section('scripts')
@vite(['resources/js/pages/inventory/index.js', 'resources/js/pages/inventory/dashboard/datatables.js'])
@endsection