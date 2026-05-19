<x-modal-layout id="edit_data_master_role_modal" size="" title="Edit Data Role">
  {{-- Form Edit :begin --}}
  <form class="row g-2" id="edit_data_role" autocomplete="off">
    {{-- Name --}}
    <div class="col-lg-6">
      <label class="form-label" for="edit_data_role_name">Name
        <span class="text-danger">*</span>
      </label>
      <input autocomplete="off" class="form-control" id="edit_data_role_name" name="name" type="text"
        placeholder="Role name" />
    </div>

    {{-- Guard --}}
    <div class="col-lg-6">
      <label class="form-label" for="edit_data_role_guard_name">Guard
        <span class="text-danger">*</span>
      </label>
      <input autocomplete="off" class="form-control" id="edit_data_role_guard_name" name="guard_name" type="text"
        placeholder="Role guard" />
    </div>

    {{-- Description --}}
    <div class="col-lg-12">
      <label class="form-label" for="edit_data_role_description">Description</label>
      <textarea autocomplete="off" class="form-control" id="edit_data_role_description" name="description" rows="5"
        placeholder="Role description"></textarea>
    </div>

    <hr />

    {{-- Submit Button --}}
    <div class="col-lg-12 mt-2">
      <button class="btn btn-primary" type="submit">Update Data</button>
    </div>
  </form>
  {{-- Form Edit :end --}}
</x-modal-layout>