<x-static-modal-layout id="delete_data_master_storage_rack_modal" size="" title="Delete Data Storage Rack">
 <h4 class="fw-semibold">Are your sure want to delete this data?</h4>
 <p>Data you want to delete: <span id="deleted_data" class="text-capitalize fw-semibold text-muted"></span></p>
 <p class="m-0">Click <span class="badge badge-label badge-soft-danger">Confirm Delete</span> if you want to delete it
  and click <span class="badge badge-label badge-soft-info">I'm Not Sure</span> if you want to cancel it</p>

 <hr />

 <div class="d-flex align-items-center justify-content-end mt-2 gap-2">
  {{-- Cancel Button --}}
  <div>
   <button class="btn btn-info" data-bs-dismiss="modal" type="button">I'm Not Sure</button>
  </div>
  {{-- Confirm Button --}}
  <div>
   <button class="btn btn-danger" id="confirm_delete" type="submit">Confirm Delete</button>
  </div>
 </div>
</x-static-modal-layout>