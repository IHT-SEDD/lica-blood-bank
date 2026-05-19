<x-modal-layout id="edit_data_stock_blood_modal" size="" title="Edit Data Blood Stock">
 {{-- Form Edit :begin --}}
 <form class="row g-2" id="edit_data_stock_blood" autocomplete="off">
  {{-- Volume --}}
  <div class="col-lg-3">
   <label class="form-label" for="edit_data_blood_stock_volume">Volume<span class="text-danger">*</span></label>
   <input autocomplete="off" class="form-control form-control-sm" id="edit_data_blood_stock_volume" name="volume"
    type="text" placeholder="ml" />
  </div>

  {{-- Storage Rack --}}
  <div class="col-lg-9">
   <label class="form-label" for="edit_data_blood_stock_storage_rack">Storage Rack
    <span class="text-danger">*</span>
   </label>
   <select class="form-control form-control-sm" id="edit_data_blood_stock_storage_rack" name="storage_rack_id"
    placeholder="Choose storage_rack..."></select>
  </div>

  {{-- Is Expired? --}}
  <div class="col-lg-12">
   <div>
    <div class="form-check form-check-info my-1">
     <input checked="" class="form-check-input" id="edit_data_blood_stock_is_expired" name="is_expired"
      type="checkbox" />
     <label class="form-check-label" for="edit_data_blood_stock_is_expired">Expired?</label>
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