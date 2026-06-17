<x-static-modal-layout id="confirmation_data_{{ $id ?? 'unknown' }}_modal" size="" title="{{ $title ?? 'Unknown' }}">
 <h4 class="fw-semibold">Apakah kamu yakin ingin
  <span class="text-lowercase">{{ $action ?? 'Unknown' }}</span> data ini?
 </h4>
 <p>Data yang kamu ingin <span class="text-lowercase">{{ $action ?? 'Unknown' }}</span>:
  <span id="confirm_data" class="text-capitalize fw-semibold text-muted"></span>
 </p>
 <p class="m-0">Klik
  <span class="badge badge-label badge-soft-danger">Konfirmasi {{ $action ?? 'Unknown' }}</span>
  jika kamu ingin untuk <span class="text-lowercase">{{ $action ?? 'Unknown' }}</span> dan klik
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
   <button class="btn btn-danger" id="confirm_action" type="submit">Konfirmasi {{ $action ?? 'Unknown' }}</button>
  </div>
 </div>
</x-static-modal-layout>