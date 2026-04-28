@extends('layouts.vertical', ['title' => 'Core UI Elements'])

@section('content')
@include('layouts.shared.page-title', [
'title' => 'Core UI Elements',
'subTitle' =>
'Explore essential components like buttons, alerts, images, badges, and more to build consistent, responsive
interfaces.',
'badgeIcon' => 'layout-grid',
'badgeTitle' => 'UI Essentials',
])

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Buttons</h5>
                <div class="card-action">
                    <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Default Buttons</h5>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <button class="btn btn-default">Default</button>
                            <button class="btn btn-primary">Primary</button>
                            <button class="btn btn-secondary">Secondary</button>
                            <button class="btn btn-success">Success</button>
                            <button class="btn btn-danger">Danger</button>
                            <button class="btn btn-warning">Warning</button>
                            <button class="btn btn-info">Info</button>
                            <button class="btn btn-light">Light</button>
                            <button class="btn btn-dark">Dark</button>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Rounded Buttons</h5>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <button class="btn btn-primary rounded-pill">Primary</button>
                            <button class="btn btn-secondary rounded-pill">Secondary</button>
                            <button class="btn btn-success rounded-pill">Success</button>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Outline Buttons</h5>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <button class="btn btn-outline-primary">Primary</button>
                            <button class="btn btn-outline-success">Success</button>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Soft Buttons</h5>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <button class="btn btn-soft-primary">Primary</button>
                            <button class="btn btn-soft-warning">Warning</button>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Ghost Buttons</h5>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <button class="btn btn-ghost-danger">Danger</button>
                            <button class="btn btn-ghost-info">Info</button>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Button Sizes</h5>
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <button class="btn btn-primary btn-sm">Small</button>
                            <button class="btn btn-primary">Default</button>
                            <button class="btn btn-primary btn-lg">Large</button>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Disabled Buttons</h5>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <button class="btn btn-dark" disabled="">Disabled</button>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Block Buttons</h5>
                        <div class="d-grid gap-2 mb-2">
                            <button class="btn btn-primary btn-lg">Block Button</button>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Toggle Buttons</h5>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <button class="btn btn-primary" data-bs-toggle="button">Toggle</button>
                            <button aria-pressed="true" class="btn btn-primary active"
                                data-bs-toggle="button">Active</button>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Button Tags</h5>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <a class="btn btn-primary" href="#">Link</a>
                            <button class="btn btn-primary">Button</button>
                            <input class="btn btn-primary" type="submit" value="Submit" />
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Icon Buttons</h5>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <button class="btn btn-icon btn-primary"><i class="ti ti-star"></i></button>
                            <button class="btn btn-icon btn-outline-info"><i class="ti ti-credit-card"></i></button>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Button Groups</h5>
                        <div class="btn-group">
                            <button class="btn btn-light">Left</button>
                            <button class="btn btn-light">Middle</button>
                            <button class="btn btn-light">Right</button>
                        </div>
                    </div>
                </div> <!-- end row -->
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div> <!-- end col-12 -->
</div> <!-- end row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Avatar &amp; Image Styles</h5>
                <div class="card-action">
                    <a class="card-action-item" data-action="card-toggle" href="#"><i class="ti ti-chevron-up"></i></a>
                </div>
            </div>
            <div class="card-body">
                <!-- Images Shapes -->
                <div class="row">
                    <div class="col-sm-2 text-center">
                        <img alt="image" class="img-fluid" src="/images/stock/small-1.jpg" />
                        <p class="mb-0 mt-2"><code>.img-fluid</code></p>
                    </div>
                    <div class="col-sm-2 text-center">
                        <img alt="image" class="img-fluid rounded" src="/images/stock/small-2.jpg" />
                        <p class="mb-0 mt-2"><code>.rounded</code></p>
                    </div>
                    <div class="col-sm-2 text-center">
                        <img alt="image" class="img-fluid rounded" src="/images/users/user-2.jpg" width="120" />
                        <p class="mb-0 mt-2"><code>.rounded</code></p>
                    </div>
                    <div class="col-sm-2 text-center">
                        <img alt="image" class="img-fluid rounded-circle" src="/images/users/user-5.jpg" width="120" />
                        <p class="mb-0 mt-2"><code>.rounded-circle</code></p>
                    </div>
                    <div class="col-sm-2 text-center">
                        <img alt="image" class="img-fluid img-thumbnail" src="/images/stock/small-5.jpg" />
                        <p class="mb-0 mt-2"><code>.img-thumbnail</code></p>
                    </div>
                    <div class="col-sm-2 text-center">
                        <img alt="image" class="img-fluid rounded-circle img-thumbnail" src="/images/users/user-8.jpg"
                            width="120" />
                        <p class="mb-0 mt-2"><code>.rounded-circle .img-thumbnail</code></p>
                    </div>
                </div>
                <!-- Avatar Sizes -->
                <div class="row mt-4">
                    <div class="col-xl-6">
                        <div class="col">
                            <h5 class="mb-2 pb-1">Avatar Sizes</h5>
                            <div class="row text-center">
                                <div class="col">
                                    <!-- .avatar-xs -->
                                    <img alt="image" class="img-fluid avatar-xs rounded"
                                        src="/images/users/user-2.jpg" />
                                    <p class="mt-2">
                                        <code>.avatar-xs</code>
                                    </p>
                                </div>
                                <div class="col">
                                    <!-- .avatar-xs -->
                                    <div class="avatar-xs mx-auto">
                                        <span class="avatar-title text-bg-primary rounded">
                                            xs
                                        </span>
                                    </div>
                                    <p class="mt-2">
                                        <code>.avatar-xs</code>
                                    </p>
                                </div>
                                <div class="col">
                                    <!-- .avatar-xs -->
                                    <div class="avatar-xs mx-auto">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded">
                                            xs
                                        </span>
                                    </div>
                                    <p class="mt-2">
                                        <code>.avatar-xs</code>
                                    </p>
                                </div>
                            </div> <!-- end row-->
                            <div class="row text-center mt-3">
                                <div class="col">
                                    <!-- .avatar-sm -->
                                    <img alt="image" class="img-fluid avatar-sm rounded"
                                        src="/images/users/user-3.jpg" />
                                    <p class="mt-2">
                                        <code>.avatar-sm</code>
                                    </p>
                                </div>
                                <div class="col">
                                    <!-- .avatar-sm -->
                                    <div class="avatar-sm mx-auto">
                                        <span class="avatar-title text-bg-primary rounded">
                                            sm
                                        </span>
                                    </div>
                                    <p class="mt-2">
                                        <code>.avatar-sm</code>
                                    </p>
                                </div>
                                <div class="col">
                                    <!-- .avatar-sm -->
                                    <div class="avatar-sm mx-auto">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded">
                                            sm
                                        </span>
                                    </div>
                                    <p class="mt-2">
                                        <code>.avatar-sm</code>
                                    </p>
                                </div>
                            </div> <!-- end row-->
                            <div class="row text-center mt-3">
                                <div class="col">
                                    <!-- .avatar-md -->
                                    <img alt="image" class="img-fluid avatar-md rounded"
                                        src="/images/users/user-4.jpg" />
                                    <p class="mt-2">
                                        <code>.avatar-md</code>
                                    </p>
                                </div>
                                <div class="col">
                                    <!-- .avatar-md -->
                                    <div class="avatar-md mx-auto">
                                        <span class="avatar-title text-bg-primary rounded">
                                            md
                                        </span>
                                    </div>
                                    <p class="mt-2">
                                        <code>.avatar-md</code>
                                    </p>
                                </div>
                                <div class="col">
                                    <!-- .avatar-md -->
                                    <div class="avatar-md mx-auto">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded">
                                            md
                                        </span>
                                    </div>
                                    <p class="mt-2">
                                        <code>.avatar-md</code>
                                    </p>
                                </div>
                            </div> <!-- end row-->
                            <div class="row text-center mt-3">
                                <div class="col">
                                    <!-- .avatar-lg -->
                                    <img alt="image" class="img-fluid avatar-lg rounded"
                                        src="/images/users/user-5.jpg" />
                                    <p class="mt-2">
                                        <code>.avatar-lg</code>
                                    </p>
                                </div>
                                <div class="col">
                                    <!-- .avatar-lg -->
                                    <div class="avatar-lg mx-auto">
                                        <span class="avatar-title text-bg-primary rounded">
                                            LG
                                        </span>
                                    </div>
                                    <p class="mt-2">
                                        <code>.avatar-lg</code>
                                    </p>
                                </div>
                                <div class="col">
                                    <!-- .avatar-lg -->
                                    <div class="avatar-lg mx-auto">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded">
                                            LG
                                        </span>
                                    </div>
                                    <p class="mt-2">
                                        <code>.avatar-lg</code>
                                    </p>
                                </div>
                            </div> <!-- end row-->
                            <div class="row text-center mt-3">
                                <div class="col">
                                    <!-- .avatar-xl -->
                                    <img alt="image" class="img-fluid avatar-xl rounded"
                                        src="/images/users/user-6.jpg" />
                                    <p class="mt-2">
                                        <code>.avatar-xl</code>
                                    </p>
                                </div>
                                <div class="col">
                                    <!-- .avatar-xl -->
                                    <div class="avatar-xl mx-auto">
                                        <span class="avatar-title text-bg-primary rounded">
                                            XL
                                        </span>
                                    </div>
                                    <p class="mt-2">
                                        <code>.avatar-xl</code>
                                    </p>
                                </div>
                                <div class="col">
                                    <!-- .avatar-xl -->
                                    <div class="avatar-xl mx-auto">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded">
                                            XL
                                        </span>
                                    </div>
                                    <p class="mt-2">
                                        <code>.avatar-xl</code>
                                    </p>
                                </div>
                            </div> <!-- end row-->
                            <!-- Repeat similar block for sm, md, lg, xl -->
                        </div>
                    </div>
                    <!-- Rounded Circle Avatars -->
                    <div class="col-xl-6">
                        <div class="col">
                            <h5 class="mb-2 pb-1">Avatar Sizes with Rounded</h5>
                            <div class="row text-center">
                                <div class="col">
                                    <!-- .avatar-xs -->
                                    <img alt="image" class="img-fluid avatar-xs rounded-circle"
                                        src="/images/users/user-7.jpg" />
                                    <p class="mt-2">
                                        <code>.avatar-xs</code>
                                    </p>
                                </div>
                                <div class="col">
                                    <!-- .avatar-xs -->
                                    <div class="avatar-xs mx-auto">
                                        <span class="avatar-title text-bg-primary rounded-circle">
                                            xs
                                        </span>
                                    </div>
                                    <p class="mt-2">
                                        <code>.avatar-xs</code>
                                    </p>
                                </div>
                                <div class="col">
                                    <!-- .avatar-xs -->
                                    <div class="avatar-xs mx-auto">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                            xs
                                        </span>
                                    </div>
                                    <p class="mt-2">
                                        <code>.avatar-xs</code>
                                    </p>
                                </div>
                            </div> <!-- end row-->
                            <div class="row text-center mt-3">
                                <div class="col">
                                    <!-- .avatar-sm -->
                                    <img alt="image" class="img-fluid avatar-sm rounded-circle"
                                        src="/images/users/user-8.jpg" />
                                    <p class="mt-2">
                                        <code>.avatar-sm</code>
                                    </p>
                                </div>
                                <div class="col">
                                    <!-- .avatar-sm -->
                                    <div class="avatar-sm mx-auto">
                                        <span class="avatar-title text-bg-primary rounded-circle">
                                            sm
                                        </span>
                                    </div>
                                    <p class="mt-2">
                                        <code>.avatar-sm</code>
                                    </p>
                                </div>
                                <div class="col">
                                    <!-- .avatar-sm -->
                                    <div class="avatar-sm mx-auto">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                            sm
                                        </span>
                                    </div>
                                    <p class="mt-2">
                                        <code>.avatar-sm</code>
                                    </p>
                                </div>
                            </div> <!-- end row-->
                            <div class="row text-center mt-3">
                                <div class="col">
                                    <!-- .avatar-md -->
                                    <img alt="image" class="img-fluid avatar-md rounded-circle"
                                        src="/images/users/user-9.jpg" />
                                    <p class="mt-2">
                                        <code>.avatar-md</code>
                                    </p>
                                </div>
                                <div class="col">
                                    <!-- .avatar-md -->
                                    <div class="avatar-md mx-auto">
                                        <span class="avatar-title text-bg-primary rounded-circle">
                                            md
                                        </span>
                                    </div>
                                    <p class="mt-2">
                                        <code>.avatar-md</code>
                                    </p>
                                </div>
                                <div class="col">
                                    <!-- .avatar-md -->
                                    <div class="avatar-md mx-auto">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                            md
                                        </span>
                                    </div>
                                    <p class="mt-2">
                                        <code>.avatar-md</code>
                                    </p>
                                </div>
                            </div> <!-- end row-->
                            <div class="row text-center mt-3">
                                <div class="col">
                                    <!-- .avatar-lg -->
                                    <img alt="image" class="img-fluid avatar-lg rounded-circle"
                                        src="/images/users/user-10.jpg" />
                                    <p class="mt-2">
                                        <code>.avatar-lg</code>
                                    </p>
                                </div>
                                <div class="col">
                                    <!-- .avatar-lg -->
                                    <div class="avatar-lg mx-auto">
                                        <span class="avatar-title text-bg-primary rounded-circle">
                                            LG
                                        </span>
                                    </div>
                                    <p class="mt-2">
                                        <code>.avatar-lg</code>
                                    </p>
                                </div>
                                <div class="col">
                                    <!-- .avatar-lg -->
                                    <div class="avatar-lg mx-auto">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                            LG
                                        </span>
                                    </div>
                                    <p class="mt-2">
                                        <code>.avatar-lg</code>
                                    </p>
                                </div>
                            </div> <!-- end row-->
                            <div class="row text-center mt-3">
                                <div class="col">
                                    <!-- .avatar-xl -->
                                    <img alt="image" class="img-fluid avatar-xl rounded-circle"
                                        src="/images/users/user-1.jpg" />
                                    <p class="mt-2">
                                        <code>.avatar-xl</code>
                                    </p>
                                </div>
                                <div class="col">
                                    <!-- .avatar-xl -->
                                    <div class="avatar-xl mx-auto">
                                        <span class="avatar-title text-bg-primary rounded-circle">
                                            XL
                                        </span>
                                    </div>
                                    <p class="mt-2">
                                        <code>.avatar-xl</code>
                                    </p>
                                </div>
                                <div class="col">
                                    <!-- .avatar-xl -->
                                    <div class="avatar-xl mx-auto">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                            XL
                                        </span>
                                    </div>
                                    <p class="mt-2">
                                        <code>.avatar-xl</code>
                                    </p>
                                </div>
                            </div> <!-- end row-->
                        </div>
                    </div>
                </div>
                <!-- Avatar Groups -->
                <div class="row mt-4">
                    <div class="col">
                        <h5 class="mb-2 pb-1">Avatar Groups</h5>
                        <div class="row">
                            <div class="col-xl-3">
                                <!-- Default Group -->
                                <div class="avatar-group">
                                    <div class="avatar">
                                        <img alt="" class="rounded-circle avatar-sm" src="/images/users/user-4.jpg" />
                                    </div>
                                    <div class="avatar">
                                        <img alt="" class="rounded-circle avatar-sm" src="/images/users/user-5.jpg" />
                                    </div>
                                    <div class="avatar">
                                        <img alt="" class="rounded-circle avatar-sm" src="/images/users/user-3.jpg" />
                                    </div>
                                    <div class="avatar">
                                        <img alt="" class="rounded-circle avatar-sm" src="/images/users/user-8.jpg" />
                                    </div>
                                    <div class="avatar">
                                        <img alt="" class="rounded-circle avatar-sm" src="/images/users/user-2.jpg" />
                                    </div>
                                </div>
                            </div> <!-- end col-->
                            <div class="col-xl-3">
                                <!-- Default Group (Soft) -->
                                <div class="avatar-group">
                                    <div class="avatar avatar-md">
                                        <span class="avatar-title text-bg-primary rounded-circle fw-bold">
                                            K
                                        </span>
                                    </div>
                                    <div class="avatar avatar-md">
                                        <span class="avatar-title text-bg-primary rounded-circle fw-bold">
                                            H
                                        </span>
                                    </div>
                                    <div class="avatar avatar-md">
                                        <span class="avatar-title text-bg-primary rounded-circle fw-bold">
                                            L
                                        </span>
                                    </div>
                                    <div class="avatar avatar-md">
                                        <span class="avatar-title text-bg-primary rounded-circle fw-bold">
                                            G
                                        </span>
                                    </div>
                                </div>
                            </div> <!-- end col-->
                            <div class="col-xl-3">
                                <!-- Default Group (Soft) -->
                                <div class="avatar-group">
                                    <div class="avatar avatar-lg">
                                        <span
                                            class="avatar-title bg-primary-subtle text-primary rounded-circle fw-bold shadow">
                                            K
                                        </span>
                                    </div>
                                    <div class="avatar avatar-lg">
                                        <span
                                            class="avatar-title bg-primary-subtle text-primary rounded-circle fw-bold shadow">
                                            H
                                        </span>
                                    </div>
                                    <div class="avatar avatar-lg">
                                        <span
                                            class="avatar-title bg-primary-subtle text-primary rounded-circle fw-bold shadow">
                                            L
                                        </span>
                                    </div>
                                    <div class="avatar avatar-lg">
                                        <span
                                            class="avatar-title bg-primary-subtle text-primary rounded-circle fw-bold shadow">
                                            G
                                        </span>
                                    </div>
                                </div>
                            </div> <!-- end col-->
                            <div class="col-xl-3">
                                <!-- Default Group (Soft) -->
                                <div class="avatar-group">
                                    <div class="avatar">
                                        <img alt="" class="rounded-circle avatar-xl" src="/images/users/user-10.jpg" />
                                    </div>
                                    <div class="avatar avatar-xl">
                                        <span class="avatar-title text-bg-primary rounded-circle fs-xl fw-bold">
                                            D
                                        </span>
                                    </div>
                                    <div class="avatar">
                                        <img alt="" class="rounded-circle avatar-xl" src="/images/users/user-7.jpg" />
                                    </div>
                                    <div class="avatar">
                                        <img alt="" class="rounded-circle avatar-xl" src="/images/users/user-1.jpg" />
                                    </div>
                                    <div class="avatar avatar-xl">
                                        <span class="avatar-title fs-xl text-bg-primary rounded-circle fw-bold">
                                            9+
                                        </span>
                                    </div>
                                </div>
                            </div> <!-- end col-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- end row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Badge Variations</h5>
                <div class="card-action">
                    <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Basic Badges</h5>
                        <span class="badge badge-default">Default</span>
                        <span class="badge text-bg-primary">Primary</span>
                        <span class="badge text-bg-secondary">Secondary</span>
                        <span class="badge text-bg-success">Success</span>
                        <span class="badge text-bg-danger">Danger</span>
                        <span class="badge text-bg-warning">Warning</span>
                        <span class="badge text-bg-info">Info</span>
                        <span class="badge text-bg-light">Light</span>
                        <span class="badge text-bg-dark">Dark</span>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Basic Pill Badges</h5>
                        <span class="badge badge-default rounded-pill">Default</span>
                        <span class="badge text-bg-primary rounded-pill">Primary</span>
                        <span class="badge text-bg-secondary rounded-pill">Secondary</span>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Outline Badges</h5>
                        <span class="badge badge-outline-primary">Primary</span>
                        <span class="badge badge-outline-secondary">Secondary</span>
                        <span class="badge badge-outline-success">Success</span>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Outline Pill Badges</h5>
                        <span class="badge badge-outline-primary rounded-pill">Primary</span>
                        <span class="badge badge-outline-secondary rounded-pill">Secondary</span>
                        <span class="badge badge-outline-success rounded-pill">Success</span>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Lighten Badges</h5>
                        <span class="badge badge-soft-primary">Primary</span>
                        <span class="badge badge-soft-secondary">Secondary</span>
                        <span class="badge badge-soft-success">Success</span>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Lighten Pill Badges</h5>
                        <span class="badge badge-soft-primary rounded-pill">Primary</span>
                        <span class="badge badge-soft-secondary rounded-pill">Secondary</span>
                        <span class="badge badge-soft-success rounded-pill">Success</span>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Label Badges</h5>
                        <span class="badge badge-label badge-default">Default</span>
                        <span class="badge badge-label text-bg-primary">Primary</span>
                        <span class="badge badge-label text-bg-secondary">Secondary</span>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Square Badges</h5>
                        <span class="badge badge-square badge-default">0</span>
                        <span class="badge badge-square text-bg-primary">1</span>
                        <span class="badge badge-square text-bg-secondary">2</span>
                        <span class="badge badge-square text-bg-success">3</span>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Circle Badges</h5>
                        <span class="badge badge-circle badge-default">0</span>
                        <span class="badge badge-circle text-bg-primary">1</span>
                        <span class="badge badge-circle text-bg-secondary">2</span>
                        <span class="badge badge-circle text-bg-success">3</span>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Positioned</h5>
                        <div class="d-flex flex-wrap gap-3">
                            <button class="btn btn-primary position-relative" type="button">
                                Inbox
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    99+
                                    <span class="visually-hidden">unread messages</span>
                                </span>
                            </button>
                            <button class="btn btn-primary position-relative" type="button">
                                Profile
                                <span
                                    class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                    <span class="visually-hidden">New alerts</span>
                                </span>
                            </button>
                            <button class="btn btn-primary" type="button">
                                Notifications <span class="badge text-bg-light ms-1">4</span>
                            </button>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Headings with Badges</h5>
                        <h1>h1.Example heading <span class="badge text-bg-primary">New</span></h1>
                        <h2>h2.Example heading <span class="badge text-bg-primary">New</span></h2>
                        <h3>h3.Example heading <span class="badge text-bg-primary">New</span></h3>
                        <h4>h4.Example heading <span class="badge text-bg-primary">New</span></h4>
                        <h5>h5.Example heading <span class="badge text-bg-primary">New</span></h5>
                        <h6>h6.Example heading <span class="badge text-bg-primary">New</span></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- end row -->
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Accordions Variations</h5>
                <div class="card-action">
                    <a class="card-action-item" data-action="card-toggle" href="#!">
                        <i class="ti ti-chevron-up"></i>
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-4">
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Default Accordions</h5>
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button aria-controls="collapseOne" aria-expanded="true" class="accordion-button"
                                        data-bs-target="#collapseOne" data-bs-toggle="collapse" type="button">
                                        Accordion Item #1
                                    </button>
                                </h2>
                                <div aria-labelledby="headingOne" class="accordion-collapse collapse show"
                                    data-bs-parent="#accordionExample" id="collapseOne">
                                    <div class="accordion-body">
                                        <strong>This is the first item's accordion body.</strong> It is
                                        shown by default, until the collapse
                                        plugin adds the appropriate classes that we use to style each
                                        element. These classes control the overall
                                        appearance, as well as the showing and hiding via CSS
                                        transitions. You can modify any of this with
                                        custom CSS or overriding our default variables. It's also worth
                                        noting that just about any HTML can go
                                        within the <code>.accordion-body</code>, though the transition
                                        does limit overflow.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button aria-controls="collapseTwo" aria-expanded="false"
                                        class="accordion-button collapsed" data-bs-target="#collapseTwo"
                                        data-bs-toggle="collapse" type="button">
                                        Accordion Item #2
                                    </button>
                                </h2>
                                <div aria-labelledby="headingTwo" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionExample" id="collapseTwo">
                                    <div class="accordion-body">
                                        <strong>This is the second item's accordion body.</strong> It is
                                        hidden by default, until the collapse
                                        plugin adds the appropriate classes that we use to style each
                                        element. These classes control the overall
                                        appearance, as well as the showing and hiding via CSS
                                        transitions. You can modify any of this with
                                        custom CSS or overriding our default variables. It's also worth
                                        noting that just about any HTML can go
                                        within the <code>.accordion-body</code>, though the transition
                                        does limit overflow.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button aria-controls="collapseThree" aria-expanded="false"
                                        class="accordion-button collapsed" data-bs-target="#collapseThree"
                                        data-bs-toggle="collapse" type="button">
                                        Accordion Item #3
                                    </button>
                                </h2>
                                <div aria-labelledby="headingThree" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionExample" id="collapseThree">
                                    <div class="accordion-body">
                                        <strong>This is the third item's accordion body.</strong> It is
                                        hidden by default, until the collapse
                                        plugin adds the appropriate classes that we use to style each
                                        element. These classes control the overall
                                        appearance, as well as the showing and hiding via CSS
                                        transitions. You can modify any of this with
                                        custom CSS or overriding our default variables. It's also worth
                                        noting that just about any HTML can go
                                        within the <code>.accordion-body</code>, though the transition
                                        does limit overflow.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Flush Accordions</h5>
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button aria-controls="flush-collapseOne" aria-expanded="false"
                                        class="accordion-button" data-bs-target="#flush-collapseOne"
                                        data-bs-toggle="collapse" type="button">
                                        Accordion Item #1
                                    </button>
                                </h2>
                                <div aria-labelledby="flush-headingOne" class="accordion-collapse collapse show"
                                    data-bs-parent="#accordionFlushExample" id="flush-collapseOne">
                                    <div class="accordion-body">
                                        <p class="m-0">Placeholder content for this accordion, which is
                                            intended to demonstrate the
                                            <code>.accordion-flush</code> class. This is the first
                                            item's accordion body.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingTwo">
                                    <button aria-controls="flush-collapseTwo" aria-expanded="false"
                                        class="accordion-button collapsed" data-bs-target="#flush-collapseTwo"
                                        data-bs-toggle="collapse" type="button">
                                        Accordion Item #2
                                    </button>
                                </h2>
                                <div aria-labelledby="flush-headingTwo" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionFlushExample" id="flush-collapseTwo">
                                    <div class="accordion-body">Placeholder content for this accordion,
                                        which is intended to demonstrate the
                                        <code>.accordion-flush</code> class. This is the second item's
                                        accordion body. Let's imagine this being
                                        filled with some actual content.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingThree">
                                    <button aria-controls="flush-collapseThree" aria-expanded="false"
                                        class="accordion-button collapsed" data-bs-target="#flush-collapseThree"
                                        data-bs-toggle="collapse" type="button">
                                        Accordion Item #3
                                    </button>
                                </h2>
                                <div aria-labelledby="flush-headingThree" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionFlushExample" id="flush-collapseThree">
                                    <div class="accordion-body">Placeholder content for this accordion,
                                        which is intended to demonstrate the
                                        <code>.accordion-flush</code> class. This is the third item's
                                        accordion body. Nothing more exciting
                                        happening here in terms of content, but just filling up the
                                        space to make it look, at least at first
                                        glance, a bit more representative of how this would look in a
                                        real-world application.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Always Open Accordions</h5>
                        <div class="accordion" id="accordionPanelsStayOpenExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                                    <button aria-controls="panelsStayOpen-collapseOne" aria-expanded="true"
                                        class="accordion-button" data-bs-target="#panelsStayOpen-collapseOne"
                                        data-bs-toggle="collapse" type="button">
                                        Accordion Item #1
                                    </button>
                                </h2>
                                <div aria-labelledby="panelsStayOpen-headingOne"
                                    class="accordion-collapse collapse show" id="panelsStayOpen-collapseOne">
                                    <div class="accordion-body">
                                        <strong>This is the first item's accordion body.</strong> It
                                        is shown by default, until the collapse
                                        plugin adds the appropriate classes that we use to style
                                        each element. These classes control the
                                        overall appearance, as well as the showing and hiding via
                                        CSS transitions. You can modify any of
                                        this with custom CSS or overriding our default variables.
                                        It's also worth noting that just about any
                                        HTML can go within the <code>.accordion-body</code>, though
                                        the transition does limit overflow.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                                    <button aria-controls="panelsStayOpen-collapseTwo" aria-expanded="false"
                                        class="accordion-button collapsed" data-bs-target="#panelsStayOpen-collapseTwo"
                                        data-bs-toggle="collapse" type="button">
                                        Accordion Item #2
                                    </button>
                                </h2>
                                <div aria-labelledby="panelsStayOpen-headingTwo" class="accordion-collapse collapse"
                                    id="panelsStayOpen-collapseTwo">
                                    <div class="accordion-body">
                                        <strong>This is the second item's accordion body.</strong>
                                        It is hidden by default, until the
                                        collapse plugin adds the appropriate classes that we use to
                                        style each element. These classes
                                        control the overall appearance, as well as the showing and
                                        hiding via CSS transitions. You can
                                        modify any of this with custom CSS or overriding our default
                                        variables. It's also worth noting that
                                        just about any HTML can go within the
                                        <code>.accordion-body</code>, though the transition does
                                        limit
                                        overflow.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                                    <button aria-controls="panelsStayOpen-collapseThree" aria-expanded="false"
                                        class="accordion-button collapsed"
                                        data-bs-target="#panelsStayOpen-collapseThree" data-bs-toggle="collapse"
                                        type="button">
                                        Accordion Item #3
                                    </button>
                                </h2>
                                <div aria-labelledby="panelsStayOpen-headingThree" class="accordion-collapse collapse"
                                    id="panelsStayOpen-collapseThree">
                                    <div class="accordion-body">
                                        <strong>This is the third item's accordion body.</strong> It
                                        is hidden by default, until the
                                        collapse plugin adds the appropriate classes that we use to
                                        style each element. These classes
                                        control the overall appearance, as well as the showing and
                                        hiding via CSS transitions. You can
                                        modify any of this with custom CSS or overriding our default
                                        variables. It's also worth noting that
                                        just about any HTML can go within the
                                        <code>.accordion-body</code>, though the transition does
                                        limit
                                        overflow.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Accordion Without Arrow </h5>
                        <div class="accordion accordion-arrow-none" id="withoutarrowaccordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="withoutarrowheadingOne">
                                    <button aria-controls="withoutarrowcollapseOne" aria-expanded="true"
                                        class="accordion-button" data-bs-target="#withoutarrowcollapseOne"
                                        data-bs-toggle="collapse" type="button">
                                        Accordion Item #1
                                    </button>
                                </h2>
                                <div aria-labelledby="withoutarrowheadingOne" class="accordion-collapse collapse show"
                                    data-bs-parent="#withoutarrowaccordionExample" id="withoutarrowcollapseOne">
                                    <div class="accordion-body">
                                        <strong>This is the first item's accordion body.</strong> It
                                        is shown by default, until the collapse
                                        plugin adds the appropriate classes that we use to style
                                        each element. These classes control the overall
                                        appearance, as well as the showing and hiding via CSS
                                        transitions. You can modify any of this with
                                        custom CSS or overriding our default variables. It's also
                                        worth noting that just about any HTML can go
                                        within the <code>.accordion-body</code>, though the
                                        transition does limit overflow.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="withoutarrowheadingTwo">
                                    <button aria-controls="withoutarrowcollapseTwo" aria-expanded="false"
                                        class="accordion-button collapsed" data-bs-target="#withoutarrowcollapseTwo"
                                        data-bs-toggle="collapse" type="button">
                                        Accordion Item #2
                                    </button>
                                </h2>
                                <div aria-labelledby="withoutarrowheadingTwo" class="accordion-collapse collapse"
                                    data-bs-parent="#withoutarrowaccordionExample" id="withoutarrowcollapseTwo">
                                    <div class="accordion-body">
                                        <strong>This is the second item's accordion body.</strong>
                                        It is hidden by default, until the collapse
                                        plugin adds the appropriate classes that we use to style
                                        each element. These classes control the overall
                                        appearance, as well as the showing and hiding via CSS
                                        transitions. You can modify any of this with
                                        custom CSS or overriding our default variables. It's also
                                        worth noting that just about any HTML can go
                                        within the <code>.accordion-body</code>, though the
                                        transition does limit overflow.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="withoutarrowheadingThree">
                                    <button aria-controls="withoutarrowcollapseThree" aria-expanded="false"
                                        class="accordion-button collapsed" data-bs-target="#withoutarrowcollapseThree"
                                        data-bs-toggle="collapse" type="button">
                                        Accordion Item #3
                                    </button>
                                </h2>
                                <div aria-labelledby="withoutarrowheadingThree" class="accordion-collapse collapse"
                                    data-bs-parent="#withoutarrowaccordionExample" id="withoutarrowcollapseThree">
                                    <div class="accordion-body">
                                        <strong>This is the third item's accordion body.</strong> It
                                        is hidden by default, until the collapse
                                        plugin adds the appropriate classes that we use to style
                                        each element. These classes control the overall
                                        appearance, as well as the showing and hiding via CSS
                                        transitions. You can modify any of this with
                                        custom CSS or overriding our default variables. It's also
                                        worth noting that just about any HTML can go
                                        within the <code>.accordion-body</code>, though the
                                        transition does limit overflow.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Bordered Accordions</h5>
                        <div class="accordion accordion-bordered" id="BorderedaccordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="BorderedheadingOne">
                                    <button aria-controls="BorderedcollapseOne" aria-expanded="true"
                                        class="accordion-button" data-bs-target="#BorderedcollapseOne"
                                        data-bs-toggle="collapse" type="button">
                                        Accordion Item #1
                                    </button>
                                </h2>
                                <div aria-labelledby="BorderedheadingOne" class="accordion-collapse collapse show"
                                    data-bs-parent="#BorderedaccordionExample" id="BorderedcollapseOne">
                                    <div class="accordion-body">
                                        <strong>This is the first item's accordion
                                            body.</strong> It is shown by default, until the
                                        collapse
                                        plugin adds the appropriate classes that we use to style
                                        each element. These classes control the overall
                                        appearance, as well as the showing and hiding via CSS
                                        transitions. You can modify any of this with
                                        custom CSS or overriding our default variables. It's
                                        also worth noting that just about any HTML can go
                                        within the <code>.accordion-body</code>, though the
                                        transition does limit overflow.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="BorderedheadingTwo">
                                    <button aria-controls="BorderedcollapseTwo" aria-expanded="false"
                                        class="accordion-button collapsed" data-bs-target="#BorderedcollapseTwo"
                                        data-bs-toggle="collapse" type="button">
                                        Accordion Item #2
                                    </button>
                                </h2>
                                <div aria-labelledby="BorderedheadingTwo" class="accordion-collapse collapse"
                                    data-bs-parent="#BorderedaccordionExample" id="BorderedcollapseTwo">
                                    <div class="accordion-body">
                                        <strong>This is the second item's accordion
                                            body.</strong> It is hidden by default, until the
                                        collapse
                                        plugin adds the appropriate classes that we use to style
                                        each element. These classes control the overall
                                        appearance, as well as the showing and hiding via CSS
                                        transitions. You can modify any of this with
                                        custom CSS or overriding our default variables. It's
                                        also worth noting that just about any HTML can go
                                        within the <code>.accordion-body</code>, though the
                                        transition does limit overflow.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="BorderedheadingThree">
                                    <button aria-controls="BorderedcollapseThree" aria-expanded="false"
                                        class="accordion-button collapsed" data-bs-target="#BorderedcollapseThree"
                                        data-bs-toggle="collapse" type="button">
                                        Accordion Item #3
                                    </button>
                                </h2>
                                <div aria-labelledby="BorderedheadingThree" class="accordion-collapse collapse"
                                    data-bs-parent="#BorderedaccordionExample" id="BorderedcollapseThree">
                                    <div class="accordion-body">
                                        <strong>This is the third item's accordion
                                            body.</strong> It is hidden by default, until the
                                        collapse
                                        plugin adds the appropriate classes that we use to style
                                        each element. These classes control the overall
                                        appearance, as well as the showing and hiding via CSS
                                        transitions. You can modify any of this with
                                        custom CSS or overriding our default variables. It's
                                        also worth noting that just about any HTML can go
                                        within the <code>.accordion-body</code>, though the
                                        transition does limit overflow.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Custom Icon Accordion</h5>
                        <div class="accordion accordion-custom-icon accordion-arrow-none"
                            id="CustomIconaccordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="CustomIconheadingOne">
                                    <button aria-controls="CustomIconcollapseOne" aria-expanded="true"
                                        class="accordion-button" data-bs-target="#CustomIconcollapseOne"
                                        data-bs-toggle="collapse" type="button">
                                        Accordion item with tabler icons
                                        <i class="ti ti-plus accordion-icon accordion-icon-on"></i>
                                        <i class="ti ti-minus accordion-icon accordion-icon-off"></i>
                                    </button>
                                </h2>
                                <div aria-labelledby="CustomIconheadingOne" class="accordion-collapse collapse show"
                                    data-bs-parent="#CustomIconaccordionExample" id="CustomIconcollapseOne">
                                    <div class="accordion-body">
                                        <strong>This is the first item's accordion
                                            body.</strong> It is shown by default, until the
                                        collapse
                                        plugin adds the appropriate classes that we use to style
                                        each element. These classes control the overall
                                        appearance, as well as the showing and hiding via CSS
                                        transitions. You can modify any of this with
                                        custom CSS or overriding our default variables. It's
                                        also worth noting that just about any HTML can go
                                        within the <code>.accordion-body</code>, though the
                                        transition does limit overflow.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="CustomIconheadingTwo">
                                    <button aria-controls="CustomIconcollapseTwo" aria-expanded="false"
                                        class="accordion-button collapsed" data-bs-target="#CustomIconcollapseTwo"
                                        data-bs-toggle="collapse" type="button">
                                        Accordion item with lucid icons
                                        <i class="accordion-icon accordion-icon-on avatar-xxs me-n1"
                                            data-lucide="plus-circle"></i>
                                        <i class="accordion-icon accordion-icon-off avatar-xxs me-n1"
                                            data-lucide="minus-circle"></i>
                                    </button>
                                </h2>
                                <div aria-labelledby="CustomIconheadingTwo" class="accordion-collapse collapse"
                                    data-bs-parent="#CustomIconaccordionExample" id="CustomIconcollapseTwo">
                                    <div class="accordion-body">
                                        <strong>This is the second item's accordion
                                            body.</strong> It is hidden by default, until the
                                        collapse
                                        plugin adds the appropriate classes that we use to style
                                        each element. These classes control the overall
                                        appearance, as well as the showing and hiding via CSS
                                        transitions. You can modify any of this with
                                        custom CSS or overriding our default variables. It's
                                        also worth noting that just about any HTML can go
                                        within the <code>.accordion-body</code>, though the
                                        transition does limit overflow.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="CustomIconheadingThree">
                                    <button aria-controls="CustomIconcollapseThree" aria-expanded="false"
                                        class="accordion-button collapsed" data-bs-target="#CustomIconcollapseThree"
                                        data-bs-toggle="collapse" type="button">
                                        Accordion item with tabler icons
                                        <i class="ti ti-arrow-down accordion-icon accordion-icon-on"></i>
                                        <i class="ti ti-arrow-up accordion-icon accordion-icon-off"></i>
                                    </button>
                                </h2>
                                <div aria-labelledby="CustomIconheadingThree" class="accordion-collapse collapse"
                                    data-bs-parent="#CustomIconaccordionExample" id="CustomIconcollapseThree">
                                    <div class="accordion-body">
                                        <strong>This is the third item's accordion
                                            body.</strong> It is hidden by default, until the
                                        collapse
                                        plugin adds the appropriate classes that we use to style
                                        each element. These classes control the overall
                                        appearance, as well as the showing and hiding via CSS
                                        transitions. You can modify any of this with
                                        custom CSS or overriding our default variables. It's
                                        also worth noting that just about any HTML can go
                                        within the <code>.accordion-body</code>, though the
                                        transition does limit overflow.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col-->
            </div>
        </div>
    </div>
