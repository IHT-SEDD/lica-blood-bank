<x-static-modal-layout id="restore_data_stock_in_modal" size="" title="Restore Data Stock In">
 <h4 class="fw-semibold">Are your sure want to restore this data?</h4>
 <p>Data you want to restore: <span id="restored_data" class="text-capitalize fw-semibold text-muted"></span></p>
 <p class="m-0">Click <span class="badge badge-label badge-soft-success">Confirm Restore</span> if you want to restore
  it and click <span class="badge badge-label badge-soft-danger">Cancel Restore</span> if you want to cancel it
 </p>

 <hr />

 <div class="d-flex align-items-center justify-content-end mt-2 gap-2">
  {{-- Cancel Button --}}
  <div>
   <button class="btn btn-danger" data-bs-dismiss="modal" type="button">Cancel Restore</button>
  </div>
  {{-- Confirm Button --}}
  <div>
   <button class="btn btn-success" id="confirm_restore" type="submit">Confirm Restore</button>
  </div>
 </div>
</x-static-modal-layout>