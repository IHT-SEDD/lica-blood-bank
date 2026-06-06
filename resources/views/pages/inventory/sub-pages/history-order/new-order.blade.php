@extends('layouts.vertical', ['title' => 'Add New Order'])

@section('styles')
@endsection

@section('content')
<div class="row py-4">
 {{-- Header :begin --}}
 <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-1 mb-2">
  {{-- Button Add New Order --}}
  <a href="{{ route('inventory.history-order.index') }}" class="btn btn-soft-primary">
   <i class="ti ti-arrow-left-dashed fs-lg align-middle flex-shrink-0 me-1"></i>
   {{ __('Keluar') }}
  </a>

  {{-- Title --}}
  <h1 class="fw-bold">Buat Permintaan Darah Baru</h1>
 </div>
 {{-- Header :end --}}

 {{-- Main Content :begin --}}
 <div class="col-12">
  {{-- Card :begin --}}
  <div class="card">
   {{-- Card Body :begin --}}
   <div class="card-body">
    @include('pages.inventory.sub-pages.history-order.partials.form-add-new-order')
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
'resources/js/pages/inventory/history-order/form-add.js'
])
@endsection