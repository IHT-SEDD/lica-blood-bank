@extends('layouts.vertical', ['title' => 'Menus & Navigation Links'])

@section('content')
@include('layouts.shared.page-title', [
'title' => 'Menus &amp; Navigation Links',
'subTitle' =>
'Design intuitive navigation with menus, dropdowns, navbars, and link variations to guide user flow effectively.',
'badgeIcon' => 'navigation',
'badgeTitle' => 'Site Structure',
])

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Breadcrumb Variations</h5>
                <div class="card-action">
                    <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <!-- Basic Breadcrumbs -->
                    <div class="col-xl-6">
                        <h5 class="pb-1 mb-2">Basic</h5>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-2 py-2">
                                <li aria-current="page" class="breadcrumb-item active">Home</li>
                            </ol>
                        </nav>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-2 py-2">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li aria-current="page" class="breadcrumb-item active">Library</li>
                            </ol>
                        </nav>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 py-2">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item"><a href="#">Library</a></li>
                                <li aria-current="page" class="breadcrumb-item active">Data</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- Breadcrumbs with Icons -->
                    <div class="col-xl-6">
                        <h5 class="pb-1 mb-2">With Icons</h5>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-light bg-opacity-50 p-2 mb-2">
                                <li aria-current="page" class="breadcrumb-item active"><i
                                        class="ti ti-smart-home me-1"></i>Home</li>
                            </ol>
                        </nav>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-light bg-opacity-50 p-2 mb-2">
                                <li class="breadcrumb-item"><a href="#"><i class="ti ti-smart-home"></i>
                                        Home</a></li>
                                <li aria-current="page" class="breadcrumb-item active">Library</li>
                            </ol>
                        </nav>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-light bg-opacity-50 p-2 mb-0">
                                <li class="breadcrumb-item"><a href="#"><i class="ti ti-smart-home"></i>
                                        Home</a></li>
                                <li class="breadcrumb-item"><a href="#">Library</a></li>
                                <li aria-current="page" class="breadcrumb-item active">Data</li>
                            </ol>
                        </nav>
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
                <h5 class="card-title mb-0">Dropdowns Variations</h5>
                <div class="card-action">
                    <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Single Button Dropdowns </h5>
                        <div class="row">
                            <div class="col-auto">
                                <!-- Choose Option -->
                                <div class="dropdown">
                                    <button aria-expanded="false" aria-haspopup="true"
                                        class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"
                                        id="dropdownMenuButton" type="button">
                                        Choose Option
                                    </button>
                                    <div aria-labelledby="dropdownMenuButton" class="dropdown-menu">
                                        <a class="dropdown-item" href="#">Profile Settings</a>
                                        <a class="dropdown-item" href="#">Notifications</a>
                                        <a class="dropdown-item" href="#">Logout</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <!-- Quick Actions -->
                                <div class="dropdown">
                                    <a aria-expanded="false" aria-haspopup="true"
                                        class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" href="#"
                                        id="dropdownMenuLink" role="button">
                                        Quick Actions
                                    </a>
                                    <div aria-labelledby="dropdownMenuLink" class="dropdown-menu">
                                        <a class="dropdown-item" href="#">Create New</a>
                                        <a class="dropdown-item" href="#">Upload File</a>
                                        <a class="dropdown-item" href="#">View Reports</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Menu Alignment </h5>
                        <div class="dropdown">
                            <button aria-expanded="false" aria-haspopup="true" class="btn btn-light dropdown-toggle"
                                data-bs-toggle="dropdown" type="button">
                                Right-aligned menu
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Custom Dropdown Arrow </h5>
                        <div class="row">
                            <div class="col-auto">
                                <!-- Without Arrow -->
                                <div class="dropdown">
                                    <button aria-expanded="false" aria-haspopup="true"
                                        class="btn btn-primary dropdown-toggle drop-arrow-none"
                                        data-bs-toggle="dropdown" id="dropdownMenuButton1" type="button">
                                        Without Arrow
                                    </button>
                                    <div aria-labelledby="dropdownMenuButton1" class="dropdown-menu">
                                        <a class="dropdown-item" href="#">Download Report</a>
                                        <a class="dropdown-item" href="#">View Analytics</a>
                                        <a class="dropdown-item" href="#">Export Data</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <!-- Tabler Icon  -->
                                <div class="dropdown">
                                    <button aria-expanded="false" aria-haspopup="true"
                                        class="btn btn-outline-primary dropdown-toggle drop-arrow-none"
                                        data-bs-toggle="dropdown" id="dropdownMenuButton2" type="button">
                                        Tabler Icon <i class="ti ti-chevron-down align-middle ms-1"></i>
                                    </button>
                                    <div aria-labelledby="dropdownMenuButton2" class="dropdown-menu">
                                        <a class="dropdown-item" href="#">Edit Profile</a>
                                        <a class="dropdown-item" href="#">Account Settings</a>
                                        <a class="dropdown-item" href="#">Sign Out</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <!-- Lucide Icon  -->
                                <div class="dropdown">
                                    <button aria-expanded="false" aria-haspopup="true"
                                        class="btn btn-primary dropdown-toggle drop-arrow-none"
                                        data-bs-toggle="dropdown" id="dropdownMenuButton3" type="button">
                                        Lucide Icon <i class="avatar-xxs ms-2" data-lucide="square-chevron-down"></i>
                                    </button>
                                    <div aria-labelledby="dropdownMenuButton3" class="dropdown-menu">
                                        <a class="dropdown-item" href="#">New Project</a>
                                        <a class="dropdown-item" href="#">Manage Team</a>
                                        <a class="dropdown-item" href="#">Billing Info</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Split Button Dropdowns </h5>
                        <div class="d-flex flex-wrap gap-2">
                            <div class="btn-group">
                                <!-- Primary -->
                                <button class="btn btn-primary" type="button">Primary</button>
                                <button aria-expanded="false" aria-haspopup="true"
                                    class="btn btn-primary dropdown-toggle dropdown-toggle-split drop-arrow-none"
                                    data-bs-toggle="dropdown" type="button">
                                    <i class="ti ti-chevron-down align-middle"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Separated link</a>
                                </div>
                            </div><!-- /btn-group -->
                            <div class="btn-group">
                                <button class="btn btn-soft-primary" type="button">Soft Btn</button>
                                <button aria-expanded="false" aria-haspopup="true"
                                    class="btn btn-soft-primary dropdown-toggle dropdown-toggle-split drop-arrow-none"
                                    data-bs-toggle="dropdown" type="button">
                                    <i class="ti ti-chevron-down align-middle"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Separated link</a>
                                </div>
                            </div><!-- /btn-group -->
                        </div>
                    </div> <!-- end col-->
                </div>
                <div class="row mt-4">
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Dropup Variation </h5>
                        <div class="d-flex flex-wrap gap-2">
                            <!-- Default dropup button -->
                            <div class="btn-group dropup">
                                <button aria-expanded="false" aria-haspopup="true" class="btn btn-light dropdown-toggle"
                                    data-bs-toggle="dropdown" type="button">Dropup</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Upload File</a>
                                    <a class="dropdown-item" href="#">Sync Data</a>
                                    <a class="dropdown-item" href="#">Import from CSV</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Advanced Settings</a>
                                </div>
                            </div>
                            <!-- Split dropup button -->
                            <div class="btn-group dropup">
                                <button class="btn btn-light" type="button">
                                    Split dropup
                                </button>
                                <button aria-expanded="false" aria-haspopup="true"
                                    class="btn btn-light dropdown-toggle dropdown-toggle-split"
                                    data-bs-toggle="dropdown" type="button">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">New Task</a>
                                    <a class="dropdown-item" href="#">Assign User</a>
                                    <a class="dropdown-item" href="#">Set Deadline</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Project Settings</a>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Dropstart Variation </h5>
                        <div class="d-flex flex-wrap gap-2">
                            <!-- Default dropstart button -->
                            <div class="btn-group dropstart">
                                <button aria-expanded="false" aria-haspopup="true"
                                    class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" type="button">
                                    Dropstart
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Separated link</a>
                                </div>
                            </div>
                            <!-- Split dropstart button -->
                            <div class="btn-group">
                                <div class="btn-group dropstart" role="group">
                                    <button aria-expanded="false" aria-haspopup="true"
                                        class="btn btn-secondary dropdown-toggle dropdown-split dropdown-toggle-split"
                                        data-bs-toggle="dropdown" type="button">
                                        <span class="visually-hidden">Toggle Dropstart</span>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">Separated link</a>
                                    </div>
                                </div>
                                <button class="btn btn-secondary" type="button">
                                    Split dropstart
                                </button>
                            </div>
                        </div>
                    </div> <!-- end col-->
                </div>
                <div class="row mt-4">
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Dropend Variation </h5>
                        <div class="d-flex flex-wrap gap-2">
                            <!-- Default dropend button -->
                            <div class="btn-group dropend">
                                <button aria-expanded="false" aria-haspopup="true"
                                    class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" type="button">
                                    Dropend
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">View Profile</a>
                                    <a class="dropdown-item" href="#">Message User</a>
                                    <a class="dropdown-item" href="#">Report Issue</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Block User</a>
                                </div>
                            </div>
                            <!-- Split dropend button -->
                            <div class="btn-group dropend">
                                <button class="btn btn-primary" type="button">
                                    Split Dropend
                                </button>
                                <button aria-expanded="false" aria-haspopup="true"
                                    class="btn btn-primary dropdown-toggle-split dropdown-toggle"
                                    data-bs-toggle="dropdown" type="button">
                                    <span class="visually-hidden">Toggle Dropright</span>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">New Invoice</a>
                                    <a class="dropdown-item" href="#">Send Reminder</a>
                                    <a class="dropdown-item" href="#">Duplicate</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Delete Invoice</a>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Active Item </h5>
                        <div class="btn-group">
                            <button aria-expanded="false" aria-haspopup="true" class="btn btn-secondary dropdown-toggle"
                                data-bs-toggle="dropdown" type="button">
                                Active Item
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Regular link</a>
                                <a class="dropdown-item active" href="#">Active link</a>
                                <a class="dropdown-item" href="#">Another link</a>
                            </div>
                        </div>
                    </div> <!-- end col-->
                </div>
                <div class="row mt-4">
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Disabled Item </h5>
                        <div class="btn-group">
                            <button aria-expanded="false" aria-haspopup="true" class="btn btn-primary dropdown-toggle"
                                data-bs-toggle="dropdown" type="button">
                                Disabled
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Regular link</a>
                                <a class="dropdown-item disabled" href="#" tabindex="-1">Disabled link</a>
                                <a class="dropdown-item" href="#">Another link</a>
                            </div>
                        </div>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Headers </h5>
                        <div class="btn-group">
                            <button aria-expanded="false" aria-haspopup="true" class="btn btn-secondary dropdown-toggle"
                                data-bs-toggle="dropdown" type="button">
                                Header
                            </button>
                            <div class="dropdown-menu">
                                <h6 class="dropdown-header">Dropdown header</h6>
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                            </div>
                        </div>
                    </div> <!-- end col-->
                </div>
                <div class="row mt-4">
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Dark Dropdowns </h5>
                        <div class="dropdown">
                            <button aria-expanded="false" class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown"
                                type="button">
                                Dark Dropdown
                            </button>
                            <ul class="dropdown-menu" data-bs-theme="dark">
                                <li><a class="dropdown-item active" href="#">Dashboard</a></li>
                                <li><a class="dropdown-item" href="#">My Orders</a></li>
                                <li><a class="dropdown-item" href="#">Billing Settings</a></li>
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                                <li><a class="dropdown-item" href="#">Logout</a></li>
                            </ul>
                        </div>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Centered Dropdowns </h5>
                        <div class="hstack gap-2">
                            <div class="dropdown-center">
                                <button aria-expanded="false" class="btn btn-secondary dropdown-toggle"
                                    data-bs-toggle="dropdown" type="button">
                                    Centered dropdown
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Action two</a></li>
                                    <li><a class="dropdown-item" href="#">Action three</a></li>
                                </ul>
                            </div>
                            <div class="dropup-center dropup">
                                <button aria-expanded="false" class="btn btn-secondary dropdown-toggle"
                                    data-bs-toggle="dropdown" type="button">
                                    Centered dropup
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Action two</a></li>
                                    <li><a class="dropdown-item" href="#">Action three</a></li>
                                </ul>
                            </div>
                        </div>
                    </div> <!-- end col-->
                </div>
                <div class="row mt-4">
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Dropdown Options</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <div class="dropdown">
                                <button aria-expanded="false" class="btn btn-secondary dropdown-toggle"
                                    data-bs-offset="10,20" data-bs-toggle="dropdown" type="button">
                                    Offset
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Profile Settings</a></li>
                                    <li><a class="dropdown-item" href="#">Privacy Settings</a></li>
                                    <li><a class="dropdown-item" href="#">Notification Preferences</a></li>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-secondary" type="button">Reference</button>
                                <button aria-expanded="false"
                                    class="btn btn-secondary dropdown-toggle dropdown-toggle-split"
                                    data-bs-reference="parent" data-bs-toggle="dropdown" type="button">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Manage Subscription</a></li>
                                    <li><a class="dropdown-item" href="#">Account Preferences</a></li>
                                    <li><a class="dropdown-item" href="#">Help &amp; Support</a></li>
                                    <li>
                                        <hr class="dropdown-divider" />
                                    </li>
                                    <li><a class="dropdown-item" href="#">Log Out</a></li>
                                </ul>
                            </div>
                        </div>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Auto Close Behavior</h5>
                        <div class="hstack gap-2">
                            <div class="btn-group">
                                <button aria-expanded="false" class="btn btn-secondary dropdown-toggle"
                                    data-bs-auto-close="true" data-bs-toggle="dropdown" type="button">
                                    Default dropdown
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Menu item</a></li>
                                    <li><a class="dropdown-item" href="#">Menu item</a></li>
                                    <li><a class="dropdown-item" href="#">Menu item</a></li>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <button aria-expanded="false" class="btn btn-secondary dropdown-toggle"
                                    data-bs-auto-close="inside" data-bs-toggle="dropdown" type="button">
                                    Clickable inside
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Menu item</a></li>
                                    <li><a class="dropdown-item" href="#">Menu item</a></li>
                                    <li><a class="dropdown-item" href="#">Menu item</a></li>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <button aria-expanded="false" class="btn btn-secondary dropdown-toggle"
                                    data-bs-auto-close="outside" data-bs-toggle="dropdown" type="button">
                                    Clickable outside
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Menu item</a></li>
                                    <li><a class="dropdown-item" href="#">Menu item</a></li>
                                    <li><a class="dropdown-item" href="#">Menu item</a></li>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <button aria-expanded="false" class="btn btn-secondary dropdown-toggle"
                                    data-bs-auto-close="false" data-bs-toggle="dropdown" type="button">
                                    Manual close
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Menu item</a></li>
                                    <li><a class="dropdown-item" href="#">Menu item</a></li>
                                    <li><a class="dropdown-item" href="#">Menu item</a></li>
                                </ul>
                            </div>
                        </div>
                    </div> <!-- end col-->
                </div>
                <div class="row mt-4">
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Text </h5>
                        <div class="btn-group">
                            <button aria-expanded="false" aria-haspopup="true" class="btn btn-primary dropdown-toggle"
                                data-bs-toggle="dropdown" type="button">
                                Text Dropdown
                            </button>
                            <div class="dropdown-menu p-3 text-muted" style="max-width: 200px;">
                                <p>
                                    Some example text that's free-flowing within the dropdown menu.
                                </p>
                                <p class="mb-0">
                                    And this is more example text.
                                </p>
                            </div>
                        </div>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Forms </h5>
                        <div class="dropdown">
                            <button aria-expanded="false" aria-haspopup="true" class="btn btn-secondary dropdown-toggle"
                                data-bs-toggle="dropdown" type="button">
                                Form
                            </button>
                            <div class="dropdown-menu">
                                <form class="px-4 py-3">
                                    <div class="mb-3">
                                        <label class="form-label" for="exampleDropdownFormEmail1">Email
                                            address</label>
                                        <input class="form-control" id="exampleDropdownFormEmail1"
                                            placeholder="email@example.com" type="email" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="exampleDropdownFormPassword1">Password</label>
                                        <input class="form-control" id="exampleDropdownFormPassword1"
                                            placeholder="Password" type="password" />
                                    </div>
                                    <div class="mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" id="dropdownCheck" type="checkbox" />
                                            <label class="form-check-label" for="dropdownCheck">
                                                Remember me
                                            </label>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary" type="submit">Sign in</button>
                                </form>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">New around here? Sign up</a>
                                <a class="dropdown-item" href="#">Forgot password?</a>
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
                <h5 class="card-title mb-0">Links Variations</h5>
                <div class="card-action">
                    <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Colored Links </h5>
                        <p><a class="link-primary" href="#">Primary link</a></p>
                        <p><a class="link-secondary" href="#">Secondary link</a></p>
                        <p><a class="link-success" href="#">Success link</a></p>
                        <p><a class="link-danger" href="#">Danger link</a></p>
                        <p><a class="link-warning" href="#">Warning link</a></p>
                        <p><a class="link-info" href="#">Info link</a></p>
                        <p><a class="link-light" href="#">Light link</a></p>
                        <p><a class="link-dark" href="#">Dark link</a></p>
                        <p class="mb-0"><a class="link-body-emphasis" href="#">Emphasis link</a></p>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Link Utilities</h5>
                        <p><a class="link-primary text-decoration-underline link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
                                href="#">Primary link</a></p>
                        <p><a class="link-secondary text-decoration-underline link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
                                href="#">Secondary link</a></p>
                        <p><a class="link-success text-decoration-underline link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
                                href="#">Success link</a></p>
                        <p><a class="link-danger text-decoration-underline link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
                                href="#">Danger link</a></p>
                        <p><a class="link-warning text-decoration-underline link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
                                href="#">Warning link</a></p>
                        <p><a class="link-info text-decoration-underline link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
                                href="#">Info link</a></p>
                        <p><a class="link-light text-decoration-underline link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
                                href="#">Light link</a></p>
                        <p><a class="link-dark text-decoration-underline link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
                                href="#">Dark link</a></p>
                        <p><a class="link-body-emphasis text-decoration-underline link-offset-2 link-underline-opacity-25 link-underline-opacity-75-hover"
                                href="#">Emphasis link</a></p>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Link Opacity </h5>
                        <p><a class="link-opacity-10" href="#">Link opacity 10</a></p>
                        <p><a class="link-opacity-25" href="#">Link opacity 25</a></p>
                        <p><a class="link-opacity-50" href="#">Link opacity 50</a></p>
                        <p><a class="link-opacity-75" href="#">Link opacity 75</a></p>
                        <p class="mb-0"><a class="link-opacity-100" href="#">Link opacity 100</a></p>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Link Hover Opacity</h5>
                        <p><a class="link-opacity-10-hover" href="#">Link hover opacity 10</a></p>
                        <p><a class="link-opacity-25-hover" href="#">Link hover opacity 25</a></p>
                        <p><a class="link-opacity-50-hover" href="#">Link hover opacity 50</a></p>
                        <p><a class="link-opacity-75-hover" href="#">Link hover opacity 75</a></p>
                        <p class="mb-0"><a class="link-opacity-100-hover" href="#">Link hover opacity 100</a>
                        </p>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Underline Color </h5>
                        <p><a class="text-decoration-underline link-underline-primary" href="#">Primary
                                underline</a></p>
                        <p><a class="text-decoration-underline link-underline-secondary" href="#">Secondary
                                underline</a></p>
                        <p><a class="text-decoration-underline link-underline-success" href="#">Success
                                underline</a></p>
                        <p><a class="text-decoration-underline link-underline-danger" href="#">Danger
                                underline</a></p>
                        <p><a class="text-decoration-underline link-underline-warning" href="#">Warning
                                underline</a></p>
                        <p><a class="text-decoration-underline link-underline-info" href="#">Info underline</a>
                        </p>
                        <p><a class="text-decoration-underline link-underline-light" href="#">Light
                                underline</a></p>
                        <p class="mb-0"><a class="text-decoration-underline link-underline-dark" href="#">Dark
                                underline</a></p>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Underline Opacity</h5>
                        <p><a class="text-decoration-underline link-offset-2 link-underline link-underline-opacity-0"
                                href="#">Underline opacity 0</a></p>
                        <p><a class="text-decoration-underline link-offset-2 link-underline link-underline-opacity-10"
                                href="#">Underline opacity 10</a></p>
                        <p><a class="text-decoration-underline link-offset-2 link-underline link-underline-opacity-25"
                                href="#">Underline opacity 25</a></p>
                        <p><a class="text-decoration-underline link-offset-2 link-underline link-underline-opacity-50"
                                href="#">Underline opacity 50</a></p>
                        <p><a class="text-decoration-underline link-offset-2 link-underline link-underline-opacity-75"
                                href="#">Underline opacity 75</a></p>
                        <p class="mb-0"><a
                                class="text-decoration-underline link-offset-2 link-underline link-underline-opacity-100"
                                href="#">Underline opacity 100</a></p>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Underline Offset</h5>
                        <p><a href="#">Default link</a></p>
                        <p><a class="text-decoration-underline link-offset-1" href="#">Offset 1 link</a></p>
                        <p><a class="text-decoration-underline link-offset-2" href="#">Offset 2 link</a></p>
                        <p class="mb-0"><a class="text-decoration-underline link-offset-3" href="#">Offset 3
                                link</a></p>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Hover Variants</h5>
                        <a class="link-offset-2 link-offset-3-hover text-decoration-underline link-underline link-underline-opacity-0 link-underline-opacity-75-hover"
                            href="#">
                            Underline opacity 0
                        </a>
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
                <h5 class="card-title mb-0">List Group Variations</h5>
                <div class="card-action">
                    <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Basic Example </h5>
                        <ul class="list-group">
                            <li class="list-group-item"><i class="ti ti-cloud me-1 align-middle fs-xl"></i> Dropbox
                                Cloud Storage</li>
                            <li class="list-group-item"><i class="ti ti-brand-slack me-1 align-middle fs-xl"></i>
                                Slack Team Collaboration</li>
                            <li class="list-group-item"><i class="ti ti-brand-windows me-1 align-middle fs-xl"></i>
                                Microsoft Windows OS</li>
                            <li class="list-group-item"><i
                                    class="ti ti-device-desktop-analytics me-1 align-middle fs-xl"></i> Zendesk
                                Customer Support</li>
                            <li class="list-group-item"><i class="ti ti-brand-stripe me-1 align-middle fs-xl"></i>
                                Stripe Payment Integration</li>
                        </ul>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Active Items</h5>
                        <ul class="list-group">
                            <li class="list-group-item active"><i
                                    class="ti ti-brand-github me-1 align-middle fs-xl"></i> GitHub Repository</li>
                            <li class="list-group-item"><i class="ti ti-brand-figma me-1 align-middle fs-xl"></i>
                                Figma Design Tool</li>
                            <li class="list-group-item"><i class="ti ti-brand-notion me-1 align-middle fs-xl"></i>
                                Notion Workspace</li>
                            <li class="list-group-item"><i class="ti ti-brand-trello me-1 align-middle fs-xl"></i>
                                Trello Task Manager</li>
                            <li class="list-group-item"><i class="ti ti-cloud me-1 align-middle fs-xl"></i>
                                DigitalOcean Cloud</li>
                        </ul>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Disabled Items</h5>
                        <ul class="list-group">
                            <li aria-disabled="true" class="list-group-item disabled"><i
                                    class="ti ti-cloud me-1 align-middle fs-xl"></i> Dropbox Cloud Storage</li>
                            <li class="list-group-item"><i class="ti ti-brand-slack me-1 align-middle fs-xl"></i>
                                Slack Team Collaboration</li>
                            <li class="list-group-item"><i class="ti ti-brand-windows me-1 align-middle fs-xl"></i>
                                Microsoft Windows OS</li>
                            <li class="list-group-item"><i
                                    class="ti ti-device-desktop-analytics me-1 align-middle fs-xl"></i> Zendesk
                                Customer Support</li>
                            <li class="list-group-item"><i class="ti ti-brand-stripe me-1 align-middle fs-xl"></i>
                                Stripe Payment Integration</li>
                        </ul>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Links and Buttons</h5>
                        <div class="list-group">
                            <a class="list-group-item list-group-item-action active" href="#">
                                Stripe Payment Integration
                            </a>
                            <a class="list-group-item list-group-item-action" href="#">Dropbox Cloud Service</a>
                            <button class="list-group-item list-group-item-action" type="button">Slack
                                Communication</button>
                            <button class="list-group-item list-group-item-action" type="button">Notion Productivity
                                App</button>
                            <a class="list-group-item list-group-item-action disabled" href="#" tabindex="-1">Zendesk
                                Support Tool</a>
                        </div>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Flush </h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Slack Collaboration Tool</li>
                            <li class="list-group-item">Dropbox Cloud Storage</li>
                            <li class="list-group-item">Notion Workspace Organizer</li>
                            <li class="list-group-item">Zendesk Customer Support</li>
                            <li class="list-group-item">Stripe Payment Processor</li>
                        </ul>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Horizontal</h5>
                        <ul class="list-group list-group-horizontal mb-3">
                            <li class="list-group-item">Slack</li>
                            <li class="list-group-item">Notion</li>
                            <li class="list-group-item">Dropbox</li>
                        </ul>
                        <ul class="list-group list-group-horizontal-sm mb-3">
                            <li class="list-group-item">Figma</li>
                            <li class="list-group-item">Stripe</li>
                            <li class="list-group-item">Zendesk</li>
                        </ul>
                        <ul class="list-group list-group-horizontal-md">
                            <li class="list-group-item">Trello</li>
                            <li class="list-group-item">Asana</li>
                            <li class="list-group-item">ClickUp</li>
                        </ul>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Contextual Classes</h5>
                        <ul class="list-group">
                            <li class="list-group-item">Dapibus ac facilisis in</li>
                            <li class="list-group-item list-group-item-primary">A simple primary
                                list group item</li>
                            <li class="list-group-item list-group-item-secondary">A simple secondary
                                list group item</li>
                            <li class="list-group-item list-group-item-success">A simple success
                                list group item</li>
                            <li class="list-group-item list-group-item-danger">A simple danger list
                                group item</li>
                            <li class="list-group-item list-group-item-warning">A simple warning
                                list group item</li>
                            <li class="list-group-item list-group-item-info">A simple info list
                                group item</li>
                            <li class="list-group-item list-group-item-light">A simple light list
                                group item</li>
                            <li class="list-group-item list-group-item-dark">A simple dark list
                                group item</li>
                        </ul>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Contextual Classes with Link </h5>
                        <div class="list-group">
                            <a class="list-group-item list-group-item-action" href="#">Darius ac facilities
                                in</a>
                            <a class="list-group-item list-group-item-action list-group-item-primary" href="#">A
                                simple primary list group item</a>
                            <a class="list-group-item list-group-item-action list-group-item-secondary" href="#">A
                                simple secondary list group item</a>
                            <a class="list-group-item list-group-item-action list-group-item-success" href="#">A
                                simple success list group item</a>
                            <a class="list-group-item list-group-item-action list-group-item-danger" href="#">A
                                simple danger list group item</a>
                            <a class="list-group-item list-group-item-action list-group-item-warning" href="#">A
                                simple warning list group item</a>
                            <a class="list-group-item list-group-item-action list-group-item-info" href="#">A
                                simple info list group item</a>
                            <a class="list-group-item list-group-item-action list-group-item-light" href="#">A
                                simple light list group item</a>
                            <a class="list-group-item list-group-item-action list-group-item-dark" href="#">A
                                simple dark list group item</a>
                        </div>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Custom Content</h5>
                        <div class="list-group">
                            <a class="list-group-item list-group-item-action active" href="#">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-2">List group item heading</h5>
                                    <small>3 days ago</small>
                                </div>
                                <p class="mb-1">Donec id elit non mi porta gravida at eget metuss
                                    Maecenas sed diam eget risus varius blandit.</p>
                                <small>Donec id elit non mi porta.</small>
                            </a>
                            <a class="list-group-item list-group-item-action" href="#">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-2">List group item heading</h5>
                                    <small class="text-muted">3 days ago</small>
                                </div>
                                <p class="mb-1">Donec id elit non mi porta gravida at eget metus.
                                    Maecenas sed diam eget risus varius blandit.</p>
                                <small class="text-muted">Donec id elit non mi porta.</small>
                            </a>
                            <a class="list-group-item list-group-item-action" href="#">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-2">List group item heading</h5>
                                    <small class="text-muted">3 days ago</small>
                                </div>
                                <p class="mb-1">Donec id elit non mi porta gravida at eget metus.
                                    Maecenas sed diam eget risus varius blandit.</p>
                                <small class="text-muted">Donec id elit non mi porta.</small>
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">With Badges </h5>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Gmail Notifications
                                <span class="badge bg-primary rounded-pill">14</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Unprocessed Orders
                                <span class="badge bg-success rounded-pill">2</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Urgent Tickets
                                <span class="badge bg-danger rounded-pill">99+</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Completed Transactions
                                <span class="badge bg-success rounded-pill">20+</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Invoices Awaiting Approval
                                <span class="badge bg-warning rounded-pill">12</span>
                            </li>
                        </ul>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Checkboxes and Radios</h5>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <input class="form-check-input me-1" id="newsletterCheckbox" type="checkbox" value="" />
                                <label class="form-check-label" for="newsletterCheckbox">Subscribe to
                                    newsletter</label>
                            </li>
                            <li class="list-group-item">
                                <input class="form-check-input me-1" id="termsCheckbox" type="checkbox" value="" />
                                <label class="form-check-label" for="termsCheckbox">Accept terms and
                                    conditions</label>
                            </li>
                        </ul>
                        <ul class="list-group mt-2">
                            <li class="list-group-item">
                                <input checked="" class="form-check-input me-1" id="emailRadio"
                                    name="notificationOptions" type="radio" value="" />
                                <label class="form-check-label" for="emailRadio">Notify by Email</label>
                            </li>
                            <li class="list-group-item">
                                <input class="form-check-input me-1" id="smsRadio" name="notificationOptions"
                                    type="radio" value="" />
                                <label class="form-check-label" for="smsRadio">Notify by SMS</label>
                            </li>
                        </ul>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Numbered </h5>
                        <ol class="list-group list-group-numbered">
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">Admin Admin</div>
                                    Admin Admin
                                </div>
                                <span class="badge bg-primary rounded-pill">865</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">Admin React Admin</div>
                                    Admin React Admin
                                </div>
                                <span class="badge bg-primary rounded-pill">140</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">Angular Version</div>
                                    Angular Version
                                </div>
                                <span class="badge bg-primary rounded-pill">85</span>
                            </li>
                        </ol>
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
                <h5 class="card-title mb-0">Pagination Variations</h5>
                <div class="card-action">
                    <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="">
                            <h5 class="mb-2 pb-1">Default Pagination</h5>
                            <nav aria-label="Page navigation example">
                                <ul class="pagination mb-0">
                                    <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                </ul>
                            </nav>
                        </div>
                        <div class="mt-4">
                            <h5 class="mb-2 pb-1">Alignment</h5>
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="javascript: void(0);" tabindex="-1">Previous</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">1</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">2</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="javascript: void(0);">Next</a>
                                    </li>
                                </ul>
                            </nav>
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-end">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="javascript: void(0);" tabindex="-1">Previous</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">1</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">2</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="javascript: void(0);">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class="mt-4">
                            <h5 class="mb-2 pb-1">Disabled and active states</h5>
                            <nav aria-label="...">
                                <ul class="pagination mb-0">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li aria-current="page" class="page-item active">
                                        <a class="page-link" href="#">2</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class="mt-4">
                            <h5 class="mb-2 pb-1">Custom Icon Pagination</h5>
                            <nav>
                                <ul class="pagination pagination-boxed">
                                    <li class="page-item">
                                        <a aria-label="Previous" class="page-link" href="javascript: void(0);">
                                            <i class="ti ti-chevron-left"></i>
                                        </a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">1</a></li>
                                    <li class="page-item active"><a class="page-link" href="javascript: void(0);">2</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">3</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">4</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">5</a>
                                    </li>
                                    <li class="page-item">
                                        <a aria-label="Next" class="page-link" href="javascript: void(0);">
                                            <i class="ti ti-chevron-right align-middle"></i>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                            <nav>
                                <ul class="pagination pagination-boxed mb-0">
                                    <li class="page-item">
                                        <a aria-label="Previous" class="page-link" href="javascript: void(0);">
                                            <i data-lucide="arrow-left"></i>
                                        </a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">1</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">2</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="javascript: void(0);">3</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">4</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">5</a>
                                    </li>
                                    <li class="page-item">
                                        <a aria-label="Next" class="page-link" href="javascript: void(0);">
                                            <i data-lucide="arrow-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div> <!-- end card-->
                    <div class="col-lg-6">
                        <div class="">
                            <h5 class="mb-2 pb-1">Sizing</h5>
                            <nav>
                                <ul class="pagination pagination-lg">
                                    <li class="page-item">
                                        <a aria-label="Previous" class="page-link" href="javascript: void(0);">
                                            <span aria-hidden="true">«</span>
                                        </a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">1</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">2</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">3</a>
                                    </li>
                                    <li class="page-item">
                                        <a aria-label="Next" class="page-link" href="javascript: void(0);">
                                            <span aria-hidden="true">»</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                            <nav>
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item">
                                        <a aria-label="Previous" class="page-link" href="javascript: void(0);">
                                            <span aria-hidden="true">«</span>
                                        </a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">1</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">2</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">3</a>
                                    </li>
                                    <li class="page-item">
                                        <a aria-label="Next" class="page-link" href="javascript: void(0);">
                                            <span aria-hidden="true">»</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class="mt-4">
                            <h5 class="mb-2 pb-1">Boxed Pagination</h5>
                            <nav>
                                <ul class="pagination pagination-boxed">
                                    <li class="page-item">
                                        <a aria-label="Previous" class="page-link" href="javascript: void(0);">
                                            <span aria-hidden="true">«</span>
                                        </a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">1</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">2</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="javascript: void(0);">3</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">4</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">5</a>
                                    </li>
                                    <li class="page-item">
                                        <a aria-label="Next" class="page-link" href="javascript: void(0);">
                                            <span aria-hidden="true">»</span>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="pagination pagination-lg pagination-boxed">
                                    <li class="page-item">
                                        <a aria-label="Previous" class="page-link" href="javascript: void(0);">
                                            <span aria-hidden="true">«</span>
                                        </a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">1</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">2</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="javascript: void(0);">3</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">4</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">5</a>
                                    </li>
                                    <li class="page-item">
                                        <a aria-label="Next" class="page-link" href="javascript: void(0);">
                                            <span aria-hidden="true">»</span>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="pagination pagination-sm pagination-boxed mb-0">
                                    <li class="page-item">
                                        <a aria-label="Previous" class="page-link" href="javascript: void(0);">
                                            <span aria-hidden="true">«</span>
                                        </a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">1</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">2</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="javascript: void(0);">3</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">4</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">5</a>
                                    </li>
                                    <li class="page-item">
                                        <a aria-label="Next" class="page-link" href="javascript: void(0);">
                                            <span aria-hidden="true">»</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class="mt-4">
                            <h5 class="mb-2 pb-1">Rounded Pagination</h5>
                            <nav>
                                <ul class="pagination pagination-rounded pagination-boxed mb-0">
                                    <li class="page-item">
                                        <a aria-label="Previous" class="page-link" href="javascript: void(0);">
                                            <span aria-hidden="true">«</span>
                                        </a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">1</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">2</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="javascript: void(0);">3</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">4</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">5</a>
                                    </li>
                                    <li class="page-item">
                                        <a aria-label="Next" class="page-link" href="javascript: void(0);">
                                            <span aria-hidden="true">»</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div> <!-- end card-->
                </div> <!-- end col-->
            </div>
        </div>
    </div>
