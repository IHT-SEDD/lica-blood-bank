<x-static-modal-layout id="blood_release_modal" size="" title="Konfirmasi Pengeluaran Darah">
 <h4 class="fw-semibold">Apakah kamu yakin ingin mengeluarkan darah ini?</h4>

 <div class="row g-2 pt-2 pb-3">
  {{-- Penerima Darah --}}
  <div class="col-12">
   <label class="form-label" for="blood_received_by">{{ __('Penerima Darah') }}
    <span class="text-danger">*</span>
   </label>
   <input autocomplete="off" class="form-control" id="blood_received_by" name="blood_received_by" type="text"
    placeholder="Nama penerima darah" />
   <div class="invalid-feedback" id="blood_received_by_error"></div>
  </div>

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
  <span class="badge badge-label badge-soft-dark">Print Barcode</span>
  lalu klik
  <span class="badge badge-label badge-soft-success">Keluarkan</span>
  jika kamu ingin mengeluarkan dan klik
  <span class="badge badge-label badge-soft-danger">Batalkan</span>
  jika kamu ingin membatalkannya
 </p>

 <hr />

 <div class="d-flex align-items-center justify-content-end mt-2 gap-2">
  {{-- Cancel Button --}}
  <div>
   <button class="btn btn-danger" data-bs-dismiss="modal" type="button">Batalkan</button>
  </div>
  {{-- Print Barcode Button --}}
  <div>
   <button class="btn btn-dark" id="print_barcode_release_btn" type="button">Print Barcode</button>
  </div>
  {{-- Confirm Button --}}
  <div>
   <button class="btn btn-success" id="confirm_release" type="submit">Keluarkan</button>
  </div>
 </div>
</x-static-modal-layout>