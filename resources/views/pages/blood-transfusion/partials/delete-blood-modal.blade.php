<x-static-modal-layout id="delete_data_blood_modal" size=""
 title="Konfirmasi Hapus Data Darah">
 <h4 class="fw-semibold">Apakah kamu yakin ingin menghapus data ini?</h4>
 
 <p class="m-0">Klik <span class="badge badge-label badge-soft-danger">Hapus</span> jika kamu ingin menghapus dan klik
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
   <button class="btn btn-danger" id="confirm_delete" type="submit">Hapus</button>
  </div>
 </div>
</x-static-modal-layout>