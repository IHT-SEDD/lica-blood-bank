@extends('layouts.vertical', ['title' => 'Stock In'])

@section('content')
{{-- Header :begin --}}
<div class="row pt-4 pb-2">
 {{-- Title & Tools :begin --}}
 <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
  {{-- Title & Add Stock :begin --}}
  <div class="d-flex align-items-center justify-content-center gap-2">
   {{-- Title --}}
   <h1 class="fw-bold mb-0">STOCK IN</h1>

   {{-- Button Add New Blood --}}
   <button class="btn btn-soft-info" data-bs-target="#add_blood_modal" data-bs-toggle="modal" type="button">
    Add Blood
   </button>
  </div>
  {{-- Title & Add Stock :end --}}

  {{-- Tools :begin --}}
  <div class="d-flex align-items-center justify-content-center gap-2">
   {{-- Select Blood Group --}}
   <div>
    <select class="form-control" id="filter-blood-group" name="filter-blood-group"
     placeholder="Filter by blood group..."></select>
   </div>

   {{-- Select Blood Component --}}
   <div>
    <select class="form-control" id="filter-blood-component" name="filter-blood-component"
     placeholder="Filter by blood component..."></select>
   </div>

   {{-- Date Range Picker :begin --}}
   <div>
    <div class="input-group">
     <span class="input-group-text" id="stock-in-date-filter">
      <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
     </span>
     <input class="form-control stock-in-date-filter" aria-describedby="stock-in-date-filter" data-date-format="d-m-Y"
      data-provider="flatpickr" data-range-date="true" data-enable-time="true" type="text"
      placeholder="Choose date range" />
    </div>
   </div>
   {{-- Date Range Picker :end --}}
  </div>
  {{-- Tools :end --}}
 </div>
 {{-- Title & Tools :end --}}
</div>
{{-- Header :end --}}

{{-- Datatables :begin --}}
<div class="row">
 <div class="col-xxl-12">
  {{-- Card :begin --}}
  <div class="card card-h-100">
   {{-- Card Body :begin --}}
   <div class="card-body">
   </div>
   {{-- Card Body :end --}}
  </div>
  {{-- Card :end --}}
 </div>
</div>
{{-- Datatables :end --}}

{{-- Modal Add New Blood :begin --}}
<x-modal-layout id="add_blood_modal" size="modal-lg" title="Add New Blood">
 @include('pages.inventory.sub-pages.stock-in.partials.form-add-blood')
</x-modal-layout>
{{-- Modal Add New Blood :end --}}
@endsection

@section('scripts')
@vite(['resources/js/pages/inventory/index.js', 'resources/js/pages/inventory/dashboard/datatables.js'])
@endsection