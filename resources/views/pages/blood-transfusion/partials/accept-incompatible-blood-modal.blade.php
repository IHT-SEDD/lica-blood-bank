<x-modal-layout id="accept_incompatible_blood_modal" size="modal-md" title="{{ __('Accept Incompatible Blood') }}">
  <div class="text-center">
    <i class="ti ti-alert-triangle text-warning mb-3" style="font-size: 5rem;"></i>
    <h4 class="mb-3">{{ __('Are you sure you want to accept this incompatible blood pack?') }}</h4>
    <p class="text-muted mb-4">{{ __('This action will mark the blood transfusion as approved for incompatible blood.
      This cannot be undone.') }}</p>
    <div class="d-flex align-items-center justify-content-center gap-2">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
      <button type="button" class="btn btn-warning" id="confirm_accept_incompatible">{{ __('Confirm Accept') }}</button>
    </div>
  </div>
</x-modal-layout>