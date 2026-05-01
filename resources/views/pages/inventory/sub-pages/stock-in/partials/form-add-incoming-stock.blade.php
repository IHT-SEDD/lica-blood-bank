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
    <p class="fw-semibold fs-5 mb-0">Processing...</p>
  </div>
</div>
{{-- Full Screen Loading Overlay :end --}}

{{-- Form Add New Blood :begin --}}
<form class="row g-2" id="add_new_incoming_stock" autocomplete="off">
  {{-- Choose Purchase Order --}}
  <div class="col-lg-4">
    <label class="form-label" for="select-purchase-order">Choose Purchase Order
      <span class="text-danger">*</span>
    </label>
    <select class="form-control" id="select-purchase-order" name="po_number"
      placeholder="Choose purchase order"></select>
    <small class="form-text text-muted fs-6">
      Choose purchase order from dropdown to start add incoming stock.
    </small>
  </div>

  {{-- Batch Number --}}
  <div class="col-lg-4">
    <label class="form-label" for="batch_number">Batch Number</label>
    <input autocomplete="off" class="form-control" id="batch_number" name="batch_number" type="text" value=1
      placeholder="Batch number" />
    <small class="form-text text-muted fs-6">
      The batch number functions to mark the number of items sent in 1 order.
    </small>
  </div>

  {{-- Choose Add Incoming Stock Method --}}
  <div class="col-lg-4">
    <label class="form-label" for="select-add-data-method">Choose Method of Add Incoming Stock
      <span class="text-danger">*</span>
    </label>
    <select class="form-control" id="select-add-data-method" name="method_add" placeholder="Choose method"></select>
    <small class="form-text text-muted fs-6">
      Choose manual if you want to add manually or choose excel if you want to import an excel data.
    </small>
  </div>

  <hr />

  {{-- Main Content :begin --}}
  <div class="col-lg-12 my-1" id="main_form_wrapper">
    {{-- Loading Form :begin --}}
    <div class="d-flex align-items-center justify-content-center">
      <div class="spinner-border avatar-lg text-primary m-2 d-none" role="status"
        id="loading_form_add_new_incoming_stock"></div>
    </div>
    {{-- Loading Form :end --}}

    {{-- Add Manual :begin --}}
    <div class="d-none" id="add_manually_method_wrapper">
      {{-- Add Row Blood Data Button --}}
      <div class="w-xxl-25 w-lg-50 w-100 mb-2">
        <div class="input-group">
          <input aria-label="Count Table Row" class="form-control" type="number" min="1" id="add_row_blood_data_count"
            placeholder="Count row" />
          <button class="btn btn-dark add_row_blood_data" type="button" id="add_row_blood_data">
            <i class="ti ti-plus fs-4 me-2"></i>
            Add Row
          </button>
        </div>
      </div>

      {{-- Table Blood Data :begin --}}
      <div class="table-responsive" id="table_blood_data_wrapper">
        <table class="table table-sm table-striped align-middle mb-0" id="table_blood_data">
          <thead class="bg-light align-middle bg-opacity-25 thead-sm">
            <tr class="text-uppercase fs-xxs">
              <th>Bag Number <span class="text-danger">*</span></th>
              <th>Group <span class="text-danger">*</span></th>
              <th style="width: 3%;">Rhesus <span class="text-danger">*</span></th>
              <th>Component <span class="text-danger">*</span></th>
              <th style="width: 5%;">Volume <span class="text-danger">*</span></th>
              <th style="width: 8%;">Aftap <span class="text-danger">*</span></th>
              <th style="width: 8%;">Expiry <span class="text-danger">*</span></th>
              <th style="width: 8%;">Process <span class="text-danger">*</span></th>
              <th>HIV?</th>
              <th>HCV?</th>
              <th>HbsAG?</th>
              <th>Syphilis?</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="blood_data_row">
            {{-- Populate by JS --}}
          </tbody>
        </table>
      </div>
      {{-- Table Blood Data :end --}}
    </div>
    {{-- Add Manual :end --}}

    {{-- Add Via Excel :begin --}}
    <div class="d-none" id="add_excel_method_wrapper">
      <div class="d-flex align-items-center justify-content-start gap-3">
        {{-- Download Template --}}
        <div>
          <label class="form-label" for="download_template_excel">Download Template Excel</label>
          <button
            class="btn btn-sm btn-soft-secondary d-flex align-items-center justify-content-center download_template_excel"
            type="button" id="download_template_excel">
            <i class="ti ti-download fs-4 me-2"></i>
            Template Excel
          </button>
        </div>
        {{-- Upload File --}}
        <div class="col-4">
          <label class="form-label" for="incoming_stock_excel">Upload Data
            <span class="text-danger">*</span>
          </label>
          <input class="form-control" id="incoming_stock_excel" type="file" name="incoming_stock_excel"
            accept=".xlsx,.xls,.csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" />
        </div>
      </div>
    </div>
    {{-- Add Via Excel :end --}}
  </div>
  {{-- Main Content :end --}}

  <hr />

  {{-- Submit Button --}}
  <div class="col-lg-12 m-0">
    <button class="btn btn-primary" type="submit">Add Incoming Stock</button>
  </div>
</form>
{{-- Form Add New Blood :end --}}