<x-modal-layout id="edit_data_blood_transfusion_modal" size="modal-lg" title="Edit Blood Transfusion Request">
    {{-- Form Edit :begin --}}
    <form class="row g-2" id="edit_data_blood_transfusion" autocomplete="off" novalidate>
        <input type="hidden" id="edit_data_blood_transfusion_id" name="id" />

        <div class="row">
            {{-- Title Patient --}}
            <div style="border-bottom: 2px dashed #ccc; padding-bottom: 6px; margin-bottom: 12px; margin-top: 12px;">
                <h4>{{ __('Patient Data') }}</h4>
            </div>

            {{-- Blood Group --}}
            <div class="col-xxl-6 col-md-6 col-12 mt-2">
                <label class="form-label" for="edit_data_select-blood-group">{{ __('Blood Group') }}</label>
                <select class="form-control" id="edit_data_select-blood-group" name="blood_group"
                    placeholder="{{ __('A,B,AB,O') }}"></select>
            </div>

            {{-- Blood Rhesus --}}
            <div class="col-xxl-6 col-md-6 col-12 mt-2">
                <label class="form-label" for="edit_data_select-blood-rhesus">{{ __('Blood Rhesus') }}</label>
                <select class="form-control" id="edit_data_select-blood-rhesus" name="blood_rhesus"
                    placeholder="{{ __('+/-') }}"></select>
            </div>

            {{-- Relation Name --}}
            <div class="col-xxl-6 col-md-12 col-12 mt-2">
                <label class="form-label" for="edit_data_relation_name">{{ __('Relation Name') }}
                </label>
                <input autocomplete="off" class="form-control" id="edit_data_relation_name" name="relation_name" type="text"
                    placeholder="{{ __('Patient Relation Name') }}" />
            </div>

            {{-- Relation Type --}}
            <div class="col-xxl-6 col-md-12 col-12 mt-2">
                <label class="form-label" for="edit_data_select-relation-type">{{ __('Relation Type') }}
                </label>
                <select class="form-control" id="edit_data_select-relation-type" name="relation_type"
                    placeholder="{{ __('Choose Relation Type') }}"></select>
            </div>

            {{-- Title Transaction --}}
            <div style="border-bottom: 2px dashed #ccc; padding-bottom: 6px; margin-bottom: 12px; margin-top: 24px;">
                <h4>{{ __('Transaction') }} {{ __('Details') }}</h4>
            </div>

            {{-- Insurance --}}
            <div class="col-xxl-6 col-md-6 col-12 mt-2">
                <label class="form-label" for="edit_data_select-insurance">{{ __('Insurance') }}</label>
                <span class="text-danger">*</span>
                <select class="form-control" id="edit_data_select-insurance" name="insurance_id"
                    placeholder="{{ __('Choose') }} {{ __('Insurance') }}" ></select>
            </div>

            {{-- Room --}}
            <div class="col-xxl-6 col-md-6 col-12 mt-2">
                <label class="form-label" for="edit_data_select-room">{{ __('Room') }}</label>
                <span class="text-danger">*</span>
                <select class="form-control" id="edit_data_select-room" name="room_id"
                    placeholder="{{ __('Choose') }} {{ __('Room') }}" ></select>
            </div>

            {{-- Doctor --}}
            <div class="col-xxl-12 col-md-12 col-12 mt-2">
                <label class="form-label" for="edit_data_select-doctor">{{ __('Doctor') }}</label>
                <span class="text-danger">*</span>
                <select class="form-control" id="edit_data_select-doctor" name="doctor_id"
                    placeholder="{{ __('Choose') }} {{ __('Doctor') }}" ></select>
            </div>

            {{-- Blood Required Date --}}
            <div class="col-xxl-12 col-md-12 col-12 mt-2">
                <label class="col-form-label m-0" for="edit_data_blood_required_at">
                    {{ __('Required Date') }}
                    <span class="text-danger">*</span>
                </label>
                <input class="form-control patient-blood-required-date" aria-describedby="edit_data_blood_required_at"
                    data-date-format="d-m-Y" data-provider="flatpickr" type="text" id="edit_data_blood_required_at" name="blood_required_at"
                    placeholder="{{ __('Choose') }} {{ __('Required Date') }}" />
            </div>

            {{-- Diagnosis --}}
            <div class="col-xxl-12 col-md-12 col-12 mt-2">
                <label class="form-label" for="edit_data_diagnosis">{{ __('Diagnosis') }}</label>
                <textarea class="form-control" id="edit_data_diagnosis" name="diagnosis" placeholder="{{ __('Diagnosis') }}"
                    rows="3"></textarea>
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
