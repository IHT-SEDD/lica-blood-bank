@extends('layouts.vertical', ['title' => 'Ton AI - A Simple Simple AI Tools'])

@section('styles')
@endsection

@section('content')
    <div class="outlook-box mt-4">
        <div aria-labelledby="outlookSidebaroffcanvasLabel"
            class="offcanvas-lg offcanvas-start outlook-left-menu rounded-start-4" id="outlookSidebaroffcanvas" tabindex="-1">
            <div class="card rounded-0 mb-0">
                <div class="card-body p-0" data-simplebar="" style="height: calc(100vh - 170px);">
                    <div class="chat-sidebar p-3">
                        <!-- Top Actions -->
                        <div class="mb-4">
                            <div class="list-group list-group-flush list-custom">
                                <a class="list-group-item list-group-item-action active d-flex align-items-center gap-2"
                                    href="#">
                                    <i class="ti ti-message-circle fs-md"></i> Start Chat
                                </a>
                                <a class="list-group-item list-group-item-action d-flex align-items-center gap-2"
                                    href="#">
                                    <i class="ti ti-search fs-md"></i> Find Threads
                                </a>
                                <a class="list-group-item list-group-item-action d-flex align-items-center gap-2"
                                    href="#">
                                    <i class="ti ti-archive fs-md"></i> Saved Sessions
                                </a>
                                <a class="list-group-item list-group-item-action d-flex align-items-center gap-2"
                                    href="#">
                                    <i class="ti ti-brain fs-md"></i> AI Tools
                                </a>
                                <a class="list-group-item list-group-item-action d-flex align-items-center gap-2"
                                    href="#">
                                    <i class="ti ti-video fs-md"></i> AI Vision
                                </a>
                                <a class="list-group-item list-group-item-action d-flex align-items-center gap-2"
                                    href="#">
                                    <i class="ti ti-grid-pattern fs-md"></i> Explore Models
                                </a>
                            </div>
                        </div>
                        <!-- Workspace Folders -->
                        <div class="mb-3">
                            <h6 class="text-muted text-uppercase fs-xs mb-2">Workspaces</h6>
                            <div class="list-group list-group-flush list-custom">
                                <a class="list-group-item list-group-item-action d-flex align-items-center gap-2"
                                    href="#">
                                    <i class="ti ti-folder-plus fs-md"></i> New Workspace
                                </a>
                                <a class="list-group-item list-group-item-action d-flex align-items-center gap-2"
                                    href="#">
                                    <i class="ti ti-folder fs-md"></i> Marketing
                                </a>
                                <a class="list-group-item list-group-item-action d-flex align-items-center gap-2"
                                    href="#">
                                    <i class="ti ti-folder fs-md"></i> Design Team
                                </a>
                                <a class="list-group-item list-group-item-action d-flex align-items-center gap-2"
                                    href="#">
                                    <i class="ti ti-folder fs-md"></i> DevOps
                                </a>
                                <a class="list-group-item list-group-item-action d-flex align-items-center gap-2"
                                    href="#">
                                    <i class="ti ti-folder fs-md"></i> Legal
                                </a>
                                <a class="list-group-item list-group-item-action d-flex align-items-center gap-2"
                                    href="#">
                                    <i class="ti ti-folder fs-md"></i> Freelancers
                                </a>
                            </div>
                        </div>
                        <!-- Recent Chats -->
                        <div>
                            <h6 class="text-muted text-uppercase fs-xs mb-2">Recent Conversations</h6>
                            <div class="list-group list-group-flush list-custom">
                                <a class="list-group-item list-group-item-action text-body py-1" href="#">Website
                                    Redesign Brief</a>
                                <a class="list-group-item list-group-item-action text-body py-1" href="#">Sprint
                                    Planning Q2</a>
                                <a class="list-group-item list-group-item-action text-body py-1" href="#">Client
                                    Onboarding Script</a>
                                <a class="list-group-item list-group-item-action text-body py-1" href="#">Legal
                                    Agreement Review</a>
                                <a class="list-group-item list-group-item-action text-body py-1" href="#">Product
                                    Launch Sequence</a>
                                <a class="list-group-item list-group-item-action text-body py-1" href="#">Budget
                                    Automation Draft</a>
                            </div>
                        </div>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div>
        <div class="card mb-0 rounded-0 flex-grow-1" style="min-height: calc(100vh - 168px);">
            <div class="card-header d-lg-none d-flex">
                <button aria-controls="outlookSidebaroffcanvas" class="btn btn-sm btn-default btn-icon"
                    data-bs-target="#outlookSidebaroffcanvas" data-bs-toggle="offcanvas" type="button">
                    <i class="ti ti-menu-2 fs-lg"></i>
                </button>
            </div>
            <div class="card-body d-flex flex-column justify-content-between align-items-center h-100">
                <div class="row justify-content-center">
                    <div class="col-xl-8">
                        <div class="mt-3">
                            <div class="avatar avatar-sm rounded-circle mx-auto text-bg-dark">
                                <span class="avatar-title">
                                    <i class="fs-md" data-lucide="sparkles"></i>
                                </span>
                            </div>
                            <h3 class="mb-4 mt-2 text-center">How can I help, Maxine 👋?</h3>
                            <div class="py-4">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <div class="card card-hovered rounded-3">
                                            <div class="card-body">
                                                <div
                                                    class="avatar-lg mb-2 rounded-circle text-bg-light d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-brain fs-xxl"></i>
                                                </div>
                                                <p class="mb-0 text-muted">Generate AI-powered insights from
                                                    customer reviews</p>
                                                <a class="stretched-link" href="#!"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card card-hovered rounded-3">
                                            <div class="card-body">
                                                <div
                                                    class="avatar-lg mb-2 rounded-circle text-bg-light d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-robot fs-xxl"></i>
                                                </div>
                                                <p class="mb-0 text-muted">Create chatbot responses for common
                                                    support questions</p>
                                                <a class="stretched-link" href="#!"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card card-hovered rounded-3">
                                            <div class="card-body">
                                                <div
                                                    class="avatar-lg mb-2 rounded-circle text-bg-light d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-text-recognition fs-xxl"></i>
                                                </div>
                                                <p class="mb-0 text-muted">Summarize lengthy documents using AI
                                                </p>
                                                <a class="stretched-link" href="#!"></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-100 text-center">
                    <div class="card-body">
                        <textarea class="form-control" id="exampleFormControlTextarea1" placeholder="Enter message" rows="4"></textarea>
                        <div class="d-flex gap-1 mt-2 align-items-center">
                            <button class="btn btn-sm btn-icon btn-default" data-bs-placement="top"
                                data-bs-toggle="tooltip" data-bs-trigger="hover" title="Attach files" type="button">
                                <i class="ti ti-paperclip fs-sm"></i>
                            </button>
                            <button class="btn btn-sm btn-icon btn-default" data-bs-placement="top"
                                data-bs-toggle="tooltip" data-bs-trigger="hover" title="Insert link" type="button">
                                <i class="ti ti-link fs-sm"></i>
                            </button>
                            <button class="btn btn-sm btn-icon btn-default" data-bs-placement="top"
                                data-bs-toggle="tooltip" data-bs-trigger="hover" title="Insert photo" type="button">
                                <i class="ti ti-photo-up fs-sm"></i>
                            </button>
                            <button class="btn btn-sm btn-icon btn-default" data-bs-placement="top"
                                data-bs-toggle="tooltip" data-bs-trigger="hover" title="Voice" type="button">
                                <i class="ti ti-microphone fs-sm"></i>
                            </button>
                            <button class="btn btn-sm btn-primary ms-auto" type="button">
                                <i class="ti ti-send fs-sm me-1"></i> Send
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end row-->
@endsection

@section('scripts')
@endsection
