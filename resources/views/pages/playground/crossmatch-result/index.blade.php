@extends('layouts.vertical', ['title' => __('Fixing Crossmatch Result')])

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
    <h1 class="fw-bold uppercase">{{ __('Perbaikan Hasil Crossmatch') }}</h1>
  </div>

  {{-- Step 1 --}}
  <div class="col-xxl-3 col-5">
    <div class="card text-bg-dark border-0">
      {{-- Card Header --}}
      <div class="card-header">
        <h5 class="card-title">Langkah 1</h5>
        <h6 class="card-subtitle text-body-secondary">Pilih no. bdrs atau no. order transaksi</h6>
        <div class="card-action">
          <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
          <a class="card-action-item" data-action="card-refresh" href="#!"><i class="ti ti-refresh"></i></a>
        </div>
      </div>

      {{-- Card Body --}}
      <div class="card-body">
        {{-- Nomor Lab --}}
        <div class="col-12 mb-0 pb-0">
          <label class="form-label" for="choose_lab_number">No. BDRS</label>
          <select class="form-control form-control-sm tomselect-sm" id="choose_lab_number" name="lab_number"
            placeholder="Pilih nomor BDRS..."></select>
        </div>

        <div class="col-12 py-2 text-center">
          <span class="fw-semibold">OR</span>
        </div>

        {{-- Nomor Order --}}
        <div class="col-12 mt-0 pt-0">
          <label class="form-label" for="choose_order_number">No. Order</label>
          <select class="form-control form-control-sm tomselect-sm" id="choose_order_number" name="order_number"
            placeholder="Pilih nomor order"></select>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xxl-9 col-7">
    {{-- Data Pasien --}}
    <div class="card">
      {{-- Card Header --}}
      <div class="card-header">
        <h5 class="card-title">Data Pasien</h5>
        <h6 class="card-subtitle text-body-secondary">Periksa data pasien dibawah ini</h6>
        <div class="card-action">
          <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
        </div>
      </div>

      {{-- Card Body --}}
      <div class="card-body">
        <div class="row">
          {{-- Sisi Kiri --}}
          <div class="col-xxl-4 col-12">
            {{-- Nama & Jenis Kelamin --}}
            <div class="row mb-2">
              <div class="col-12 text-capitalize text-muted fw-medium my-0" style="font-size: 11.5px;">
                {{ __('Nama / JK') }}
              </div>
              <div class="col-12 text-capitalize fs-6 fw-semibold my-0">
                <span id="patient_detail" data-patient-detail="name"></span>
                <span class="text-muted">/</span>
                <span id="patient_detail" data-patient-detail="gender"></span>
              </div>
            </div>

            {{-- Patient Email --}}
            <div class="row mb-2">
              <div class="col-12 text-capitalize text-muted fw-medium my-0" style="font-size: 11.5px;">{{ __('Email') }}
              </div>
              <div class="col-12 text-capitalize fs-6 fw-semibold my-0" id="patient_detail" data-patient-detail="email">
              </div>
            </div>

            {{-- Patient Age --}}
            <div class="row mb-2">
              <div class="col-12 text-capitalize text-muted fw-medium my-0" style="font-size: 11.5px;">{{ __('Umur') }}
              </div>
              <div class="col-12 text-capitalize fs-6 fw-semibold my-0" id="patient_detail" data-patient-detail="age">
              </div>
            </div>

            {{-- Patient Blood Group --}}
            <div class="row mb-2">
              <div class="col-12 text-capitalize text-muted fw-medium my-0" style="font-size: 11.5px;">
                {{ __('Goldar & Rhesus') }}
              </div>
              <div class="col-12 text-capitalize fs-6 fw-semibold my-0">
                <span id="patient_detail" data-patient-detail="blood_group"></span>
                <span id="patient_detail" data-patient-detail="blood_rhesus"></span>
              </div>
            </div>

            {{-- Patient Address --}}
            <div class="row mb-2">
              <div class="col-12 text-capitalize text-muted fw-medium my-0" style="font-size: 11.5px;">{{ __('Alamat')
                }}</div>
              <div class="col-12 text-capitalize fs-6 fw-semibold my-0" id="patient_detail"
                data-patient-detail="address">
              </div>
            </div>
          </div>

          {{-- Sisi Tengah --}}
          <div class="col-xxl-4 col-12">
            {{-- Patient Insurance --}}
            <div class="row mb-2">
              <div class="col-12 text-capitalize text-muted fw-medium my-0" style="font-size: 11.5px;">{{ __('Asuransi')
                }}
              </div>
              <div class="col-12 text-capitalize fs-6 fw-semibold my-0" id="patient_detail"
                data-patient-detail="insurance">
              </div>
            </div>

            {{-- Patient Room --}}
            <div class="row mb-2">
              <div class="col-12 text-capitalize text-muted fw-medium my-0" style="font-size: 11.5px;">{{ __('Ruangan /
                Jenis
                Pasien') }}</div>
              <div class="col-12 text-capitalize fs-6 fw-semibold my-0">
                <span id="patient_detail" data-patient-detail="room"></span>
                <span class="text-muted">/</span>
                <span id="patient_detail" data-patient-detail="type_patient"></span>
              </div>
            </div>

            {{-- Patient Doctor --}}
            <div class="row mb-2">
              <div class="col-12 text-capitalize text-muted fw-medium my-0" style="font-size: 11.5px;">{{ __('Dokter')
                }}</div>
              <div class="col-12 text-capitalize fs-6 fw-semibold my-0" id="patient_detail"
                data-patient-detail="doctor"></div>
            </div>

            {{-- Patient Diagnosis --}}
            <div class="row mb-2">
              <div class="col-12 text-capitalize text-muted fw-medium my-0" style="font-size: 11.5px;">{{
                __('Diagnosis') }}
              </div>
              <div class="col-12 text-capitalize fs-6 fw-semibold my-0" id="patient_detail"
                data-patient-detail="diagnosis">
              </div>
            </div>
          </div>

          {{-- Sisi Kanan --}}
          <div class="col-xxl-4 col-12">
            {{-- Created At --}}
            <div class="row mb-2">
              <div class="col-12 text-capitalize text-muted fw-medium my-0" style="font-size: 11.5px;">{{ __('Tgl.
                Dibuat') }}
              </div>
              <div class="col-12 text-capitalize fs-6 fw-semibold my-0" id="patient_detail"
                data-patient-detail="created_at">
              </div>
            </div>

            {{-- Dicheckin Oleh --}}
            <div class="row mb-2">
              <div class="col-12 text-capitalize text-muted fw-medium my-0" style="font-size: 11.5px;">{{ __('Checkin
                Oleh') }}
              </div>
              <div class="col-12 text-capitalize fs-6 fw-semibold my-0" id="patient_detail"
                data-patient-detail="checkedin_by">
              </div>
            </div>

            {{-- No. BDRS --}}
            <div class="row mb-2">
              <div class="col-12 text-capitalize text-muted fw-medium my-0" style="font-size: 11.5px;">{{ __('No. BDRS')
                }}
              </div>
              <div class="col-12 text-capitalize fs-6 fw-semibold my-0" id="patient_detail"
                data-patient-detail="lab_number">
              </div>
            </div>

            {{-- No. Order --}}
            <div class="row mb-2">
              <div class="col-12 text-capitalize text-muted fw-medium my-0" style="font-size: 11.5px;">{{ __('No.
                Order') }}
              </div>
              <div class="col-12 text-capitalize fs-6 fw-semibold my-0" id="patient_detail"
                data-patient-detail="order_number">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12">
    {{-- Data Pemeriksaan --}}
    <div class="card border-primary border border-dashed">
      {{-- Card Header --}}
      <div class="card-header">
        <h5 class="card-title">Langkah 2</h5>
        <h6 class="card-subtitle text-body-secondary">Periksa data pemeriksaan sebelum mengubah</h6>
        <div class="card-action">
          <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
        </div>
      </div>

      {{-- Card Body --}}
      <div class="card-body">
        <table
          class="table table-sm table-centered table-hover table-sm dt-responsive align-middle mb-0 list-tests-table"
          id="list-tests-table">
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
</div>

@include('pages.playground.crossmatch-result.partials.edit-data-crossmatch')
@endsection

@section('scripts')
@vite([
'resources/js/pages/playground/crossmatch-result/index.js',
])
@endsection