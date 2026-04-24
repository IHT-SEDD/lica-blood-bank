@extends('layouts.vertical', ['title' => 'Contacts'])

@section('content')
@include('layouts.shared.page-title', [
'title' => 'Contact Directory App',
'subTitle' =>
'Effortlessly manage and browse contacts with a clean, searchable interface. Ideal for teams, organizations, and
internal directories.',
'badgeIcon' => 'notebook-text',
'badgeTitle' => 'People &amp; Teams',
])

<div class="row mb-3">
    <div class="col-lg-12">
        <form class="rounded border p-3">
            <div class="row gap-3">
                <div class="col-lg-4">
                    <div class="app-search">
                        <input class="form-control" placeholder="Search contact name..." type="text" />
                        <i class="app-search-icon text-muted" data-lucide="search"></i>
                    </div>
                </div>
                <div class="col">
                    <div class="d-flex justify-content-end flex-wrap align-items-center gap-2">
                        <!-- <span class="me-2 fw-semibold">Filter By:</span> -->
                        <!-- Designation Filter -->
                        <div class="app-search">
                            <select class="form-select form-control my-1 my-md-0">
                                <option selected="">Designation</option>
                                <option value="Manager">Manager</option>
                                <option value="Developer">Developer</option>
                                <option value="Designer">Designer</option>
                                <option value="Sales">Sales</option>
                                <option value="Support">Support</option>
                            </select>
                            <i class="app-search-icon text-muted" data-lucide="user-check"></i>
                        </div>
                        <!-- Location Filter -->
                        <div class="app-search">
                            <select class="form-select form-control my-1 my-md-0">
                                <option selected="">Location</option>
                                <option value="USA">USA</option>
                                <option value="UK">UK</option>
                                <option value="Germany">Germany</option>
                                <option value="India">India</option>
                                <option value="Canada">Canada</option>
                            </select>
                            <i class="app-search-icon text-muted" data-lucide="map-pin"></i>
                        </div>
                        <!-- Department Filter -->
                        <div class="app-search">
                            <select class="form-select form-control my-1 my-md-0">
                                <option selected="">Department</option>
                                <option value="UI/UX">UI/UX</option>
                                <option value="Engineering">Engineering</option>
                                <option value="HR">HR</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Finance">Finance</option>
                            </select>
                            <i class="app-search-icon text-muted" data-lucide="layers"></i>
                        </div>
                        <button class="btn btn-primary" type="submit">Apply</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row row-cols-xxl-4 row-cols-md-2 row-cols-1">
    <div class="col">
        <div class="card card-hovered">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3 position-relative">
                        <img alt="avatar" class="rounded-circle" height="72" src="/images/users/user-1.jpg"
                            width="72" />
                        <span class="position-absolute bottom-0 end-0 badge bg-primary rounded-circle p-1 shadow-sm"
                            title="Rating 4.8">
                            <i class="ti ti-star text-white"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-1 d-flex align-items-center">
                            <a class="link-reset fw-medium fs-md" href="#!">Daniel Carter</a>
                            <img alt="USA" class="ms-2 rounded-circle" height="16" src="/images/flags/us.svg" />
                        </h5>
                        <p class="text-muted mb-1">Product Manager</p>
                        <span class="badge bg-success-subtle text-success fw-medium">VERIFIED</span>
                    </div>
                    <div class="ms-auto">
                        <div class="dropdown">
                            <a class="btn btn-icon btn-ghost-light text-muted" data-bs-toggle="dropdown" href="#">
                                <i class="ti ti-dots-vertical fs-xl"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="ti ti-share me-2 fs-15"></i>Share</a>
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-edit me-2 fs-15"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-ban me-2 fs-15"></i>Block</a></li>
                                <li><a class="dropdown-item text-danger" href="#"><i
                                            class="ti ti-trash me-2 fs-15"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-2">
                    Daniel is a tech-savvy product manager with a track record of launching scalable SaaS
                    products.
                </p>
                <a class="icon-link icon-link-hover link-reset fw-medium" href="#!">
                    View Profile <i class="bi fs-16 ti ti-arrow-narrow-right"></i>
                </a>
            </div> <!-- end card-body -->
            <div class="card-footer">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CAMPAIGNS</div>
                        <div class="fw-bold">76</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CONTACTS</div>
                        <div class="fw-bold">335</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">ENGAGEMENT</div>
                        <div class="fw-bold">97%</div>
                    </div>
                </div>
            </div> <!-- end card-footer-->
        </div> <!-- end card-->
    </div> <!-- end col-->
    <div class="col">
        <div class="card card-hovered">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3 position-relative">
                        <img alt="avatar" class="rounded-circle" height="72" src="/images/users/user-2.jpg"
                            width="72" />
                        <span class="position-absolute bottom-0 end-0 badge bg-primary rounded-circle p-1 shadow-sm"
                            title="Rating 4.7">
                            <i class="ti ti-star text-white"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-1 d-flex align-items-center">
                            <a class="link-reset fw-medium fs-md" href="#!">Liam Carter</a>
                            <img alt="USA" class="ms-2 rounded-circle" height="16" src="/images/flags/us.svg" />
                        </h5>
                        <p class="text-muted mb-1">Creative Director</p>
                        <span class="badge bg-success-subtle text-success fw-medium">VERIFIED</span>
                    </div>
                    <div class="ms-auto">
                        <div class="dropdown">
                            <a class="btn btn-icon btn-ghost-light text-muted" data-bs-toggle="dropdown" href="#">
                                <i class="ti ti-dots-vertical fs-xl"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="ti ti-share me-2 fs-15"></i>Share</a>
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-edit me-2 fs-15"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-ban me-2 fs-15"></i>Block</a></li>
                                <li><a class="dropdown-item text-danger" href="#"><i
                                            class="ti ti-trash me-2 fs-15"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-2">Liam leads design teams and defines creative visions across
                    multi-channel campaigns.</p>
                <a class="icon-link icon-link-hover link-reset fw-medium" href="#!">View Profile <i
                        class="bi fs-16 ti ti-arrow-narrow-right"></i></a>
            </div>
            <div class="card-footer">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CAMPAIGNS</div>
                        <div class="fw-bold">132</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CONTACTS</div>
                        <div class="fw-bold">580</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">ENGAGEMENT</div>
                        <div class="fw-bold">91%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card card-hovered">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3 position-relative">
                        <img alt="avatar" class="rounded-circle" height="72" src="/images/users/user-3.jpg"
                            width="72" />
                        <span class="position-absolute bottom-0 end-0 badge bg-primary rounded-circle p-1 shadow-sm"
                            title="Rating 4.6">
                            <i class="ti ti-star text-white"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-1 d-flex align-items-center">
                            <a class="link-reset fw-medium fs-md" href="#!">Emma Scott</a>
                            <img alt="UK" class="ms-2 rounded-circle" height="16" src="/images/flags/gb.svg" />
                        </h5>
                        <p class="text-muted mb-1">UI/UX Designer</p>
                    </div>
                    <div class="ms-auto">
                        <div class="dropdown">
                            <a class="btn btn-icon btn-ghost-light text-muted" data-bs-toggle="dropdown" href="#">
                                <i class="ti ti-dots-vertical fs-xl"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="ti ti-share me-2 fs-15"></i>Share</a>
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-edit me-2 fs-15"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-ban me-2 fs-15"></i>Block</a></li>
                                <li><a class="dropdown-item text-danger" href="#"><i
                                            class="ti ti-trash me-2 fs-15"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-2">Emma specializes in creating intuitive, user-centered web and
                    mobile interfaces.</p>
                <a class="icon-link icon-link-hover link-reset fw-medium" href="#!">View Profile <i
                        class="bi fs-16 ti ti-arrow-narrow-right"></i></a>
            </div>
            <div class="card-footer">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CAMPAIGNS</div>
                        <div class="fw-bold">115</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CONTACTS</div>
                        <div class="fw-bold">430</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">ENGAGEMENT</div>
                        <div class="fw-bold">87%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card card-hovered">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3 position-relative">
                        <img alt="avatar" class="rounded-circle" height="72" src="/images/users/user-4.jpg"
                            width="72" />
                        <span class="position-absolute bottom-0 end-0 badge bg-primary rounded-circle p-1 shadow-sm"
                            title="Rating 4.8">
                            <i class="ti ti-star text-white"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-1 d-flex align-items-center">
                            <a class="link-reset fw-medium fs-md" href="#!">Noah Blake</a>
                            <img alt="Australia" class="ms-2 rounded-circle" height="16" src="/images/flags/au.svg" />
                        </h5>
                        <p class="text-muted mb-1">Frontend Developer</p>
                    </div>
                    <div class="ms-auto">
                        <div class="dropdown">
                            <a class="btn btn-icon btn-ghost-light text-muted" data-bs-toggle="dropdown" href="#">
                                <i class="ti ti-dots-vertical fs-xl"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="ti ti-share me-2 fs-15"></i>Share</a>
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-edit me-2 fs-15"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-ban me-2 fs-15"></i>Block</a></li>
                                <li><a class="dropdown-item text-danger" href="#"><i
                                            class="ti ti-trash me-2 fs-15"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-2">Noah builds scalable and responsive interfaces with modern
                    JavaScript frameworks.</p>
                <a class="icon-link icon-link-hover link-reset fw-medium" href="#!">View Profile <i
                        class="bi fs-16 ti ti-arrow-narrow-right"></i></a>
            </div>
            <div class="card-footer">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CAMPAIGNS</div>
                        <div class="fw-bold">121</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CONTACTS</div>
                        <div class="fw-bold">400</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">ENGAGEMENT</div>
                        <div class="fw-bold">93%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card card-hovered">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3 position-relative">
                        <img alt="avatar" class="rounded-circle" height="72" src="/images/users/user-5.jpg"
                            width="72" />
                        <span class="position-absolute bottom-0 end-0 badge bg-primary rounded-circle p-1 shadow-sm"
                            title="Rating 4.5">
                            <i class="ti ti-star text-white"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-1 d-flex align-items-center">
                            <a class="link-reset fw-medium fs-md" href="#!">Olivia White</a>
                            <img alt="France" class="ms-2 rounded-circle" height="16" src="/images/flags/fr.svg" />
                        </h5>
                        <p class="text-muted mb-1">Marketing Manager</p>
                    </div>
                    <div class="ms-auto">
                        <div class="dropdown">
                            <a class="btn btn-icon btn-ghost-light text-muted" data-bs-toggle="dropdown" href="#">
                                <i class="ti ti-dots-vertical fs-xl"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="ti ti-share me-2 fs-15"></i>Share</a>
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-edit me-2 fs-15"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-ban me-2 fs-15"></i>Block</a></li>
                                <li><a class="dropdown-item text-danger" href="#"><i
                                            class="ti ti-trash me-2 fs-15"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-2">Olivia drives customer acquisition through strategic performance
                    campaigns.</p>
                <a class="icon-link icon-link-hover link-reset fw-medium" href="#!">View Profile <i
                        class="bi fs-16 ti ti-arrow-narrow-right"></i></a>
            </div>
            <div class="card-footer">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CAMPAIGNS</div>
                        <div class="fw-bold">140</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CONTACTS</div>
                        <div class="fw-bold">560</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">ENGAGEMENT</div>
                        <div class="fw-bold">88%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card card-hovered">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3 position-relative">
                        <img alt="avatar" class="rounded-circle" height="72" src="/images/users/user-6.jpg"
                            width="72" />
                        <span class="position-absolute bottom-0 end-0 badge bg-primary rounded-circle p-1 shadow-sm"
                            title="Rating 4.6">
                            <i class="ti ti-star text-white"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-1 d-flex align-items-center">
                            <a class="link-reset fw-medium fs-md" href="#!">Ethan Moore</a>
                            <img alt="Germany" class="ms-2 rounded-circle" height="16" src="/images/flags/de.svg" />
                        </h5>
                        <p class="text-muted mb-1">Brand Consultant</p>
                    </div>
                    <div class="ms-auto">
                        <div class="dropdown">
                            <a class="btn btn-icon btn-ghost-light text-muted" data-bs-toggle="dropdown" href="#">
                                <i class="ti ti-dots-vertical fs-xl"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="ti ti-share me-2 fs-15"></i>Share</a>
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-edit me-2 fs-15"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-ban me-2 fs-15"></i>Block</a></li>
                                <li><a class="dropdown-item text-danger" href="#"><i
                                            class="ti ti-trash me-2 fs-15"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-2">Ethan helps startups position their brand with high-impact
                    storytelling.</p>
                <a class="icon-link icon-link-hover link-reset fw-medium" href="#!">View Profile <i
                        class="bi fs-16 ti ti-arrow-narrow-right"></i></a>
            </div>
            <div class="card-footer">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CAMPAIGNS</div>
                        <div class="fw-bold">98</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CONTACTS</div>
                        <div class="fw-bold">380</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">ENGAGEMENT</div>
                        <div class="fw-bold">79%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card card-hovered">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3 position-relative">
                        <img alt="avatar" class="rounded-circle" height="72" src="/images/users/user-7.jpg"
                            width="72" />
                        <span class="position-absolute bottom-0 end-0 badge bg-primary rounded-circle p-1 shadow-sm"
                            title="Rating 4.9">
                            <i class="ti ti-star text-white"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-1 d-flex align-items-center">
                            <a class="link-reset fw-medium fs-md" href="#!">Ava Mitchell</a>
                            <img alt="Canada" class="ms-2 rounded-circle" height="16" src="/images/flags/ca.svg" />
                        </h5>
                        <p class="text-muted mb-1">SEO Specialist</p>
                        <span class="badge bg-success-subtle text-success fw-medium">VERIFIED</span>
                    </div>
                    <div class="ms-auto">
                        <div class="dropdown">
                            <a class="btn btn-icon btn-ghost-light text-muted" data-bs-toggle="dropdown" href="#">
                                <i class="ti ti-dots-vertical fs-xl"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="ti ti-share me-2 fs-15"></i>Share</a>
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-edit me-2 fs-15"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-ban me-2 fs-15"></i>Block</a></li>
                                <li><a class="dropdown-item text-danger" href="#"><i
                                            class="ti ti-trash me-2 fs-15"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-2">Ava improves online visibility through white-hat SEO strategies
                    and audits.</p>
                <a class="icon-link icon-link-hover link-reset fw-medium" href="#!">View Profile <i
                        class="bi fs-16 ti ti-arrow-narrow-right"></i></a>
            </div>
            <div class="card-footer">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CAMPAIGNS</div>
                        <div class="fw-bold">150</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CONTACTS</div>
                        <div class="fw-bold">620</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">ENGAGEMENT</div>
                        <div class="fw-bold">95%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card card-hovered">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3 position-relative">
                        <img alt="avatar" class="rounded-circle" height="72" src="/images/users/user-8.jpg"
                            width="72" />
                        <span class="position-absolute bottom-0 end-0 badge bg-primary rounded-circle p-1 shadow-sm"
                            title="Rating 4.4">
                            <i class="ti ti-star text-white"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-1 d-flex align-items-center">
                            <a class="link-reset fw-medium fs-md" href="#!">Lucas Bennett</a>
                            <img alt="India" class="ms-2 rounded-circle" height="16" src="/images/flags/in.svg" />
                        </h5>
                        <p class="text-muted mb-1">Growth Hacker</p>
                    </div>
                    <div class="ms-auto">
                        <div class="dropdown">
                            <a class="btn btn-icon btn-ghost-light text-muted" data-bs-toggle="dropdown" href="#">
                                <i class="ti ti-dots-vertical fs-xl"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="ti ti-share me-2 fs-15"></i>Share</a>
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-edit me-2 fs-15"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-ban me-2 fs-15"></i>Block</a></li>
                                <li><a class="dropdown-item text-danger" href="#"><i
                                            class="ti ti-trash me-2 fs-15"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-2">Lucas focuses on scalable growth through product-led and
                    data-driven experiments.</p>
                <a class="icon-link icon-link-hover link-reset fw-medium" href="#!">View Profile <i
                        class="bi fs-16 ti ti-arrow-narrow-right"></i></a>
            </div>
            <div class="card-footer">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CAMPAIGNS</div>
                        <div class="fw-bold">103</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CONTACTS</div>
                        <div class="fw-bold">470</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">ENGAGEMENT</div>
                        <div class="fw-bold">86%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card card-hovered">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3 position-relative">
                        <img alt="avatar" class="rounded-circle" height="72" src="/images/users/user-9.jpg"
                            width="72" />
                        <span class="position-absolute bottom-0 end-0 badge bg-primary rounded-circle p-1 shadow-sm"
                            title="Rating 4.3">
                            <i class="ti ti-star text-white"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-1 d-flex align-items-center">
                            <a class="link-reset fw-medium fs-md" href="#!">Sophia Taylor</a>
                            <img alt="Spain" class="ms-2 rounded-circle" height="16" src="/images/flags/es.svg" />
                        </h5>
                        <p class="text-muted mb-1">Digital Analyst</p>
                        <span class="badge bg-success-subtle text-success fw-medium">VERIFIED</span>
                    </div>
                    <div class="ms-auto">
                        <div class="dropdown">
                            <a class="btn btn-icon btn-ghost-light text-muted" data-bs-toggle="dropdown" href="#">
                                <i class="ti ti-dots-vertical fs-xl"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="ti ti-share me-2 fs-15"></i>Share</a>
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-edit me-2 fs-15"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-ban me-2 fs-15"></i>Block</a></li>
                                <li><a class="dropdown-item text-danger" href="#"><i
                                            class="ti ti-trash me-2 fs-15"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-2">Sophia provides actionable insights through advanced data
                    visualization.</p>
                <a class="icon-link icon-link-hover link-reset fw-medium" href="#!">View Profile <i
                        class="bi fs-16 ti ti-arrow-narrow-right"></i></a>
            </div>
            <div class="card-footer">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CAMPAIGNS</div>
                        <div class="fw-bold">111</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CONTACTS</div>
                        <div class="fw-bold">355</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">ENGAGEMENT</div>
                        <div class="fw-bold">82%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card card-hovered">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3 position-relative">
                        <img alt="avatar" class="rounded-circle" height="72" src="/images/users/user-10.jpg"
                            width="72" />
                        <span class="position-absolute bottom-0 end-0 badge bg-primary rounded-circle p-1 shadow-sm"
                            title="Rating 4.5">
                            <i class="ti ti-star text-white"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-1 d-flex align-items-center">
                            <a class="link-reset fw-medium fs-md" href="#!">Mason Rivera</a>
                            <img alt="Italy" class="ms-2 rounded-circle" height="16" src="/images/flags/it.svg" />
                        </h5>
                        <p class="text-muted mb-1">Content Strategist</p>
                        <span class="badge bg-success-subtle text-success fw-medium">VERIFIED</span>
                    </div>
                    <div class="ms-auto">
                        <div class="dropdown">
                            <a class="btn btn-icon btn-ghost-light text-muted" data-bs-toggle="dropdown" href="#">
                                <i class="ti ti-dots-vertical fs-xl"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="ti ti-share me-2 fs-15"></i>Share</a>
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-edit me-2 fs-15"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-ban me-2 fs-15"></i>Block</a></li>
                                <li><a class="dropdown-item text-danger" href="#"><i
                                            class="ti ti-trash me-2 fs-15"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-2">Mason crafts content plans that align with business goals and
                    brand tone.</p>
                <a class="icon-link icon-link-hover link-reset fw-medium" href="#!">View Profile <i
                        class="bi fs-16 ti ti-arrow-narrow-right"></i></a>
            </div>
            <div class="card-footer">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CAMPAIGNS</div>
                        <div class="fw-bold">124</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CONTACTS</div>
                        <div class="fw-bold">512</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">ENGAGEMENT</div>
                        <div class="fw-bold">89%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card card-hovered">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3 position-relative">
                        <img alt="avatar" class="rounded-circle" height="72" src="/images/users/user-1.jpg"
                            width="72" />
                        <span class="position-absolute bottom-0 end-0 badge bg-primary rounded-circle p-1 shadow-sm"
                            title="Rating 4.7">
                            <i class="ti ti-star text-white"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-1 d-flex align-items-center">
                            <a class="link-reset fw-medium fs-md" href="#!">Isabella Reed</a>
                            <img alt="Brazil" class="ms-2 rounded-circle" height="16" src="/images/flags/br.svg" />
                        </h5>
                        <p class="text-muted mb-1">Social Media Manager</p>
                    </div>
                    <div class="ms-auto">
                        <div class="dropdown">
                            <a class="btn btn-icon btn-ghost-light text-muted" data-bs-toggle="dropdown" href="#">
                                <i class="ti ti-dots-vertical fs-xl"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="ti ti-share me-2 fs-15"></i>Share</a>
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-edit me-2 fs-15"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-ban me-2 fs-15"></i>Block</a></li>
                                <li><a class="dropdown-item text-danger" href="#"><i
                                            class="ti ti-trash me-2 fs-15"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-2">Isabella leads engagement and audience growth across social
                    platforms.</p>
                <a class="icon-link icon-link-hover link-reset fw-medium" href="#!">View Profile <i
                        class="bi fs-16 ti ti-arrow-narrow-right"></i></a>
            </div>
            <div class="card-footer">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CAMPAIGNS</div>
                        <div class="fw-bold">108</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CONTACTS</div>
                        <div class="fw-bold">498</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">ENGAGEMENT</div>
                        <div class="fw-bold">84%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card card-hovered">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3 position-relative">
                        <img alt="avatar" class="rounded-circle" height="72" src="/images/users/user-2.jpg"
                            width="72" />
                        <span class="position-absolute bottom-0 end-0 badge bg-primary rounded-circle p-1 shadow-sm"
                            title="Rating 4.6">
                            <i class="ti ti-star text-white"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-1 d-flex align-items-center">
                            <a class="link-reset fw-medium fs-md" href="#!">James Lee</a>
                            <img alt="South Korea" class="ms-2 rounded-circle" height="16" src="/images/flags/kr.svg" />
                        </h5>
                        <p class="text-muted mb-1">Data Engineer</p>
                    </div>
                    <div class="ms-auto">
                        <div class="dropdown">
                            <a class="btn btn-icon btn-ghost-light text-muted" data-bs-toggle="dropdown" href="#">
                                <i class="ti ti-dots-vertical fs-xl"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="ti ti-share me-2 fs-15"></i>Share</a>
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-edit me-2 fs-15"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-ban me-2 fs-15"></i>Block</a></li>
                                <li><a class="dropdown-item text-danger" href="#"><i
                                            class="ti ti-trash me-2 fs-15"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-2">James builds and optimizes data pipelines for analytics and ML
                    workflows.</p>
                <a class="icon-link icon-link-hover link-reset fw-medium" href="#!">View Profile <i
                        class="bi fs-16 ti ti-arrow-narrow-right"></i></a>
            </div>
            <div class="card-footer">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CAMPAIGNS</div>
                        <div class="fw-bold">138</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CONTACTS</div>
                        <div class="fw-bold">560</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">ENGAGEMENT</div>
                        <div class="fw-bold">91%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card card-hovered">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3 position-relative">
                        <img alt="avatar" class="rounded-circle" height="72" src="/images/users/user-3.jpg"
                            width="72" />
                        <span class="position-absolute bottom-0 end-0 badge bg-primary rounded-circle p-1 shadow-sm"
                            title="Rating 4.5">
                            <i class="ti ti-star text-white"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-1 d-flex align-items-center">
                            <a class="link-reset fw-medium fs-md" href="#!">Mia Johnson</a>
                            <img alt="Japan" class="ms-2 rounded-circle" height="16" src="/images/flags/jp.svg" />
                        </h5>
                        <p class="text-muted mb-1">Project Manager</p>
                        <span class="badge bg-success-subtle text-success fw-medium">VERIFIED</span>
                    </div>
                    <div class="ms-auto">
                        <div class="dropdown">
                            <a class="btn btn-icon btn-ghost-light text-muted" data-bs-toggle="dropdown" href="#">
                                <i class="ti ti-dots-vertical fs-xl"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="ti ti-share me-2 fs-15"></i>Share</a>
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-edit me-2 fs-15"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-ban me-2 fs-15"></i>Block</a></li>
                                <li><a class="dropdown-item text-danger" href="#"><i
                                            class="ti ti-trash me-2 fs-15"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-2">Mia ensures timely project delivery and team collaboration
                    across sprints.</p>
                <a class="icon-link icon-link-hover link-reset fw-medium" href="#!">View Profile <i
                        class="bi fs-16 ti ti-arrow-narrow-right"></i></a>
            </div>
            <div class="card-footer">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CAMPAIGNS</div>
                        <div class="fw-bold">120</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CONTACTS</div>
                        <div class="fw-bold">405</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">ENGAGEMENT</div>
                        <div class="fw-bold">85%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card card-hovered">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3 position-relative">
                        <img alt="avatar" class="rounded-circle" height="72" src="/images/users/user-4.jpg"
                            width="72" />
                        <span class="position-absolute bottom-0 end-0 badge bg-primary rounded-circle p-1 shadow-sm"
                            title="Rating 4.4">
                            <i class="ti ti-star text-white"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-1 d-flex align-items-center">
                            <a class="link-reset fw-medium fs-md" href="#!">Benjamin Adams</a>
                            <img alt="South Africa" class="ms-2 rounded-circle" height="16"
                                src="/images/flags/za.svg" />
                        </h5>
                        <p class="text-muted mb-1">Product Designer</p>
                        <span class="badge bg-success-subtle text-success fw-medium">VERIFIED</span>
                    </div>
                    <div class="ms-auto">
                        <div class="dropdown">
                            <a class="btn btn-icon btn-ghost-light text-muted" data-bs-toggle="dropdown" href="#">
                                <i class="ti ti-dots-vertical fs-xl"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="ti ti-share me-2 fs-15"></i>Share</a>
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-edit me-2 fs-15"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-ban me-2 fs-15"></i>Block</a></li>
                                <li><a class="dropdown-item text-danger" href="#"><i
                                            class="ti ti-trash me-2 fs-15"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-2">Benjamin designs products that are both functional and
                    delightful to use.</p>
                <a class="icon-link icon-link-hover link-reset fw-medium" href="#!">View Profile <i
                        class="bi fs-16 ti ti-arrow-narrow-right"></i></a>
            </div>
            <div class="card-footer">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CAMPAIGNS</div>
                        <div class="fw-bold">106</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CONTACTS</div>
                        <div class="fw-bold">376</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">ENGAGEMENT</div>
                        <div class="fw-bold">80%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card card-hovered">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3 position-relative">
                        <img alt="avatar" class="rounded-circle" height="72" src="/images/users/user-8.jpg"
                            width="72" />
                        <span class="position-absolute bottom-0 end-0 badge bg-primary rounded-circle p-1 shadow-sm"
                            title="Rating 4.8">
                            <i class="ti ti-star text-white"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-1 d-flex align-items-center">
                            <a class="link-reset fw-medium fs-md" href="#!">Charlotte Evans</a>
                            <img alt="Netherlands" class="ms-2 rounded-circle" height="16" src="/images/flags/nl.svg" />
                        </h5>
                        <p class="text-muted mb-1">Creative Strategist</p>
                    </div>
                    <div class="ms-auto">
                        <div class="dropdown">
                            <a class="btn btn-icon btn-ghost-light text-muted" data-bs-toggle="dropdown" href="#">
                                <i class="ti ti-dots-vertical fs-xl"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="ti ti-share me-2 fs-15"></i>Share</a>
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-edit me-2 fs-15"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-ban me-2 fs-15"></i>Block</a></li>
                                <li><a class="dropdown-item text-danger" href="#"><i
                                            class="ti ti-trash me-2 fs-15"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-2">Charlotte combines data with creativity to craft powerful brand
                    messages.</p>
                <a class="icon-link icon-link-hover link-reset fw-medium" href="#!">View Profile <i
                        class="bi fs-16 ti ti-arrow-narrow-right"></i></a>
            </div>
            <div class="card-footer">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CAMPAIGNS</div>
                        <div class="fw-bold">129</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CONTACTS</div>
                        <div class="fw-bold">590</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">ENGAGEMENT</div>
                        <div class="fw-bold">94%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card card-hovered">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3 position-relative">
                        <img alt="avatar" class="rounded-circle" height="72" src="/images/users/user-7.jpg"
                            width="72" />
                        <span class="position-absolute bottom-0 end-0 badge bg-primary rounded-circle p-1 shadow-sm"
                            title="Rating 4.6">
                            <i class="ti ti-star text-white"></i>
                        </span>
                    </div>
                    <div>
                        <h5 class="mb-1 d-flex align-items-center">
                            <a class="link-reset fw-medium fs-md" href="#!">Daniel Foster</a>
                            <img alt="Sweden" class="ms-2 rounded-circle" height="16" src="/images/flags/se.svg" />
                        </h5>
                        <p class="text-muted mb-1">Innovation Lead</p>
                        <span class="badge bg-success-subtle text-success fw-medium">VERIFIED</span>
                    </div>
                    <div class="ms-auto">
                        <div class="dropdown">
                            <a class="btn btn-icon btn-ghost-light text-muted" data-bs-toggle="dropdown" href="#">
                                <i class="ti ti-dots-vertical fs-xl"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="ti ti-share me-2 fs-15"></i>Share</a>
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-edit me-2 fs-15"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-ban me-2 fs-15"></i>Block</a></li>
                                <li><a class="dropdown-item text-danger" href="#"><i
                                            class="ti ti-trash me-2 fs-15"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-2">
                    Daniel drives innovation initiatives and fosters cross-functional collaboration.
                </p>
                <a class="icon-link icon-link-hover link-reset fw-medium" href="#!">View Profile <i
                        class="bi fs-16 ti ti-arrow-narrow-right"></i></a>
            </div>
            <div class="card-footer">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CAMPAIGNS</div>
                        <div class="fw-bold">117</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">CONTACTS</div>
                        <div class="fw-bold">488</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted fs-xs mb-1">ENGAGEMENT</div>
                        <div class="fw-bold">90%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- end row -->
<ul class="pagination pagination-rounded pagination-boxed justify-content-center">
    <li class="page-item">
        <a aria-label="Previous" class="page-link" href="javascript: void(0);">
            <span aria-hidden="true">«</span>
        </a>
    </li>
    <li class="page-item active"><a class="page-link" href="javascript: void(0);">1</a></li>
    <li class="page-item"><a class="page-link" href="javascript: void(0);">2</a></li>
    <li class="page-item"><a class="page-link" href="javascript: void(0);">3</a></li>
    <li class="page-item"><a class="page-link" href="javascript: void(0);">4</a></li>
    <li class="page-item"><a class="page-link" href="javascript: void(0);">5</a></li>
    <li class="page-item">
        <a aria-label="Next" class="page-link" href="javascript: void(0);">
            <span aria-hidden="true">»</span>
        </a>
    </li>
</ul>
@endsection