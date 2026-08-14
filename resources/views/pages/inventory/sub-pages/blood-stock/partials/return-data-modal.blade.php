<x-static-modal-layout id="return_data_stock_blood_modal" size="" title="Konfirmasi Kembalikan Darah Ke Stock">
  <div class="row">
    <div class="col-lg-12">
      <div class="bg-warning bg-opacity-50 text-dark p-2 rounded-2">
        <h5 class="fw-semibold">PERHATIAN!</h5>
        <p class="fw-semibold mb-0">Dengan menekan tombol
          <span class="badge badge-label fw-semibold badge-soft-danger">Konfirmasi</span>
          maka anda menyetujui untuk mengembalikan darah yang telah
          <span class="badge badge-label fw-semibold badge-soft-dark">Dikeluarkan</span> atau <span
            class="badge badge-label fw-semibold badge-soft-dark">Sudah Digunakan</span>
          untuk pasien diatas.
        </p>
        <p class="fw-semibold mb-0">
          Aksi ini tidak bisa dibatalkan, harap berhati-hati sebelum mengambil keputusan.
        </p>
      </div>
    </div>
  </div>

  <hr />

  <div class="d-flex align-items-center justify-content-end mt-2 gap-2">
    <div>
      <button class="btn btn-info" data-bs-dismiss="modal" type="button">Batalkan</button>
    </div>
    <div>
      <button class="btn btn-danger" id="confirm_return" type="submit">Konfirmasi</button>
    </div>
  </div>
</x-static-modal-layout>