@extends('layouts.vertical', ['title' => __('Config Setting - Blood Component')])

@section('styles')
@endsection

@section('content')
<div class="row py-3">
 <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
  <a href="{{ route('playground.index') }}" class="btn btn-sm btn-soft-primary">
   <i class="ti ti-arrow-left-dashed fs-lg align-middle flex-shrink-0 me-1"></i>
   {{ __('Keluar') }}
  </a>

  {{-- Title --}}
  <h1 class="fw-bold uppercase">{{ __('Pengaturan Konfigurasi Komponen Darah') }}</h1>
 </div>

 {{-- Tabel komponen darah --}}
 <div class="col-9">
  <div class="card">
   {{-- Card Header --}}
   <div class="card-header">
    <h5 class="card-title">Konfigurasi Komponen Darah</h5>
    <h6 class="card-subtitle text-body-secondary">Konfigurasi ini ditujukan untuk integrasi API</h6>
    <div class="card-action">
     <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
    </div>
   </div>

   {{-- Card Body --}}
   <div class="card-body">
    <table
     class="table table-sm table-centered table-hover table-sm dt-responsive align-middle mb-0 list-config-blood-component-table"
     id="list-config-blood-component-table">
     <thead class="thead-sm text-uppercase fs-xxs">
      <tr>
       <th></th>
       <th></th>
       <th></th>
       <th></th>
       <th></th>
       <th></th>
      </tr>
     </thead>
    </table>
   </div>
  </div>
 </div>

 {{-- Form Add --}}
 <div class="col-3">
  {{-- Card Form :begin --}}
  <div class="card ">
   {{-- Card Header :begin --}}
   <div class="card-header justify-content-between align-items-center">
    <h5 class="card-title text-capitalize mb-0">Add New Configuration Data</h5>
   </div>
   {{-- Card Header :end --}}

   {{-- Card Body Form :begin --}}
   <div class="card-body">
    <form class="row g-2" id="add_new_config_blood_component" autocomplete="off">
     {{-- Blood Component --}}
     <div class="col-lg-12">
      <label class="form-label" for="select-blood-component">{{ __('Blood Component') }}
       <span class="text-danger">*</span>
      </label>
      <select class="form-control" id="select-blood-component" name="blood_component"
       placeholder="{{ __('Choose') }} {{ __('Blood Component') }}..."></select>
     </div>

     {{-- Blood Component Label --}}
     <div class="col-lg-12">
      <label class="form-label" for="select-blood-component-label">{{ __('Blood Component Label') }}
       <span class="text-danger">*</span>
      </label>
      <select class="form-control" id="select-blood-component-label" name="blood_component_label"
       placeholder="{{ __('Choose') }} {{ __('Blood Component LabelP') }}..."></select>
     </div>

     {{-- Keyword --}}
     <div class="col-lg-12">
      <label class="form-label" for="keyword">Keyword
       <span class="text-danger">*</span>
      </label>
      <textarea autocomplete="off" class="form-control" id="keyword" name="keyword" rows="5"
       placeholder="Keyword configuration"></textarea>
     </div>

     {{-- General Code --}}
     <div class="col-lg-12">
      <label class="form-label" for="general_code">General Code
       <span class="text-danger">*</span>
      </label>
      <textarea autocomplete="off" class="form-control" id="general_code" name="general_code" rows="5"
       placeholder="General code configuration"></textarea>
     </div>

     {{-- Submit Button --}}
     <div class="col-lg-12">
      <button class="btn btn-primary" type="submit">{{ __('Submit') }} {{ __('Data') }}</button>
     </div>
    </form>
   </div>
   {{-- Card Body Form :end --}}
  </div>
  {{-- Card Form :end --}}
 </div>
</div>

@include('pages.playground.crossmatch-result.partials.edit-data-crossmatch')
@endsection

@section('scripts')
@vite([
'resources/js/pages/playground/config/blood-component/index.js',
'resources/js/pages/playground/config/blood-component/datatable.js',
])
@endsection