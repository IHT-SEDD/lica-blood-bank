@extends('layouts.master-page-layout')

@section('datatable-header')
<div class="d-flex align-items-center justify-content-center gap-2">
  {{-- Select Role --}}
  <div>
    <select class="form-control" id="filter-role" name="filter-role" placeholder="Filter by role..."></select>
  </div>

  {{-- Date filter :begin --}}
  <div>
    <div class="input-group">
      <span class="input-group-text" id="master-user-table-date-filter">
        <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
      </span>
      <input class="form-control master-user-table-date-filter" aria-describedby="master-user-table-date-filter"
        data-date-format="d-m-Y" data-provider="flatpickr" data-range-date="true" type="text"
        placeholder="Choose date range" />
    </div>
  </div>
  {{-- Date filter :begin --}}
</div>
@endsection

@section('datatable-content')
<table class="table table-striped dt-responsive align-middle mb-0 master-user-table" id="master-user-table">
  <thead class="thead-sm text-uppercase fs-xxs">
    <tr>
      <th>No</th>
      <th>Name</th>
      <th>Username</th>
      <th>Email</th>
      <th>Status</th>
      <th>Created At</th>
      <th>Updated At</th>
      <th>Email Verified At</th>
      <th>Deleted At</th>
      <th>Action</th>
    </tr>
  </thead>
</table>
@endsection

@section('form-content')
<form class="row g-2" id="add_new_user" autocomplete="off">
  {{-- Name --}}
  <div class="col-lg-6">
    <label class="form-label" for="name">Name
      <span class="text-danger">*</span>
    </label>
    <input autocomplete="off" class="form-control" id="name" name="name" type="text" placeholder="your full name" />
  </div>

  {{-- Username --}}
  <div class="col-lg-6">
    <label class="form-label" for="username">Username
      <span class="text-danger">*</span>
    </label>
    <input autocomplete="off" class="form-control" id="username" name="username" type="text"
      placeholder="your username" />
  </div>

  {{-- Email --}}
  <div class="col-lg-12">
    <label class="col-form-label" for="email">Email</label>
    <input autocomplete="off" class="form-control" id="email" name="email" type="email"
      placeholder="youremail@mail.com" />
  </div>

  {{-- Password --}}
  <div class="col-lg-8">
    <label class="form-label" for="password">Password
      <span class="text-danger">*</span>
    </label>
    <input autocomplete="new-password" class="form-control" id="password" name="password" placeholder="••••••••"
      type="password" />
  </div>

  {{-- Role --}}
  <div class="col-lg-4">
    <label class="form-label" for="select-role">Role
      <span class="text-danger">*</span>
    </label>
    <select class="form-control" id="select-role" name="role" placeholder="Choose role..."></select>
  </div>

  {{-- Is Active? --}}
  <div class="col-lg-3">
    <div>
      <div class="form-check form-check-info my-1">
        <input checked="" class="form-check-input" id="is_active" name="is_active" type="checkbox" />
        <label class="form-check-label" for="is_active">Active?</label>
      </div>
    </div>
  </div>

  {{-- Submit Button --}}
  <div class="col-lg-12">
    <button class="btn btn-primary" type="submit">Add New User</button>
  </div>
</form>
@endsection

@section('modal-content')
@include('pages.master.user.partials.edit-data-modal')
@include('pages.master.user.partials.delete-data-modal')
@endsection

@section('custom-scripts')

@endsection