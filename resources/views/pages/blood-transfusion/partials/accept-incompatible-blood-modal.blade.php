<x-static-modal-layout id="confirmation_data_approve_incompatible_modal" size=""
  title="Konfirmasi Approve Incompatible">
  <h4 class="fw-semibold">Apakah kamu yakin ingin
    <span class="text-lowercase">approve hasil incompatible</span> untuk data ini?
  </h4>
  <p class="m-0">Klik
    <span class="badge badge-label badge-soft-danger">Approve</span>
    jika kamu ingin untuk <span class="text-lowercase">approve hasil incompatible</span> dan klik
    <span class="badge badge-label badge-soft-info">Batalkan</span> jika kamu ingin membatalkannya
  </p>

  <hr />

  <div class="d-flex align-items-center justify-content-end mt-2 gap-2">
    {{-- Cancel Button --}}
    <div>
      <button class="btn btn-info" data-bs-dismiss="modal" type="button">Batalkan</button>
    </div>
    {{-- Confirm Button --}}
    <div>
      <button class="btn btn-danger" id="confirm_action" type="submit">Approve</button>
    </div>
  </div>
</x-static-modal-layout>