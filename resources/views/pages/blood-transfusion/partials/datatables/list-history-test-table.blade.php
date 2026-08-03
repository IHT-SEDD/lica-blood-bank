{{-- Month & Year Picker --}}
<div class="col-3">
  <div class="input-group">
    <span class="input-group-text" id="history-test-month-filter">
      <i data-lucide="calendar" class="align-middle flex-shrink-0"></i>
    </span>
    <input class="form-control history-test-month-filter" aria-describedby="history-test-month-filter"
      data-provider="monthpickr" type="text" placeholder="Pilih bulan dan tahun" />
  </div>
</div>

<table class="table table-sm table-striped dt-responsive align-middle mb-0 list-history-test-table"
  id="list-history-test-table">
  <thead class="thead-sm text-uppercase fs-xxs">
    <tr>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
    </tr>
  </thead>
</table>