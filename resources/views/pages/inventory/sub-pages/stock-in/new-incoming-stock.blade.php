@extends('layouts.vertical', ['title' => 'Add New Incoming Stock'])

@section('styles')
@endsection

@section('content')
<div class="row py-4">
 {{-- Header :begin --}}
 <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-1 mb-2">
  {{-- Button Add New Order --}}
  <a href="{{ route('inventory.stock-in.index') }}" class="btn btn-soft-primary">
   Back to stock in
  </a>

  {{-- Title --}}
  <h1 class="fw-bold">Add New Incoming Stock</h1>
 </div>
 {{-- Header :end --}}

 {{-- Main Content :begin --}}
 <div class="col-12">
  {{-- Card :begin --}}
  <div class="card">
   {{-- Card Body :begin --}}
   <div class="card-body">
    @include('pages.inventory.sub-pages.stock-in.partials.form-add-incoming-stock')
   </div>
   {{-- Card Body :end --}}
  </div>
  {{-- Card :end --}}
 </div>
 {{-- Main Content :end --}}
</div>
@endsection

@section('scripts')
@vite([
'resources/js/pages/inventory/stock-in/form-add.js',
])
@endsection