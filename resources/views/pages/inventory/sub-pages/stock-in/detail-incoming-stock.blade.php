@extends('layouts.vertical', ['title' => __('Detail Incoming Stock')])

@section('styles')
@vite([
'node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css',
'node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css',
])
@endsection

@section('content')
<div class="row py-4">
  {{-- Header :begin --}}
  <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-1 mb-2">
    {{-- Button Back to Stock In List --}}
    <a href="{{ route('inventory.stock-in.index') }}" class="btn btn-soft-primary">
      {{ __('Back To') }} {{ __('Stock In') }}
    </a>

    {{-- Title --}}
    <h1 class="fw-bold mb-0">{{ __('Detail') }} {{ __('Incoming Stock') }} <span id="po_number_title"></span></h1>
  </div>
  {{-- Header :end --}}

  {{-- Incoming Stock Detail :begin --}}
  <div class="col-lg-7 col-md-6 col-12">
    <div class="row">
      {{-- Incoming Detail :begin --}}
      <div class="col-12">
        <div class="card">
          {{-- Card Header --}}
          <div class="card-header justify-content-between align-items-center">
            <h5 class="card-title text-capitalize mb-0">{{ __('Incoming Data') }}</h5>
            <div class="card-action">
              <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
            </div>
          </div>

          {{-- Card Body --}}
          <div class="card-body">
            <div class="row" id="incoming_stock_detail_wrapper">
              {{-- Left Side :begin --}}
              <div class="col-xxl-6 col-12">
                {{-- PO Number --}}
                <div class="row mb-2">
                  <div class="col-4 text-capitalize fs-6 text-muted my-0">{{ __('PO Number') }}</div>
                  <div class="col-8 text-capitalize fs-5 fw-semibold my-0" id="incoming_data" data-order="po_number">
                  </div>
                </div>

                {{-- Batch --}}
                <div class="row mb-2">
                  <div class="col-4 text-capitalize fs-6 text-muted my-0">{{ __('Batch Number') }}</div>
                  <div class="col-8 text-capitalize fs-5 fw-semibold my-0" id="incoming_data" data-order="batch_number">
                  </div>
                </div>

                {{-- Status --}}
                <div class="row mb-2">
                  <div class="col-4 text-capitalize fs-6 text-muted my-0">{{ __('Status') }}</div>
                  <div class="col-8 text-capitalize fs-5 fw-semibold my-0" id="incoming_data" data-order="status"></div>
                </div>

                {{-- Registered By --}}
                <div class="row mb-2">
                  <div class="col-4 text-capitalize fs-6 text-muted my-0">{{ __('Registered By') }}</div>
                  <div class="col-8 text-capitalize fs-5 fw-semibold my-0" id="incoming_data"
                    data-order="registered_by">
                  </div>
                </div>
              </div>
              {{-- Left Side :end --}}

              {{-- Right Side :begin --}}
              <div class="col-xxl-6 col-12">
                {{-- Received By --}}
                <div class="row mb-2">
                  <div class="col-4 text-capitalize fs-6 text-muted my-0">{{ __('Stock Received By') }}</div>
                  <div class="col-8 text-capitalize fs-5 fw-semibold my-0" id="patient_detail"
                    data-patient-detail="stock_received_by"></div>
                </div>

                {{-- Received At --}}
                <div class="row mb-2">
                  <div class="col-4 text-capitalize fs-6 text-muted my-0">{{ __('Stock Received At') }}</div>
                  <div class="col-8 text-capitalize fs-5 fw-semibold my-0" id="patient_detail"
                    data-patient-detail="stock_received_at"></div>
                </div>

                {{-- Stock Ready At --}}
                <div class="row mb-2">
                  <div class="col-4 text-capitalize fs-6 text-muted my-0">{{ __('Stock Ready At') }}</div>
                  <div class="col-8 text-capitalize fs-5 fw-semibold my-0" id="patient_detail"
                    data-patient-detail="stock_ready_at"></div>
                </div>

                {{-- Registered At --}}
                <div class="row mb-2">
                  <div class="col-4 text-capitalize fs-6 text-muted my-0">{{ __('Registered At') }}</div>
                  <div class="col-8 text-capitalize fs-5 fw-semibold my-0" id="incoming_data"
                    data-order="registered_at">
                  </div>
                </div>
              </div>
              {{-- Right Side :end --}}
            </div>
          </div>
        </div>
      </div>
      {{-- Incoming Detail :end --}}

      {{-- Table Incoming Blood Detail :begin --}}
      <div class="col-12">
        <div class="card">
          {{-- Card Header --}}
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title text-capitalize mb-0">{{ __('Incoming Stock Detail') }}</h5>
            <div class="card-action">
              <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
            </div>
          </div>

          {{-- Card Body --}}
          <div class="card-body">
            <table class="table table-sm table-striped dt-responsive align-middle mb-0 incoming-blood-detail-table"
              id="incoming-blood-detail-table">
              <thead class="thead-sm text-uppercase fs-xxs">
                <tr>
                  <th>{{ __('Bag Number') }}</th>
                  <th>{{ __('Blood Pack') }}</th>
                  <th>{{ __('Volume') }}</th>
                  <th>{{ __('Aftap') }}</th>
                  <th>{{ __('Expiry') }}</th>
                  <th>{{ __('Process') }}</th>
                  <th>{{ __('Ready At') }}</th>
                  <th>{{ __('Created At') }}</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
      {{-- Table Incoming Blood Detail :end --}}
    </div>
  </div>
  {{-- Incoming Stock Detail :end --}}

  {{-- Order Detail :begin --}}
  <div class="col-lg-5 col-md-6 col-12">
    <div class="row">
      {{-- Order Detail :begin --}}
      <div class="col-12">
        <div class="card">
          {{-- Card Header --}}
          <div class="card-header justify-content-between align-items-center">
            <h5 class="card-title text-capitalize mb-0">{{ __('Order Data') }}</h5>
            <div class="card-action">
              <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
            </div>
          </div>

          {{-- Card Body --}}
          <div class="card-body" id="order_detail_wrapper">
            <div class="row">
              {{-- Left Side :begin --}}
              <div class="col-xxl-6 col-12">
                {{-- PO Number --}}
                <div class="row mb-2">
                  <div class="col-4 text-capitalize fs-6 text-muted my-0">{{ __('PO Number') }}</div>
                  <div class="col-8 text-capitalize fs-5 fw-semibold my-0" id="order_data" data-order="po_number"></div>
                </div>

                {{-- Vendor Name --}}
                <div class="row mb-2">
                  <div class="col-4 text-capitalize fs-6 text-muted my-0">{{ __('Vendor Name') }}</div>
                  <div class="col-8 text-capitalize fs-5 fw-semibold my-0" id="order_data" data-order="vendor_name">
                  </div>
                </div>

                {{-- Status --}}
                <div class="row mb-2">
                  <div class="col-4 text-capitalize fs-6 text-muted my-0">{{ __('Status') }}</div>
                  <div class="col-8 text-capitalize fs-5 fw-semibold my-0" id="order_data" data-order="status"></div>
                </div>
              </div>
              {{-- Left Side :end --}}

              {{-- Right Side :begin --}}
              <div class="col-xxl-6 col-12">
                {{-- Ordered By --}}
                <div class="row mb-2">
                  <div class="col-4 text-capitalize fs-6 text-muted my-0">{{ __('Ordered By') }}</div>
                  <div class="col-8 text-capitalize fs-5 fw-semibold my-0" id="order_data" data-order="ordered_by">
                  </div>
                </div>

                {{-- Ordered At --}}
                <div class="row mb-2">
                  <div class="col-4 text-capitalize fs-6 text-muted my-0">{{ __('Ordered At') }}</div>
                  <div class="col-8 text-capitalize fs-5 fw-semibold my-0" id="order_data" data-order="ordered_at">
                  </div>
                </div>

                {{-- PO File --}}
                <div class="row mb-2">
                  <div class="col-4 text-capitalize fs-6 text-muted my-0">{{ __('PO File') }}</div>
                  <div class="col-8 text-capitalize fs-5 fw-semibold my-0" id="patient_detail"
                    data-patient-detail="po_file"></div>
                </div>
              </div>
              {{-- Right Side :end --}}
            </div>

            <div class="row">
              {{-- Description --}}
              <div class="row mb-2">
                <div class="col-xxl-2 col-4 text-capitalize fs-6 text-muted my-0">{{ __('Description') }}</div>
                <div class="col-xxl-10 col-8 text-capitalize fs-5 fw-semibold my-0" id="order_data" data-order="description">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      {{-- Order Detail :end --}}

      {{-- Blood Order Detail :begin --}}
      <div class="col-12">
        <div class="card">
          {{-- Card Header --}}
          <div class="card-header justify-content-between align-items-center">
            <h5 class="card-title text-capitalize mb-0">{{ __('Order Blood Data') }}</h5>
            <div class="card-action">
              <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
            </div>
          </div>

          {{-- Card Body --}}
          <div class="card-body">
            {{-- Order Blood --}}
            <table class="table table-sm table-striped dt-responsive align-middle mb-0 order-blood-table"
              id="order-blood-table">
              <thead class="thead-sm text-uppercase fs-xxs">
                <tr>
                  <th>{{ __('Blood') }}</th>
                  <th>{{ __('Qty') }}</th>
                  <th>{{ __('Note') }}</th>
                  <th>{{ __('Order By') }}</th>
                  <th>{{ __('Order At') }}</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
      {{-- Blood Order Detail :end --}}
    </div>
  </div>
  {{-- Order Detail :end --}}
</div>
@endsection

@section('scripts')
@vite([
'resources/js/pages/inventory/stock-in/detail/index.js'
])
@endsection