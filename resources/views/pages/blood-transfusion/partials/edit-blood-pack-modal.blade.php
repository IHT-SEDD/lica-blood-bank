<x-modal-layout id="edit_blood_pack_modal" size="modal-xl" title="Edit Blood Pack">
    <div class="row g-3">
        {{-- Left: Available Blood Packs Datatable --}}
        <div class="col-lg-6 col-12">
            <h6 class="text-uppercase fw-semibold mb-3">{{ __('Available Blood Packs') }}</h6>
            <div class="table-responsive">
                <table class="table table-sm table-hover mt-2" id="edit-blood-pack-available-table">
                    <thead>
                        <tr>
                            <th>{{ __('Blood Group') }}</th>
                            <th>{{ __('Rhesus') }}</th>
                            <th>{{ __('Component') }}</th>
                            <th class="text-end">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        {{-- Right: Selected Blood Packs --}}
        <div class="col-lg-6 col-12">
            <h6 class="text-uppercase fw-semibold mb-3">{{ __('Selected Blood Packs') }}</h6>
            <div class="table-responsive">
                <table class="table table-hover" id="edit-blood-pack-selected-table">
                    <thead>
                        <tr>
                            <th>{{ __('Blood Group') }}</th>
                            <th>{{ __('Rhesus') }}</th>
                            <th>{{ __('Component') }}</th>
                            <th class="text-end">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Footer Save Button --}}
    <div class="d-flex justify-content-end mt-4 gap-2">
        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">{{ __('Cancel') }}</button>
        <button class="btn btn-primary" id="btn-save-edit-blood-pack" type="button">
            {{ __('Save Changes') }}
        </button>
    </div>
</x-modal-layout>
