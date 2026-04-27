<x-modal-layout id="edit_data_master_storage_modal" size="" title="Edit Data Storage">
  {{-- Form Edit :begin --}}
  <form class="row g-2" id="edit_data_storage" autocomplete="off">
    {{-- Name --}}
    <div class="col-lg-6">
      <label class="form-label" for="edit_data_storage_name">Name
        <span class="text-danger">*</span>
      </label>
      <input autocomplete="off" class="form-control" id="edit_data_storage_name" name="name" type="text"
        placeholder="storage  name" />
    </div>

    {{-- Model --}}
    <div class="col-lg-6">
      <label class="form-label" for="edit_data_storage_model">Model</label>
      <input autocomplete="off" class="form-control" id="edit_data_storage_model" name="model" type="text"
        placeholder="storage model" />
    </div>

    {{-- Serial Number --}}
    <div class="col-lg-4">
      <label class="form-label" for="edit_data_storage_serial_number">Serial Number</label>
      <input autocomplete="off" class="form-control" id="edit_data_storage_serial_number" name="serial_number"
        type="text" placeholder="storage SN" />
    </div>

    {{-- Manufacturer --}}
    <div class="col-lg-4">
      <label class="form-label" for="edit_data_storage_manufacturer">Manufacturer</label>
      <input autocomplete="off" class="form-control" id="edit_data_storage_manufacturer" name="manufacturer"
        placeholder="storage manufacturer" type="text" />
    </div>

    {{-- Rack Capacity --}}
    <div class="col-lg-4">
      <label class="form-label" for="edit_data_storage_rack_capacity">Rack Capacity
        <span class="text-danger">*</span>
      </label>
      <input autocomplete="off" class="form-control" id="edit_data_storage_rack_capacity" name="rack_capacity"
        placeholder="1 - 999" type="number" />
    </div>

    {{-- Is Active? --}}
    <div class="col-lg-12">
      <div>
        <div class="form-check form-check-success my-1">
          <input checked="" class="form-check-input" id="edit_data_storage_is_active" type="checkbox"
            name="is_active" />
          <label class="form-check-label" for="edit_data_storage_is_active">Active?</label>
        </div>
      </div>
    </div>

    <hr />

    {{-- Submit Button --}}
    <div class="col-lg-12 mt-2">
      <button class="btn btn-primary" type="submit">Update Data</button>
    </div>
  </form>
  {{-- Form Edit :end --}}
</x-modal-layout>