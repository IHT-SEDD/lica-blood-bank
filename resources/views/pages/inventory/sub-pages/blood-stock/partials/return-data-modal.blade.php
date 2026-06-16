<x-static-modal-layout id="return_data_stock_blood_modal" size="modal-lg" title="Konfirmasi Kembalikan Darah Ke Stock">
  <div class="row">
    {{-- Data darah yang akan dikembalikan --}}
    <div class="col-lg-5">
      <h4 class="fw-semibold">Data Darah Pasien Sebelum</h4>
      <hr />
      <div class="table-responsive">
        <table class="table table-borderless table-sm align-middle mb-0" style="line-height: 1;"
          data-table="patient_blood_before">
          <tr>
            <td width="160">No. Labu</td>
            <td width="10">:</td>
            <td id="bag_number">-</td>
          </tr>
          <tr>
            <td>Status Darah</td>
            <td>:</td>
            <td id="blood_status">-</td>
          </tr>
          <tr>
            <td>No. BDRS</td>
            <td>:</td>
            <td id="bdrs_no">-</td>
          </tr>
          <tr>
            <td>Nama Pasien</td>
            <td>:</td>
            <td id="patient_name">-</td>
          </tr>
          <tr>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td id="gender">-</td>
          </tr>
          <tr>
            <td>Tanggal Dikeluarkan</td>
            <td>:</td>
            <td id="released_at">-</td>
          </tr>
          <tr>
            <td>Dikeluarkan Oleh</td>
            <td>:</td>
            <td id="released_by">-</td>
          </tr>
        </table>
      </div>
    </div>

    {{-- Icon --}}
    <div class="col-lg-2">
      <div class="d-flex align-items-center justify-content-center h-100">
        <i class="ti ti-s-turn-right align-middle fs-1"></i>
      </div>
    </div>

    {{-- Data darah pasien terbaru --}}
    <div class="col-lg-5">
      <h4 class="fw-semibold">Data Darah Pasien Terbaru</h4>
      <hr />
      <div class="table-responsive">
        <table class="table table-borderless table-sm align-middle mb-0" style="line-height: 1;"
          data-table="patient_blood_new">
          <tr>
            <td width="180">No. Labu</td>
            <td width="10">:</td>
            <td id="bag_number"><select class="form-control form-control-sm tomselect-sm" id="return_data_blood_stock"
                name="blood_stock_id" placeholder="Pilih labu darah..."></select></td>
          </tr>
          <tr>
            <td>Status Darah</td>
            <td>:</td>
            <td id="blood_status">-</td>
          </tr>
          <tr>
            <td>No. BDRS</td>
            <td>:</td>
            <td id="bdrs_no">-</td>
          </tr>
          <tr>
            <td>Nama Pasien</td>
            <td>:</td>
            <td id="patient_name">-</td>
          </tr>
          <tr>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td id="gender">-</td>
          </tr>
          <tr>
            <td>Tandai Sebagai Transaksi Batal</td>
            <td>:</td>
            <td id="cancel_transaction">
              <input class="form-check-input form-check-input-light fs-14" id="is_cancel_transaction" type="checkbox"
                name="is_cancel_transaction" />
            </td>
          </tr>
          <tr class="d-none" id="cancel_transaction_reason_wrapper">
            <td id="cancel_transaction_reason" colspan="3">
              <textarea autocomplete="off" class="form-control" id="cancel_reason" name="cancel_reason" rows="6"
                placeholder="Alasan pembatalan transaksi"></textarea>
              <div class="invalid-feedback" id="cancel_reason_error"></div>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>

  <div class="row pt-4">
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