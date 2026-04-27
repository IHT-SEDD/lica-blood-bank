{{-- Form Add New Order :begin --}}
<form class="row g-2" id="add_new_order" autocomplete="off">
  {{-- Order Data :begin --}}
  <div class="col-12">
    {{-- Title --}}
    <div style="border-bottom: 2px dashed #ccc; padding-bottom: 6px; margin-bottom: 12px;">
      <h4>Order Data Details</h4>
    </div>

    {{-- PO Number --}}
    <div class="mb-1">
      <label class="form-label" for="po_number">PO Number
        <span class="text-danger">*</span>
      </label>
      <input autocomplete="off" class="form-control" id="po_number" name="po_number" type="text" placeholder="PO Number"
        readonly />
      <small class="form-text text-muted">
        Click inputs to generate po number.
      </small>
    </div>

    {{-- Vendor --}}
    <div class="mb-1">
      <label class="form-label" for="select-vendor">Vendor
        <span class="text-danger">*</span>
      </label>
      <select class="form-control" id="select-vendor" name="vendor_id" placeholder="Choose vendor..."></select>
    </div>

    {{-- Total Quantity --}}
    <div class="mb-1">
      <label class="form-label" for="total_quantity">Total Quantity
        <span class="text-danger">*</span>
      </label>
      <input autocomplete="off" class="form-control" id="total_quantity" name="total_quantity" type="text"
        placeholder="Order total quantity" readonly />
      <small class="form-text text-muted">
        Total quantity appear automatic based on order blood details.
      </small>
    </div>
  </div>
  {{-- Order Data :end --}}

  <hr />

  {{-- Order Blood Details :begin --}}
  <div class="col-12">
    {{-- Title --}}
    <div class="d-flex align-items-center justify-content-start gap-2"
      style="border-bottom: 2px dashed #ccc; padding-bottom: 6px; margin-bottom: 12px;">
      <h4 class="m-0">Order Blood Data Details</h4>
      <button class="btn btn-sm btn-info" id="add_new_data">
        <i class="ti ti-circle-plus"></i>
      </button>
    </div>

    <div class="row" id="blood-data-container">
      {{-- Card Blood Data :begin --}}
      <div class="card col-12 blood-data-card">
        {{-- Blood Data Header :begin --}}
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title text-capitalize mb-0 title-blood-data"></h5>
          <div class="card-action">
            <a class="card-action-item text-danger delete-card" data-action="delete-blood-data" href="#!">
              <i class="ti ti-trash"></i>
            </a>
            <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
          </div>
        </div>
        {{-- Blood Data Header :end --}}

        {{-- Card Body :begin --}}
        <div class="card-body">
          <div class="row g-2">
            {{-- Blood Group --}}
            <div class="col-lg-3 col-12">
              <label class="form-label" for="select-blood-group">Group
                <span class="text-danger">*</span>
              </label>
              <select class="form-control select-blood-group" id="select-blood-group" name="blood_data[0][blood_group]"
                placeholder="Choose blood group..."></select>
            </div>

            {{-- Blood Rhesus --}}
            <div class="col-lg-3 col-12">
              <label class="form-label" for="select-blood-rhesus">Rhesus
                <span class="text-danger">*</span>
              </label>
              <select class="form-control select-blood-rhesus" id="select-blood-rhesus"
                name="blood_data[0][blood_rhesus]" placeholder="Choose blood rhesus..."></select>
            </div>

            {{-- Blood Component --}}
            <div class="col-lg-3 col-12">
              <label class="form-label" for="select-blood-component">Component
                <span class="text-danger">*</span>
              </label>
              <select class="form-control select-blood-component" id="select-blood-component"
                name="blood_data[0][blood_component]" placeholder="Choose blood component..."></select>
            </div>

            {{-- Blood Volume --}}
            <div class="col-lg-3 col-12">
              <label class="form-label" for="blood_volume">Volume
                <span class="text-danger">*</span>
              </label>
              <input autocomplete="off" class="form-control blood_volume" id="blood_volume"
                name="blood_data[0][blood_volume]" type="text" placeholder="Volume" />
            </div>

            {{-- Blood Quantity --}}
            <div class="col-lg-3 col-12">
              <label class="form-label" for="blood_quantity">Qty
                <span class="text-danger">*</span>
              </label>
              <input autocomplete="off" class="form-control blood_quantity" id="blood_quantity"
                name="blood_data[0][blood_quantity]" type="text" placeholder="Quantity" />
            </div>

            {{-- Blood Status --}}
            <div class="col-lg-7">
              <div class="row my-lg-4">
                {{-- HIV --}}
                <div class="col-3">
                  <div class="form-check form-check-danger my-1">
                    <input class="form-check-input" id="is_hiv" name="blood_data[0][is_hiv]" type="checkbox" />
                    <label class="form-check-label" for="is_hiv">HIV</label>
                  </div>
                </div>
                {{-- HBSAG --}}
                <div class="col-3">
                  <div class="form-check form-check-danger my-1">
                    <input class="form-check-input" id="is_hbsag" name="blood_data[0][is_hbsag]" type="checkbox" />
                    <label class="form-check-label" for="is_hbsag">HBSAG</label>
                  </div>
                </div>
                {{-- HCV --}}
                <div class="col-3">
                  <div class="form-check form-check-danger my-1">
                    <input class="form-check-input" id="is_hcv" name="blood_data[0][is_hcv]" type="checkbox" />
                    <label class="form-check-label" for="is_hcv">HCV</label>
                  </div>
                </div>
                {{-- SYPHILIS --}}
                <div class="col-3">
                  <div class="form-check form-check-danger my-1">
                    <input class="form-check-input" id="is_syphilis" name="blood_data[0][is_syphilis]"
                      type="checkbox" />
                    <label class="form-check-label" for="is_syphilis">SYPHILIS</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        {{-- Card Body :end --}}
      </div>
      {{-- Card Blood Data :end --}}
    </div>
  </div>
  {{-- Order Blood Details :end --}}

  <hr />

  {{-- Submit Button --}}
  <div class="col-lg-12 mt-2">
    <button class="btn btn-primary" type="submit">Submit Order</button>
  </div>
</form>
{{-- Form Add New Order :end --}}