</div><!-- end row -->
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Scrollspy Variations</h5>
                <div class="card-action">
                    <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-12">
                        <h5 class="mb-2 pb-1">Example in Navbar </h5>
                        <nav class="navbar navbar-light bg-light px-3" id="navbar-example2">
                            <a class="navbar-brand" href="#">Navbar</a>
                            <ul class="nav nav-pills">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#fat">@fat</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#mdo">@mdo</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a aria-expanded="false" aria-haspopup="true"
                                        class="nav-link dropdown-toggle drop-arrow-none" data-bs-toggle="dropdown"
                                        href="#" role="button">Dropdown <i class="ti ti-chevron-down"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#one">one</a>
                                        <a class="dropdown-item" href="#two">two</a>
                                        <div class="dropdown-divider" role="separator"></div>
                                        <a class="dropdown-item" href="#three">three</a>
                                    </div>
                                </li>
                            </ul>
                        </nav>
                        <div class="overflow-auto mt-2 position-relative" data-bs-offset="0" data-bs-spy="scroll"
                            data-bs-target="#navbar-example2" style="height: 200px;">
                            <h4 id="fat">@fat</h4>
                            <p>Ad leggings keytar, brunch id art party dolor labore. Pitchfork yr
                                enim lo-fi before they sold out qui. Tumblr farm-to-table bicycle
                                rights whatever. Anim keffiyeh carles cardigan. Velit seitan
                                mcsweeney's photo booth 3 wolf moon irure. Cosby sweater lomo jean
                                shorts, williamsburg hoodie minim qui you probably haven't heard of
                                them et cardigan trust fund culpa biodiesel wes anderson aesthetic.
                                Nihil tattooed accusamus, cred irony biodiesel keffiyeh artisan
                                ullamco consequat.</p>
                            <h4 id="mdo">@mdo</h4>
                            <p>Veniam marfa mustache skateboard, adipisicing fugiat velit pitchfork
                                beard. Freegan beard aliqua cupidatat mcsweeney's vero. Cupidatat
                                four loko nisi, ea helvetica nulla carles. Tattooed cosby sweater
                                food truck, mcsweeney's quis non freegan vinyl. Lo-fi wes anderson
                                +1 sartorial. Carles non aesthetic exercitation quis gentrify.
                                Brooklyn adipisicing craft beer vice keytar deserunt.</p>
                            <h4 id="one">one</h4>
                            <p>Occaecat commodo aliqua delectus. Fap craft beer deserunt skateboard
                                ea. Lomo bicycle rights adipisicing banh mi, velit ea sunt next
                                level locavore single-origin coffee in magna veniam. High life id
                                vinyl, echo park consequat quis aliquip banh mi pitchfork. Vero VHS
                                est adipisicing. Consectetur nisi DIY minim messenger bag. Cred ex
                                in, sustainable delectus consectetur fanny pack iphone.</p>
                            <h4 id="two">two</h4>
                            <p>In incididunt echo park, officia deserunt mcsweeney's proident master
                                cleanse thundercats sapiente veniam. Excepteur VHS elit, proident
                                shoreditch +1 biodiesel laborum craft beer. Single-origin coffee
                                wayfarers irure four loko, cupidatat terry richardson master
                                cleanse. Assumenda you probably haven't heard of them art party
                                fanny pack, tattooed nulla cardigan tempor ad. Proident wolf
                                nesciunt sartorial keffiyeh eu banh mi sustainable. Elit wolf
                                voluptate, lo-fi ea portland before they sold out four loko.
                                Locavore enim nostrud mlkshk brooklyn nesciunt.</p>
                            <h4 id="three">three</h4>
                            <p>Ad leggings keytar, brunch id art party dolor labore. Pitchfork yr
                                enim lo-fi before they sold out qui. Tumblr farm-to-table bicycle
                                rights whatever. Anim keffiyeh carles cardigan. Velit seitan
                                mcsweeney's photo booth 3 wolf moon irure. Cosby sweater lomo jean
                                shorts, williamsburg hoodie minim qui you probably haven't heard of
                                them et cardigan trust fund culpa biodiesel wes anderson aesthetic.
                                Nihil tattooed accusamus, cred irony biodiesel keffiyeh artisan
                                ullamco consequat.</p>
                            <p>Keytar twee blog, culpa messenger bag marfa whatever delectus food
                                truck. Sapiente synth id assumenda. Locavore sed helvetica cliche
                                irony, thundercats you probably haven't heard of them consequat
                                hoodie gluten-free lo-fi fap aliquip. Labore elit placeat before
                                they sold out, terry richardson proident brunch nesciunt quis cosby
                                sweater pariatur keffiyeh ut helvetica artisan. Cardigan craft beer
                                seitan readymade velit. VHS chambray laboris tempor veniam. Anim
                                mollit minim commodo ullamco thundercats.
                            </p>
                        </div>
                    </div> <!-- end col-->
                </div>
                <div class="row mt-4">
                    <div class="col-xl-12">
                        <h5 class="mb-2 pb-1">Example with Nested Nav</h5>
                        <div class="row">
                            <div class="col-2">
                                <nav class="h-100 flex-column align-items-stretch pe-4 border-end" id="navbar-example3">
                                    <nav class="nav nav-pills flex-column">
                                        <a class="nav-link" href="#item-1">Item 1</a>
                                        <nav class="nav nav-pills flex-column">
                                            <a class="nav-link ms-3 my-1" href="#item-1-1">Item 1-1</a>
                                            <a class="nav-link ms-3 my-1" href="#item-1-2">Item 1-2</a>
                                        </nav>
                                        <a class="nav-link" href="#item-2">Item 2</a>
                                        <a class="nav-link" href="#item-3">Item 3</a>
                                        <nav class="nav nav-pills flex-column">
                                            <a class="nav-link ms-3 my-1" href="#item-3-1">Item 3-1</a>
                                            <a class="nav-link ms-3 my-1" href="#item-3-2">Item 3-2</a>
                                        </nav>
                                    </nav>
                                </nav>
                            </div>
                            <div class="col-10">
                                <div class="overflow-auto mt-2 position-relative" data-bs-offset="0"
                                    data-bs-spy="scroll" data-bs-target="#navbar-example3" style="height: 300px;">
                                    <h4 id="item-1">Item 1</h4>
                                    <p>Ex consequat commodo adipisicing exercitation aute excepteur
                                        occaecat ullamco duis aliqua id magna ullamco eu. Do aute
                                        ipsum ipsum ullamco cillum consectetur ut et aute
                                        consectetur labore. Fugiat laborum incididunt tempor eu
                                        consequat enim dolore proident. Qui laborum do non excepteur
                                        nulla magna eiusmod consectetur in. Aliqua et aliqua officia
                                        quis et incididunt voluptate non anim reprehenderit
                                        adipisicing dolore ut consequat deserunt mollit dolore.
                                        Aliquip nulla enim veniam non fugiat id cupidatat nulla elit
                                        cupidatat commodo velit ut eiusmod cupidatat elit dolore.
                                    </p>
                                    <h5 id="item-1-1">Item 1-1</h5>
                                    <p>Amet tempor mollit aliquip pariatur excepteur commodo do ea
                                        cillum commodo Lorem et occaecat elit qui et. Aliquip labore
                                        ex ex esse voluptate occaecat Lorem ullamco deserunt. Aliqua
                                        cillum excepteur irure consequat id quis ea. Sit proident
                                        ullamco aute magna pariatur nostrud labore. Reprehenderit
                                        aliqua commodo eiusmod aliquip est do duis amet proident
                                        magna consectetur consequat eu commodo fugiat non quis. Enim
                                        aliquip exercitation ullamco adipisicing voluptate excepteur
                                        minim exercitation minim minim commodo adipisicing
                                        exercitation officia nisi adipisicing. Anim id duis qui
                                        consequat labore adipisicing sint dolor elit cillum anim et
                                        fugiat.</p>
                                    <h5 id="item-1-2">Item 1-2</h5>
                                    <p>Cillum nisi deserunt magna eiusmod qui eiusmod velit
                                        voluptate pariatur laborum sunt enim. Irure laboris mollit
                                        consequat incididunt sint et culpa culpa incididunt
                                        adipisicing magna magna occaecat. Nulla ipsum cillum eiusmod
                                        sint elit excepteur ea labore enim consectetur in labore
                                        anim. Proident ullamco ipsum esse elit ut Lorem eiusmod
                                        dolor et eiusmod. Anim occaecat nulla in non consequat
                                        eiusmod velit incididunt.</p>
                                    <h4 id="item-2">Item 2</h4>
                                    <p>Quis magna Lorem anim amet ipsum do mollit sit cillum
                                        voluptate ex nulla tempor. Laborum consequat non elit enim
                                        exercitation cillum aliqua consequat id aliqua. Esse ex
                                        consectetur mollit voluptate est in duis laboris ad sit
                                        ipsum anim Lorem. Incididunt veniam velit elit elit veniam
                                        Lorem aliqua quis ullamco deserunt sit enim elit aliqua esse
                                        irure. Laborum nisi sit est tempor laborum mollit labore
                                        officia laborum excepteur commodo non commodo dolor
                                        excepteur commodo. Ipsum fugiat ex est consectetur ipsum
                                        commodo tempor sunt in proident.</p>
                                    <h4 id="item-3">Item 3</h4>
                                    <p>Quis anim sit do amet fugiat dolor velit sit ea ea do
                                        reprehenderit culpa duis. Nostrud aliqua ipsum fugiat minim
                                        proident occaecat excepteur aliquip culpa aute tempor
                                        reprehenderit. Deserunt tempor mollit elit ex pariatur
                                        dolore velit fugiat mollit culpa irure ullamco est ex
                                        ullamco excepteur.</p>
                                    <h5 id="item-3-1">Item 3-1</h5>
                                    <p>Deserunt quis elit Lorem eiusmod amet enim enim amet minim
                                        Lorem proident nostrud. Ea id dolore anim exercitation aute
                                        fugiat labore voluptate cillum do laboris labore. Ex velit
                                        exercitation nisi enim labore reprehenderit labore nostrud
                                        ut ut. Esse officia sunt duis aliquip ullamco tempor eiusmod
                                        deserunt irure nostrud irure. Ullamco proident veniam
                                        laboris ea consectetur magna sunt ex exercitation aliquip
                                        minim enim culpa occaecat exercitation. Est tempor excepteur
                                        aliquip laborum consequat do deserunt laborum esse eiusmod
                                        irure proident ipsum esse qui.</p>
                                    <h5 id="item-3-2">Item 3-2</h5>
                                    <p>Labore sit culpa commodo elit adipisicing sit aliquip elit
                                        proident voluptate minim mollit nostrud aute reprehenderit
                                        do. Mollit excepteur eu Lorem ipsum anim commodo sint labore
                                        Lorem in exercitation velit incididunt. Occaecat consectetur
                                        nisi in occaecat proident minim enim sunt reprehenderit
                                        exercitation cupidatat et do officia. Aliquip consequat ad
                                        labore labore mollit ut amet. Sit pariatur tempor proident
                                        in veniam culpa aliqua excepteur elit magna fugiat eiusmod
                                        amet officia.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-xl-12">
                        <h5 class="mb-2 pb-1">Example with list-group</h5>
                        <div class="row">
                            <div class="col-2">
                                <div class="list-group" id="list-example">
                                    <a class="list-group-item list-group-item-action active" href="#list-item-1">Item
                                        1</a>
                                    <a class="list-group-item list-group-item-action" href="#list-item-2">Item 2</a>
                                    <a class="list-group-item list-group-item-action" href="#list-item-3">Item 3</a>
                                    <a class="list-group-item list-group-item-action" href="#list-item-4">Item 4</a>
                                </div>
                            </div>
                            <div class="col-10">
                                <div class="overflow-auto mt-2 position-relative" data-bs-offset="0"
                                    data-bs-spy="scroll" data-bs-target="#list-example" style="height: 200px;">
                                    <h4 id="list-item-1">Item 1</h4>
                                    <p>Ex consequat commodo adipisicing exercitation aute excepteur
                                        occaecat ullamco duis aliqua id magna ullamco eu. Do aute
                                        ipsum ipsum ullamco cillum consectetur ut et aute
                                        consectetur labore. Fugiat laborum incididunt tempor eu
                                        consequat enim dolore proident. Qui laborum do non excepteur
                                        nulla magna eiusmod consectetur in. Aliqua et aliqua officia
                                        quis et incididunt voluptate non anim reprehenderit
                                        adipisicing dolore ut consequat deserunt mollit dolore.
                                        Aliquip nulla enim veniam non fugiat id cupidatat nulla elit
                                        cupidatat commodo velit ut eiusmod cupidatat elit dolore.
                                    </p>
                                    <h4 id="list-item-2">Item 2</h4>
                                    <p>Quis magna Lorem anim amet ipsum do mollit sit cillum
                                        voluptate ex nulla tempor. Laborum consequat non elit enim
                                        exercitation cillum aliqua consequat id aliqua. Esse ex
                                        consectetur mollit voluptate est in duis laboris ad sit
                                        ipsum anim Lorem. Incididunt veniam velit elit elit veniam
                                        Lorem aliqua quis ullamco deserunt sit enim elit aliqua esse
                                        irure. Laborum nisi sit est tempor laborum mollit labore
                                        officia laborum excepteur commodo non commodo dolor
                                        excepteur commodo. Ipsum fugiat ex est consectetur ipsum
                                        commodo tempor sunt in proident.</p>
                                    <h4 id="list-item-3">Item 3</h4>
                                    <p>Quis anim sit do amet fugiat dolor velit sit ea ea do
                                        reprehenderit culpa duis. Nostrud aliqua ipsum fugiat minim
                                        proident occaecat excepteur aliquip culpa aute tempor
                                        reprehenderit. Deserunt tempor mollit elit ex pariatur
                                        dolore velit fugiat mollit culpa irure ullamco est ex
                                        ullamco excepteur.</p>
                                    <h4 id="list-item-4">Item 4</h4>
                                    <p>Quis anim sit do amet fugiat dolor velit sit ea ea do
                                        reprehenderit culpa duis. Nostrud aliqua ipsum fugiat minim
                                        proident occaecat excepteur aliquip culpa aute tempor
                                        reprehenderit. Deserunt tempor mollit elit ex pariatur
                                        dolore velit fugiat mollit culpa irure ullamco est ex
                                        ullamco excepteur.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- end row -->

@endsection