@extends('layouts.vertical', ['title' => 'Visual Feedback'])

@section('styles')
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Visual Feedback',
        'subTitle' =>
            'Display loading states, progress indicators, carousels, and other UI elements that inform users of ongoing processes.',
        'badgeIcon' => 'loader-2',
        'badgeTitle' => 'User Cues',
    ])

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Progress Variations</h5>
                    <div class="card-action">
                        <a class="card-action-item" data-action="card-toggle" href="#!"><i
                                class="ti ti-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">Examples </h5>
                            <div class="progress mb-2">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="0" class="progress-bar"
                                    role="progressbar"></div>
                            </div>
                            <div class="progress mb-2">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="25" class="progress-bar"
                                    role="progressbar" style="width: 25%"></div>
                            </div>
                            <div class="progress mb-2">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="50" class="progress-bar"
                                    role="progressbar" style="width: 50%"></div>
                            </div>
                            <div class="progress mb-2">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="75" class="progress-bar"
                                    role="progressbar" style="width: 75%"></div>
                            </div>
                            <div class="progress">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="100" class="progress-bar"
                                    role="progressbar" style="width: 100%"></div>
                            </div>
                        </div> <!-- end col-->
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">Height </h5>
                            <div class="progress mb-2" style="height: 1px;">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="25" class="progress-bar bg-danger"
                                    role="progressbar" style="width: 25%;"></div>
                            </div>
                            <div class="progress mb-2" style="height: 3px;">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="25" class="progress-bar"
                                    role="progressbar" style="width: 25%; height: 20px;">
                                </div>
                            </div>
                            <div class="progress mb-2 progress-sm">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="25"
                                    class="progress-bar bg-success" role="progressbar" style="width: 25%"></div>
                            </div>
                            <div class="progress mb-2 progress-md">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="50" class="progress-bar bg-info"
                                    role="progressbar" style="width: 50%"></div>
                            </div>
                            <div class="progress progress-lg mb-2">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="75"
                                    class="progress-bar bg-warning" role="progressbar" style="width: 75%"></div>
                            </div>
                            <div class="progress progress-xl">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="38"
                                    class="progress-bar bg-success" role="progressbar" style="width: 38%"></div>
                            </div>
                        </div> <!-- end col-->
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">Multiple Bars </h5>
                            <div class="progress">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="15" class="progress-bar"
                                    role="progressbar" style="width: 15%"></div>
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="30"
                                    class="progress-bar bg-success" role="progressbar" style="width: 30%">
                                </div>
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="20"
                                    class="progress-bar bg-info" role="progressbar" style="width: 20%"></div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">Animated Stripes</h5>
                            <div class="progress">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="75"
                                    class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    style="width: 75%"></div>
                            </div>
                        </div> <!-- end col-->
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">Labels </h5>
                            <div class="progress mb-3">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="25" class="progress-bar"
                                    role="progressbar" style="width: 25%;">25%</div>
                            </div>
                            <div aria-label="Example with label" aria-valuemax="100" aria-valuemin="0"
                                aria-valuenow="10" class="progress" role="progressbar">
                                <div class="progress-bar overflow-visible text-dark" style="width: 10%">Long
                                    label text for the progress bar, set to a dark color</div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">Backgrounds</h5>
                            <div class="progress mb-2">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="25"
                                    class="progress-bar bg-success" role="progressbar" style="width: 25%">
                                </div>
                            </div>
                            <div class="progress mb-2">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="50"
                                    class="progress-bar bg-info" role="progressbar" style="width: 50%"></div>
                            </div>
                            <div class="progress mb-2">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="75"
                                    class="progress-bar bg-warning" role="progressbar" style="width: 75%">
                                </div>
                            </div>
                            <div class="progress mb-2">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="100"
                                    class="progress-bar bg-danger" role="progressbar" style="width: 100%">
                                </div>
                            </div>
                            <div class="progress mb-2">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="65"
                                    class="progress-bar bg-dark" role="progressbar" style="width: 65%"></div>
                            </div>
                            <div class="progress">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="50"
                                    class="progress-bar bg-secondary" role="progressbar" style="width: 50%">
                                </div>
                            </div>
                        </div> <!-- end col-->
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">Striped </h5>
                            <div class="progress mb-2">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="10"
                                    class="progress-bar progress-bar-striped" role="progressbar" style="width: 10%">
                                </div>
                            </div>
                            <div class="progress mb-2">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="25"
                                    class="progress-bar progress-bar-striped bg-success" role="progressbar"
                                    style="width: 25%"></div>
                            </div>
                            <div class="progress mb-2">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="50"
                                    class="progress-bar progress-bar-striped bg-info" role="progressbar"
                                    style="width: 50%"></div>
                            </div>
                            <div class="progress mb-2">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="75"
                                    class="progress-bar progress-bar-striped bg-warning" role="progressbar"
                                    style="width: 75%"></div>
                            </div>
                            <div class="progress">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="100"
                                    class="progress-bar progress-bar-striped bg-danger" role="progressbar"
                                    style="width: 100%"></div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">Steps</h5>
                            <div class="position-relative m-4">
                                <div class="progress" style="height: 2px;">
                                    <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="25" class="progress-bar"
                                        role="progressbar" style="width: 50%;"></div>
                                </div>
                                <button
                                    class="position-absolute top-0 start-0 translate-middle btn btn-icon btn-primary rounded-pill"
                                    type="button">1</button>
                                <button
                                    class="position-absolute top-0 start-50 translate-middle btn btn-icon btn-primary rounded-pill"
                                    type="button">2</button>
                                <button
                                    class="position-absolute top-0 start-100 translate-middle btn btn-icon btn-light rounded-pill"
                                    type="button">3</button>
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
                    <h5 class="card-title mb-0">Spinners Variations</h5>
                    <div class="card-action">
                        <a class="card-action-item" data-action="card-toggle" href="#!"><i
                                class="ti ti-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">Border Spinner</h5>
                            <div class="spinner-border m-2" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div> <!-- end col-->
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">Colors </h5>
                            <div>
                                <div class="spinner-border text-primary m-2" role="status"></div>
                                <div class="spinner-border text-secondary m-2" role="status"></div>
                                <div class="spinner-border text-success m-2" role="status"></div>
                                <div class="spinner-border text-danger m-2" role="status"></div>
                                <div class="spinner-border text-warning m-2" role="status"></div>
                                <div class="spinner-border text-info m-2" role="status"></div>
                                <div class="spinner-border text-light m-2" role="status"></div>
                                <div class="spinner-border text-dark m-2" role="status"></div>
                            </div>
                        </div> <!-- end col-->
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">Alignment</h5>
                            <div class="d-flex align-items-center">
                                <strong>Loading...</strong>
                                <div aria-hidden="true" class="spinner-border ms-auto" role="status"></div>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                <div class="spinner-border" role="status"></div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">Buttons Spinner</h5>
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <div class="d-flex flex-wrap gap-2">
                                        <button class="btn btn-primary btn-icon" disabled="" type="button">
                                            <span aria-hidden="true" class="spinner-border spinner-border-sm"
                                                role="status"></span><span class="visually-hidden">Loading...</span>
                                        </button>
                                        <button class="btn btn-primary btn-icon rounded-circle" disabled=""
                                            type="button">
                                            <span aria-hidden="true" class="spinner-border spinner-border-sm"
                                                role="status"></span><span class="visually-hidden">Loading...</span>
                                        </button>
                                        <button class="btn btn-primary" disabled="" type="button">
                                            <span aria-hidden="true" class="spinner-border spinner-border-sm"
                                                role="status"></span><span class="visually-hidden">Loading...</span>
                                        </button>
                                        <button class="btn btn-primary" disabled="" type="button">
                                            <span aria-hidden="true" class="spinner-border spinner-border-sm me-2"
                                                role="status"></span>
                                            Loading...
                                        </button>
                                    </div>
                                </div><!-- end col -->
                                <div class="col-lg-6">
                                    <div class="d-flex flex-wrap gap-2">
                                        <button class="btn btn-primary btn-icon" disabled="" type="button">
                                            <span aria-hidden="true" class="spinner-grow spinner-grow-sm"
                                                role="status"></span><span class="visually-hidden">Loading...</span>
                                        </button>
                                        <button class="btn btn-primary btn-icon rounded-circle" disabled=""
                                            type="button">
                                            <span aria-hidden="true" class="spinner-grow spinner-grow-sm"
                                                role="status"></span><span class="visually-hidden">Loading...</span>
                                        </button>
                                        <button class="btn btn-primary" disabled="" type="button">
                                            <span aria-hidden="true" class="spinner-grow spinner-grow-sm"
                                                role="status"></span><span class="visually-hidden">Loading...</span>
                                        </button>
                                        <button class="btn btn-primary" disabled="" type="button">
                                            <span aria-hidden="true" class="spinner-grow spinner-grow-sm me-2"
                                                role="status"></span>
                                            Loading...
                                        </button>
                                    </div>
                                </div><!-- end col -->
                            </div> <!-- end row -->
                        </div> <!-- end col-->
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">Growing Spinner</h5>
                            <div class="spinner-grow m-2" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                <div class="spinner-border" role="status"></div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">Color Growing Spinner</h5>
                            <div>
                                <div class="spinner-grow text-primary m-2" role="status"></div>
                                <div class="spinner-grow text-secondary m-2" role="status"></div>
                                <div class="spinner-grow text-success m-2" role="status"></div>
                                <div class="spinner-grow text-danger m-2" role="status"></div>
                                <div class="spinner-grow text-warning m-2" role="status"></div>
                                <div class="spinner-grow text-info m-2" role="status"></div>
                                <div class="spinner-grow text-light m-2" role="status"></div>
                                <div class="spinner-grow text-dark m-2" role="status"></div>
                            </div>
                        </div> <!-- end col-->
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">Size</h5>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="spinner-border avatar-lg text-primary m-2" role="status">
                                    </div>
                                    <div class="spinner-grow avatar-lg text-secondary m-2" role="status">
                                    </div>
                                </div><!-- end col -->
                                <div class="col-lg-6">
                                    <div class="spinner-border avatar-md text-primary m-2" role="status">
                                    </div>
                                    <div class="spinner-grow avatar-md text-secondary m-2" role="status">
                                    </div>
                                </div><!-- end col -->
                                <div class="col-lg-6">
                                    <div class="spinner-border avatar-sm text-primary m-2" role="status">
                                    </div>
                                    <div class="spinner-grow avatar-sm text-secondary m-2" role="status">
                                    </div>
                                </div><!-- end col -->
                                <div class="col-lg-6">
                                    <div class="spinner-border spinner-border-sm m-2" role="status"></div>
                                    <div class="spinner-grow spinner-grow-sm m-2" role="status"></div>
                                </div><!-- end col -->
                            </div><!--end row-->
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
                    <h5 class="card-title mb-0">Carousel Variations</h5>
                    <div class="card-action">
                        <a class="card-action-item" data-action="card-toggle" href="#!"><i
                                class="ti ti-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">Slides Only</h5>
                            <div class="carousel slide" data-bs-ride="carousel" id="carouselExampleSlidesOnly">
                                <div class="carousel-inner" role="listbox">
                                    <div class="carousel-item active">
                                        <!-- First slide -->
                                        <img alt="First slide" class="d-block img-fluid"
                                            src="/images/stock/small-1.jpg" />
                                    </div>
                                    <div class="carousel-item">
                                        <!-- Second slide -->
                                        <img alt="Second slide" class="d-block img-fluid"
                                            src="/images/stock/small-2.jpg" />
                                    </div>
                                    <div class="carousel-item">
                                        <!-- Third slide -->
                                        <img alt="Third slide" class="d-block img-fluid"
                                            src="/images/stock/small-3.jpg" />
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end col-->
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">With Controls</h5>
                            <div class="carousel slide" data-bs-ride="carousel" id="carouselExampleControls">
                                <div class="carousel-inner" role="listbox">
                                    <div class="carousel-item active">
                                        <!-- First slide -->
                                        <img alt="First slide" class="d-block img-fluid"
                                            src="/images/stock/small-4.jpg" />
                                    </div>
                                    <div class="carousel-item">
                                        <!-- Second slide -->
                                        <img alt="Second slide" class="d-block img-fluid"
                                            src="/images/stock/small-5.jpg" />
                                    </div>
                                    <div class="carousel-item">
                                        <!-- Third slide -->
                                        <img alt="Third slide" class="d-block img-fluid"
                                            src="/images/stock/small-6.jpg" />
                                    </div>
                                </div>
                                <a class="carousel-control-prev" data-bs-slide="prev" href="#carouselExampleControls"
                                    role="button">
                                    <span aria-hidden="true" class="carousel-control-prev-icon"></span>
                                    <span class="visually-hidden">Previous</span>
                                </a>
                                <a class="carousel-control-next" data-bs-slide="next" href="#carouselExampleControls"
                                    role="button">
                                    <span aria-hidden="true" class="carousel-control-next-icon"></span>
                                    <span class="visually-hidden">Next</span>
                                </a>
                            </div>
                        </div> <!-- end col-->
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">With Indicators </h5>
                            <div class="carousel slide" data-bs-ride="carousel" id="carouselExampleIndicators">
                                <div class="carousel-indicators">
                                    <button aria-current="true" aria-label="Slide 1" class="active" data-bs-slide-to="0"
                                        data-bs-target="#carouselExampleIndicators" type="button"></button>
                                    <button aria-label="Slide 2" data-bs-slide-to="1"
                                        data-bs-target="#carouselExampleIndicators" type="button"></button>
                                    <button aria-label="Slide 3" data-bs-slide-to="2"
                                        data-bs-target="#carouselExampleIndicators" type="button"></button>
                                </div>
                                <div class="carousel-inner" role="listbox">
                                    <div class="carousel-item active">
                                        <!-- First slide -->
                                        <img alt="First slide" class="d-block img-fluid"
                                            src="/images/stock/small-7.jpg" />
                                    </div>
                                    <div class="carousel-item">
                                        <!-- Second slide -->
                                        <img alt="Second slide" class="d-block img-fluid"
                                            src="/images/stock/small-1.jpg" />
                                    </div>
                                    <div class="carousel-item">
                                        <!-- Third slide -->
                                        <img alt="Third slide" class="d-block img-fluid"
                                            src="/images/stock/small-2.jpg" />
                                    </div>
                                </div>
                                <a class="carousel-control-prev" data-bs-slide="prev" href="#carouselExampleIndicators"
                                    role="button">
                                    <span aria-hidden="true" class="carousel-control-prev-icon"></span>
                                    <span class="visually-hidden">Previous</span>
                                </a>
                                <a class="carousel-control-next" data-bs-slide="next" href="#carouselExampleIndicators"
                                    role="button">
                                    <span aria-hidden="true" class="carousel-control-next-icon"></span>
                                    <span class="visually-hidden">Next</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">With Captions </h5>
                            <div class="carousel slide" data-bs-ride="carousel" id="carouselExampleCaption">
                                <div class="carousel-inner" role="listbox">
                                    <div class="carousel-item active">
                                        <!-- first slide  -->
                                        <img alt="..." class="d-block img-fluid" src="/images/stock/small-3.jpg" />
                                        <div class="carousel-caption d-none d-md-block">
                                            <h3 class="text-white">First slide label</h3>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <!-- secound slide  -->
                                        <img alt="..." class="d-block img-fluid" src="/images/stock/small-4.jpg" />
                                        <div class="carousel-caption d-none d-md-block">
                                            <h3 class="text-white">Second slide label</h3>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <!-- third slide  -->
                                        <img alt="..." class="d-block img-fluid" src="/images/stock/small-5.jpg" />
                                        <div class="carousel-caption d-none d-md-block">
                                            <h3 class="text-white">Third slide label</h3>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                        </div>
                                    </div>
                                </div>
                                <a class="carousel-control-prev" data-bs-slide="prev" href="#carouselExampleCaption"
                                    role="button">
                                    <span aria-hidden="true" class="carousel-control-prev-icon"></span>
                                    <span class="visually-hidden">Previous</span>
                                </a>
                                <a class="carousel-control-next" data-bs-slide="next" href="#carouselExampleCaption"
                                    role="button">
                                    <span aria-hidden="true" class="carousel-control-next-icon"></span>
                                    <span class="visually-hidden">Next</span>
                                </a>
                            </div>
                        </div> <!-- end col-->
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">Crossfade</h5>
                            <div class="carousel slide carousel-fade" data-bs-ride="carousel" id="carouselExampleFade">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <!-- First slide -->
                                        <img alt="First slide" class="d-block img-fluid"
                                            src="/images/stock/small-6.jpg" />
                                    </div>
                                    <div class="carousel-item">
                                        <!-- Second slide -->
                                        <img alt="Second slide" class="d-block img-fluid"
                                            src="/images/stock/small-7.jpg" />
                                    </div>
                                    <div class="carousel-item">
                                        <!-- Third slide -->
                                        <img alt="Third slide" class="d-block img-fluid"
                                            src="/images/stock/small-1.jpg" />
                                    </div>
                                </div>
                                <a class="carousel-control-prev" data-bs-slide="prev" href="#carouselExampleFade"
                                    role="button">
                                    <span aria-hidden="true" class="carousel-control-prev-icon"></span>
                                    <span class="visually-hidden">Previous</span>
                                </a>
                                <a class="carousel-control-next" data-bs-slide="next" href="#carouselExampleFade"
                                    role="button">
                                    <span aria-hidden="true" class="carousel-control-next-icon"></span>
                                    <span class="visually-hidden">Next</span>
                                </a>
                            </div>
                        </div> <!-- end col-->
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">Individual Interval</h5>
                            <div class="carousel slide" data-bs-ride="carousel" id="carouselExampleInterval">
                                <div class="carousel-inner">
                                    <div class="carousel-item active" data-bs-interval="1000">
                                        <!-- First slide -->
                                        <img alt="First slide" class="img-fluid d-block w-100"
                                            src="/images/stock/small-2.jpg" />
                                    </div>
                                    <div class="carousel-item" data-bs-interval="2000">
                                        <!-- Second slide -->
                                        <img alt="Second slide" class="img-fluid d-block w-100"
                                            src="/images/stock/small-3.jpg" />
                                    </div>
                                    <div class="carousel-item">
                                        <!-- Third slide -->
                                        <img alt="Third slide" class="img-fluid d-block w-100"
                                            src="/images/stock/small-4.jpg" />
                                    </div>
                                </div>
                                <a class="carousel-control-prev" data-bs-slide="prev" href="#carouselExampleInterval"
                                    role="button">
                                    <span aria-hidden="true" class="carousel-control-prev-icon"></span>
                                    <span class="visually-hidden">Previous</span>
                                </a>
                                <a class="carousel-control-next" data-bs-slide="next" href="#carouselExampleInterval"
                                    role="button">
                                    <span aria-hidden="true" class="carousel-control-next-icon"></span>
                                    <span class="visually-hidden">Next</span>
                                </a>
                            </div>
                        </div> <!-- end col-->
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">Dark Variant</h5>
                            <div class="carousel carousel-dark slide" id="carouselExampleDark">
                                <div class="carousel-indicators">
                                    <button aria-current="true" aria-label="Slide 1" class="active" data-bs-slide-to="0"
                                        data-bs-target="#carouselExampleDark" type="button"></button>
                                    <button aria-label="Slide 2" data-bs-slide-to="1"
                                        data-bs-target="#carouselExampleDark" type="button"></button>
                                    <button aria-label="Slide 3" data-bs-slide-to="2"
                                        data-bs-target="#carouselExampleDark" type="button"></button>
                                </div>
                                <div class="carousel-inner">
                                    <div class="carousel-item active" data-bs-interval="10000">
                                        <!-- first slide  -->
                                        <img alt="Images" class="img-fluid" src="/images/stock/small-8.jpg" />
                                        <div class="carousel-caption d-none d-md-block">
                                            <h4 class="fw-bold">First slide label</h4>
                                            <p>Some representative placeholder content for the first slide.</p>
                                        </div>
                                    </div>
                                    <div class="carousel-item" data-bs-interval="2000">
                                        <!-- secound slide  -->
                                        <img alt="Images" class="img-fluid" src="/images/stock/small-9.jpg" />
                                        <div class="carousel-caption d-none d-md-block">
                                            <h4 class="fw-bold">Second slide label</h4>
                                            <p>Some representative placeholder content for the second slide.</p>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <!-- third slide  -->
                                        <img alt="Images" class="img-fluid" src="/images/stock/small-10.jpg" />
                                        <div class="carousel-caption d-none d-md-block">
                                            <h4 class="fw-bold">Third slide label</h4>
                                            <p>Some representative placeholder content for the third slide.</p>
                                        </div>
                                    </div>
                                </div>
                                <button class="carousel-control-prev" data-bs-slide="prev"
                                    data-bs-target="#carouselExampleDark" type="button">
                                    <span aria-hidden="true" class="carousel-control-prev-icon"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" data-bs-slide="next"
                                    data-bs-target="#carouselExampleDark" type="button">
                                    <span aria-hidden="true" class="carousel-control-next-icon"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
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
                    <h5 class="card-title mb-0">Placeholders Variations</h5>
                    <div class="card-action">
                        <a class="card-action-item" data-action="card-toggle" href="#!"><i
                                class="ti ti-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">Examples </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border shadow-none mb-md-0">
                                        <!-- card-img-top -->
                                        <img alt="..." class="card-img-top" src="/images/stock/small-1.jpg" />
                                        <div class="card-body">
                                            <h5 class="card-title mb-2">Card Title</h5>
                                            <p class="card-text">Some quick example text to build on the card
                                                title and make up the bulk of the card's
                                                content.</p>
                                            <a class="btn btn-primary" href="#">Go somewhere</a>
                                        </div> <!-- end card-body-->
                                    </div> <!-- end card-->
                                </div> <!-- end col-->
                                <div class="col-md-6">
                                    <div aria-hidden="true" class="card border shadow-none mb-0">
                                        <svg aria-label="Placeholder" class="card-img-top"
                                            preserveaspectratio="xMidYMid slice" role="img"
                                            style="aspect-ratio: 16 / 10;" viewbox="0 0 16 10" width="100%"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <title>Placeholder</title>
                                            <rect fill="#b1b2f8" height="10" width="16"></rect>
                                        </svg>
                                        <div class="card-body">
                                            <h5 class="card-title mb-2 placeholder-glow">
                                                <span class="placeholder col-6"> </span>
                                            </h5>
                                            <p class="card-text placeholder-glow">
                                                <span class="placeholder col-7"></span>
                                                <span class="placeholder col-4"></span>
                                                <span class="placeholder col-4"></span>
                                                <span class="placeholder col-6"></span>
                                                <span class="placeholder col-3"></span>
                                            </p>
                                            <a aria-disabled="true" class="btn btn-primary disabled placeholder col-6">
                                                <span class="invisible">Read Only</span></a>
                                        </div> <!-- end card-body-->
                                    </div> <!-- end card-->
                                </div> <!-- end col-->
                            </div> <!-- end row-->
                        </div> <!-- end col-->
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">Color </h5>
                            <span class="placeholder col-12"></span>
                            <span class="placeholder col-12 bg-primary"></span>
                            <span class="placeholder col-12 bg-secondary"></span>
                            <span class="placeholder col-12 bg-success"></span>
                            <span class="placeholder col-12 bg-danger"></span>
                            <span class="placeholder col-12 bg-warning"></span>
                            <span class="placeholder col-12 bg-info"></span>
                            <span class="placeholder col-12 bg-light"></span>
                            <span class="placeholder col-12 bg-dark"></span>
                        </div> <!-- end col-->
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">Width</h5>
                            <span class="placeholder col-6"></span>
                            <span class="placeholder w-75"></span>
                            <span class="placeholder" style="width: 25%;"></span>
                            <span class="placeholder" style="width: 10%;"></span>
                        </div>
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">Sizing</h5>
                            <span class="placeholder col-12 placeholder-lg"></span>
                            <span class="placeholder col-12"></span>
                            <span class="placeholder col-12 placeholder-sm"></span>
                            <span class="placeholder col-12 placeholder-xs"></span>
                        </div> <!-- end col-->
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">How it works</h5>
                            <p aria-hidden="true">
                                <span class="placeholder col-6"></span>
                            </p>
                            <a aria-hidden="true" class="btn btn-primary disabled placeholder col-4" href="#"></a>
                        </div>
                        <div class="col-xl-6">
                            <h5 class="mb-2 pb-1">Animation</h5>
                            <p class="placeholder-glow">
                                <span class="placeholder col-12"></span>
                            </p>
                            <p class="placeholder-wave mb-0">
                                <span class="placeholder col-12"></span>
                            </p>
                        </div> <!-- end col-->
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end row -->
@endsection

@section('scripts')
@endsection
