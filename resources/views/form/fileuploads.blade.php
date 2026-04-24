@extends('layouts.vertical', ['title' => 'File Uploads'])

@section('styles')
    @vite(['node_modules/dropzone/dist/dropzone.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'File Upload with Dropzone',
        'subTitle' =>
            'Drag and drop files easily with Dropzone.js — a lightweight library for file previews, validations, and async uploads.',
        'badgeIcon' => 'upload-cloud',
        'badgeTitle' => 'Drag &amp; Drop Uploads',
    ])


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Dropzone</h5>
                </div>
                <div class="card-body rounded-bottom-0">
                    <p class="text-muted mb-2">
                        DropzoneJS is an open source library that provides drag’n’drop file uploads with image
                        previews.
                    </p>
                    <a class="btn btn-link shadow-none p-0 fw-medium" href="https://www.dropzone.dev/" target="_blank">
                        View Official Website
                        <i class="ti ti-chevron-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body rounded-top-0 border-top-0">
                    <form action="/" class="dropzone" data-plugin="dropzone" data-previews-container="#file-previews"
                        data-upload-preview-template="#uploadPreviewTemplate" id="myAwesomeDropzone" method="post">
                        <div class="fallback">
                            <input name="file" type="file" />
                        </div>
                        <div class="dz-message needsclick">
                            <div class="avatar-lg mx-auto mb-3">
                                <span class="avatar-title bg-info-subtle text-info rounded-circle">
                                    <i class="fs-24 ti ti-cloud-upload"></i>
                                </span>
                            </div>
                            <h4 class="mb-2">Drop files here or click to upload.</h4>
                            <p class="text-muted fst-italic mb-3">You can drag images here, or browse files via
                                the button below.</p>
                            <button class="btn btn-sm btn-default" type="button">Browse Images</button>
                        </div>
                    </form>
                    <!-- Preview -->
                    <div class="dropzone-previews mt-3" id="file-previews"></div>
                    <!-- file preview template -->
                    <div class="d-none" id="uploadPreviewTemplate">
                        <div class="card mt-1 mb-0 border-dashed border">
                            <div class="p-2">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <img alt="" class="avatar-sm rounded bg-light" data-dz-thumbnail=""
                                            src="#" />
                                    </div>
                                    <div class="col ps-0">
                                        <a class="fw-semibold" data-dz-name="" href="javascript:void(0);"></a>
                                        <p class="mb-0 text-muted" data-dz-size=""></p>
                                    </div>
                                    <div class="col-auto">
                                        <!-- Button -->
                                        <a class="btn btn-link shadow-none btn-lg text-danger" data-dz-remove=""
                                            href="">
                                            <i class="ti ti-x"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end file preview template -->
                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection

@section('scripts')
    @vite(['resources/js/pages/form-fileupload.js'])
@endsection
