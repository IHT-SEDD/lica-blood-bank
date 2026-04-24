@extends('layouts.vertical', ['title' => 'Pricing'])

@section('content')
@include('layouts.shared.page-title', [
'title' => 'Simple, Transparent Pricing',
'subTitle' =>
'Choose a plan that fits your needs. Scalable options for individuals, teams, and enterprises with no hidden fees.',
'badgeIcon' => 'credit-card',
'badgeTitle' => 'Flexible Plans',
])


<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 my-4 my-lg-0 rounded-4">
            <div class="card-body rounded-top-4 px-lg-4 p-5 pb-2 text-center">
                <div class="text-center">
                    <h3 class="fw-bold mb-1">Single License</h3>
                    <p class="text-muted mb-0">Perfect for personal or one-client projects</p>
                </div>
                <div class="my-4">
                    <h1 class="display-6 fw-bold mb-0">$39</h1>
                    <small class="d-block text-muted fs-base">One-time payment</small>
                    <small class="d-block text-muted">Single project use</small>
                </div>
                <ul class="list-unstyled text-start fs-sm mb-0">
                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i> 1 project usage</li>
                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i> Full component access
                    </li>
                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i> Basic documentation
                    </li>
                    <li class="mb-2"><i class="ti ti-x text-danger me-2"></i> No multi-client use</li>
                    <li class="mb-2"><i class="ti ti-x text-danger me-2"></i> No SaaS/resale rights</li>
                </ul>
            </div>
            <div class="card-footer bg-transparent rounded-bottom-4 px-5 py-4">
                <a class="btn btn-outline-primary w-100 py-2 fw-semibold rounded-pill" href="#!">Buy
                    Single License</a>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 my-4 my-lg-0 rounded-4">
            <div class="card-body rounded-top-4 px-lg-4 p-5 pb-2 text-center">
                <div class="text-center">
                    <h3 class="fw-bold mb-1">Multiple License</h3>
                    <p class="text-muted mb-0">For developers or agencies working with multiple clients</p>
                </div>
                <div class="my-4">
                    <h1 class="display-6 fw-bold mb-0">$199</h1>
                    <small class="d-block text-muted fs-base">One-time payment</small>
                    <small class="d-block text-muted">Up to 5 projects</small>
                </div>
                <ul class="list-unstyled text-start fs-sm mb-0">
                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i> Use in up to 5 projects
                    </li>
                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i> Commercial client use
                    </li>
                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i> Lifetime updates</li>
                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i> Premium support</li>
                    <li class="mb-2"><i class="ti ti-x text-danger me-2"></i> No resale/SaaS rights</li>
                </ul>
            </div>
            <div class="card-footer bg-transparent rounded-bottom-4 px-5 py-4">
                <a class="btn btn-primary w-100 py-2 fw-semibold rounded-pill" href="#!">Buy Multiple
                    License</a>
            </div>
            <span
                class="position-absolute top-0 start-50 translate-middle-x badge bg-primary-subtle text-primary rounded-pill px-3 py-1 mt-3">
                Best Value
            </span>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 my-4 my-lg-0 rounded-4">
            <div class="card-body rounded-top-4 px-lg-4 p-5 pb-2 text-center">
                <div class="text-center">
                    <h3 class="fw-bold mb-1">Extended License</h3>
                    <p class="text-muted mb-0">For SaaS products or items offered in paid applications</p>
                </div>
                <div class="my-4">
                    <h1 class="display-6 fw-bold mb-0">$799</h1>
                    <small class="d-block text-muted fs-base">One-time payment</small>
                    <small class="d-block text-muted">Commercial redistribution rights</small>
                </div>
                <ul class="list-unstyled text-start fs-sm mb-0">
                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i> Unlimited project usage
                    </li>
                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i> SaaS &amp; resale
                        rights</li>
                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i> Full Figma source files
                    </li>
                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i> Priority support</li>
                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i> Custom licensing
                        agreement</li>
                </ul>
            </div>
            <div class="card-footer bg-transparent rounded-bottom-4 px-5 py-4">
                <a class="btn btn-dark w-100 py-2 fw-semibold rounded-pill" href="#!">Buy Extended
                    License</a>
            </div>
        </div>
    </div> <!-- end col-->
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 my-4 my-lg-0 rounded-4">
            <div class="card-body rounded-top-4 px-lg-4 p-5 pb-2 text-center">
                <div class="text-center">
                    <h3 class="fw-bold mb-1">Custom License</h3>
                    <p class="text-muted mb-0">Tailored for enterprise or unique distribution needs</p>
                </div>
                <div class="my-4">
                    <h1 class="display-6 fw-bold mb-0">Contact Us</h1>
                    <small class="d-block text-muted fs-base">Flexible pricing</small>
                    <small class="d-block text-muted">Based on project scope</small>
                </div>
                <ul class="list-unstyled text-start fs-sm mb-0">
                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i> Unlimited users &amp;
                        usage</li>
                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i> White-label allowed
                    </li>
                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i> Custom branding
                        permissions</li>
                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i> Dedicated account
                        support</li>
                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i> NDA and legal agreement
                    </li>
                </ul>
            </div>
            <div class="card-footer bg-transparent rounded-bottom-4 px-5 py-4">
                <a class="btn btn-outline-dark w-100 py-2 fw-semibold rounded-pill"
                    href="mailto:sales@example.com">Request Custom License</a>
            </div>
            <span
                class="position-absolute top-0 start-50 translate-middle-x badge bg-primary-subtle text-primary rounded-pill px-3 py-1 mt-3">
                Enterprise
            </span>
        </div>
    </div>
</div> <!-- end row-->
@endsection

@section('scripts')
@endsection