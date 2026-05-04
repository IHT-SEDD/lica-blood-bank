@extends('layouts.vertical', ['title' => 'Detail Order'])

@section('styles')
@endsection

@section('content')
<div class="row py-4">
  {{-- Full Screen Loading Overlay :begin --}}
  <div id="fullscreen_loading_overlay" style="
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0, 0, 0, 0.45);
        backdrop-filter: blur(2px);
    " class="d-flex align-items-center justify-content-center d-none">
    <div class="text-center text-white">
      <div class="spinner-border avatar-lg text-primary mb-2" role="status"></div>
      <p class="fw-semibold fs-5 mb-0">{{ __('Processing') }}...</p>
    </div>
  </div>
  {{-- Full Screen Loading Overlay :end --}}

  {{-- Header :begin --}}
  <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-1 mb-2">
    {{-- Button Back to Order List --}}
    <a href="{{ route('inventory.history-order.index') }}" class="btn btn-soft-primary">
      {{ __('Back To') }} {{ __('History Order') }}
    </a>

    {{-- Title --}}
    <h1 class="fw-bold mb-0">{{ __('Detail') }} {{ __('order of') }} <span id="po_number_title"></span></h1>
  </div>
  {{-- Header :end --}}

  {{-- Toolbar :begin --}}
  <div class="container-fluid mb-2 d-none" id="toolbar_wrapper">
    <div class="bg-light bg-opacity-50 rounded-2" style="padding: 7px;">
      <div class="row g-2 align-items-center">
        {{-- LEFT --}}
        <div class="col-12 col-lg-4 d-flex flex-wrap gap-2 justify-content-start">
          <button class="btn btn-sm btn-soft-dark fw-medium mb-0" id="print_po_btn"
            data-bs-title="{{ __('Print') }} {{ __('PO File') }}" data-bs-toggle="tooltip" data-bs-trigger="hover">
            <i class="ti ti-printer fs-4 me-2"></i>{{ __('Print') }} PO
          </button>

          <button class="btn btn-sm btn-info fw-medium mb-0" id="download_po_btn"
            data-bs-title="{{ __('Download') }} {{ __('PO File') }}" data-bs-toggle="tooltip" data-bs-trigger="hover">
            <i class="ti ti-download fs-4 me-2"></i>{{ __('Download') }} PO
          </button>

          <button class="btn btn-sm btn-primary fw-medium mb-0" id="generate_po_btn"
            data-bs-title="{{ __('Generate') }} {{ __('PO File') }}" data-bs-toggle="tooltip" data-bs-trigger="hover">
            <i class="ti ti-file-spark fs-4 me-2"></i>{{ __('Generate') }} PO
          </button>
        </div>

        {{-- CENTER --}}
        <div class="col-12 col-lg-4 d-flex flex-wrap gap-2 justify-content-center">
          <button class="btn btn-sm btn-secondary fw-medium mb-0" id="update_to_draft_btn"
            data-bs-title="{{ __('Set Status to') }} {{ __('Draft') }}" data-bs-toggle="tooltip"
            data-bs-trigger="hover">
            <i class="ti ti-notes fs-4 me-2"></i>{{ __('Draft') }}
          </button>

          <button class="btn btn-sm btn-success fw-medium mb-0" id="update_to_done_btn"
            data-bs-title="{{ __('Set Status to') }} {{ __('Done') }}" data-bs-toggle="tooltip" data-bs-trigger="hover">
            <i class="ti ti-check fs-4 me-2"></i>{{ __('Done') }}
          </button>

          <button class="btn btn-sm btn-danger fw-medium mb-0" id="delete_order_btn"
            data-bs-title="{{ __('Delete') }} {{ __('Order Data') }}" data-bs-toggle="tooltip" data-bs-trigger="hover">
            <i class="ti ti-trash fs-4 me-2"></i>{{ __('Delete') }}
          </button>
        </div>

        {{-- RIGHT --}}
        <div class="col-12 col-lg-4 d-flex flex-wrap gap-2 justify-content-lg-end justify-content-start">
          <button class="btn btn-sm btn-soft-dark fw-medium mb-0" id="edit_order_btn">
            <i class="ti ti-file-pencil fs-4 me-2"></i>{{ __('Edit') }} {{ __('Order Data') }}
          </button>

          <button class="btn btn-sm btn-soft-danger fw-medium mb-0" id="cancel_edit_order_btn">
            <i class="ti ti-x fs-4 me-2"></i>{{ __('Cancel') }} {{ __('Edit') }} {{ __('Order Data') }}
          </button>

          <button class="btn btn-sm btn-success fw-medium mb-0" id="submit_order_btn">
            <i class="ti ti-check fs-4 me-2"></i>{{ __('Submit') }} {{ __('Changes') }}
          </button>
        </div>
      </div>
    </div>
  </div>
  {{-- Toolbar :end --}}

  {{-- Table Blood Order :begin --}}
  <div class="col-lg-8 col-12">
    <div class="card">
      {{-- Card Header --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title text-capitalize mb-0">{{ __('Blood Order') }}</h5>
        <div class="card-action">
          <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
        </div>
      </div>

      {{-- Card Body --}}
      <div class="card-body">
        {{-- Add Row Button --}}
        <div class="col-lg-4 mb-2">
          <div class="input-group">
            <input aria-label="Count Table Row" class="form-control form-control-sm" type="number" min="1"
              id="add_row_blood_data_count" placeholder="Count row" />
            <button class="btn btn-sm btn-dark add_row_blood_data" type="button" id="add_row_blood_data">
              <i class="ti ti-plus fs-4 me-2"></i>{{ __('Row') }}
            </button>
          </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive" id="table_blood_data_wrapper">
          <table class="table table-sm table-striped align-middle mb-0" id="table_blood_data">
            <thead class="bg-light align-middle bg-opacity-25 thead-sm">
              <tr class="text-uppercase fs-xxs">
                <th>{{ __('Blood Pack') }}<span class="text-danger">*</span></th>
                <th>{{ __('Quantity') }}<span class="text-danger">*</span></th>
                <th>{{ __('Note') }}</th>
                <th>{{ __('Action') }}</th>
              </tr>
            </thead>
            <tbody id="blood_data_row">
              {{-- Populate by JS --}}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  {{-- Table Blood Order :end --}}

  {{-- Order Detail :begin --}}
  <div class="col-lg-4 col-12">
    <div class="card">
      {{-- Card Header --}}
      <div class="card-header justify-content-between align-items-center">
        <h5 class="card-title text-capitalize mb-0">{{ __('Order Data') }}</h5>

        <div class="card-action">
          <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
        </div>
      </div>

      {{-- Card Body --}}
      <div class="card-body">
        <div class="row g-2">
          {{-- PO Number --}}
          <div class="col-lg-6 col-12">
            <label class="form-label" for="po_number">{{ __('PO Number') }}</label>
            <input autocomplete="off" class="form-control" id="po_number" type="text" placeholder="PO Number"
              disabled />
          </div>

          {{-- Vendor --}}
          <div class="col-lg-6 col-12">
            <label class="form-label" for="select-vendor">{{ __('Vendor') }}
              <span class="text-danger">*</span>
            </label>
            <select class="form-control" id="select-vendor" name="vendor_id" placeholder="Choose vendor..."></select>
          </div>

          {{-- Description --}}
          <div class="col-12">
            <label class="form-label" for="description">{{ __('Description') }}</label>
            <textarea autocomplete="off" class="form-control" id="description" name="description" rows="5"
              placeholder="Order description" readonly></textarea>
          </div>
        </div>
      </div>
    </div>
  </div>
  {{-- Order Detail :end --}}

  {{-- Log Activity :begin --}}
  <div class="col-lg-6 col-12">
    <div class="card">
      {{-- Card Header --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title text-capitalize mb-0">{{ __('Log Activity') }}</h5>
        <div class="card-action">
          <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
        </div>
      </div>

      {{-- Card Body --}}
      <div class="card-body order-log-data-container">
        <div class="timeline timeline-icon-bordered">
          {{-- Populate by JS --}}
        </div>
      </div>
    </div>
  </div>
  {{-- Log Activity :end --}}
</div>
@endsection

@section('scripts')
@vite([
'resources/js/pages/inventory/history-order/detail-order.js'
])
@endsection