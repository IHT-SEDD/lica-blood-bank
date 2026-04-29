{{-- Form Add New Order :begin --}}
<form id="add_new_order" autocomplete="off">
  {{-- Order Data Detail --}}
  <div style="border-bottom: 2px dashed #ccc; padding-bottom: 6px; margin-bottom: 12px;">
    <h4>Order Data Details</h4>
  </div>

  {{-- Order Data Inputs :begin --}}
  <div class="row">
    {{-- Left Side :begin --}}
    <div class="col-lg-6 col-12 mb-1">
      {{-- PO Number --}}
      <div class="col-12">
        <label class="form-label" for="po_number">PO Number
          <span class="text-danger">*</span>
        </label>
        <input autocomplete="off" class="form-control" id="po_number" name="po_number" type="text"
          placeholder="PO Number" readonly />
        <small class="form-text text-muted">
          Click inputs to generate po number.
        </small>
      </div>

      {{-- Description --}}
      <div class="col-12">
        <label class="form-label" for="description">Description</label>
        <textarea autocomplete="off" class="form-control" id="description" name="description" rows="5"
          placeholder="Order description"></textarea>
      </div>
    </div>
    {{-- Left Side :begin --}}

    {{-- Right Side :begin --}}
    <div class="col-lg-6 col-12">
      {{-- Vendor Data :begin --}}
      <div class="row mb-1">
        {{-- Vendor --}}
        <div class="col-lg-6 col-12">
          <label class="form-label" for="select-vendor">Vendor
            <span class="text-danger">*</span>
          </label>
          <select class="form-control" id="select-vendor" name="vendor_id" placeholder="Choose vendor..."></select>
        </div>

        {{-- Vendor Name --}}
        <div class="col-lg-6 col-12">
          <label class="form-label" for="vendor-name">Vendor Name</label>
          <input autocomplete="off" class="form-control" id="vendor-name" type="text" placeholder="Vendor name"
            readonly />
          <small class="form-text text-muted">
            Vendor name fill automatic based on vendor you choose.
          </small>
        </div>

        {{-- Vendor Address --}}
        <div class="col-12">
          <label class="form-label" for="vendor-address">Vendor Address</label>
          <textarea autocomplete="off" class="form-control" id="vendor-address" rows="5" placeholder="Vendor address"
            readonly></textarea>
          <small class="form-text text-muted">
            Vendor address fill automatic based on vendor you choose.
          </small>
        </div>
      </div>
      {{-- Vendor Data :end --}}
    </div>
    {{-- Right Side :begin --}}
  </div>
  {{-- Order Data Inputs :end --}}

  {{-- Blood Data Detail --}}
  <div class="d-flex align-items-center justify-content-start gap-2"
    style="border-bottom: 2px dashed #ccc; padding-bottom: 6px; margin-bottom: 12px;">
    <h4 class="m-0">Blood Data Details</h4>
    <button class="btn btn-sm btn-info" type="button" id="add_blood_data" data-bs-title="Add blood row"
      data-bs-toggle="tooltip" data-bs-trigger="hover">
      <i data-lucide="heart-plus" class="align-middle fs-4"></i>
    </button>
  </div>

  {{-- Blood Data Inputs :begin --}}
  <div class="row" id="blood_data_container">
    {{-- Populate By JS --}}
  </div>
  {{-- Blood Data Inputs :end --}}

  <hr />

  {{-- Submit Button --}}
  <div class="mt-6 d-flex align-items-center justify-content-end gap-2">
    <div class="form-check form-check-info">
      <input class="form-check-input" id="save_as_draft" name="draft" type="checkbox" />
      <label class="form-check-label fw-semibold" for="save_as_draft">Save as draft?</label>
    </div>
    <button class="btn btn-success" type="submit">Add New Order</button>
  </div>
</form>
{{-- Form Add New Order :end --}}