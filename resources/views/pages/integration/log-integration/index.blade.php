@extends('layouts.integration-page-layout')

@section('datatable-receive-data-header')
<div class="d-flex align-items-center justify-content-center gap-2">
    <div>
        <div class="input-group">
            <span class="input-group-text" id="receive-data-date-addon">
                <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
            </span>
            <input class="form-control receive-data-date-filter"
                aria-describedby="receive-data-date-addon" data-date-format="d-m-Y" data-provider="flatpickr"
                data-range-date="true" type="text" placeholder="Choose date range" />
        </div>
    </div>
</div>
@endsection

@section('datatable-receive-data')
<table class="table table-sm table-striped dt-responsive align-middle mb-0 receive-data-table"
 id="receive-data-table">
 <thead class="thead-sm text-uppercase fs-xxs">
    <tr>
        <th>Date</th>
        <th>Order Number</th>
        <th>Message</th>
        <th>Status</th>
        <th>EndPoint</th>
        <th>Payload</th>
    </tr>
 </thead>
</table>
@endsection


@section('datatable-send-result-header')
<div class="d-flex align-items-center justify-content-center gap-2">
    <div>
        <div class="input-group">
            <span class="input-group-text" id="send-result-date-addon">
                <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
            </span>
            <input class="form-control send-result-date-filter"
                aria-describedby="send-result-date-addon" data-date-format="d-m-Y" data-provider="flatpickr"
                data-range-date="true" type="text" placeholder="Choose date range" />
        </div>
    </div>
</div>
@endsection

@section('datatable-send-result')
<table class="table table-sm table-striped dt-responsive align-middle mb-0 send-result-table"
 id="send-result-table">
 <thead class="thead-sm text-uppercase fs-xxs">
    <tr>
        <th>Date</th>
        <th>Order Number</th>
        <th>Message</th>
        <th>Status</th>
        <th>EndPoint</th>
        <th>Payload</th>
    </tr>
 </thead>
</table>
@endsection

@section('modal-content')
<!-- Modal View Payload -->
<div class="modal fade" id="modal-view-payload" tabindex="-1" aria-labelledby="modal-view-payload-title" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-view-payload-title">Integration Payload Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <pre class="bg-light p-3 rounded" style="max-height: 500px; overflow-y: auto;"><code id="payload-display" class="language-json text-wrap text-break"></code></pre>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('custom-scripts')
@endsection