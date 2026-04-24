@extends('layouts.vertical', ['title' => 'Interactive Components'])

@section('styles')
@endsection

@section('content')
@include('layouts.shared.page-title', [
'title' => 'Interactive Components',
'subTitle' =>
'Enhance user experience with dynamic Bootstrap elements like modals, collapses, tabs, tooltips, and more.',
'badgeIcon' => 'mouse-pointer-click',
'badgeTitle' => 'JS Powered UI',
])

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Collapse Variations</h5>
                <div class="card-action">
                    <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Collapse </h5>
                        <p>
                            <a aria-controls="collapseExample" aria-expanded="false" class="btn btn-primary"
                                data-bs-toggle="collapse" href="#collapseExample">
                                Link with href
                            </a>
                            <button aria-controls="collapseExample" aria-expanded="false" class="btn btn-primary ms-1"
                                data-bs-target="#collapseExample" data-bs-toggle="collapse" type="button">
                                Button with data-bs-target
                            </button>
                        </p>
                        <div class="collapse show" id="collapseExample">
                            <div class="card mb-0">
                                <div class="card-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life
                                    accusamus terry
                                    richardson ad squid. Nihil anim keffiyeh helvetica, craft beer
                                    labore wes
                                    anderson cred nesciunt sapiente ea proident.
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Collapse Horizontal </h5>
                        <p>
                            <button aria-controls="collapseWidthExample" aria-expanded="false" class="btn btn-primary"
                                data-bs-target="#collapseWidthExample" data-bs-toggle="collapse" type="button">
                                Toggle width collapse
                            </button>
                        </p>
                        <div style="min-height: 100px;">
                            <div class="collapse collapse-horizontal" id="collapseWidthExample">
                                <div class="card mb-0" style="width: 300px;">
                                    <div class="card-body">
                                        This is some placeholder content for a horizontal collapse. It's
                                        hidden by default and shown when triggered.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Multiple Targets</h5>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <a aria-controls="multiCollapseExample1" aria-expanded="false" class="btn btn-primary"
                                data-bs-toggle="collapse" href="#multiCollapseExample1" role="button">Toggle first
                                element</a>
                            <button aria-controls="multiCollapseExample2" aria-expanded="false" class="btn btn-primary"
                                data-bs-target="#multiCollapseExample2" data-bs-toggle="collapse" type="button">Toggle
                                second element</button>
                            <button aria-controls="multiCollapseExample1 multiCollapseExample2" aria-expanded="false"
                                class="btn btn-primary" data-bs-target=".multi-collapse" data-bs-toggle="collapse"
                                type="button">Toggle both elements</button>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseExample1">
                                    <div class="card mb-0">
                                        <div class="card-body">
                                            Anim pariatur cliche reprehenderit, enim eiusmod high life
                                            accusamus terry richardson ad squid. Nihil anim keffiyeh
                                            helvetica, craft beer labore wes anderson cred nesciunt sapiente
                                            ea proident.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="collapse multi-collapse" id="multiCollapseExample2">
                                    <div class="card mb-0">
                                        <div class="card-body">
                                            Anim pariatur cliche reprehenderit, enim eiusmod high life
                                            accusamus terry richardson ad squid. Nihil anim keffiyeh
                                            helvetica, craft beer labore wes anderson cred nesciunt sapiente
                                            ea proident.
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row-->
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
                <h5 class="card-title mb-0">Modals Variations</h5>
                <div class="card-action">
                    <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Bootstrap Modals </h5>
                        <!-- Standard modal content -->
                        <div aria-hidden="true" aria-labelledby="standard-modalLabel" class="modal fade"
                            id="standard-modal" role="dialog" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="standard-modalLabel">Modal Heading
                                        </h4>
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                            type="button"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h5>Text in a modal</h5>
                                        <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula.
                                        </p>
                                        <hr />
                                        <h5>Overflowing text to show scroll behavior</h5>
                                        <p>Cras mattis consectetur purus sit amet fermentum. Cras justo
                                            odio, dapibus ac facilisis in, egestas eget quam. Morbi leo
                                            risus, porta ac consectetur ac, vestibulum at eros.</p>
                                        <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur
                                            et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus
                                            dolor auctor.</p>
                                        <p class="mb-0">Aenean lacinia bibendum nulla sed consectetur.
                                            Praesent commodo cursus magna, vel scelerisque nisl consectetur
                                            et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor
                                            fringilla.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-light" data-bs-dismiss="modal"
                                            type="button">Close</button>
                                        <button class="btn btn-primary" type="button">Save
                                            changes</button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                        <!--  Modal content for the Large example -->
                        <div aria-hidden="true" aria-labelledby="myLargeModalLabel" class="modal fade"
                            id="bs-example-modal-lg" role="dialog" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                            type="button"></button>
                                    </div>
                                    <div class="modal-body">
                                        ...
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                        <div aria-hidden="true" aria-labelledby="mySmallModalLabel" class="modal fade"
                            id="bs-example-modal-sm" role="dialog" tabindex="-1">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="mySmallModalLabel">Small modal</h4>
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                            type="button"></button>
                                    </div>
                                    <div class="modal-body">
                                        ...
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                        <!-- Full width modal content -->
                        <div aria-hidden="true" aria-labelledby="fullWidthModalLabel" class="modal fade"
                            id="full-width-modal" role="dialog" tabindex="-1">
                            <div class="modal-dialog modal-full-width">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="fullWidthModalLabel">Modal Heading
                                        </h4>
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                            type="button"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h5>Text in a modal</h5>
                                        <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula.
                                        </p>
                                        <hr />
                                        <h5>Overflowing text to show scroll behavior</h5>
                                        <p>Cras mattis consectetur purus sit amet fermentum. Cras justo
                                            odio, dapibus ac facilisis in, egestas eget quam. Morbi leo
                                            risus, porta ac consectetur ac, vestibulum at eros.</p>
                                        <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur
                                            et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus
                                            dolor auctor.</p>
                                        <p class="mb-0">Aenean lacinia bibendum nulla sed consectetur.
                                            Praesent commodo cursus magna, vel scelerisque nisl consectetur
                                            et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor
                                            fringilla.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-light" data-bs-dismiss="modal"
                                            type="button">Close</button>
                                        <button class="btn btn-primary" type="button">Save
                                            changes</button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                        <!-- Long Content Scroll Modal -->
                        <div aria-hidden="true" aria-labelledby="scrollableModalTitle" class="modal fade"
                            id="scrollable-modal" role="dialog" tabindex="-1">
                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="scrollableModalTitle">Modal title</h5>
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                            type="button"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Cras mattis consectetur purus sit amet fermentum. Cras justo
                                            odio, dapibus ac facilisis in, egestas
                                            eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum
                                            at eros.</p>
                                        <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur
                                            et. Vivamus sagittis lacus vel augue
                                            laoreet rutrum faucibus dolor auctor.</p>
                                        <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo
                                            cursus magna, vel scelerisque nisl
                                            consectetur et. Donec sed odio dui. Donec ullamcorper nulla non
                                            metus auctor fringilla.</p>
                                        <p>Cras mattis consectetur purus sit amet fermentum. Cras justo
                                            odio, dapibus ac facilisis in, egestas
                                            eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum
                                            at eros.</p>
                                        <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur
                                            et. Vivamus sagittis lacus vel augue
                                            laoreet rutrum faucibus dolor auctor.</p>
                                        <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo
                                            cursus magna, vel scelerisque nisl
                                            consectetur et. Donec sed odio dui. Donec ullamcorper nulla non
                                            metus auctor fringilla.</p>
                                        <p>Cras mattis consectetur purus sit amet fermentum. Cras justo
                                            odio, dapibus ac facilisis in, egestas
                                            eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum
                                            at eros.</p>
                                        <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur
                                            et. Vivamus sagittis lacus vel augue
                                            laoreet rutrum faucibus dolor auctor.</p>
                                        <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo
                                            cursus magna, vel scelerisque nisl
                                            consectetur et. Donec sed odio dui. Donec ullamcorper nulla non
                                            metus auctor fringilla.</p>
                                        <p>Cras mattis consectetur purus sit amet fermentum. Cras justo
                                            odio, dapibus ac facilisis in, egestas
                                            eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum
                                            at eros.</p>
                                        <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur
                                            et. Vivamus sagittis lacus vel augue
                                            laoreet rutrum faucibus dolor auctor.</p>
                                        <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo
                                            cursus magna, vel scelerisque nisl
                                            consectetur et. Donec sed odio dui. Donec ullamcorper nulla non
                                            metus auctor fringilla.</p>
                                        <p>Cras mattis consectetur purus sit amet fermentum. Cras justo
                                            odio, dapibus ac facilisis in, egestas
                                            eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum
                                            at eros.</p>
                                        <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur
                                            et. Vivamus sagittis lacus vel augue
                                            laoreet rutrum faucibus dolor auctor.</p>
                                        <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo
                                            cursus magna, vel scelerisque nisl
                                            consectetur et. Donec sed odio dui. Donec ullamcorper nulla non
                                            metus auctor fringilla.</p>
                                        <p>Cras mattis consectetur purus sit amet fermentum. Cras justo
                                            odio, dapibus ac facilisis in, egestas
                                            eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum
                                            at eros.</p>
                                        <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur
                                            et. Vivamus sagittis lacus vel augue
                                            laoreet rutrum faucibus dolor auctor.</p>
                                        <p class="mb-0">Aenean lacinia bibendum nulla sed consectetur.
                                            Praesent commodo cursus magna, vel scelerisque nisl
                                            consectetur et. Donec sed odio dui. Donec ullamcorper nulla non
                                            metus auctor fringilla.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal"
                                            type="button">Close</button>
                                        <button class="btn btn-primary" type="button">Save
                                            changes</button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                        <div class="d-flex flex-wrap gap-2">
                            <!-- Standard  modal -->
                            <button class="btn btn-primary" data-bs-target="#standard-modal" data-bs-toggle="modal"
                                type="button">Standard Modal</button>
                            <!-- Large modal -->
                            <button class="btn btn-primary" data-bs-target="#bs-example-modal-lg" data-bs-toggle="modal"
                                type="button">Large Modal</button>
                            <!-- Small modal -->
                            <button class="btn btn-primary" data-bs-target="#bs-example-modal-sm" data-bs-toggle="modal"
                                type="button">Small Modal</button>
                            <!-- Full width modal -->
                            <button class="btn btn-primary" data-bs-target="#full-width-modal" data-bs-toggle="modal"
                                type="button">Full Width Modal</button>
                            <!-- Scrollable modal -->
                            <button class="btn btn-primary" data-bs-target="#scrollable-modal" data-bs-toggle="modal"
                                type="button">Scrollable Modal</button>
                        </div>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Modal Position</h5>
                        <!-- Top modal content -->
                        <div aria-hidden="true" class="modal fade" id="top-modal" role="dialog" tabindex="-1">
                            <div class="modal-dialog modal-top">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="topModalLabel">Modal Heading</h4>
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                            type="button"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h5 class="mt-0">Text in a modal</h5>
                                        <p class="mb-0">Duis mollis, est non commodo luctus, nisi erat
                                            porttitor ligula.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-light" data-bs-dismiss="modal"
                                            type="button">Close</button>
                                        <button class="btn btn-primary" type="button">Save
                                            changes</button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                        <!-- Bottom modal content -->
                        <div aria-hidden="true" class="modal fade" id="bottom-modal" role="dialog" tabindex="-1">
                            <div class="modal-dialog modal-bottom">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="bottomModalLabel">Modal Heading</h4>
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                            type="button"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h5 class="mt-0">Text in a modal</h5>
                                        <p class="mb-0">Duis mollis, est non commodo luctus, nisi erat
                                            porttitor ligula.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-light" data-bs-dismiss="modal"
                                            type="button">Close</button>
                                        <button class="btn btn-primary" type="button">Save
                                            changes</button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                        <!-- Center modal content -->
                        <div aria-hidden="true" class="modal fade" id="centermodal" role="dialog" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myCenterModalLabel">Center modal</h4>
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                            type="button"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h5 class="mt-0">Overflowing text to show scroll behavior</h5>
                                        <p>Cras mattis consectetur purus sit amet fermentum. Cras justo
                                            odio, dapibus ac facilisis in, egestas eget quam. Morbi leo
                                            risus, porta ac consectetur ac, vestibulum at eros.</p>
                                        <p class="mb-0">Praesent commodo cursus magna, vel scelerisque
                                            nisl consectetur et. Vivamus sagittis lacus vel augue laoreet
                                            rutrum faucibus dolor auctor.</p>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                        <div class="d-flex flex-wrap gap-2">
                            <!-- Top modal -->
                            <button class="btn btn-primary" data-bs-target="#top-modal" data-bs-toggle="modal"
                                type="button">Top Modal</button>
                            <!-- Bottom modal -->
                            <button class="btn btn-primary" data-bs-target="#bottom-modal" data-bs-toggle="modal"
                                type="button">Bottom Modal</button>
                            <!-- Center modal -->
                            <button class="btn btn-primary" data-bs-target="#centermodal" data-bs-toggle="modal"
                                type="button">Center modal</button>
                        </div>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Multiple Modal</h5>
                        <!-- Modal Heading -->
                        <div aria-hidden="true" aria-labelledby="multiple-oneModalLabel" class="modal fade"
                            id="multiple-one" role="dialog" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="multiple-oneModalLabel">Modal Heading
                                        </h4>
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                            type="button"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h5 class="mt-0">Text in a modal</h5>
                                        <p class="mb-0">Duis mollis, est non commodo luctus, nisi erat
                                            porttitor ligula.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary" data-bs-dismiss="modal"
                                            data-bs-target="#multiple-two" data-bs-toggle="modal"
                                            type="button">Next</button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                        <!-- Modal Heading -->
                        <div aria-hidden="true" aria-labelledby="multiple-twoModalLabel" class="modal fade"
                            id="multiple-two" role="dialog" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="multiple-twoModalLabel">Modal Heading
                                        </h4>
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                            type="button"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h5 class="mt-0">Text in a modal</h5>
                                        <p class="mb-0">Duis mollis, est non commodo luctus, nisi erat
                                            porttitor ligula.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary" data-bs-dismiss="modal"
                                            type="button">Close</button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                        <div class="d-flex flex-wrap gap-2">
                            <!-- Multiple modal -->
                            <button class="btn btn-primary" data-bs-target="#multiple-one" data-bs-toggle="modal"
                                type="button">Multiple Modal</button>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Toggle Between Modals</h5>
                        <!-- Modal 1-->
                        <div aria-hidden="true" aria-labelledby="exampleModalToggleLabel" class="modal fade"
                            id="exampleModalToggle" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalToggleLabel">Modal 1</h5>
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                            type="button"></button>
                                    </div>
                                    <div class="modal-body">
                                        Show a second modal and hide this one with the button below.
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary" data-bs-dismiss="modal"
                                            data-bs-target="#exampleModalToggle2" data-bs-toggle="modal">Open second
                                            modal</button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                        <!-- Modal 2-->
                        <div aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" class="modal fade"
                            id="exampleModalToggle2" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalToggleLabel2">Modal 2</h5>
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                            type="button"></button>
                                    </div>
                                    <div class="modal-body">
                                        Hide this modal and show the first with the button below.
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary" data-bs-dismiss="modal"
                                            data-bs-target="#exampleModalToggle" data-bs-toggle="modal">Back to
                                            first</button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                        <a class="btn btn-primary" data-bs-toggle="modal" href="#exampleModalToggle" role="button">Open
                            first modal</a>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Fullscreen Modal </h5>
                        <div class="hstack gap-2 flex-wrap">
                            <!-- Fullscreen Modal -->
                            <button class="btn btn-primary" data-bs-target="#fullscreeexampleModal"
                                data-bs-toggle="modal" type="button"> Fullscreen Modal</button>
                            <!-- Full Screen Below sm -->
                            <button class="btn btn-primary" data-bs-target="#exampleModalFullscreenSm"
                                data-bs-toggle="modal" type="button">Full Screen Below sm</button>
                            <!-- Full Screen Below md -->
                            <button class="btn btn-primary" data-bs-target="#exampleModalFullscreenMd"
                                data-bs-toggle="modal" type="button">Full Screen Below md</button>
                            <!-- Full Screen Below lg -->
                            <button class="btn btn-primary" data-bs-target="#exampleModalFullscreenLg"
                                data-bs-toggle="modal" type="button">Full Screen Below lg</button>
                            <!-- Full Screen Below xl -->
                            <button class="btn btn-primary" data-bs-target="#exampleModalFullscreenXl"
                                data-bs-toggle="modal" type="button">Full Screen Below xl</button>
                            <!-- Full Screen Below xxl -->
                            <button class="btn btn-primary" data-bs-target="#exampleModalFullscreenXxl"
                                data-bs-toggle="modal" type="button">Full Screen Below xxl</button>
                        </div>
                        <!-- Full Screen Modal -->
                        <div aria-hidden="true" aria-labelledby="fullscreeexampleModalLabel" class="modal fade"
                            id="fullscreeexampleModal" tabindex="-1">
                            <div class="modal-dialog modal-fullscreen">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="fullscreeexampleModalLabel">Full
                                            Screen Modal</h5>
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                            type="button"></button>
                                    </div>
                                    <div class="modal-body">
                                        ...
                                    </div>
                                    <div class="modal-footer">
                                        <a class="btn btn-light" data-bs-dismiss="modal"
                                            href="javascript:void(0);">Close</a>
                                        <button class="btn btn-primary" type="button">Save
                                            Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Full screen below sm -->
                        <div aria-hidden="true" aria-labelledby="exampleModalFullscreenSmLabel" class="modal fade"
                            id="exampleModalFullscreenSm" tabindex="-1">
                            <div class="modal-dialog modal-fullscreen-sm-down">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalFullscreenSmLabel">Full
                                            screen below sm</h5>
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                            type="button"></button>
                                    </div>
                                    <div class="modal-body">
                                        ...
                                    </div>
                                    <div class="modal-footer">
                                        <a class="btn btn-light" data-bs-dismiss="modal"
                                            href="javascript:void(0);">Close</a>
                                        <button class="btn btn-primary" type="button">Save
                                            Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Full screen below md -->
                        <div aria-hidden="true" aria-labelledby="exampleModalFullscreenMdLabel" class="modal fade"
                            id="exampleModalFullscreenMd" tabindex="-1">
                            <div class="modal-dialog modal-fullscreen-md-down">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalFullscreenMdLabel">Full
                                            screen below md</h5>
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                            type="button"></button>
                                    </div>
                                    <div class="modal-body">
                                        ...
                                    </div>
                                    <div class="modal-footer">
                                        <a class="btn btn-light" data-bs-dismiss="modal"
                                            href="javascript:void(0);">Close</a>
                                        <button class="btn btn-primary" type="button">Save
                                            Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Full screen below lg -->
                        <div aria-hidden="true" aria-labelledby="exampleModalFullscreenLgLabel" class="modal fade"
                            id="exampleModalFullscreenLg" tabindex="-1">
                            <div class="modal-dialog modal-fullscreen-lg-down">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalFullscreenLgLabel">Full
                                            screen below lg</h5>
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                            type="button"></button>
                                    </div>
                                    <div class="modal-body">
                                        ...
                                    </div>
                                    <div class="modal-footer">
                                        <a class="btn btn-light" data-bs-dismiss="modal"
                                            href="javascript:void(0);">Close</a>
                                        <button class="btn btn-primary" type="button">Save
                                            Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Full screen below xl -->
                        <div aria-hidden="true" aria-labelledby="exampleModalFullscreenXlLabel" class="modal fade"
                            id="exampleModalFullscreenXl" tabindex="-1">
                            <div class="modal-dialog modal-fullscreen-sm-down">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalFullscreenXlLabel">Full
                                            screen below xl</h5>
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                            type="button"></button>
                                    </div>
                                    <div class="modal-body">
                                        ...
                                    </div>
                                    <div class="modal-footer">
                                        <a class="btn btn-light" data-bs-dismiss="modal"
                                            href="javascript:void(0);">Close</a>
                                        <button class="btn btn-primary" type="button">Save
                                            Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Full screen below xxl -->
                        <div aria-hidden="true" aria-labelledby="exampleModalFullscreenXxlLabel" class="modal fade"
                            id="exampleModalFullscreenXxl" tabindex="-1">
                            <div class="modal-dialog modal-fullscreen-xxl-down">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalFullscreenXxlLabel">Full
                                            screen below xxl</h5>
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                            type="button"></button>
                                    </div>
                                    <div class="modal-body">
                                        ...
                                    </div>
                                    <div class="modal-footer">
                                        <a class="btn btn-light" data-bs-dismiss="modal"
                                            href="javascript:void(0);">Close</a>
                                        <button class="btn btn-primary" type="button">Save
                                            Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Static Backdrop</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <!-- Static Backdrop modal -->
                            <button class="btn btn-primary" data-bs-target="#staticBackdrop" data-bs-toggle="modal"
                                type="button">
                                Static Backdrop
                            </button>
                        </div> <!-- btn list -->
                        <!-- Modal -->
                        <div aria-hidden="true" aria-labelledby="staticBackdropLabel" class="modal fade"
                            data-bs-backdrop="static" data-bs-keyboard="false" id="staticBackdrop" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                            type="button"></button>
                                    </div> <!-- end modal header -->
                                    <div class="modal-body">
                                        <p class="m-0">I will not close if you click outside me. Don't
                                            even try to press escape key.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal"
                                            type="button">Close</button>
                                        <button class="btn btn-primary" type="button">Understood</button>
                                    </div> <!-- end modal footer -->
                                </div> <!-- end modal content-->
                            </div> <!-- end modal dialog-->
                        </div> <!-- end modal-->
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Varying Modal Content</h5>
                        <div class="hstack gap-2 flex-wrap">
                            <button class="btn btn-primary" data-bs-target="#exampleModal" data-bs-toggle="modal"
                                data-bs-whatever="@mdo" type="button">Open modal
                                for @mdo</button>
                            <button class="btn btn-primary" data-bs-target="#exampleModal" data-bs-toggle="modal"
                                data-bs-whatever="@fat" type="button">Open modal
                                for @fat</button>
                            <button class="btn btn-primary" data-bs-target="#exampleModal" data-bs-toggle="modal"
                                data-bs-whatever="@getbootstrap" type="button">Open modal for @getbootstrap</button>
                        </div>
                        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="exampleModal"
                            tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                            type="button"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form>
                                            <div class="mb-3">
                                                <label class="col-form-label" for="recipient-name">Recipient:</label>
                                                <input class="form-control" id="recipient-name" type="text" />
                                            </div>
                                            <div class="mb-3">
                                                <label class="col-form-label" for="message-text">Message:</label>
                                                <textarea class="form-control" id="message-text"></textarea>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal"
                                            type="button">Close</button>
                                        <button class="btn btn-primary" type="button">Send
                                            message</button>
                                    </div>
                                </div>
                            </div>
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
                <h5 class="card-title mb-0">Notifications Variations</h5>
                <div class="card-action">
                    <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div>
                            <h5 class="mb-2 pb-1">Basic</h5>
                            <div class="p-3">
                                <!-- Basic -->
                                <div aria-atomic="true" aria-live="assertive" class="toast fade show" role="alert">
                                    <div class="toast-header">
                                        <img alt="brand-logo" class="me-1" height="16" src="/images/logo-sm.png" />
                                        <strong class="me-auto text-body">SIMPLE</strong>
                                        <small>11 mins ago</small>
                                        <button aria-label="Close" class="ms-2 btn-close" data-bs-dismiss="toast"
                                            type="button"></button>
                                    </div>
                                    <div class="toast-body">
                                        Hello, world! This is a toast message.
                                    </div>
                                </div>
                                <!--end toast-->
                            </div>
                        </div>
                        <div class="mt-4">
                            <h5 class="mb-2 pb-1">Placement</h5>
                            <div class="p-3">
                                <div aria-atomic="true" aria-live="polite"
                                    class="d-flex justify-content-center align-items-center" style="min-height: 200px;">
                                    <!-- Then put toasts within -->
                                    <div aria-atomic="true" aria-live="assertive" class="toast fade show"
                                        data-bs-toggle="toast" role="alert">
                                        <div class="toast-header">
                                            <img alt="brand-logo" class="me-1" height="16" src="/images/logo-sm.png" />
                                            <strong class="me-auto text-body">SIMPLE</strong>
                                            <small>11 mins ago</small>
                                            <button aria-label="Close" class="ms-2 btn-close" data-bs-dismiss="toast"
                                                type="button"></button>
                                        </div>
                                        <div class="toast-body">
                                            Hello, world! This is a toast message.
                                        </div>
                                    </div>
                                    <!--end toast-->
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h5 class="mb-2 pb-1">Placement</h5>
                            <form>
                                <div class="mb-3">
                                    <label for="selectToastPlacement">Toast placement</label>
                                    <select class="form-select mt-2" id="selectToastPlacement">
                                        <option selected="" value="">Select a position...
                                        </option>
                                        <option value="top-0 start-0">Top left</option>
                                        <option value="top-0 start-50 translate-middle-x">Top center
                                        </option>
                                        <option value="top-0 end-0">Top right</option>
                                        <option value="top-50 start-0 translate-middle-y">Middle left
                                        </option>
                                        <option value="top-50 start-50 translate-middle">Middle center
                                        </option>
                                        <option value="top-50 end-0 translate-middle-y">Middle right
                                        </option>
                                        <option value="bottom-0 start-0">Bottom left</option>
                                        <option value="bottom-0 start-50 translate-middle-x">Bottom
                                            center</option>
                                        <option value="bottom-0 end-0">Bottom right</option>
                                    </select>
                                </div>
                            </form>
                            <div aria-atomic="true" aria-live="polite"
                                class="bg-light position-relative bd-example-toasts" style="min-height:294px">
                                <div class="toast-container position-absolute p-3" id="toastPlacement">
                                    <div class="toast show">
                                        <div class="toast-header">
                                            <img alt="brand-logo" class="me-1" height="16" src="/images/logo-sm.png" />
                                            <strong class="me-auto text-body">SIMPLE</strong>
                                            <small>11 mins ago</small>
                                        </div>
                                        <div class="toast-body">
                                            Hello, world! This is a toast message.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card-->
                    <div class="col-lg-6">
                        <div>
                            <h5 class="mb-2 pb-1">Live Toast</h5>
                            <button class="btn btn-primary" id="liveToastBtn" type="button">Show live
                                toast</button>
                            <div class="toast-container position-fixed top-0 end-0 p-3">
                                <div aria-atomic="true" aria-live="assertive" class="toast" id="liveToast" role="alert">
                                    <div class="toast-header">
                                        <img alt="brand-logo" class="me-1" height="16" src="/images/logo-sm.png" />
                                        <strong class="me-auto text-body">SIMPLE</strong>
                                        <small>11 mins ago</small>
                                        <button aria-label="Close" class="btn-close" data-bs-dismiss="toast"
                                            type="button"></button>
                                    </div>
                                    <div class="toast-body">
                                        Hello, world! This is a toast message.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h5 class="mb-2 pb-1">Translucent</h5>
                            <div class="p-3 bg-light bg-opacity-50">
                                <!-- Translucent -->
                                <div aria-atomic="true" aria-live="assertive" class="toast fade show" role="alert">
                                    <div class="toast-header">
                                        <img alt="brand-logo" class="me-1" height="16" src="/images/logo-sm.png" />
                                        <strong class="me-auto text-body">SIMPLE</strong>
                                        <small>11 mins ago</small>
                                        <button aria-label="Close" class="ms-2 btn-close" data-bs-dismiss="toast"
                                            type="button"></button>
                                    </div>
                                    <div class="toast-body">
                                        Hello, world! This is a toast message.
                                    </div>
                                </div>
                                <!--end toast-->
                            </div>
                        </div>
                        <div class="mt-4">
                            <h5 class="mb-2 pb-1">Stacking</h5>
                            <div class="p-3">
                                <div aria-atomic="true" aria-live="polite"
                                    style="position: relative; min-height: 200px;">
                                    <!-- Position it -->
                                    <div class="toast-container" style="position: absolute; top: 0; right: 0;">
                                        <!-- Then put toasts within -->
                                        <div aria-atomic="true" aria-live="assertive" class="toast fade show"
                                            role="alert">
                                            <div class="toast-header">
                                                <img alt="brand-logo" class="me-1" height="16"
                                                    src="/images/logo-sm.png" />
                                                <strong class="me-auto text-body">SIMPLE</strong>
                                                <small class="text-muted">just now</small>
                                                <button aria-label="Close" class="ms-2 btn-close"
                                                    data-bs-dismiss="toast" type="button"></button>
                                            </div>
                                            <div class="toast-body">
                                                See? Just like this.
                                            </div>
                                        </div>
                                        <!--end toast-->
                                        <div aria-atomic="true" aria-live="assertive" class="toast fade show"
                                            role="alert">
                                            <div class="toast-header">
                                                <img alt="brand-logo" class="me-1" height="16"
                                                    src="/images/logo-sm.png" />
                                                <strong class="me-auto text-body">SIMPLE</strong>
                                                <small class="text-muted">2 seconds ago</small>
                                                <button aria-label="Close" class="ms-2 btn-close"
                                                    data-bs-dismiss="toast" type="button"></button>
                                            </div>
                                            <div class="toast-body">
                                                Heads up, toasts will stack automatically
                                            </div>
                                        </div>
                                        <!--end toast-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h5 class="mb-2 pb-1">Custom content</h5>
                            <div aria-atomic="true" aria-live="assertive" class="toast show align-items-center mb-2"
                                role="alert">
                                <div class="d-flex">
                                    <div class="toast-body">
                                        Hello, world! This is a toast message.
                                    </div>
                                    <button aria-label="Close" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                                        type="button"></button>
                                </div>
                            </div>
                            <div aria-atomic="true" aria-live="assertive"
                                class="toast show align-items-center text-white bg-primary border-0 mb-2" role="alert">
                                <div class="d-flex">
                                    <div class="toast-body">
                                        Hello, world! This is a toast message.
                                    </div>
                                    <button aria-label="Close" class="btn-close btn-close-white me-2 m-auto"
                                        data-bs-dismiss="toast" type="button"></button>
                                </div>
                            </div>
                            <div aria-atomic="true" aria-live="assertive" class="toast show mb-2" role="alert">
                                <div class="toast-body">
                                    Hello, world! This is a toast message.
                                    <div class="mt-2 pt-2 border-top">
                                        <button class="btn btn-primary btn-sm" type="button">Take
                                            action</button>
                                        <button class="btn btn-secondary btn-sm" data-bs-dismiss="toast"
                                            type="button">Close</button>
                                    </div>
                                </div>
                            </div>
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
                <h5 class="card-title mb-0">Offcanvas Variations</h5>
                <div class="card-action">
                    <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Offcanvas </h5>
                        <div class="d-flex flex-wrap gap-2">
                            <a aria-controls="offcanvasExample" class="btn btn-primary" data-bs-toggle="offcanvas"
                                href="#offcanvasExample" role="button">
                                Link with href
                            </a>
                            <button aria-controls="offcanvasExample" class="btn btn-primary"
                                data-bs-target="#offcanvasExample" data-bs-toggle="offcanvas" type="button">
                                Button with data-bs-target
                            </button>
                        </div> <!-- end d-flex flex-wrap gap-2-->
                        <div aria-labelledby="offcanvasExampleLabel" class="offcanvas offcanvas-start"
                            id="offcanvasExample" tabindex="-1">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="offcanvasExampleLabel">Offcanvas</h5>
                                <button aria-label="Close" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                    type="button"></button>
                            </div> <!-- end offcanvas-header-->
                            <div class="offcanvas-body">
                                <div>
                                    Some text as placeholder. In real life you can have the elements you
                                    have chosen. Like, text,
                                    images, lists, etc.
                                </div>
                                <h5 class="mt-3">List</h5>
                                <ul class="ps-3">
                                    <li class="">Nemo enim ipsam voluptatem quia aspernatur</li>
                                    <li class="">Neque porro quisquam est, qui dolorem</li>
                                    <li class="">Quis autem vel eum iure qui in ea</li>
                                </ul>
                                <ul class="ps-3">
                                    <li class="">At vero eos et accusamus et iusto odio dignissimos
                                    </li>
                                    <li class="">Et harum quidem rerum facilis</li>
                                    <li class="">Temporibus autem quibusdam et aut officiis</li>
                                </ul>
                            </div> <!-- end offcanvas-body-->
                        </div> <!-- end offcanvas-->
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Offcanvas Backdrop </h5>
                        <div class="d-flex flex-wrap gap-2">
                            <!-- Enable body scrolling -->
                            <button aria-controls="offcanvasScrolling" class="btn btn-primary"
                                data-bs-target="#offcanvasScrolling" data-bs-toggle="offcanvas" type="button">Enable
                                body scrolling</button>
                            <!-- Enable backdrop (default) -->
                            <button aria-controls="offcanvasWithBackdrop" class="btn btn-primary"
                                data-bs-target="#offcanvasWithBackdrop" data-bs-toggle="offcanvas" type="button">Enable
                                backdrop (default)</button>
                            <!-- Enable both scrolling & backdrop -->
                            <button aria-controls="offcanvasWithBothOptions" class="btn btn-primary"
                                data-bs-target="#offcanvasWithBothOptions" data-bs-toggle="offcanvas"
                                type="button">Enable both scrolling &amp; backdrop</button>
                        </div> <!-- end d-flex flex-wrap gap-2-->
                        <div aria-labelledby="offcanvasScrollingLabel" class="offcanvas offcanvas-start"
                            data-bs-backdrop="false" data-bs-scroll="true" id="offcanvasScrolling" tabindex="-1">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Colored with
                                    scrolling</h5>
                                <button aria-label="Close" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                    type="button"></button>
                            </div> <!-- end offcanvas-header-->
                            <div class="offcanvas-body">
                                <div>
                                    Some text as placeholder. In real life you can have the elements you
                                    have chosen. Like, text,
                                    images, lists, etc.
                                </div>
                                <h5 class="mt-3">List</h5>
                                <ul class="ps-3">
                                    <li class="">Nemo enim ipsam voluptatem quia aspernatur</li>
                                    <li class="">Neque porro quisquam est, qui dolorem</li>
                                    <li class="">Quis autem vel eum iure qui in ea</li>
                                </ul>
                                <ul class="ps-3">
                                    <li class="">At vero eos et accusamus et iusto odio dignissimos
                                    </li>
                                    <li class="">Et harum quidem rerum facilis</li>
                                    <li class="">Temporibus autem quibusdam et aut officiis</li>
                                </ul>
                            </div> <!-- end offcanvas-body-->
                        </div> <!-- end offcanvas-->
                        <div aria-labelledby="offcanvasWithBackdropLabel" class="offcanvas offcanvas-start"
                            id="offcanvasWithBackdrop" tabindex="-1">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="offcanvasWithBackdropLabel">Offcanvas with
                                    backdrop</h5>
                                <button aria-label="Close" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                    type="button"></button>
                            </div> <!-- end offcanvas-header-->
                            <div class="offcanvas-body">
                                <div>
                                    Some text as placeholder. In real life you can have the elements you
                                    have chosen. Like, text,
                                    images, lists, etc.
                                </div>
                                <h5 class="mt-3">List</h5>
                                <ul class="ps-3">
                                    <li class="">Nemo enim ipsam voluptatem quia aspernatur</li>
                                    <li class="">Neque porro quisquam est, qui dolorem</li>
                                    <li class="">Quis autem vel eum iure qui in ea</li>
                                </ul>
                                <ul class="ps-3">
                                    <li class="">At vero eos et accusamus et iusto odio dignissimos
                                    </li>
                                    <li class="">Et harum quidem rerum facilis</li>
                                    <li class="">Temporibus autem quibusdam et aut officiis</li>
                                </ul>
                            </div> <!-- end offcanvas-body-->
                        </div> <!-- end offcanvas-->
                        <div aria-labelledby="offcanvasWithBothOptionsLabel" class="offcanvas offcanvas-start"
                            data-bs-scroll="true" id="offcanvasWithBothOptions" tabindex="-1">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Backdroped
                                    with scrolling</h5>
                                <button aria-label="Close" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                    type="button"></button>
                            </div> <!-- end offcanvas-header-->
                            <div class="offcanvas-body">
                                <div>
                                    Some text as placeholder. In real life you can have the elements you
                                    have chosen. Like, text,
                                    images, lists, etc.
                                </div>
                                <h5 class="mt-3">List</h5>
                                <ul class="ps-3">
                                    <li class="">Nemo enim ipsam voluptatem quia aspernatur</li>
                                    <li class="">Neque porro quisquam est, qui dolorem</li>
                                    <li class="">Quis autem vel eum iure qui in ea</li>
                                </ul>
                                <ul class="ps-3">
                                    <li class="">At vero eos et accusamus et iusto odio dignissimos
                                    </li>
                                    <li class="">Et harum quidem rerum facilis</li>
                                    <li class="">Temporibus autem quibusdam et aut officiis</li>
                                </ul>
                            </div> <!-- end offcanvas-body-->
                        </div> <!-- end offcanvas-->
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Offcanvas Placement</h5>
                        <div>
                            <div class="d-flex flex-wrap gap-2">
                                <!-- Toggle Top offcanvas -->
                                <button aria-controls="offcanvasTop" class="btn btn-primary"
                                    data-bs-target="#offcanvasTop" data-bs-toggle="offcanvas" type="button">Toggle
                                    Top offcanvas</button>
                                <!-- Toggle right offcanvas -->
                                <button aria-controls="offcanvasRight" class="btn btn-primary"
                                    data-bs-target="#offcanvasRight" data-bs-toggle="offcanvas" type="button">Toggle
                                    right offcanvas</button>
                                <!-- Toggle bottom offcanvas -->
                                <button aria-controls="offcanvasBottom" class="btn btn-primary"
                                    data-bs-target="#offcanvasBottom" data-bs-toggle="offcanvas" type="button">Toggle
                                    bottom offcanvas</button>
                                <!-- Toggle Left offcanvas -->
                                <button aria-controls="offcanvasLeft" class="btn btn-primary mt-2 mt-lg-0"
                                    data-bs-target="#offcanvasLeft" data-bs-toggle="offcanvas" type="button">Toggle
                                    Left offcanvas</button>
                            </div> <!-- end d-flex flex-wrap gap-2-->
                            <div aria-labelledby="offcanvasTopLabel" class="offcanvas offcanvas-top" id="offcanvasTop"
                                tabindex="-1">
                                <div class="offcanvas-header">
                                    <h5 id="offcanvasTopLabel">Offcanvas Top</h5>
                                    <button aria-label="Close" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                        type="button"></button>
                                </div> <!-- end offcanvas-header-->
                                <div class="offcanvas-body">
                                    <div>
                                        Some text as placeholder. In real life you can have the elements you
                                        have chosen. Like, text,
                                        images, lists, etc.
                                    </div>
                                    <h5 class="mt-3">List</h5>
                                    <ul class="ps-3">
                                        <li class="">Nemo enim ipsam voluptatem quia aspernatur</li>
                                        <li class="">Neque porro quisquam est, qui dolorem</li>
                                        <li class="">Quis autem vel eum iure qui in ea</li>
                                    </ul>
                                </div> <!-- end offcanvas-body-->
                            </div> <!-- end offcanvas-->
                            <div aria-labelledby="offcanvasRightLabel" class="offcanvas offcanvas-end"
                                id="offcanvasRight" tabindex="-1">
                                <div class="offcanvas-header">
                                    <h5 id="offcanvasRightLabel">Offcanvas right</h5>
                                    <button aria-label="Close" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                        type="button"></button>
                                </div> <!-- end offcanvas-header-->
                                <div class="offcanvas-body">
                                    <div>
                                        Some text as placeholder. In real life you can have the elements you
                                        have chosen. Like, text,
                                        images, lists, etc.
                                    </div>
                                    <h5 class="mt-3">List</h5>
                                    <ul class="ps-3">
                                        <li class="">Nemo enim ipsam voluptatem quia aspernatur</li>
                                        <li class="">Neque porro quisquam est, qui dolorem</li>
                                        <li class="">Quis autem vel eum iure qui in ea</li>
                                    </ul>
                                </div> <!-- end offcanvas-body-->
                            </div> <!-- end offcanvas-->
                            <div aria-labelledby="offcanvasBottomLabel" class="offcanvas offcanvas-bottom"
                                id="offcanvasBottom" tabindex="-1">
                                <div class="offcanvas-header">
                                    <h5 class="offcanvas-title" id="offcanvasBottomLabel">Offcanvas
                                        bottom</h5>
                                    <button aria-label="Close" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                        type="button"></button>
                                </div> <!-- end offcanvas-header-->
                                <div class="offcanvas-body">
                                    <div>
                                        Some text as placeholder. In real life you can have the elements you
                                        have chosen. Like, text,
                                        images, lists, etc.
                                    </div>
                                    <h5 class="mt-3">List</h5>
                                    <ul class="ps-3">
                                        <li class="">Nemo enim ipsam voluptatem quia aspernatur</li>
                                        <li class="">Neque porro quisquam est, qui dolorem</li>
                                        <li class="">Quis autem vel eum iure qui in ea</li>
                                    </ul>
                                </div> <!-- end offcanvas-body-->
                            </div> <!-- end offcanvas-->
                            <div aria-labelledby="offcanvasLeftLabel" class="offcanvas offcanvas-start"
                                id="offcanvasLeft" tabindex="-1">
                                <div class="offcanvas-header">
                                    <h5 id="offcanvasLeftLabel">Offcanvas Left</h5>
                                    <button aria-label="Close" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                        type="button"></button>
                                </div> <!-- end offcanvas-header-->
                                <div class="offcanvas-body">
                                    <div>
                                        Some text as placeholder. In real life you can have the elements you
                                        have chosen. Like, text,
                                        images, lists, etc.
                                    </div>
                                    <h5 class="mt-3">List</h5>
                                    <ul class="ps-3">
                                        <li class="">Nemo enim ipsam voluptatem quia aspernatur</li>
                                        <li class="">Neque porro quisquam est, qui dolorem</li>
                                        <li class="">Quis autem vel eum iure qui in ea</li>
                                    </ul>
                                </div> <!-- end offcanvas-body-->
                            </div> <!-- end offcanvas-->
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Dark Offcanvas</h5>
                        <button aria-controls="offcanvasDark" class="btn btn-primary" data-bs-target="#offcanvasDark"
                            data-bs-toggle="offcanvas" type="button">Dark
                            offcanvas</button>
                        <div aria-labelledby="offcanvasDarkLabel" class="offcanvas offcanvas-start text-bg-dark"
                            id="offcanvasDark" tabindex="-1">
                            <div class="offcanvas-header">
                                <h5 id="offcanvasDarkLabel">Dark Offcanvas</h5>
                                <button aria-label="Close" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                                    type="button"></button>
                            </div> <!-- end offcanvas-header-->
                            <div class="offcanvas-body">
                                <div>
                                    Some text as placeholder. In real life you can have the elements you
                                    have chosen. Like, text,
                                    images, lists, etc.
                                </div>
                                <h5 class="mt-3">List</h5>
                                <ul class="ps-3">
                                    <li class="">Nemo enim ipsam voluptatem quia aspernatur</li>
                                    <li class="">Neque porro quisquam est, qui dolorem</li>
                                    <li class="">Quis autem vel eum iure qui in ea</li>
                                </ul>
                            </div> <!-- end offcanvas-body-->
                        </div> <!-- end offcanvas-->
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
                <h5 class="card-title mb-0">Popovers Variations</h5>
                <div class="card-action">
                    <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Simple Popover</h5>
                        <button class="btn btn-primary"
                            data-bs-content="Click here to get support from our team. We're here 24/7 to assist you."
                            data-bs-toggle="popover" title="Need Help?" type="button">
                            Get Support Info
                        </button>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Dismiss on Next Click </h5>
                        <button class="btn btn-primary"
                            data-bs-content="Get quick tips and tricks to improve your workflow instantly."
                            data-bs-toggle="popover" data-bs-trigger="focus" tabindex="0" title="Quick Tips"
                            type="button">
                            Show Tips
                        </button>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Hover</h5>
                        <button class="btn btn-dark"
                            data-bs-content="Discover features you didn’t know existed. Hover to explore more!"
                            data-bs-toggle="popover" data-bs-trigger="hover" tabindex="0" title="Exciting Features!"
                            type="button">
                            Please Hover Me
                        </button>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Four Directions</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <!-- Popover on top -->
                            <button class="btn btn-primary"
                                data-bs-content="This popover appears above the button. Great for tips or info."
                                data-bs-placement="top" data-bs-toggle="popover" title="Top Popover" type="button">
                                Popover on top
                            </button>
                            <!-- Popover on bottom -->
                            <button class="btn btn-primary"
                                data-bs-content="This popover shows below. Perfect for additional details."
                                data-bs-placement="bottom" data-bs-toggle="popover" title="Bottom Popover"
                                type="button">
                                Popover on bottom
                            </button>
                            <!-- Popover on right -->
                            <button class="btn btn-primary"
                                data-bs-content="Slide in from the right to provide quick insights."
                                data-bs-placement="right" data-bs-toggle="popover" title="Right Popover" type="button">
                                Popover on right
                            </button>
                            <!-- Popover on left -->
                            <button class="btn btn-primary"
                                data-bs-content="Appears on the left side. Great for tooltips or notes."
                                data-bs-placement="left" data-bs-toggle="popover" title="Left Popover" type="button">
                                Popover on left
                            </button>
                        </div>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Custom Popovers </h5>
                        <div class="d-flex flex-wrap gap-2">
                            <!-- Primary Popover -->
                            <button class="btn btn-primary"
                                data-bs-content="This is a primary-themed popover styled using CSS variables."
                                data-bs-custom-class="popover-primary" data-bs-placement="right"
                                data-bs-title="Primary Popover" data-bs-toggle="popover" type="button">
                                Primary Popover
                            </button>
                            <!-- Success Popover -->
                            <button class="btn btn-success"
                                data-bs-content="This is a success-themed popover styled using CSS variables."
                                data-bs-custom-class="popover-success" data-bs-placement="right"
                                data-bs-title="Success Popover" data-bs-toggle="popover" type="button">
                                Success Popover
                            </button>
                            <!-- Danger Popover -->
                            <button class="btn btn-danger"
                                data-bs-content="This is a danger-themed popover styled using CSS variables."
                                data-bs-custom-class="popover-danger" data-bs-placement="right"
                                data-bs-title="Danger Popover" data-bs-toggle="popover" type="button">
                                Danger Popover
                            </button>
                            <!-- Info Popover -->
                            <button class="btn btn-info"
                                data-bs-content="This is an info-themed popover styled using CSS variables."
                                data-bs-custom-class="popover-info" data-bs-placement="right"
                                data-bs-title="Info Popover" data-bs-toggle="popover" type="button">
                                Info Popover
                            </button>
                            <!-- Dark Popover -->
                            <button class="btn btn-dark"
                                data-bs-content="This is a dark-themed popover styled using CSS variables."
                                data-bs-custom-class="popover-dark" data-bs-placement="right"
                                data-bs-title="Dark Popover" data-bs-toggle="popover" type="button">
                                Dark Popover
                            </button>
                            <!-- Secondary Popover -->
                            <button class="btn btn-secondary"
                                data-bs-content="This is a secondary-themed popover styled using CSS variables."
                                data-bs-custom-class="popover-secondary" data-bs-placement="right"
                                data-bs-title="Secondary Popover" data-bs-toggle="popover" type="button">
                                Secondary Popover
                            </button>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Disabled Elements</h5>
                        <span class="d-inline-block"
                            data-bs-content="This button is disabled, but the popover still works."
                            data-bs-placement="top" data-bs-toggle="popover">
                            <button class="btn btn-primary" disabled="" style="pointer-events: none;" type="button">
                                Disabled Button
                            </button>
                        </span>
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
                <h5 class="card-title mb-0">Tabs Variations</h5>
                <div class="card-action">
                    <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Default Tabs </h5>
                        <ul class="nav nav-tabs mb-3">
                            <li class="nav-item">
                                <a aria-expanded="false" class="nav-link" data-bs-toggle="tab" href="#overview">
                                    Overview
                                </a>
                            </li>
                            <li class="nav-item">
                                <a aria-expanded="true" class="nav-link active" data-bs-toggle="tab" href="#activity">
                                    Activity
                                </a>
                            </li>
                            <li class="nav-item">
                                <a aria-expanded="false" class="nav-link" data-bs-toggle="tab" href="#settings">
                                    Settings
                                </a>
                            </li>
                            <li class="nav-item">
                                <a aria-expanded="false" class="nav-link disabled" data-bs-toggle="tab" href="#">
                                    Disabled
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane" id="overview">
                                <p class="mb-0">
                                    This dashboard provides a quick overview of your recent activity,
                                    performance metrics, and system status.
                                    You can easily monitor key indicators, recent logins, pending tasks, and
                                    overall user engagement.
                                </p>
                            </div>
                            <div class="tab-pane show active" id="activity">
                                <p class="mb-0">
                                    View your latest interactions and actions taken across the platform.
                                    This includes recent file uploads,
                                    comments, status updates, and notification history to keep you up to
                                    date with ongoing changes.
                                </p>
                            </div>
                            <div class="tab-pane" id="settings">
                                <p class="mb-0">
                                    Customize your account preferences including theme options, notification
                                    settings, and privacy controls.
                                    Adjust layout configurations to suit your workflow and manage
                                    integration with third-party services.
                                </p>
                            </div>
                        </div>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Tabs Justified </h5>

                        <ul class="nav nav-justified nav-tabs mb-3">
                            <li class="nav-item">
                                <a aria-expanded="false" class="nav-link" data-bs-toggle="tab" href="#overview1">
                                    Overview
                                </a>
                            </li>
                            <li class="nav-item">
                                <a aria-expanded="true" class="nav-link active" data-bs-toggle="tab" href="#profile1">
                                    Profile
                                </a>
                            </li>
                            <li class="nav-item">
                                <a aria-expanded="false" class="nav-link" data-bs-toggle="tab" href="#settings1">
                                    Settings
                                </a>
                            </li>
                            <li class="nav-item">
                                <a aria-expanded="false" class="nav-link" data-bs-toggle="tab" href="#projects1">
                                    Projects
                                </a>
                            </li>
                            <li class="nav-item">
                                <a aria-expanded="false" class="nav-link" data-bs-toggle="tab" href="#support1">
                                    Support
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane" id="overview1">
                                <p class="mb-0">
                                    Get a high-level summary of recent activity, key performance indicators,
                                    and important announcements.
                                    Stay informed and make quick decisions based on real-time insights.
                                </p>
                            </div>
                            <div class="tab-pane show active" id="profile1">
                                <p class="mb-0">
                                    Customize your profile, update personal information, and manage security
                                    settings like passwords and 2FA.
                                    Keep your account secure and up to date with your latest details.
                                </p>
                            </div>
                            <div class="tab-pane" id="settings1">
                                <p class="mb-0">
                                    Configure system preferences, theme options, and notification settings.
                                    Easily adapt the platform to fit
                                    your workflow and preferences.
                                </p>
                            </div>
                            <div class="tab-pane" id="projects1">
                                <p class="mb-0">
                                    View and manage all ongoing projects, tasks, and milestones. Collaborate
                                    with your team and track progress
                                    in real-time.
                                </p>
                            </div>
                            <div class="tab-pane" id="support1">
                                <p class="mb-0">
                                    Need help? Reach out to our support team or browse the help center for
                                    common questions, guides, and documentation.
                                </p>
                            </div>
                        </div>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Tabs Vertical Left </h5>
                        <div class="row">
                            <div class="col-sm-3 mb-2 mb-sm-0">
                                <div aria-orientation="vertical" class="nav flex-column nav-pills" id="v-pills-tab1"
                                    role="tablist">
                                    <a aria-controls="v-pills-home" aria-selected="true"
                                        class="nav-link fw-semibold active show" data-bs-toggle="pill"
                                        href="#v-pills-home" id="v-pills-home-tab" role="tab">
                                        Overview
                                    </a>
                                    <a aria-controls="v-pills-profile" aria-selected="false"
                                        class="nav-link fw-semibold" data-bs-toggle="pill" href="#v-pills-profile"
                                        id="v-pills-profile-tab" role="tab">
                                        Profile
                                    </a>
                                    <a aria-controls="v-pills-settings" aria-selected="false"
                                        class="nav-link fw-semibold" data-bs-toggle="pill" href="#v-pills-settings"
                                        id="v-pills-settings-tab" role="tab">
                                        Settings
                                    </a>
                                    <a aria-controls="v-pills-projects" aria-selected="false"
                                        class="nav-link fw-semibold" data-bs-toggle="pill" href="#v-pills-projects"
                                        id="v-pills-projects-tab" role="tab">
                                        Projects
                                    </a>
                                    <a aria-controls="v-pills-support" aria-selected="false"
                                        class="nav-link fw-semibold" data-bs-toggle="pill" href="#v-pills-support"
                                        id="v-pills-support-tab" role="tab">
                                        Support
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <div class="tab-content" id="v-pills-tabContent">
                                    <!-- Overview Tab -->
                                    <div aria-labelledby="v-pills-home-tab" class="tab-pane fade active show"
                                        id="v-pills-home" role="tabpanel">
                                        <p class="mb-2">Welcome to your dashboard. Get an at-a-glance
                                            view of your recent activity, top stats, and personalized
                                            suggestions to enhance productivity and stay on track.</p>
                                        <ul>
                                            <li>View total project status</li>
                                            <li>Quick links to recent files</li>
                                            <li>Weekly performance charts</li>
                                        </ul>
                                        <p class="mb-0">Your dashboard is tailored to your activity and
                                            roles. Stay informed and always one step ahead.</p>
                                    </div>
                                    <!-- Profile Tab -->
                                    <div aria-labelledby="v-pills-profile-tab" class="tab-pane fade"
                                        id="v-pills-profile" role="tabpanel">
                                        <p class="mb-2">Manage your personal details, change your
                                            profile photo, and update your contact information.</p>
                                        <ul>
                                            <li>Name, Email, Phone</li>
                                            <li>Change Password</li>
                                            <li>Activity logs and preferences</li>
                                        </ul>
                                        <p class="mb-0">Keeping your profile up to date ensures a better
                                            and more secure experience.</p>
                                    </div>
                                    <!-- Settings Tab -->
                                    <div aria-labelledby="v-pills-settings-tab" class="tab-pane fade"
                                        id="v-pills-settings" role="tabpanel">
                                        <p class="mb-2">Customize your preferences, notification
                                            options, and privacy settings.</p>
                                        <ul>
                                            <li>Theme selection: Light / Dark mode</li>
                                            <li>Email &amp; push notification toggles</li>
                                            <li>Linked accounts and integrations</li>
                                        </ul>
                                        <p class="mb-0">Settings help personalize your interface and
                                            improve your workflow efficiency.</p>
                                    </div>
                                    <!-- Projects Tab -->
                                    <div aria-labelledby="v-pills-projects-tab" class="tab-pane fade"
                                        id="v-pills-projects" role="tabpanel">
                                        <p class="mb-2">Track all your active, completed, and upcoming
                                            projects in one place.</p>
                                        <ul>
                                            <li>Kanban board and Gantt charts</li>
                                            <li>Task assignments and deadlines</li>
                                            <li>Progress indicators and timelines</li>
                                        </ul>
                                        <p class="mb-0">Use collaboration tools, upload documents, and
                                            manage deliverables directly from here.</p>
                                    </div>
                                    <!-- Support Tab -->
                                    <div aria-labelledby="v-pills-support-tab" class="tab-pane fade"
                                        id="v-pills-support" role="tabpanel">
                                        <p class="mb-2">Need assistance? Access our help center or
                                            contact our support team directly.</p>
                                        <ul>
                                            <li>Browse FAQs and tutorials</li>
                                            <li>Submit a support ticket</li>
                                            <li>Live chat with support agents</li>
                                        </ul>
                                        <p class="mb-0">We’re here 24/7 to assist you with anything you
                                            need—technical or account-related.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Tabs with Colored Navs</h5>
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="tab-content" id="v-pills-tabContent1">
                                    <!-- Overview Tab -->
                                    <div aria-labelledby="v-pills-home-tab" class="tab-pane fade active show"
                                        id="v-pills-home-right" role="tabpanel">
                                        <p class="mb-2">Welcome to your dashboard. Get an at-a-glance
                                            view of your recent activity, top stats, and personalized
                                            suggestions to enhance productivity and stay on track.</p>
                                        <ul>
                                            <li>View total project status</li>
                                            <li>Quick links to recent files</li>
                                            <li>Weekly performance charts</li>
                                        </ul>
                                        <p class="mb-0">Your dashboard is tailored to your activity and
                                            roles. Stay informed and always one step ahead.</p>
                                    </div>
                                    <!-- Profile Tab -->
                                    <div aria-labelledby="v-pills-profile-tab" class="tab-pane fade"
                                        id="v-pills-profile-right" role="tabpanel">
                                        <p class="mb-2">Manage your personal details, change your
                                            profile photo, and update your contact information.</p>
                                        <ul>
                                            <li>Name, Email, Phone</li>
                                            <li>Change Password</li>
                                            <li>Activity logs and preferences</li>
                                        </ul>
                                        <p class="mb-0">Keeping your profile up to date ensures a better
                                            and more secure experience.</p>
                                    </div>
                                    <!-- Settings Tab -->
                                    <div aria-labelledby="v-pills-settings-tab" class="tab-pane fade"
                                        id="v-pills-settings-right" role="tabpanel">
                                        <p class="mb-2">Customize your preferences, notification
                                            options, and privacy settings.</p>
                                        <ul>
                                            <li>Theme selection: Light / Dark mode</li>
                                            <li>Email &amp; push notification toggles</li>
                                            <li>Linked accounts and integrations</li>
                                        </ul>
                                        <p class="mb-0">Settings help personalize your interface and
                                            improve your workflow efficiency.</p>
                                    </div>
                                    <!-- Projects Tab -->
                                    <div aria-labelledby="v-pills-projects-tab" class="tab-pane fade"
                                        id="v-pills-projects-right" role="tabpanel">
                                        <p class="mb-2">Track all your active, completed, and upcoming
                                            projects in one place.</p>
                                        <ul>
                                            <li>Kanban board and Gantt charts</li>
                                            <li>Task assignments and deadlines</li>
                                            <li>Progress indicators and timelines</li>
                                        </ul>
                                        <p class="mb-0">Use collaboration tools, upload documents, and
                                            manage deliverables directly from here.</p>
                                    </div>
                                    <!-- Support Tab -->
                                    <div aria-labelledby="v-pills-support-tab" class="tab-pane fade"
                                        id="v-pills-support-right" role="tabpanel">
                                        <p class="mb-2">Need assistance? Access our help center or
                                            contact our support team directly.</p>
                                        <ul>
                                            <li>Browse FAQs and tutorials</li>
                                            <li>Submit a support ticket</li>
                                            <li>Live chat with support agents</li>
                                        </ul>
                                        <p class="mb-0">We’re here 24/7 to assist you with anything you
                                            need—technical or account-related.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 mt-2 mt-sm-0">
                                <div aria-orientation="vertical" class="nav flex-column nav-pills nav-pills-secondary"
                                    id="v-pills-tab" role="tablist">
                                    <a aria-controls="v-pills-home" aria-selected="true"
                                        class="nav-link fw-semibold active show" data-bs-toggle="pill"
                                        href="#v-pills-home-right" id="v-pills-home-tab-right" role="tab">
                                        Overview
                                    </a>
                                    <a aria-controls="v-pills-profile" aria-selected="false"
                                        class="nav-link fw-semibold" data-bs-toggle="pill" href="#v-pills-profile-right"
                                        id="v-pills-profile-tab-right" role="tab">
                                        Profile
                                    </a>
                                    <a aria-controls="v-pills-settings" aria-selected="false"
                                        class="nav-link fw-semibold" data-bs-toggle="pill"
                                        href="#v-pills-settings-right" id="v-pills-settings-tab-right" role="tab">
                                        Settings
                                    </a>
                                    <a aria-controls="v-pills-projects" aria-selected="false"
                                        class="nav-link fw-semibold" data-bs-toggle="pill"
                                        href="#v-pills-projects-right" id="v-pills-projects-tab-right" role="tab">
                                        Projects
                                    </a>
                                    <a aria-controls="v-pills-support" aria-selected="false"
                                        class="nav-link fw-semibold" data-bs-toggle="pill" href="#v-pills-support-right"
                                        id="v-pills-support-tab-right" role="tab">
                                        Support
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Tabs Bordered </h5>
                        <ul class="nav nav-tabs nav-bordered mb-3">
                            <li class="nav-item">
                                <a aria-expanded="false" class="nav-link" data-bs-toggle="tab" href="#home-b1">
                                    Home
                                </a>
                            </li>
                            <li class="nav-item">
                                <a aria-expanded="true" class="nav-link active" data-bs-toggle="tab" href="#profile-b1">
                                    Profile
                                </a>
                            </li>
                            <li class="nav-item">
                                <a aria-expanded="false" class="nav-link" data-bs-toggle="tab" href="#settings-b1">
                                    Settings
                                </a>
                            </li>
                            <li class="nav-item">
                                <a aria-expanded="false" class="nav-link" data-bs-toggle="tab" href="#about-b1">
                                    About
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane" id="home-b1">
                                <p class="mb-0">Welcome to our online platform! Here, we strive to offer
                                    the best products and services tailored to your lifestyle. Whether
                                    you're redecorating your home or looking for expert advice on the latest
                                    trends, we've got you covered.</p>
                            </div>
                            <div class="tab-pane show active" id="profile-b1">
                                <p class="mb-0">Hi! I am an avid explorer, constantly seeking new
                                    adventures and insights. My passions include technology, literature,
                                    travel, fitness, and self-development. I enjoy learning new skills and
                                    sharing knowledge with others to foster personal growth.</p>
                            </div>
                            <div class="tab-pane" id="settings-b1">
                                <p class="mb-0">Nestled in the heart of the city, a charming cafe offers
                                    a peaceful retreat from the urban hustle. Its inviting ambiance, with
                                    its cozy decor and warm lighting, provides the perfect setting for
                                    relaxation or a productive meeting.</p>
                            </div>
                            <div class="tab-pane" id="about-b1">
                                <p class="mb-0">Our company is dedicated to offering high-quality
                                    services and products designed to enrich your life. With a focus on
                                    sustainability and innovation, we aim to create lasting value for our
                                    customers. Join us on our journey to make everyday living better!</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Bordered Tabs with Colored Border</h5>
                        <ul class="nav nav-tabs nav-justified nav-bordered nav-bordered-danger mb-3">
                            <li class="nav-item">
                                <a aria-expanded="false" class="nav-link" data-bs-toggle="tab" href="#home-b2">
                                    <i class="ti ti-home fs-lg me-md-1 align-middle"></i>
                                    <span class="d-none d-md-inline-block align-middle">Home</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a aria-expanded="true" class="nav-link active" data-bs-toggle="tab" href="#profile-b2">
                                    <i class="ti ti-user-circle fs-lg me-md-1 align-middle"></i>
                                    <span class="d-none d-md-inline-block align-middle">Profile</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a aria-expanded="false" class="nav-link" data-bs-toggle="tab" href="#settings-b2">
                                    <i class="ti ti-settings fs-lg me-md-1 align-middle"></i>
                                    <span class="d-none d-md-inline-block align-middle">Settings</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a aria-expanded="false" class="nav-link" data-bs-toggle="tab" href="#about-b2">
                                    <i class="ti ti-info-circle fs-lg me-md-1 align-middle"></i>
                                    <span class="d-none d-md-inline-block align-middle">About</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane" id="home-b2">
                                <p class="mb-0">Welcome to our online platform! Our goal is to offer a
                                    wide variety of products and services that meet the needs of modern
                                    living. From cutting-edge technology to home decor solutions, we ensure
                                    that every product enhances your lifestyle and makes your life easier.
                                </p>
                            </div>
                            <div class="tab-pane show active" id="profile-b2">
                                <p class="mb-0">Hi there! I'm an avid explorer with a passion for
                                    technology, fitness, and continuous learning. I enjoy meeting
                                    like-minded individuals and believe in expanding my knowledge on diverse
                                    subjects, from the latest gadgets to personal development.</p>
                            </div>
                            <div class="tab-pane" id="settings-b2">
                                <p class="mb-0">In the center of the city stands a quiet, charming
                                    bookstore that offers a peaceful retreat. Surrounded by vibrant streets,
                                    it provides a calm, inviting atmosphere for readers to lose themselves
                                    in books while enjoying a cup of coffee in the cozy corner.</p>
                            </div>
                            <div class="tab-pane" id="about-b2">
                                <p class="mb-0">We are a forward-thinking company focused on creating
                                    innovative solutions that empower our customers. Our team is driven by
                                    creativity and a passion for delivering exceptional experiences through
                                    high-quality products and services that cater to a variety of needs.</p>
                            </div>
                        </div>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Icons Tabs</h5>
                        <ul class="nav nav-tabs nav-bordered nav-bordered-success mb-3">
                            <li class="nav-item">
                                <a aria-expanded="false" class="nav-link" data-bs-toggle="tab" href="#home-i1">
                                    <i class="ti ti-home fs-22 align-middle"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a aria-expanded="true" class="nav-link active" data-bs-toggle="tab" href="#profile-i1">
                                    <i class="ti ti-user fs-22 align-middle"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a aria-expanded="false" class="nav-link" data-bs-toggle="tab" href="#settings-i1">
                                    <i class="ti ti-settings fs-22 align-middle"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a aria-expanded="false" class="nav-link" data-bs-toggle="tab" href="#about-i1">
                                    <i class="ti ti-info-circle fs-22 align-middle"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a aria-expanded="false" class="nav-link" data-bs-toggle="tab" href="#projects-i1">
                                    <i class="ti ti-briefcase fs-22 align-middle"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a aria-expanded="false" class="nav-link" data-bs-toggle="tab" href="#contact-i1">
                                    <i class="ti ti-mail fs-22 align-middle"></i>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane" id="home-i1">
                                <p class="mb-0">Discover our platform designed to make your daily life
                                    easier. From modern interiors to smart home gadgets, our curated
                                    selection is tailored for comfort, functionality, and style.</p>
                            </div>
                            <div class="tab-pane show active" id="profile-i1">
                                <p class="mb-0">Hello! I’m a creative thinker who thrives on innovation
                                    and meaningful connections. I enjoy exploring tech trends, reading
                                    insightful books, and traveling to experience new cultures and cuisines.
                                </p>
                            </div>
                            <div class="tab-pane" id="settings-i1">
                                <p class="mb-0">A peaceful workspace can make all the difference. That’s
                                    why we offer customizable setups, soundproofing tips, and productivity
                                    tools to help you stay focused and inspired every day.</p>
                            </div>
                            <div class="tab-pane" id="about-i1">
                                <p class="mb-0">We’re a team of innovators passionate about creating
                                    seamless experiences. Our mission is to deliver solutions that merge
                                    design, functionality, and purpose in every project we undertake.</p>
                            </div>
                            <div class="tab-pane" id="projects-i1">
                                <p class="mb-0">Our recent projects range from mobile app development to
                                    full-scale branding initiatives. We believe in data-driven strategies
                                    paired with creative storytelling to drive results.</p>
                            </div>
                            <div class="tab-pane" id="contact-i1">
                                <p class="mb-0">Have questions or ideas? We'd love to hear from you!
                                    Reach out through our contact form, social media, or drop by our office
                                    for a chat over coffee.</p>
                            </div>
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
                <h5 class="card-title mb-0">Tooltips Variations</h5>
                <div class="card-action">
                    <a class="card-action-item" data-action="card-toggle" href="#!"><i class="ti ti-chevron-up"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Basic</h5>
                        <p class="mb-0">
                            Powerful admin features like <a class="fw-medium"
                                data-bs-title="Manage your dashboard easily" data-bs-toggle="tooltip" href="#">custom
                                dashboards</a> and UI components help you build
                            scalable web applications efficiently.
                            This template includes pre-built pages, clean layouts, and reusable code blocks
                            to boost your development workflow.
                            From user management to analytics and settings, everything is modular and
                            developer-friendly.
                            Create modern admin panels with <a class="fw-medium" data-bs-title="Built with Bootstrap 5"
                                data-bs-toggle="tooltip" href="#">responsive design</a> and seamless UX.
                            Get started quickly with a professional-grade <a class="fw-medium"
                                data-bs-title="Tailored for developers" data-bs-toggle="tooltip" href="#">UI
                                toolkit</a> and supercharge your app with <a class="fw-medium"
                                data-bs-title="Includes charts, tables, forms and more" data-bs-toggle="tooltip"
                                href="#">powerful components</a> and
                            flexible layouts.
                        </p>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Disabled Elements</h5>
                        <span class="d-inline-block" data-bs-title="Disabled tooltip" data-bs-toggle="tooltip"
                            tabindex="0">
                            <button class="btn btn-primary pe-none" disabled="" type="button">Disabled
                                Button</button>
                        </span>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Hover Elements </h5>
                        <button class="btn btn-primary" data-bs-title="Tooltip appears on hover only"
                            data-bs-toggle="tooltip" data-bs-trigger="hover" type="button">Hover to
                            See Info</button>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Four Directions</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-primary" data-bs-placement="top" data-bs-title="Tooltip on top"
                                data-bs-toggle="tooltip" type="button">Tooltip ontop</button>
                            <button class="btn btn-primary" data-bs-placement="bottom" data-bs-title="Tooltip on bottom"
                                data-bs-toggle="tooltip" type="button">Tooltip
                                on bottom</button>
                            <button class="btn btn-primary" data-bs-placement="left" data-bs-title="Tooltip on left"
                                data-bs-toggle="tooltip" type="button">Tooltip on
                                left</button>
                            <button class="btn btn-primary" data-bs-placement="right" data-bs-title="Tooltip on right"
                                data-bs-toggle="tooltip" type="button">Tooltip
                                on right</button>
                        </div>
                    </div> <!-- end col-->
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">HTML Tags</h5>
                        <button class="btn btn-primary" data-bs-html="true"
                            data-bs-title="&lt;em&gt;New&lt;/em&gt; &lt;u&gt;Tooltip&lt;/u&gt; &lt;b&gt;with&lt;/b&gt; &lt;i&gt;HTML&lt;/i&gt; &lt;br&gt;Custom message here!"
                            data-bs-toggle="tooltip" type="button">
                            Tooltip with HTML
                        </button>
                    </div>
                    <div class="col-xl-6">
                        <h5 class="mb-2 pb-1">Color Tooltips</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-primary" data-bs-custom-class="tooltip-primary"
                                data-bs-placement="top" data-bs-title="This is a primary tooltip with a custom style."
                                data-bs-toggle="tooltip" type="button">
                                Primary tooltip
                            </button>
                            <button class="btn btn-danger" data-bs-custom-class="tooltip-danger" data-bs-placement="top"
                                data-bs-title="This is a danger tooltip with a custom warning message."
                                data-bs-toggle="tooltip" type="button">
                                Danger tooltip
                            </button>
                            <button class="btn btn-info" data-bs-custom-class="tooltip-info" data-bs-placement="top"
                                data-bs-title="This is an info tooltip that provides extra details."
                                data-bs-toggle="tooltip" type="button">
                                Info tooltip
                            </button>
                            <button class="btn btn-success" data-bs-custom-class="tooltip-success"
                                data-bs-placement="top"
                                data-bs-title="This is a success tooltip to indicate completion."
                                data-bs-toggle="tooltip" type="button">
                                Success tooltip
                            </button>
                            <button class="btn btn-secondary" data-bs-custom-class="tooltip-secondary"
                                data-bs-placement="top"
                                data-bs-title="This is a secondary tooltip that gives additional information."
                                data-bs-toggle="tooltip" type="button">
                                Secondary tooltip
                            </button>
                            <button class="btn btn-warning" data-bs-custom-class="tooltip-warning"
                                data-bs-placement="top" data-bs-title="This is a warning tooltip to alert you."
                                data-bs-toggle="tooltip" type="button">
                                Warning tooltip
                            </button>
                            <button class="btn btn-dark" data-bs-custom-class="tooltip-dark" data-bs-placement="top"
                                data-bs-title="This is a dark tooltip with important information."
                                data-bs-toggle="tooltip" type="button">
                                Dark tooltip
                            </button>
                        </div>
                    </div> <!-- end col-->
                </div>
            </div>
        </div>
    </div>
</div><!-- end row -->

@endsection

@section('scripts')
@vite(['resources/js/pages/ui-interactive.js'])
@endsection