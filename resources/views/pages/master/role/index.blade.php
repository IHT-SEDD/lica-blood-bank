@extends('layouts.master-page-layout')

@section('datatable-header')
<div class="d-flex align-items-center justify-content-center gap-2">
  {{-- Date filter :begin --}}
  <div>
    <div class="input-group">
      <span class="input-group-text" id="master-role-table-date-filter">
        <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
      </span>
      <input class="form-control master-role-table-date-filter" aria-describedby="master-role-table-date-filter"
        data-date-format="d-m-Y" data-provider="flatpickr" data-range-date="true" data-enable-time="true" type="text"
        placeholder="Choose date range" />
    </div>
  </div>
  {{-- Date filter :begin --}}
</div>
@endsection

@section('datatable-content')
<table class="table table-sm table-striped dt-responsive align-middle mb-0 master-role-table" id="master-role-table">
  <thead class="thead-sm text-uppercase fs-xxs">
    <tr>
      <th>No</th>
      <th>Name</th>
      <th>Description</th>
      <th>Guard</th>
      <th>Created At</th>
      <th>Updated At</th>
      <th>Action</th>
    </tr>
  </thead>
</table>
@endsection

@section('form-content')
<form class="row g-2" id="add_new_role" autocomplete="off">
  {{-- Name --}}
  <div class="col-lg-6">
    <label class="form-label" for="name">Name
      <span class="text-danger">*</span>
    </label>
    <input autocomplete="off" class="form-control" id="name" name="name" type="text" placeholder="Role name" />
  </div>

  {{-- Guard --}}
  <div class="col-lg-6">
    <label class="form-label" for="guard_name">Guard
      <span class="text-danger">*</span>
    </label>
    <input autocomplete="off" class="form-control" id="guard_name" name="guard_name" type="text"
      placeholder="Role guard" />
  </div>

  {{-- Description --}}
  <div class="col-lg-12">
    <label class="form-label" for="description">Description</label>
    <textarea autocomplete="off" class="form-control" id="description" name="description" rows="5"
      placeholder="Role description"></textarea>
  </div>

  {{-- Submit Button --}}
  <div class="col-lg-12">
    <button class="btn btn-primary" type="submit">Submit form</button>
  </div>
</form>
@endsection

@section('modal-content')
@include('pages.master.role.partials.edit-data-modal')
@include('pages.master.role.partials.delete-data-modal')
@endsection

@section('custom-scripts')

@endsection