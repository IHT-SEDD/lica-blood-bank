<x-static-modal-layout id="blood_release_all_modal" size="" title="Konfirmasi Pengeluaran Semua Darah">
 <h4 class="fw-semibold">Apakah kamu yakin ingin mengeluarkan semua darah ini?</h4>

 <div class="row g-2 pt-2 pb-3">
  {{-- Penerima Darah --}}
  <div class="col-12">
   <label class="form-label" for="blood_received_by_all">{{ __('Penerima Darah') }}
    <span class="text-danger">*</span>
   </label>
   <input autocomplete="off" class="form-control" id="blood_received_by_all" name="blood_received_by" type="text"
    placeholder="Nama penerima darah" />
   <div class="invalid-feedback" id="blood_received_by_all_error"></div>
  </div>

  {{-- No. Darah --}}
  <div class="col-12">
   <label class="form-label" for="blood_numbers_all">{{ __('No. Darah') }}
    <span class="text-danger">*</span>
   </label>
   <textarea autocomplete="off" class="form-control" id="blood_numbers_all" name="blood_numbers" rows="5"
    placeholder="Scan barcode labu satu per baris"></textarea>
   <div class="invalid-feedback d-block" id="blood_numbers_all_error" style="display:none!important;"></div>
  </div>
 </div>

 <p class="m-0">Klik
  <span class="badge badge-label badge-soft-success">Keluarkan Semua</span>
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
  {{-- Confirm Button --}}
  <div>
   <button class="btn btn-success" id="confirm_release_all" type="submit">Keluarkan Semua</button>
  </div>
 </div>
</x-static-modal-layout>