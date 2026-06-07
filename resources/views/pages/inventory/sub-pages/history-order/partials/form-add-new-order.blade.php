{{-- Form Add New Order :begin --}}
<form class="row g-2" id="add_new_order" autocomplete="off">
  {{-- Order Data Detail --}}
  <div style="border-bottom: 2px dashed #ccc; padding-bottom: 6px; margin-bottom: 12px;">
    <h4>{{ __('Detail Permintaan') }}</h4>
  </div>

  {{-- Order Data Inputs :begin --}}
  <div class="row">
    {{-- Left Side :begin --}}
    <div class="col-lg-6 col-12 mb-1">
      {{-- PO Number --}}
      <div class="col-12">
        <label class="form-label" for="po_number">{{ __('No. PO') }}
          <span class="text-danger">*</span>
        </label>
        <input autocomplete="off" class="form-control" id="po_number" name="po_number" type="text"
          placeholder="Nomor PO" readonly />
        <small class="form-text text-muted">
          {{ __('Klik untuk membuat nomor PO') }}.
        </small>
      </div>

      {{-- Description --}}
      <div class="col-12">
        <label class="form-label" for="description">{{ __('Catatan') }}</label>
        <textarea autocomplete="off" class="form-control" id="description" name="description" rows="5"
          placeholder="Catatan Permintaan"></textarea>
      </div>
    </div>
    {{-- Left Side :begin --}}

    {{-- Right Side :begin --}}
    <div class="col-lg-6 col-12">
      {{-- Vendor Data :begin --}}
      <div class="row mb-1">
        {{-- Vendor --}}
        <div class="col-lg-6 col-12">
          <label class="form-label" for="select-vendor">{{ __('PMI') }}
            <span class="text-danger">*</span>
          </label>
          <select class="form-control" id="select-vendor" name="vendor_id" placeholder="Pilih PMI..."></select>
        </div>

        {{-- Vendor Name --}}
        <div class="col-lg-6 col-12">
          <label class="form-label" for="vendor-name">{{ __('Nama PMI') }}</label>
          <input autocomplete="off" class="form-control" id="vendor-name" type="text" placeholder="Nama PMI"
            readonly />
          <small class="form-text text-muted">
            {{ __('Nama PMI otomatis muncul berdasarkan pilihan anda.') }}.
          </small>
        </div>

        {{-- Vendor Address --}}
        <div class="col-12">
          <label class="form-label" for="vendor-address">{{ __('Alamat PMI') }}</label>
          <textarea autocomplete="off" class="form-control" id="vendor-address" rows="5" placeholder="Alamat PMI"
            readonly></textarea>
          <small class="form-text text-muted">
            {{ __('Alamat PMI otomatis muncul berdasarkan pilihan anda') }}.
          </small>
        </div>
      </div>
      {{-- Vendor Data :end --}}
    </div>
    {{-- Right Side :begin --}}
  </div>
  {{-- Order Data Inputs :end --}}

  <hr />

  {{-- Blood Data Detail --}}
  <div class="row">
    {{-- Add Row Blood Data Button --}}
    <div class="w-xxl-25 w-lg-50 w-100 mb-2">
      <div class="input-group">
        <input aria-label="Count Table Row" class="form-control" type="number" min="1" id="add_row_blood_data_count"
          placeholder="Jumlah darah yang ditambah" />
        <button class="btn btn-dark add_row_blood_data" type="button" id="add_row_blood_data">
          <i class="ti ti-plus fs-4 me-2"></i>
          Tambah Darah
        </button>
      </div>
    </div>

    {{-- Table Blood Data :begin --}}
    <div class="table-responsive" id="table_blood_data_wrapper">
      <table class="table table-sm table-striped align-middle mb-0" id="table_blood_data">
        <thead class="bg-light align-middle bg-opacity-25 thead-sm">
          <tr class="text-uppercase fs-xxs">
            <th>Detail <span class="text-danger">*</span></th>
            <th>Jumlah <span class="text-danger">*</span></th>
            <th>Catatan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="blood_data_row">
          {{-- Populate by JS --}}
        </tbody>
      </table>
    </div>
    {{-- Table Blood Data :end --}}
  </div>

  <hr />

  {{-- Submit Button --}}
  <div class="mt-6 d-flex align-items-center justify-content-end gap-2">
    <div class="form-check form-check-info">
      <input class="form-check-input" id="save_as_draft" name="draft" type="checkbox" />
      <label class="form-check-label fw-semibold" for="save_as_draft">Simpan Sebagai Draft?</label>
    </div>
    <button class="btn btn-success" type="submit">Buat Permintaan</button>
  </div>
</form>
{{-- Form Add New Order :end --}}