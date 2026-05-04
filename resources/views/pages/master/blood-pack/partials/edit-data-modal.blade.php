<x-modal-layout id="edit_data_master_blood_pack_modal" size="" title="Edit Data Blood Pack">
 {{-- Form Edit :begin --}}
 <form class="row g-2" id="edit_data_blood_pack" autocomplete="off">
  {{-- Blood Group --}}
  <div class="col-lg-6">
   <label class="form-label" for="edit_data_blood_pack_select-blood-group">{{ __('Blood Group') }}
    <span class="text-danger">*</span>
   </label>
   <select class="form-control" id="edit_data_blood_pack_select-blood-group" name="blood_group"
    placeholder="{{ __('Choose') }} {{ __('Blood Group') }}..."></select>
  </div>

  {{-- Blood Rhesus --}}
  <div class="col-lg-6">
   <label class="form-label" for="edit_data_blood_pack_select-blood-rhesus">{{ __('Blood Rhesus') }}
    <span class="text-danger">*</span>
   </label>
   <select class="form-control" id="edit_data_blood_pack_select-blood-rhesus" name="blood_rhesus"
    placeholder="{{ __('Choose') }} {{ __('Blood Rhesus') }}..."></select>
  </div>

  {{-- Blood Component --}}
  <div class="col-lg-12">
   <label class="form-label" for="edit_data_blood_pack_select-blood-component">{{ __('Blood Component') }}
    <span class="text-danger">*</span>
   </label>
   <select class="form-control" id="edit_data_blood_pack_select-blood-component" name="blood_component"
    placeholder="{{ __('Choose') }} {{ __('Blood Component') }}..."></select>
  </div>

  {{-- Warning QTY --}}
  <div class="col-lg-6">
   <label class="form-label" for="edit_data_blood_pack_warning_quantity">{{ __('Warning') }} {{ __('Quantity') }}
    <span class="text-danger">*</span>
   </label>
   <input autocomplete="off" class="form-control" id="edit_data_blood_pack_warning_quantity" name="warning_quantity"
    type="number" />
  </div>

  {{-- Danger QTY --}}
  <div class="col-lg-6">
   <label class="form-label" for="edit_data_blood_pack_danger_quantity">{{ __('Danger') }} {{ __('Quantity') }}
    <span class="text-danger">*</span>
   </label>
   <input autocomplete="off" class="form-control" id="edit_data_blood_pack_danger_quantity" name="danger_quantity"
    type="number" />
  </div>

  {{-- Is Active? --}}
  <div class="col-lg-12">
   <div>
    <div class="form-check form-check-success my-1">
     <input checked="" class="form-check-input" id="edit_data_blood_pack_is_active" type="checkbox" name="is_active" />
     <label class="form-check-label" for="is_active">{{ __('Active') }}?</label>
    </div>
   </div>
  </div>

  <hr />

  {{-- Submit Button --}}
  <div class="col-lg-12 mt-2">
   <button class="btn btn-primary" type="submit">{{ __('Update') }} {{ __('Data') }}</button>
  </div>
 </form>
 {{-- Form Edit :end --}}
</x-modal-layout>