@extends('layouts.vertical', ['title' => 'Invoice Details'])

@section('content')
@include('layouts.shared.page-title', [
'title' => 'Invoice Details',
'subTitle' =>
'View and manage billing information, transaction summaries, and downloadable invoice records.',
'badgeIcon' => 'receipt-text',
'badgeTitle' => 'Billing &amp; Payments',
])

<div class="row">
    <div class="col-xl-9">
        <div class="card">
            <div class="card-body px-4">
                <!-- Invoice Header -->
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                    <div class="auth-brand mb-0">
                        <a class="logo-dark" href="/">
                            <span class="d-flex justify-content-center align-items-center gap-1">
                                <span class="avatar avatar-xs rounded-circle text-bg-dark">
                                    <span class="avatar-title">
                                        <i class="fs-md" data-lucide="sparkles"></i>
                                    </span>
                                </span>
                                <span class="logo-text text-body fw-bold fs-xl">Simple</span>
                            </span>
                        </a>
                        <a class="logo-light" href="/">
                            <span class="d-flex justify-content-center align-items-center gap-1">
                                <span class="avatar avatar-xs rounded-circle text-bg-dark">
                                    <span class="avatar-title">
                                        <i class="fs-md" data-lucide="sparkles"></i>
                                    </span>
                                </span>
                                <span class="logo-text text-white fw-bold fs-xl">Simple</span>
                            </span>
                        </a>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-success-subtle text-success mb-2 fs-xs px-2 py-1">Paid</span>
                        <h4 class="fw-bold text-dark m-0">Invoice #TNL-0125789</h4>
                    </div>
                </div>
                <!-- Invoice Info -->
                <div class="row">
                    <div class="col-4">
                        <h6 class="text-uppercase text-muted mb-2">From</h6>
                        <p class="mb-1 fw-semibold">Eleanor Hayes</p>
                        <p class="text-muted mb-1">512 Willow St,<br />Denver, CO - 80203</p>
                        <p class="text-muted mb-0">Phone: 303-892-3344</p>
                        <div class="mt-4">
                            <h6 class="text-uppercase text-muted">Invoice Date</h6>
                            <p class="mb-0 fw-medium">01 Jun 2025</p>
                        </div>
                    </div>
                    <div class="col-4">
                        <h6 class="text-uppercase text-muted mb-2">To</h6>
                        <p class="mb-1 fw-semibold">Benjamin Hart</p>
                        <p class="text-muted mb-1">99 Hillside Blvd,<br />San Mateo, CA - 94401</p>
                        <p class="text-muted mb-0">Phone: 650-328-9002</p>
                        <div class="mt-4">
                            <h6 class="text-uppercase text-muted">Due Date</h6>
                            <p class="mb-0 fw-medium">10 Jun 2025</p>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <img alt="Barcode" class="img-fluid" src="/images/qr.png" style="max-height: 80px;" />
                    </div>
                </div>
                <!-- Product Table -->
                <div class="table-responsive mt-4">
                    <table class="table table-bordered table-nowrap text-center align-middle">
                        <thead class="bg-light align-middle bg-opacity-25 thead-sm">
                            <tr class="text-uppercase fs-xxs">
                                <th style="width: 50px;">#</th>
                                <th class="text-start">Service</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>01</td>
                                <td class="text-start">
                                    <strong>Website Redesign</strong>
                                    <div class="text-muted">(Landing page + blog + pricing)</div>
                                </td>
                                <td>1</td>
                                <td>$1,000.00</td>
                                <td class="text-end">$1,000.00</td>
                            </tr>
                            <tr>
                                <td>02</td>
                                <td class="text-start">
                                    <strong>Content Creation</strong>
                                    <div class="text-muted">(5 blog posts)</div>
                                </td>
                                <td>5</td>
                                <td>$60.00</td>
                                <td class="text-end">$300.00</td>
                            </tr>
                            <tr>
                                <td>03</td>
                                <td class="text-start">
                                    <strong>Maintenance Plan</strong>
                                    <div class="text-muted">(Monthly hosting &amp; support)</div>
                                </td>
                                <td>1</td>
                                <td>$150.00</td>
                                <td class="text-end">$150.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Summary Table -->
                <div class="d-flex justify-content-end">
                    <table class="table w-auto table-borderless text-end">
                        <tbody>
                            <tr>
                                <td class="fw-medium">Subtotal</td>
                                <td>$1,450.00</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Shipping</td>
                                <td>$20.00</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Discount (10%)</td>
                                <td class="text-danger">- $145.00</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Tax (8%)</td>
                                <td>$104.00</td>
                            </tr>
                            <tr class="border-top pt-2 fs-5 fw-bold">
                                <td>Total</td>
                                <td>$1,429.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Note -->
                <div class="mt-lg-4 mt-2 bg-light bg-opacity-50 rounded px-3 py-2">
                    <p class="mb-0 text-muted"><strong>Note:</strong> Payment processed successfully. For
                        future invoices, contact <a class="fw-medium"
                            href="mailto:billing@simple.io">billing@simple.io</a>.</p>
                </div>
                <!-- Footer -->
                <div class="mt-4">
                    <p class="fw-semibold mb-3">Thank you for choosing Simple!</p>
                    <img alt="Signature" height="32" src="/images/sign.png" />
                    <p class="text-muted fs-xxs fst-italic">Authorized Signature</p>
                </div>
            </div>
        </div>
    </div> <!-- end col-9-->
    <div class="col-xl-3 d-print-none">
        <div class="card card-top-sticky">
            <div class="card-body">
                <div class="justify-content-center d-flex flex-column gap-2">
                    <a class="btn btn-primary" href="javascript:window.print()"><i class="ti ti-printer me-1"></i>
                        Print</a>
                    <a class="btn btn-info" href="javascript: void(0);"><i class="ti ti-download me-1"></i>
                        Download</a>
                    <a class="btn btn-danger" href="javascript: void(0);"><i class="ti ti-send me-1"></i>
                        Send</a>
                </div>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-9-->
</div> <!-- end row-->
@endsection

@section('scripts')
@endsection