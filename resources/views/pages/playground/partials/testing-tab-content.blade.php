<div class="col-xxl-3 col-md-4 col-6">
 <div class="row">
  {{-- Halaman Uji Coba Print --}}
  <div class="col-12">
   <div class="card">
    {{-- Card Header --}}
    <div class="card-header justify-content-between align-items-center">
     <h5 class="card-title mb-0"><i class="ti ti-flask me-2 fs-4"></i> Uji Coba Print</h5>
     <div class="card-action d-flex align-items-center gap-2">
      <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
     </div>
    </div>

    {{-- Card Body --}}
    <div class="card-body">
     <a href={{ route('playground.print.index') }} class="btn btn-soft-primary w-100" type="button">
      Masuk Halaman
     </a>
    </div>
   </div>
  </div>

  {{-- Halaman Uji Coba API --}}
  <div class="col-12">
   <div class="card">
    {{-- Card Header --}}
    <div class="card-header justify-content-between align-items-center">
     <h5 class="card-title mb-0"><i class="ti ti-flask me-2 fs-4"></i> Testing API (Send Result)</h5>
     <div class="card-action d-flex align-items-center gap-2">
      <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
     </div>
    </div>

    {{-- Card Body --}}
    <div class="card-body">
     <form action="{{ url('api/v1/blood-transfusion/send-result') }}" method="post">
      @csrf
      <label class="form-label mb-2" for="order-number">{{ __('Order Number') }}</label>
      <input autocomplete="off" class="form-control mb-2" id="order-number" name="order_number" type="text"
       placeholder="Order Number" />
      <button class="btn btn-soft-primary w-100" type="submit">Send Result</button>
     </form>
    </div>
   </div>
  </div>
 </div>
</div>