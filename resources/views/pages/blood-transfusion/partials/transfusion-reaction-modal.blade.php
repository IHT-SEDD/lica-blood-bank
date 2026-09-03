<x-modal-layout id="transfusion_reaction_modal" title="Tambahkan Reaksi Transfusi">
    {{-- Form :begin --}}
    <form class="row g-2" id="transfusion_reaction" autocomplete="off" novalidate>
        <input type="hidden" id="transfusion_reaction_blood_transfusion_id" name="id" />

        <div class="row">
            {{-- Title Patient --}}
            <div style="border-bottom: 2px dashed #ccc; padding-bottom: 6px; margin-bottom: 12px; margin-top: 12px;">
                <h4>{{ __('Data Pasien') }}</h4>
            </div>

            {{-- Patient Info --}}
            <div class="col-xxl-6 col-md-6 col-12 mt-1">
                <small class="text-muted d-block">{{ __('Nama Pasien') }}</small>
                <span id="reaction_patient_name" class="fw-semibold">-</span>
            </div>
            <div class="col-xxl-6 col-md-6 col-12 mt-1">
                <small class="text-muted d-block">{{ __('Nomor BDRS') }}</small>
                <span id="reaction_patient_bdrs_number" class="fw-semibold">-</span>
            </div>
            <div class="col-xxl-6 col-md-6 col-12 mt-1">
                <small class="text-muted d-block">{{ __('Nomor Order') }}</small>
                <span id="reaction_patient_order_number" class="fw-semibold">-</span>
            </div>
            <div class="col-xxl-6 col-md-6 col-12 mt-1">
                <small class="text-muted d-block">{{ __('Nomor Labu') }}</small>
                <span id="reaction_patient_bag_number" class="fw-semibold">-</span>
            </div>
            <div class="col-xxl-6 col-md-6 col-12 mt-1">
                <small class="text-muted d-block">{{ __('Golongan Darah') }}</small>
                <span id="reaction_patient_blood_group" class="fw-semibold">-</span>
            </div>
            <div class="col-xxl-6 col-md-6 col-12 mt-1">
                <small class="text-muted d-block">{{ __('Rhesus') }}</small>
                <span id="reaction_patient_blood_rhesus" class="fw-semibold">-</span>
            </div>

            {{-- Title Transfusion Reaction --}}
            <div style="border-bottom: 2px dashed #ccc; padding-bottom: 6px; margin-bottom: 12px; margin-top: 24px;">
                <h4>{{ __('Reaksi Transfusi') }}</h4>
            </div>

            {{-- Tgl. transfusi --}}
            <div class="col-lg-6">
                <label class="form-label" for="transfusion_reaction_at">Tanggal & Waktu Transfusi</label>
                <span class="text-danger">*</span>
                <input type="text" class="form-control form-control-sm flatpickr-custom" id="transfusion_reaction_at"
                    name="transfusion_at" data-enable-time="d-m-Y H:i" data-provider="flatpickr" />
            </div>

            {{-- Muncul hanya jika window.clientConfig?.blood_transfusion?.reaction_transfusion_via_select === true --}}
            <div class="col-xxl-12 col-md-12 col-12 mt-2 d-none" id="reaction_via_select_wrapper">
                <label class="form-label" for="transfusion_reaction_select">{{ __('Reaksi Transfusi') }}</label>
                <span class="text-danger">*</span>
                <select class="form-control" id="transfusion_reaction_select" name="transfusion_reaction_select"
                    placeholder="{{ __('Pilih reaksi transfusi!') }}"></select>
            </div>

            {{-- Default: textarea --}}
            <div class="col-xxl-12 col-md-12 col-12 mt-2" id="reaction_via_text_wrapper">
                <label class="form-label" for="transfusion_reaction_text">{{ __('Reaksi Transfusi') }}</label>
                <span class="text-danger">*</span>
                <textarea class="form-control" id="transfusion_reaction_text" name="transfusion_reaction_text"
                    placeholder="{{ __('Tulis detail reaksi transfusi yang dialami...') }}" rows="5"></textarea>
            </div>
        </div>

        <hr />

        {{-- Submit Button --}}
        <div class="col-lg-12 mt-2">
            <button class="btn btn-primary" type="submit" id="confirm_reaction_transfusion" disabled>
                {{ __('Update') }} {{ __('Data') }}
            </button>
        </div>
    </form>
    {{-- Form :end --}}
</x-modal-layout>