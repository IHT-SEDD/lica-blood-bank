<x-modal-layout id="edit_data_master_package-test_modal" size="" title="Edit Data Test">
 {{-- Form Edit :begin --}}
 <form class="row g-2" id="edit_data_package-test" autocomplete="off">
  {{-- Name --}}
  <div class="col-lg-12">
    <label class="form-label" for="name">Package Name
      <span class="text-danger">*</span>
    </label>
     <select class="form-control" id="edit_data_select-package" name="package"
      placeholder="Select Package"></select>
  </div>
  <div class="col-lg-12">
    <label class="form-label" for="general_code">Test List
      <span class="text-danger">*</span>
    </label>
    <select class="form-control" id="edit_data_select-test" name="tests[]"
      placeholder="Select Tests" multiple></select>
  </div>

  {{-- Is Active? --}}
  <div class="col-lg-12">
   <div>
    <div class="form-check form-check-info my-1">
     <input checked="" class="form-check-input" id="edit_data_package-test_is_active" name="is_active" type="checkbox" />
     <label class="form-check-label" for="edit_data_package-test_is_active">Active?</label>
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