</div><!-- end row -->
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Alerts Variations</h5>
                <div class="card-action">
                    <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Default Alert</h5>
                        <div class="alert alert-primary" role="alert">
                            This is a primary alert—something important you should know!
                        </div>
                        <div class="alert alert-secondary" role="alert">
                            This is a secondary alert—some additional context.
                        </div>
                        <div class="alert alert-success" role="alert">
                            Success! Your operation was completed successfully.
                        </div>
                        <div class="alert alert-danger" role="alert">
                            Error! Something went wrong—please try again.
                        </div>
                        <div class="alert alert-warning" role="alert">
                            Warning! Please double-check your inputs.
                        </div>
                        <div class="alert alert-info" role="alert">
                            Info: Here's something you might find useful.
                        </div>
                        <div class="alert alert-light" role="alert">
                            Light alert—just a subtle notification.
                        </div>
                        <div class="alert alert-dark mb-0" role="alert">
                            Dark alert—use for general-purpose messages.
                        </div>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Dismissing Alert with Solid Colors </h5>
                        <div class="alert alert-primary text-bg-primary alert-dismissible" role="alert">
                            <button aria-label="Close" class="btn-close btn-close-white" data-bs-dismiss="alert"
                                type="button"></button>
                            <div>Heads up! This is a primary alert with important information.</div>
                        </div>
                        <div class="alert alert-secondary text-bg-secondary alert-dismissible" role="alert">
                            <button aria-label="Close" class="btn-close btn-close-white" data-bs-dismiss="alert"
                                type="button"></button>
                            <div>Notice: This is a secondary alert with supporting details.</div>
                        </div>
                        <h5 class="mb-2 pb-1">Link Color</h5>
                        <div class="alert alert-primary" role="alert">
                            Need more info? Check out <a class="alert-link" href="#">this primary
                                link</a> for important details.
                        </div>
                        <div class="alert alert-secondary" role="alert">
                            Here's a secondary message with <a class="alert-link" href="#">a helpful
                                link</a> for additional context.
                        </div>
                        <h5 class="mb-2 pb-1">Additional Content </h5>
                        <div class="alert alert-secondary p-3 d-flex mb-0" role="alert">
                            <i class="ti ti-alarm-average fs-1 me-2"></i>
                            <div>
                                <h4 class="alert-heading">Heads up!</h4>
                                <p>This alert message gives additional information with a longer message to
                                    show content spacing within an alert.</p>
                                <hr class="border-secondary border-opacity-25" />
                                <p class="mb-0">Apply spacing classes wisely to maintain structure and
                                    clarity.</p>
                            </div>
                        </div>
                    </div> <!-- end col-->
                </div>
            </div>
        </div>
    </div>
