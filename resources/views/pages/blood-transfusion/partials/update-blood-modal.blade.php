<x-static-modal-layout id="update_blood_modal" size="" title="Konfirmasi Penggunaan Darah">
 <div id="blood_suggestion_wrapper" style="display:none;">
  <div class="pb-3">
   <div class="d-flex align-items-center justify-content-center gap-1">
    <span class="fw-medium">Pada sistem terdapat darah dengan nomor</span>
    <span class="fw-bold" id="blood_suggestion"></span>
   </div>
   <div class="d-flex align-items-center justify-content-center gap-1">
    <span class="fw-medium"> dengan tanggal expire </span>
    <span class="fw-bold" id="blood_sugestion_expiry_date"></span>
   </div>
  </div>

  <p class="m-0">Klik
   <span class="badge badge-label badge-soft-success">Gunakan Rekomendasi</span>
   jika kamu ingin menggunakan rekomendasi dan klik
   <span class="badge badge-label badge-soft-danger">Batalkan</span>
   jika kamu ingin membatalkannya dan kembali menggunakan labu yang kamu scan
  </p>

  <hr />

  <div class="d-flex align-items-center justify-content-end mt-2 gap-2">
   <div>
    <button class="btn btn-danger" id="cancel_use_blood_recomendation" type="button">Batalkan</button>
   </div>
   <div>
    <button class="btn btn-success" id="confirm_use_blood_recomendation" type="submit">Gunakan Rekomendasi</button>
   </div>
  </div>
 </div>

 <div id="blood_summary_wrapper" style="display:none;">
  <div class="pb-3">
   <div class="d-flex align-items-center justify-content-center gap-1">
    <span class="fw-semibold">Darah yang ingin anda gunakan :</span>
    <span class="fw-bold" id="blood_summary"></span>
   </div>
  </div>

  <p class="m-0">Klik
   <span class="badge badge-label badge-soft-success">Gunakan</span>
   jika kamu ingin menggunakan dan klik
   <span class="badge badge-label badge-soft-danger">Batalkan</span>
   jika kamu ingin membatalkannya
  </p>

  <hr />

  <div class="d-flex align-items-center justify-content-end mt-2 gap-2">
   <div>
    <button class="btn btn-danger" data-bs-dismiss="modal" type="button">Batalkan</button>
   </div>
   <div>
    <button class="btn btn-success" id="confirm_use_blood" type="submit">Gunakan</button>
   </div>
  </div>
 </div>
</x-static-modal-layout>