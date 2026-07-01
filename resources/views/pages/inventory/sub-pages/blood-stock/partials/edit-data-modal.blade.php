<x-modal-layout id="edit_data_stock_blood_modal" size="" title="Ubah Data Darah">
  {{-- Form Edit :begin --}}
  <form class="row g-2" id="edit_data_stock_blood" autocomplete="off" novalidate>
    {{-- Storage Rack --}}
    <div>
      <label class="form-label" for="edit_data_blood_stock_storage_rack">Rak Penyimpanan</label>
      <select class="form-control form-control-sm tomselect-sm" id="edit_data_blood_stock_storage_rack"
        name="storage_rack_id" placeholder="Pilih rak penyimpanan..."></select>
    </div>

    {{-- Status --}}
    <div>
      <label class="form-label" for="edit_data_blood_stock_status">Status</label>
      <select class="form-control form-control-sm tomselect-sm" id="edit_data_blood_stock_status" name="status"
        placeholder="Pilih status..."></select>
    </div>

    {{-- Volume --}}
    <div>
      <label class="form-label" for="edit_data_blood_stock_volume">Volume</label>
      <input autocomplete="off" class="form-control form-control-sm" id="edit_data_blood_stock_volume" name="volume"
        type="text" placeholder="ml" />
    </div>

    {{-- Tgl. Aftap --}}
    <div>
      <label class="form-label" for="edit_data_blood_stock_aftap_date">Tgl. Aftap</label>
      <input type="text" class="form-control form-control-sm flatpickr-custom" id="edit_data_blood_stock_aftap_date"
        name="aftap_date" data-enable-time="d-m-Y H:i" data-provider="flatpickr" />
    </div>

    {{-- Tgl. Expire --}}
    <div>
      <label class="form-label" for="edit_data_blood_stock_expiry_date">Tgl. Expire</label>
      <input type="text" class="form-control form-control-sm flatpickr-custom" id="edit_data_blood_stock_expiry_date"
        name="expiry_date" data-enable-time="d-m-Y H:i" data-provider="flatpickr" />
    </div>

    {{-- Tgl. Proses --}}
    <div>
      <label class="form-label" for="edit_data_blood_stock_process_date">Tgl. Proses</label>
      <input type="text" class="form-control form-control-sm flatpickr-custom" id="edit_data_blood_stock_process_date"
        name="process_date" data-enable-time="d-m-Y H:i" data-provider="flatpickr" />
    </div>

    {{-- Is Expired? --}}
    <div>
      <div>
        <div class="form-check form-check-info my-1">
          <input checked="" class="form-check-input" id="edit_data_blood_stock_is_expired" name="is_expired"
            type="checkbox" />
          <label class="form-check-label" for="edit_data_blood_stock_is_expired">Sudah expire?</label>
        </div>
      </div>
    </div>

    <hr />

    {{-- Submit Button --}}
    <div class="col-lg-12 mt-2">
      <button class="btn btn-primary" type="submit">Ubah Data</button>
    </div>
  </form>
  {{-- Form Edit :end --}}
</x-modal-layout>