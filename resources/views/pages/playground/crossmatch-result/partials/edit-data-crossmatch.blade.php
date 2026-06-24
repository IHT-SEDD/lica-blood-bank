<x-modal-layout id="edit_data_crossmatch_modal" size="" title="Ubah Data Crossmatch">
 {{-- Form Edit :begin --}}
 <form class="row g-2" id="edit_data_crossmatch" autocomplete="off">
  {{-- Mayor Result --}}
  <div class="col-lg-12">
   <label class="form-label" for="edit_data_crossmatch_mayor_result">Mayor</label>
   <select class="form-control form-control-sm tomselect-sm" id="edit_data_crossmatch_mayor_result" name="mayor_result"
    placeholder="Pilih hasil..."></select>
  </div>
  {{-- Minor Result --}}
  <div class="col-lg-12">
   <label class="form-label" for="edit_data_crossmatch_minor_result">Minor</label>
   <select class="form-control form-control-sm tomselect-sm" id="edit_data_crossmatch_minor_result" name="minor_result"
    placeholder="Pilih hasil..."></select>
  </div>
  {{-- Auto Control Result --}}
  <div class="col-lg-12 mb-2">
   <label class="form-label" for="edit_data_crossmatch_auto_control_result">Auto Control</label>
   <select class="form-control form-control-sm tomselect-sm" id="edit_data_crossmatch_auto_control_result"
    name="auto_control_result" placeholder="Pilih hasil..."></select>
  </div>

  <hr />

  {{-- Submit Button --}}
  <div class="col-lg-12 mt-2">
   <button class="btn btn-primary" type="submit">Ubah Data</button>
  </div>
 </form>
 {{-- Form Edit :end --}}
</x-modal-layout>