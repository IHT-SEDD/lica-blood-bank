<x-modal-layout id="edit_data_master_vendor_modal" size="" title="Edit Data Vendor">
  {{-- Form Edit :begin --}}
  <form class="row g-2" id="edit_data_vendor" autocomplete="off">
    {{-- Name --}}
    <div class="col-lg-12">
      <label class="form-label" for="edit_data_vendor_name">Name
        <span class="text-danger">*</span>
      </label>
      <input autocomplete="off" class="form-control" id="edit_data_vendor_name" name="name" type="text"
        placeholder="Vendor name" />
    </div>

    {{-- Address --}}
    <div class="col-lg-12">
      <label class="form-label" for="edit_data_vendor_address">Address</label>
      <textarea autocomplete="off" class="form-control" id="edit_data_vendor_address" name="address" rows="5"
        placeholder="Vendor address"></textarea>
    </div>

    {{-- Phone Number --}}
    <div class="col-lg-6">
      <label class="form-label" for="edit_data_vendor_phone_number">Phone Number</label>
      <input autocomplete="off" class="form-control" id="edit_data_vendor_phone_number" name="phone_number" type="text"
        placeholder="Vendor PIC phone number" />
    </div>

    {{-- Telephone Number --}}
    <div class="col-lg-6">
      <label class="form-label" for="edit_data_vendor_telephone_number">Telephone Number</label>
      <input autocomplete="off" class="form-control" id="edit_data_vendor_telephone_number" name="telephone_number"
        type="text" placeholder="Vendor telephone number" />
    </div>

    {{-- PIC Name --}}
    <div class="col-lg-12">
      <label class="form-label" for="edit_data_vendor_pic_name">Manufacturer</label>
      <input autocomplete="off" class="form-control" id="edit_data_vendor_pic_name" name="pic_name"
        placeholder="Vendor PIC name" type="text" />
    </div>

    {{-- Is Active? --}}
    <div class="col-lg-12">
      <div>
        <div class="form-check form-check-success my-1">
          <input checked="" class="form-check-input" id="edit_data_vendor_is_active" type="checkbox" name="is_active" />
          <label class="form-check-label" for="edit_data_vendor_is_active">Active?</label>
        </div>
      </div>
    </div>

    <hr />

    {{-- Submit Button --}}
    <div class="col-lg-12 mt-2">
      <button class="btn btn-primary" type="submit">Update Data</button>
    </div>
  </form>
  {{-- Form Edit :end --}}
</x-modal-layout>