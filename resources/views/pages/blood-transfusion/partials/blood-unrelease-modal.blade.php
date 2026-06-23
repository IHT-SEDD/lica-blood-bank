<x-static-modal-layout id="blood_unrelease_modal" size="" title="Konfirmasi Tidak Mengeluarkan Darah">
 <h4 class="fw-semibold">Apakah kamu yakin ingin tidak mengeluarkan darah ini?</h4>

 <div class="row g-2 pt-2 pb-3">
  {{-- No. Darah --}}
  <div class="col-12">
   <label class="form-label" for="blood_number">{{ __('No. Darah') }}
    <span class="text-danger">*</span>
   </label>

   <div class="input-group">
    <input autocomplete="off" class="form-control" id="blood_number" name="blood_number" type="text"
     placeholder="Scan barcode labu untuk validasi" />
    <span class="input-group-text" id="blood_number_status" style="min-width:36px;">
     {{-- icon status validasi --}}
    </span>
   </div>
   <div class="invalid-feedback d-block" id="blood_number_error" style="display:none!important;"></div>
  </div>
 </div>

 <p class="m-0 fw-medium">Klik
  <span class="badge badge-label badge-soft-danger">Tidak Keluarkan</span>
  jika kamu ingin tidak mengeluarkan dan klik
  <span class="badge badge-label badge-soft-success">Batalkan</span>
  jika kamu ingin membatalkannya
 </p>

 <hr />

 <div class="d-flex align-items-center justify-content-end mt-2 gap-2">
  {{-- Cancel Button --}}
  <div>
   <button class="btn btn-success" data-bs-dismiss="modal" type="button">Batalkan</button>
  </div>
  {{-- Confirm Button --}}
  <div>
   <button class="btn btn-danger" id="confirm_unrelease" type="submit">Tidak Keluarkan</button>
  </div>
 </div>
</x-static-modal-layout>