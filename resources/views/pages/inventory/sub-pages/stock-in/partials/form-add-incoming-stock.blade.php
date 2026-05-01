{{-- Form Add New Blood :begin --}}
<form class="row g-2" id="add_new_incoming_stock" autocomplete="off">
  {{-- Choose Purchase Order --}}
  <div class="col-lg-12">
    <label class="form-label" for="select-purchase-order">Choose Purchase Order
      <span class="text-danger">*</span>
    </label>
    <select class="form-control" id="select-purchase-order" placeholder="Choose purchase order"></select>
    <small class="form-text text-muted fs-6">
      Choose purchase order from dropdown to start add incoming stock.
    </small>
  </div>

  {{-- Batch Number --}}
  <div class="col-lg-6">
    <label class="form-label" for="batch_number">Batch Number</label>
    <input autocomplete="off" class="form-control" id="batch_number" name="batch_number" type="text"
      placeholder="Batch number" />
    <small class="form-text text-muted fs-6">
      Input batch number if exists.
    </small>
  </div>

  {{-- Choose Add Incoming Stock Method --}}
  <div class="col-lg-6">
    <label class="form-label" for="select-add-data-method">Choose Method of Add Incoming Stock
      <span class="text-danger">*</span>
    </label>
    <select class="form-control" id="select-add-data-method" placeholder="Choose method"></select>
    <small class="form-text text-muted fs-6">
      Choose manual if you want to add manually or choose excel if you want to import an excel data.
    </small>
  </div>

  <hr />

  {{-- Manually Input :begin --}}
  <div class="col-lg-12 my-1 d-none" id="add_manually_method_wrapper">
    <button class="btn btn-sm btn-soft-info mb-2 d-flex align-items-center justify-content-center add_blood_row"
      type="button" id="add_blood_row">
      <i class="ti ti-plus fs-4 me-2"></i>
      Add Blood Row
    </button>

    <div class="card m-0">
      {{-- Card Header :begin --}}
      <div class="card-header justify-content-between align-items-center">
        <h5 class="card-title text-capitalize mb-0">1# Blood Data</h5>
        <button class="btn btn-sm btn-soft-danger btn-delete-blood" type="button" data-bs-title="Delete blood row"
          data-bs-toggle="tooltip" data-bs-trigger="hover">
          <i class="ti ti-trash fs-4"></i>
        </button>
        <div class="card-action" id="blood_data[${idx}][header_collapse]">
          <a class="card-action-item" data-action="card-toggle" href="#!" id="blood_data[${idx}][toggle_header]"><i
              class="ti ti-chevron-up"></i>
          </a>
        </div>
      </div>
      {{-- Card Header :end --}}

      {{-- Card Body :begin --}}
      <div class="card-body">
        <div class="row g-2">
          {{-- Bag Number --}}
          <div class="col-lg-3 col-12">
            <label class="form-label" for="bag_number">Bag Number
              <span class="text-danger">*</span>
            </label>
            <input autocomplete="off" class="form-control" id="bag_number" name="bag_number" type="text"
              placeholder="Bag number" />
          </div>

          {{-- Group --}}
          <div class="col-lg-2 col-12">
            <label class="form-label" for="select-blood-group">Group
              <span class="text-danger">*</span>
            </label>
            <select class="form-control" id="select-blood-group" placeholder="Choose blood group"></select>
          </div>

          {{-- Rhesus --}}
          <div class="col-lg-2 col-12">
            <label class="form-label" for="select-blood-rhesus">Rhesus
              <span class="text-danger">*</span>
            </label>
            <select class="form-control" id="select-blood-rhesus" placeholder="Choose blood rhesus"></select>
          </div>

          {{-- Component --}}
          <div class="col-lg-3 col-12">
            <label class="form-label" for="select-blood-component">Component
              <span class="text-danger">*</span>
            </label>
            <select class="form-control" id="select-blood-component" placeholder="Choose blood component"></select>
          </div>

          {{-- Volume --}}
          <div class="col-lg-2 col-12">
            <label class="form-label" for="volume">Volume
              <span class="text-danger">*</span>
            </label>
            <input autocomplete="off" class="form-control" id="volume" name="volume" type="text" placeholder="mL" />
          </div>

          {{-- Aftap --}}
          <div class="col-lg-3 col-12">
            <label class="form-label" for="aftap_date">Aftap
              <span class="text-danger">*</span>
            </label>
            <input class="form-control aftap_date" aria-describedby="aftap_date" data-date-format="d-m-Y"
              data-provider="flatpickr" id="aftap_date" name="aftap_date" data-range-date="true" type="text"
              placeholder="Aftap date" />
          </div>

          {{-- Expiry --}}
          <div class="col-lg-3 col-12">
            <label class="form-label" for="expiry_date">Expiry
              <span class="text-danger">*</span>
            </label>
            <input class="form-control expiry_date" aria-describedby="expiry_date" data-date-format="d-m-Y"
              data-provider="flatpickr" id="expiry_date" name="expiry_date" data-range-date="true" type="text"
              placeholder="Expiry date" />
          </div>

          {{-- Process --}}
          <div class="col-lg-3 col-12">
            <label class="form-label" for="process_date">Process
              <span class="text-danger">*</span>
            </label>
            <input class="form-control process_date" aria-describedby="process_date" data-date-format="d-m-Y"
              data-provider="flatpickr" id="process_date" name="process_date" data-range-date="true" type="text"
              placeholder="Process date" />
          </div>

          {{-- Qty --}}
          <div class="col-lg-3 col-12">
            <label class="form-label" for="quantity">Qty
              <span class="text-danger">*</span>
            </label>
            <input autocomplete="off" class="form-control" id="quantity" name="quantity" type="text"
              placeholder="Quantity" />
          </div>

          {{-- Blood Status --}}
          <div class="col-12 d-flex flex-wrap gap-2">
            <div class="form-check form-check-danger m-0">
              <input class="form-check-input" id="is_hiv" type="checkbox" />
              <label class="form-check-label" for="is_hiv">HIV?</label>
            </div>
            <div class="form-check form-check-danger m-0">
              <input class="form-check-input" id="is_hcv" type="checkbox" />
              <label class="form-check-label" for="is_hcv">HCV?</label>
            </div>
            <div class="form-check form-check-danger m-0">
              <input class="form-check-input" id="is_hbsag" type="checkbox" />
              <label class="form-check-label" for="is_hbsag">HbsAg?</label>
            </div>
            <div class="form-check form-check-danger m-0">
              <input class="form-check-input" id="is_syphilis" type="checkbox" />
              <label class="form-check-label" for="is_syphilis">Syphilis?</label>
            </div>
          </div>
        </div>
      </div>
      {{-- Card Body :end --}}
    </div>
  </div>
  {{-- Manually Input :end --}}

  {{-- Export Excel Input :begin --}}
  <div class="col-lg-12 my-1 d-none" id="add_excel_method_wrapper">
    <div class="row g-2">
      {{-- Download Template --}}
      <div class="col-lg-4 col-12">
        <label class="form-label" for="download_template_excel">Download Template Excel</label>
        <button
          class="btn btn-sm btn-soft-secondary mb-2 d-flex align-items-center justify-content-center download_template_excel"
          type="button" id="download_template_excel">
          <i class="ti ti-download fs-4 me-2"></i>
          Template Excel
        </button>
      </div>
      {{-- Upload File --}}
      <div class="col-lg-8 col-12">
        <label class="form-label" for="download_template_excel">Upload Data
          <span class="text-danger">*</span>
        </label>
        <input class="form-control" id="inputGroupFile04" type="file" />
      </div>
    </div>
  </div>
  {{-- Export Excel Input :end --}}

  <hr />

  {{-- Submit Button --}}
  <div class="col-lg-12 m-0">
    <button class="btn btn-primary" type="submit">Add Incoming Stock</button>
  </div>
</form>
{{-- Form Add New Blood :end --}}