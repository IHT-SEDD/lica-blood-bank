@extends('layouts.vertical', ['title' => 'Detail Order'])

@section('styles')
@endsection

@section('content')
<div class="row py-4">
 {{-- Header :begin --}}
 <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-1 mb-2">
  {{-- Button Back to Order List --}}
  <a href="{{ route('inventory.history-order.index') }}" class="btn btn-soft-primary">
   Back to history order
  </a>

  {{-- Title --}}
  <h1 class="fw-bold">Detail Order of <span id="po_number_title"></span></h1>
 </div>
 {{-- Header :end --}}

 <div class="col-xxl-5 col-md-6 col-12 align-items-start">
  {{-- Order Detail :begin --}}
  <div class="col-12">
   {{-- Card :begin --}}
   <div class="card">
    {{-- Card Header :begin --}}
    <div class="card-header justify-content-between align-items-center">
     <h5 class="card-title text-capitalize mb-0">Order Data</h5>
     <div class="card-action">
      <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
     </div>
    </div>
    {{-- Card Header :end --}}

    {{-- Card Body :begin --}}
    <div class="card-body">
     <div id="order_data_container">
      {{-- Populate by JS --}}
     </div>
    </div>
    {{-- Card Body :end --}}
   </div>
   {{-- Card :end --}}
  </div>
  {{-- Order Detail :end --}}

  {{-- Log Activity :begin --}}
  <div class="col-12">
   {{-- Log Activity Card :begin --}}
   <div class="card">
    {{-- Log Activity Header :begin --}}
    <div class="card-header d-flex justify-content-between align-items-center">
     <h5 class="card-title text-capitalize mb-0">Log Activities</h5>
     <div class="card-action">
      <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
     </div>
    </div>
    {{-- Log Activity Header :end --}}

    {{-- Log Activity Body :begin --}}
    <div class="card-body order-log-data-container">
     <div class="timeline timeline-icon-bordered">
      {{-- Populate by JS --}}
     </div>
    </div>
    {{-- Log Activity Body :end --}}
   </div>
   {{-- Log Activity Card :end --}}
  </div>
  {{-- Log Activity :end --}}
 </div>

 {{-- Blood Data Detail :begin --}}
 <div class="col-xxl-7 col-md-6 col-12">
  {{-- Card :begin --}}
  <div class="card">
   {{-- Card Header :begin --}}
   <div class="card-header justify-content-between align-items-center">
    <h5 class="card-title text-capitalize mb-0">Blood Order Data</h5>
    <div class="card-action gap-2">
     <h5 class="text-capitalize fw-semibold mb-0">Total Quantity :</h5>
     <h5 class="text-capitalize fw-semibold mb-0 text-bg-secondary px-2" id="total_quantity">200 Pcs</h5>
     <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
    </div>
   </div>
   {{-- Card Header :end --}}

   {{-- Card Body :begin --}}
   <div class="card-body" id="blood_data_container">
    {{-- Populate by JS --}}
   </div>
   {{-- Card Body :end --}}
  </div>
  {{-- Card :end --}}
 </div>
 {{-- Blood Data Detail :end --}}
</div>
@endsection

@section('scripts')
@vite([
'resources/js/pages/inventory/history-order/detail-order.js'
])
@endsection