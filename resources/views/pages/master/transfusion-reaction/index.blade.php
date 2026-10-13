@extends('layouts.master-page-layout')

@section('datatable-header')
<div class="d-flex align-items-center justify-content-center gap-2">
  {{-- Select Role --}}


  {{-- Date filter :begin --}}
  <div>
    <div class="input-group">
      <span class="input-group-text" id="master-room-table-date-filter">
        <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
      </span>
      <input class="form-control master-room-table-date-filter" aria-describedby="master-room-table-date-filter"
        data-date-format="d-m-Y" data-provider="flatpickr" data-range-date="true" type="text"
        placeholder="Choose date range" />
    </div>
  </div>
  {{-- Date filter :begin --}}
</div>
@endsection

@section('datatable-content')
<table class="table table-striped dt-responsive align-middle mb-0 master-transfusion-reaction-table"
  id="master-transfusion-reaction-table">
  <thead class="thead-sm text-uppercase fs-xxs">
    <tr>
      <th>No</th>
      <th>Reaksi</th>
      <th>Level</th>
      <th>Waktu</th>
      <th>Kode</th>
      <th>Indikasi</th>
      <th>Kategori</th>
      <th>Deleted At</th>
      <th>Action</th>
    </tr>
  </thead>
</table>
@endsection

@section('form-content')
<form class="row g-2" id="add_new_transfusion-reaction" autocomplete="off">
  {{-- Name --}}
  <div class="col-lg-12">
    <label class="form-label" for="name">Nama
      <span class="text-danger">*</span>
    </label>
    <input autocomplete="off" class="form-control" id="name" name="name" type="text"
      placeholder="Masukkan nama reaksi" />
  </div>

  {{-- Category --}}
  <div class="col-lg-12">
    <label class="form-label" for="category">Kategori
      <span class="text-danger">*</span>
    </label>
    <input autocomplete="off" class="form-control" id="category" name="category" type="text"
      placeholder="Masukkan kategori reaksi" />
  </div>

  {{-- Level --}}
  <div class="col-lg-12">
    <label class="form-label" for="level">Tingkatan
      <span class="text-danger">*</span>
    </label>
    <select class="form-control" id="select-level" name="level" placeholder="Pilih tingkatan reaksi..."></select>
  </div>

  {{-- Indicator --}}
  <div class="col-lg-12">
    <label class="form-label" for="indicator">Indikasi
      <span class="text-danger">*</span>
    </label>
    <textarea autocomplete="off" class="form-control" id="indicator" name="indicator" type="text"
      placeholder="Masukkan indikasi reaksi" rows="5"></textarea>
  </div>

  {{-- Time Begin --}}
  <div class="col-lg-6">
    <label class="form-label" for="time_begin">Waktu Awal (Menit)</label>
    <input autocomplete="off" class="form-control" id="time_begin" name="time_begin" type="number" min="0" step="1"
      placeholder="Masukkan waktu awal reaksi terjadi" />
  </div>

  {{-- Time End --}}
  <div class="col-lg-6">
    <label class="form-label" for="time_end">Waktu Akhir (Menit)</label>
    <input autocomplete="off" class="form-control" id="time_end" name="time_end" type="number" min="0" step="1"
      placeholder="Masukkan waktu akhir reaksi terjadi" />
  </div>

  {{-- General Code --}}
  <div class="col-lg-12">
    <label class="form-label" for="general_code">General Code</label>
    <input autocomplete="off" class="form-control" id="general_code" name="general_code" type="text"
      placeholder="Masukkan kode reaksi" />
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
    <button class="btn btn-primary" type="submit">Add New Reaction</button>
  </div>
</form>
@endsection

@section('modal-content')
@include('pages.master.transfusion-reaction.partials.edit-data-modal')
@include('pages.master.transfusion-reaction.partials.delete-data-modal')
@endsection

@section('custom-scripts')

@endsection