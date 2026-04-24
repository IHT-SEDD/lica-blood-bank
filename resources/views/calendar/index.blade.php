@extends('layouts.vertical', ['title' => 'Calendar'])

@section('styles')

@endsection

@section('content')
@include('layouts.shared.page-title', [
'title' => 'Smart Calendar &amp; Event Management',
'subTitle' =>
'A beautifully designed, feature-rich calendar component for scheduling, task planning, and event tracking. Fully
customizable, responsive, and built to integrate with modern frameworks.',
'badgeIcon' => 'calendar',
'badgeTitle' => 'Events &amp; Scheduling',
])

<div class="outlook-box">
    <div class="card mb-0 d-none d-lg-flex rounded-end-0">
        <div class="card-body">
            <button class="btn btn-primary w-100 btn-new-event">
                <i class="ti ti-plus me-2 align-middle"></i>
                Create New Event
            </button>
            <div id="external-events">
                <p class="text-muted mt-2 fst-italic fs-xs mb-3">Drag and drop your event or click in the
                    calendar</p>
                <div class="external-event fc-event bg-transparent text-body border rounded border-light fw-medium"
                    data-class="bg-transparent text-body border rounded border-light fw-medium">
                    <i class="ti ti-circle-filled me-2"></i>Design Review Meeting
                </div>
                <div class="external-event fc-event bg-transparent text-body border rounded border-light fw-medium"
                    data-class="bg-transparent text-body border rounded border-light fw-medium">
                    <i class="ti ti-circle-filled me-2"></i>Client Presentation
                </div>
                <div class="external-event fc-event bg-transparent text-body border rounded border-light fw-medium"
                    data-class="bg-transparent text-body border rounded border-light fw-medium">
                    <i class="ti ti-circle-filled me-2"></i>Marketing Strategy Call
                </div>
                <div class="external-event fc-event bg-transparent text-body border rounded border-light fw-medium"
                    data-class="bg-transparent text-body border rounded border-light fw-medium">
                    <i class="ti ti-circle-filled me-2"></i>Product Launch Prep
                </div>
                <div class="external-event fc-event bg-transparent text-body border rounded border-light fw-medium"
                    data-class="bg-transparent text-body border rounded border-light fw-medium">
                    <i class="ti ti-circle-filled me-2"></i>Weekly Standup
                </div>
            </div>
        </div>
    </div> <!-- end card-->
    <div class="card h-100 mb-0 rounded-start-0 flex-grow-1 border-start-0">
        <div class="d-lg-none d-inline-flex card-header">
            <button class="btn btn-primary btn-new-event">
                <i class="ti ti-plus me-2 align-middle"></i>
                Create New Event
            </button>
        </div>
        <div class="card-body">
            <div id="calendar"></div>
        </div> <!-- end card-body -->
    </div> <!-- end card-->
</div> <!-- end row-->
<!-- Modal Add/Edit -->
<div class="modal fade" id="event-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form class="needs-validation" id="forms-event" name="event-form" novalidate="">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">
                        Create Event
                    </h4>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="control-label form-label" for="event-title">Event Name</label>
                                <input class="form-control" id="event-title" name="title"
                                    placeholder="Insert Event Name" required="" type="text" />
                                <div class="invalid-feedback">Please provide a valid event name</div>
                            </div>
                        </div>
                        <!-- <div class="col-12">
                                                        <div class="mb-2">
                                                            <label class="control-label form-label" for="event-category">Category</label>
                                                            <select class="form-select" name="category" id="event-category" required>
                                                                <option value="" disabled>Select a category</option>
                                                                <option value="bg-primary-subtle text-primary border-start border-3 border-primary" selected>Primary</option>
                                                                <option value="bg-secondary-subtle text-secondary border-start border-3 border-secondary">Secondary</option>
                                                                <option value="bg-success-subtle text-success border-start border-3 border-success">Success</option>
                                                                <option value="bg-info-subtle text-info border-start border-3 border-info">Info</option>
                                                                <option value="bg-warning-subtle text-warning border-start border-3 border-warning">Warning</option>
                                                                <option value="bg-danger-subtle text-danger border-start border-3 border-danger">Danger</option>
                                                                <option value="bg-dark-subtle text-dark border-start border-3 border-dark">Dark</option>
                                                            </select>

                                                            <div class="invalid-feedback">Please select a valid event category</div>
                                                        </div>
                                                    </div> -->
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <button class="btn btn-danger" id="btn-delete-event" type="button">
                            Delete
                        </button>
                        <button class="btn btn-light ms-auto" data-bs-dismiss="modal" type="button">
                            Close
                        </button>
                        <button class="btn btn-primary" id="btn-save-event" type="submit">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <!-- end modal-content-->
    </div>
    <!-- end modal dialog-->
</div>
<!-- end modal-->
@endsection

@section('scripts')
@vite(['resources/js/pages/calendar.js'])
@endsection