<x-static-modal-layout id="confirmation_data_{{ $id ?? 'unknown' }}_modal" size="" title="{{ $title ?? 'Unknown' }}">
 <h4 class="fw-semibold">Are your sure want to
  <span class="text-lowercase">{{ $action ?? 'Unknown' }}</span> this data?
 </h4>
 <p>Data you want to <span class="text-lowercase">{{ $action ?? 'Unknown' }}</span>:
  <span id="confirm_data" class="text-capitalize fw-semibold text-muted"></span>
 </p>
 <p class="m-0">Click
  <span class="badge badge-label badge-soft-danger">Confirm {{ $action ?? 'Unknown' }}</span>
  if you want to <span class="text-lowercase">{{ $action ?? 'Unknown' }}</span> it and click
  <span class="badge badge-label badge-soft-info">I'm Not Sure</span> if you want to cancel it
 </p>

 <hr />

 <div class="d-flex align-items-center justify-content-end mt-2 gap-2">
  {{-- Cancel Button --}}
  <div>
   <button class="btn btn-info" data-bs-dismiss="modal" type="button">I'm Not Sure</button>
  </div>
  {{-- Confirm Button --}}
  <div>
   <button class="btn btn-danger" id="confirm_action" type="submit">Confirm {{ $action ?? 'Unknown' }}</button>
  </div>
 </div>
</x-static-modal-layout>