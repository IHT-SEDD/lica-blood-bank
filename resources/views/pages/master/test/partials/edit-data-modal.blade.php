<x-modal-layout id="edit_data_master_test_modal" size="" title="Edit Data Test">
 {{-- Form Edit :begin --}}
 <form class="row g-2" id="edit_data_test" autocomplete="off">
  {{-- Name --}}
  <div class="col-lg-12">
   <label class="form-label" for="edit_data_test_name">Name
    <span class="text-danger">*</span>
   </label>
   <input autocomplete="off" class="form-control" id="edit_data_test_name" name="name" type="text"
    placeholder="your full name" />
  </div>
  <div class="col-lg-12">
   <label class="form-label" for="edit_data_test_general_code">General Code
    <span class="text-danger">*</span>
   </label>
   <input autocomplete="off" class="form-control" id="edit_data_test_general_code" name="general_code" type="text"
    placeholder="your full name" />
  </div>

  {{-- Is Active? --}}
  <div class="col-lg-12">
   <div>
    <div class="form-check form-check-info my-1">
     <input checked="" class="form-check-input" id="edit_data_test_is_active" name="is_active" type="checkbox" />
     <label class="form-check-label" for="edit_data_test_is_active">Active?</label>
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