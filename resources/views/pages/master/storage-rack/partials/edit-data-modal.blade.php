<x-modal-layout id="edit_data_master_storage_rack_modal" size="" title="Edit Data Storage Rack">
  {{-- Form Edit :begin --}}
  <form class="row g-2" id="edit_data_storage_rack" autocomplete="off">
    {{-- Name --}}
    <div class="col-lg-6">
      <label class="form-label" for="edit_data_storage_rack_name">Name
        <span class="text-danger">*</span>
      </label>
      <input autocomplete="off" class="form-control" id="edit_data_storage_rack_name" name="name" type="text"
        placeholder="storage rack name" />
    </div>

    {{-- Storage --}}
    <div class="col-lg-4">
      <label class="form-label" for="edit_data_storage_rack_storage">Storage
        <span class="text-danger">*</span>
      </label>
      <select class="form-control" id="edit_data_storage_rack_storage" name="storage_id"
        placeholder="Choose storage..."></select>
    </div>

    {{-- Is Active? --}}
    <div class="col-lg-3">
      <div>
        <div class="form-check form-check-info my-1">
          <input checked="" class="form-check-input" id="edit_data_storage_rack_is_active" name="is_active"
            type="checkbox" />
          <label class="form-check-label" for="edit_data_storage_rack_is_active">Active?</label>
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