</div><!-- end row -->
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Cards Variations</h5>
                <div class="card-action">
                    <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-sm-6 col-lg-3">
                        <!-- Simple card -->
                        <div class="card">
                            <div class="card-body">
                                <p class="card-text">Some quick example text to build on the card title and
                                    make
                                    up the bulk of the card's content. Some quick example text to build on
                                    the card
                                    title and make up.</p>
                                <a class="btn btn-sm btn-primary" href="javascript: void(0);">Button</a>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div><!-- end col -->
                    <div class="col-sm-6 col-lg-3">
                        <!-- Simple card -->
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-2">Basic Card with Title</h5>
                                <p class="card-text">Some quick example text to build on the card title and
                                    make
                                    up the bulk of the card's content. Some quick example text to build on
                                    the card
                                    title and make up.</p>
                                <a class="btn btn-sm btn-primary" href="javascript: void(0);">Button</a>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div><!-- end col -->
                    <div class="col-sm-6 col-lg-3">
                        <!-- Simple card -->
                        <div class="card text-bg-primary border-0">
                            <div class="card-body">
                                <h5 class="card-title mb-2">Card with Background Color</h5>
                                <p class="card-text">Some quick example text to build on the card title and
                                    make
                                    up the bulk of the card's content. Some quick example text to build on
                                    the card
                                    title and make up.</p>
                                <a class="btn btn-sm btn-light" href="javascript: void(0);">Button</a>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div><!-- end col -->
                    <div class="col-sm-6 col-lg-3">
                        <!-- Simple card -->
                        <div class="card text-bg-secondary bg-gradient border-0">
                            <div class="card-body">
                                <h5 class="card-title mb-2">Card with Background Color + Gradient</h5>
                                <p class="card-text">Some quick example text to build on the card title and
                                    make
                                    up the bulk of the card's content. Some quick example text to build on
                                    the card
                                    title and make up.</p>
                                <a class="btn btn-sm btn-light" href="javascript: void(0);">Button</a>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div><!-- end col -->
                </div> <!-- end row -->
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card">
                            <h5 class="card-header">Card with Header</h5>
                            <div class="card-body">
                                <h5 class="card-title mb-2">Special title treatment</h5>
                                <p class="card-text">With supporting text below as a natural lead-in to
                                    additional content.</p>
                                <a class="btn btn-sm btn-primary" href="javascript: void(0);">Go
                                    somewhere</a>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header d-block">
                                <h5 class="card-title mb-1">Card with Sub Header</h5>
                                <h6 class="card-subtitle text-body-secondary">Card subtitle</h6>
                            </div>
                            <div class="card-body">
                                <blockquote class="card-bodyquote mb-0">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer
                                        posuere
                                        erat a ante.</p>
                                    <footer class="mb-0">Someone famous in <cite title="Source Title">Source
                                            Title</cite>
                                    </footer>
                                </blockquote>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                Featured Card Title
                            </div>
                            <div class="card-body">
                                <a class="btn btn-sm btn-primary" href="javascript: void(0);">Go
                                    somewhere</a>
                            </div>
                            <div class="card-footer">
                                2 days ago
                            </div>
                        </div> <!-- end card-->
                    </div> <!-- end col-->
                </div> <!-- end row -->
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-2 pb-1">Advanced Card</h5>
                    </div> <!-- end col -->
                </div> <!-- end row -->
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Card with Action Tools</h5>
                                <div class="card-action">
                                    <a class="card-action-item" data-action="card-toggle" href="#!"><i
                                            class="ti ti-chevron-up"></i></a>
                                    <a class="card-action-item" data-action="card-refresh" href="#!"><i
                                            class="ti ti-refresh"></i></a>
                                    <a class="card-action-item" data-action="card-close" href="#!"><i
                                            class="ti ti-x"></i></a>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="card-text">With supporting text below as a natural lead-in to
                                    additional content.</p>
                                <a class="btn btn-sm btn-primary" href="javascript: void(0);">Go
                                    somewhere</a>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->
                    <div class="col-md-4">
                        <div class="card text-bg-primary border-0">
                            <div class="card-header">
                                <h5 class="card-title">Card with Action Tools &amp; Background Colors</h5>
                                <div class="card-action">
                                    <a class="card-action-item" data-action="card-toggle" href="#!"><i
                                            class="ti ti-chevron-up"></i></a>
                                    <a class="card-action-item" data-action="card-refresh" href="#!"><i
                                            class="ti ti-refresh"></i></a>
                                    <a class="card-action-item" data-action="card-close" href="#!"><i
                                            class="ti ti-x"></i></a>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="card-text">With supporting text below as a natural lead-in to
                                    additional content.</p>
                                <a class="btn btn-sm btn-light" href="javascript: void(0);">Go
                                    somewhere</a>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->
                    <div class="col-md-4">
                        <div class="card card-filled">
                            <div class="card-header">
                                <h5 class="card-title">Card with Action Tools</h5>
                                <div class="card-action">
                                    <a class="card-action-item" data-action="card-toggle" href="#!"><i
                                            class="ti ti-chevron-up"></i></a>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="card-text">With supporting text below as a natural lead-in to
                                    additional content.</p>
                                <a class="btn btn-sm btn-primary" href="javascript: void(0);">Go
                                    somewhere</a>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->
                </div>
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-2 pb-1">Bordered Card</h5>
                    </div> <!-- end col -->
                </div> <!-- end row -->
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body border-primary">
                                <h5 class="card-title mb-2">Card with Colored Border</h5>
                                <p class="card-text">With supporting text below as a natural lead-in to
                                    additional content.</p>
                                <a class="btn btn-primary btn-sm" href="javascript: void(0);">Button</a>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->
                    <div class="col-md-4">
                        <div class="card border-primary border border-dashed">
                            <div class="card-body">
                                <h5 class="card-title mb-2 text-primary">Card with Simple Border</h5>
                                <p class="card-text">With supporting text below as a natural lead-in to
                                    additional content.</p>
                                <a class="btn btn-primary btn-sm" href="javascript: void(0);">Button</a>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->
                    <div class="col-md-4">
                        <div class="card border-primary border-2">
                            <div class="card-body">
                                <h5 class="card-title mb-2 text-primary">Card with Double Border</h5>
                                <p class="card-text">With supporting text below as a natural lead-in to
                                    additional content.</p>
                                <a class="btn btn-primary btn-sm" href="javascript: void(0);">Button</a>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->
                </div> <!-- end row -->
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-2 pb-1">Stretched Link</h5>
                    </div> <!-- end col -->
                </div> <!-- end row -->
                <div class="row g-4">
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <img alt="..." class="card-img-top" src="/images/stock/small-3.jpg" />
                            <div class="card-body">
                                <h5 class="card-title mb-2">Card with stretched link</h5>
                                <a class="btn btn-primary mt-2 stretched-link" href="#">Go
                                    somewhere</a>
                            </div> <!-- end card-body -->
                        </div> <!-- end card -->
                    </div> <!-- end col-->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <img alt="..." class="card-img-top" src="/images/stock/small-4.jpg" />
                            <div class="card-body">
                                <h5 class="card-title mb-2"><a class="text-primary stretched-link" href="#">Card with
                                        stretched link</a></h5>
                                <p class="card-text">
                                    Some quick example text to build on the card up the bulk of the card's
                                    content.
                                </p>
                            </div> <!-- end card-body -->
                        </div> <!-- end card -->
                    </div> <!-- end col-->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <img alt="..." class="card-img-top" src="/images/stock/small-5.jpg" />
                            <div class="card-body">
                                <h5 class="card-title mb-2">Card with stretched link</h5>
                                <a class="btn btn-primary mt-2 stretched-link" href="#">Go
                                    somewhere</a>
                            </div> <!-- end card-body -->
                        </div> <!-- end card -->
                    </div> <!-- end col-->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <img alt="..." class="card-img-top" src="/images/stock/small-6.jpg" />
                            <div class="card-body">
                                <h5 class="card-title mb-2"><a class="text-primary stretched-link" href="#">Card with
                                        stretched link</a>
                                </h5>
                                <p class="card-text">
                                    Some quick example text to build on the card up the bulk of the card's
                                    content.
                                </p>
                            </div> <!-- end card-body -->
                        </div> <!-- end card -->
                    </div> <!-- end col-->
                </div> <!-- end row -->
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-2 pb-1">Card Group</h5>
                    </div> <!-- end col -->
                </div> <!-- end row -->
                <div class="card-group mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-2">Card title</h5>
                            <p class="card-text">This is a wider card with supporting text below as a
                                natural lead-in to additional content. This content is a little bit longer.
                            </p>
                        </div>
                        <div class="card-footer">
                            <span class="text-body-secondary">Last updated 3 mins ago</span>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-2">Card title</h5>
                            <p class="card-text">This card has supporting text below as a natural lead-in
                                to additional content.</p>
                        </div>
                        <div class="card-footer">
                            <span class="text-body-secondary">Last updated 3 mins ago</span>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-2">Card title</h5>
                            <p class="card-text">This is a wider card with supporting text below as a
                                natural lead-in to additional content. This card has even longer content
                                than the first to show that equal height action.</p>
                        </div>
                        <div class="card-footer">
                            <span class="text-body-secondary">Last updated 3 mins ago</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- end row -->
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Videos Variations</h5>
                <div class="card-action">
                    <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Responsive embed video 21:9 </h5>
                        <!-- 21:9 aspect ratio -->
                        <div class="ratio ratio-21x9">
                            <iframe allowfullscreen="" src="https://www.youtube.com/embed/TZe5UqlUg0c?rel=0"
                                title="7 Best AI Tools You NEED to Try in 2025"></iframe>
                        </div>
                        <div class="mt-4">
                            <h5 class="mb-2 pb-1">Responsive embed video 1:1 </h5>
                            <!-- 21:9 aspect ratio -->
                            <div class="ratio ratio-1x1">
                                <iframe allowfullscreen="" src="https://www.youtube.com/embed/TZe5UqlUg0c?rel=0"
                                    title="7 Best AI Tools You NEED to Try in 2025"></iframe>
                            </div>
                        </div> <!-- end col-->
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Responsive embed video 16:9 </h5>
                        <!-- 21:9 aspect ratio -->
                        <div class="ratio ratio-16x9">
                            <iframe allowfullscreen="" src="https://www.youtube.com/embed/TZe5UqlUg0c?rel=0"
                                title="7 Best AI Tools You NEED to Try in 2025"></iframe>
                        </div>
                        <div class="mt-4">
                            <h5 class="mb-2 pb-1">Responsive embed video 4:3</h5>
                            <!-- 21:9 aspect ratio -->
                            <div class="ratio ratio-4x3">
                                <iframe allowfullscreen="" src="https://www.youtube.com/embed/TZe5UqlUg0c?rel=0"
                                    title="7 Best AI Tools You NEED to Try in 2025"></iframe>
                            </div>
                        </div> <!-- end col-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- end row -->
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Typography Variations</h5>
                <div class="card-action">
                    <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-xl-4">
                        <h5 class="mb-2 pb-1">Display Headings</h5>
                        <h1 class="display-1">Display 1</h1>
                        <h1 class="display-2">Display 2</h1>
                        <h1 class="display-3">Display 3</h1>
                        <h1 class="display-4">Display 4</h1>
                        <h1 class="display-5">Display 5</h1>
                        <h1 class="display-6">Display 6</h1>
                    </div> <!-- end col-->
                    <div class="col-xl-4">
                        <div>
                            <h5 class="mb-2 pb-1">Headings</h5>
                            <h1>Heading 1 <small>Sub Heading</small></h1>
                            <h2>Heading 2 <small>Sub Heading</small></h2>
                            <h3>Heading 3 <small>Sub Heading</small></h3>
                            <h4>Heading 4 <small>Sub Heading</small></h4>
                            <h5>Heading 5 <small>Sub Heading</small></h5>
                            <h6>Heading 6 <small>Sub Heading</small></h6>
                        </div>
                        <div class="mt-4">
                            <h5 class="mb-2 pb-1">Naming a Source</h5>
                            <figure>
                                <blockquote class="blockquote">
                                    <p>"Design is not just what it looks like and feels like. Design is how
                                        it works."</p>
                                </blockquote>
                                <figcaption class="blockquote-footer">
                                    Steve Jobs in <cite title="Design Philosophy">Design Philosophy</cite>
                                </figcaption>
                            </figure>
                        </div>
                    </div> <!-- end col-->
                    <div class="col-xl-4">
                        <h5 class="mb-2 pb-1">Inline Text Elements</h5>
                        <p class="text-muted">Styling for common inline HTML5 elements.</p>
                        <p class="lead">Your title goes here</p>
                        <p>You can use the mark tag to <mark>highlight</mark> text.</p>
                        <p><del>This line of text is meant to be treated as deleted text.</del></p>
                        <p><s>This line of text is meant to be treated as no longer accurate.</s></p>
                        <p><ins>This line of text is meant to be treated as an addition to
                                thedocument.</ins></p>
                        <p><u>This line of text will render as underlined</u></p>
                        <p><small>This line of text is meant to be treated as fine print.</small></p>
                        <p><strong>This line rendered as bold text.</strong></p>
                        <p><em>This line rendered as italicized text.</em></p>
                        <p class="mb-0">Nulla <abbr title="attribute">attr</abbr> vitae elit libero, a
                            pharetra augue.</p>
                    </div> <!-- end col-->
                    <div class="col-xl-4">
                        <h5 class="mb-2 pb-1">Unordered </h5>
                        <ul>
                            <li>
                                Fully responsive design
                            </li>
                            <li>
                                Built with Bootstrap 5 framework
                            </li>
                            <li>
                                Clean and modern UI components
                            </li>
                            <li>
                                Cross-browser compatibility
                            </li>
                            <li>
                                Multiple form elements and validations
                                <ul>
                                    <li>
                                        Rich input controls
                                    </li>
                                    <li>
                                        Step-based form wizards
                                    </li>
                                    <li>
                                        Real-time validation
                                    </li>
                                    <li>
                                        Customizable styles
                                    </li>
                                </ul>
                            </li>
                            <li>
                                Advanced chart and graph libraries
                            </li>
                            <li>
                                Integrated data tables and sorting
                            </li>
                            <li>
                                Developer-friendly code structure
                            </li>
                        </ul>
                    </div>
                    <div class="col-xl-4">
                        <h5 class="mb-2 pb-1">Ordered</h5>
                        <ol>
                            <li>
                                Install all dependencies
                            </li>
                            <li>
                                Configure project environment settings
                            </li>
                            <li>
                                Set up folder structure and routing
                            </li>
                            <li>
                                Integrate UI components and layout
                            </li>
                            <li>
                                Implement core modules
                                <ol>
                                    <li>
                                        Authentication &amp; Authorization
                                    </li>
                                    <li>
                                        Dashboard widgets and metrics
                                    </li>
                                    <li>
                                        User profile management
                                    </li>
                                    <li>
                                        Notification &amp; messaging systems
                                    </li>
                                </ol>
                            </li>
                            <li>
                                Connect backend APIs and test data flow
                            </li>
                            <li>
                                Optimize performance and responsive design
                            </li>
                            <li>
                                Final testing and deployment
                            </li>
                        </ol>
                    </div> <!-- end col-->
                    <div class="col-xl-4">
                        <h5 class="mb-2 pb-1">Unstyled</h5>
                        <ul class="list-unstyled">
                            <li>
                                Install project dependencies
                            </li>
                            <li>
                                Configure build settings
                                <ul>
                                    <li>
                                        Update environment variables
                                    </li>
                                </ul>
                            </li>
                            <li>
                                Setup project structure and routes
                            </li>
                            <li>
                                Launch local development server
                            </li>
                        </ul>
                    </div> <!-- end col-->
                    <div class="col-xl-4">
                        <h5 class="mb-2 pb-1">Abbreviations </h5>
                        <p><abbr title="attribute">attr</abbr></p>
                        <p><abbr class="initialism" title="HyperText Markup Language">HTML</abbr></p>
                    </div>
                    <div class="col-xl-4">
                        <h5 class="mb-2 pb-1">Alignment</h5>
                        <figure class="text-center">
                            <blockquote class="blockquote">
                                <p>"Design is not just what it looks like and feels like. Design is how it
                                    works."</p>
                            </blockquote>
                            <figcaption class="blockquote-footer">
                                Steve Jobs in <cite title="Steve Jobs Biography">Steve Jobs
                                    Biography</cite>
                            </figcaption>
                        </figure>
                        <figure class="text-end">
                            <blockquote class="blockquote">
                                <p>"Simplicity is the ultimate sophistication."</p>
                            </blockquote>
                            <figcaption class="blockquote-footer">
                                Leonardo da Vinci in <cite title="Design Philosophy">Design
                                    Philosophy</cite>
                            </figcaption>
                        </figure>
                    </div> <!-- end col-->
                    <div class="col-xl-4">
                        <h5 class="mb-2 pb-1">Inline</h5>
                        <ul class="list-inline">
                            <li class="list-inline-item">This is a list item.</li>
                            <li class="list-inline-item">And another one.</li>
                            <li class="list-inline-item">But they're displayed inline.</li>
                        </ul>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Blockquotes </h5>
                        <blockquote class="blockquote">
                            <p class="mb-0">"Good design is obvious. Great design is transparent."</p>
                        </blockquote>
                        <figcaption class="blockquote-footer">
                            Joe Sparano in <cite title="Design Principles">Design Principles</cite>
                        </figcaption>
                        <p class="text-muted m-b-15">
                            Use text utilities as needed to change the alignment of your blockquote.
                        </p>
                        <blockquote class="blockquote text-center">
                            <p class="mb-0">"First, solve the problem. Then, write the code."</p>
                        </blockquote>
                        <figcaption class="blockquote-footer text-center">
                            John Johnson in <cite title="Developer Wisdom">Developer Wisdom</cite>
                        </figcaption>
                        <blockquote class="blockquote text-end">
                            <p class="mb-0">"Simplicity is the soul of efficiency."</p>
                        </blockquote>
                        <figcaption class="blockquote-footer text-end">
                            Austin Freeman in <cite title="Efficiency in Design">Efficiency in
                                Design</cite>
                        </figcaption>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Description List Alignment</h5>
                        <dl class="row mb-0">
                            <dt class="col-sm-3">Dashboard</dt>
                            <dd class="col-sm-9">An overview panel that displays key metrics and activity
                                summaries.</dd>
                            <dt class="col-sm-3">Form Validation</dt>
                            <dd class="col-sm-9">
                                <p>Includes validation for all major input types with real-time feedback.
                                </p>
                                <p>Built-in support for custom rules and error messages.</p>
                            </dd>
                            <dt class="col-sm-3">Responsive Grid</dt>
                            <dd class="col-sm-9">Utilizes Bootstrap’s flexible grid layout for consistent
                                responsiveness across devices.</dd>
                            <dt class="col-sm-3 text-truncate">User Management Module</dt>
                            <dd class="col-sm-9">Easily manage roles, permissions, and user profiles from
                                a single interface.</dd>
                            <dt class="col-sm-3">Nested Features</dt>
                            <dd class="col-sm-9">
                                <dl class="row mb-0">
                                    <dt class="col-sm-4">Email Notifications</dt>
                                    <dd class="col-sm-8">Customizable alerts and triggers integrated into
                                        app workflows.</dd>
                                </dl>
                            </dd>
                        </dl>
                    </div> <!-- end col-->
                </div>
            </div>
        </div>
    </div>
</div><!-- end row -->
@endsection