<?php

try {
    session_start();
    require 'db.php';
    require_once 'include/auth/auth_functions.php';

    if (!currentUserHasAccess($pdo, 'property_builder', 'read')) {
        if (isset($_SESSION['userid'])) {
            echo "Access denied.";
            header( "refresh:5;url=/dashboard.php" );
        }else {
            header("Location: /login.php");
        }
        exit();
    }
} catch (\Throwable $th) {
    // throw $th;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invisible Intercom</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/css/adminlte.min.css">
    <!-- For Modals -->
    <link rel="stylesheet" href="/css/bootstrap-4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="/css/select2-bootstrap4.min.css">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/include/images/favicon.ico">
    <!-- jsGrid -->
    <link rel="stylesheet" href="/css/jsgrid.min.css">
    <link rel="stylesheet" href="/css/jsgrid-theme.min.css">

    <!-- Custom -->
    <link rel="stylesheet" href="/css/property-builder/qr_code.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="/css/property-builder/image_editor.css">
    <!-- Qr code -->
    <script src="https://unpkg.com/@bitjson/qr-code@1.0.2/dist/qr-code.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html-to-image@latest/dist/html-to-image.min.js"></script>

    <!-- image editor -->
    <link rel="stylesheet" href="https://uicdn.toast.com/tui-image-editor/latest/tui-image-editor.css">
    <link rel="stylesheet" href="https://uicdn.toast.com/tui-color-picker/latest/tui-color-picker.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.6.0/fabric.min.js"></script>
    <script src="https://uicdn.toast.com/tui-color-picker/latest/tui-color-picker.js"></script>
    <script src="https://uicdn.toast.com/tui-image-editor/latest/tui-image-editor.js"></script>

    <!-- CKEditor 4 CDN -->
    <script src="https://cdn.ckeditor.com/4.20.2/full/ckeditor.js"></script>
    <link rel="stylesheet" href="https://cdn.ckeditor.com/4.20.2/skins/moono-lisa/editor.css">

    <!-- toast -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.6.0/fabric.min.js"></script>
    <script src="https://uicdn.toast.com/tui-color-picker/latest/tui-color-picker.js"></script>
    <script src="https://uicdn.toast.com/tui-image-editor/latest/tui-image-editor.js"></script>

    <!-- DataTables Library -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">

    <!-- Bootstrap Switch  -->
    <link rel="stylesheet" href="js/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">

</head>

<body class="hold-transition sidebar-mini" id="body_body">



    <div class="wrapper">

        <!-- Navbar -->
        <?php include 'shared/header.nav.php'; ?>
        <!-- /.navbar -->

        <!-- Modals Start-->
        <?php include 'shared/modal.php'; ?>
        <!-- End Modals -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar" id="sidebar-con">
            <?php
            include 'shared/aside.nav.php'; ?>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="py-3" id="display-div">

                        <div id="list_view">

                            <div class="d-flex justify-content-between align-items-center flex-wrap my-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-arrow-left pr-2" style="cursor:pointer" onclick="window.location='/dashboard.php?tab=property_builder&active=6'"></i>
                                    <button type="button" id="qrCodeTemplateAddButton" onclick="addQrTemplate('')" class="btn btn-block btn-dark" style="width:auto;">Add Template</button>
                                </div>

                                <div class="" id="changeSwitchViewEleContainer">
                                </div>
                            </div>

                            <div class="viewTemplate pt-3">
                                <div class="justify-content-center" id="qrCodeTemplateLoader" style="display: none;">
                                    <div class="loader_image">
                                        <img src="/include/images/loader.gif" alt="">
                                    </div>
                                </div>
                                
                                <div class="table-responsive p-0" id="QR_template_table">
                                </div>
                                <div class="d-flex flex-wrap align-items-center justify-content-center" id="QrCode_templates_view">
                                </div>
                            </div>
                        </div>

                        <div style="display: none;" id="editor_view">
                            <button type="button" id="addTemplateCancelButton" onclick="addQrTemplate('cancel')" class="btn btn-block btn-dark" style="width:auto;">Cancel</button>

                            <div class="">

                                <form class="pt-4">
                                    <input type="hidden" name="qr_template_id" id="qr_template_id" value="">
                                    <div class="form-group mb-3">
                                        <input type="text" name="templateName" id="templateName" required placeholder="Template Name" class="form-control">
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label for="positionRingColor">Ring Color</label>
                                            <input type="color" oninput="createTemplatePreview()" id="positionRingColor" name="positionRingColor" placeholder="Position Ring Color" class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="positionCenterColor">Center Color</label>
                                            <input type="color" id="positionCenterColor" oninput="createTemplatePreview()" name="positionCenterColor" placeholder="Position Center Color" class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="Module_Color">Module Color</label>
                                            <input type="color" id="Module_Color" oninput="createTemplatePreview()" name="Module_Color" placeholder="Module Color" class="form-control">
                                        </div>
                                    </div>


                                    <div class="d-flex w-full align-items-center">
                                        <div id="templatePreview" class="w-50">
                                        </div>
                                    </div>

                                    <div class="preview_container">
                                        <div class="preview_input">
                                            <label for="Orientation">Orientation</label>
                                            <select class="form-control" id="Orientation" name="Orientation" required>
                                                <option value="Portrait" selected>Portrait</option>
                                                <option value="Landscape">Landscape</option>
                                            </select>
                                        </div>
                                        <div class="preview_input">
                                            <label for="paperSize">Paper size</label>
                                            <select class="form-control" id="paperSize" name="paperSize" required>
                                                <option value="Letter" selected>Letter</option>
                                                <option value="A4">A4</option>
                                            </select>
                                        </div>
                                        <div>
                                            <button class="btn btn-primary" type="button" onclick="createdViewForEditorInTemplate()">Preview</button>
                                        </div>
                                    </div>

                                    <div>
                                        use maximize option for better preview
                                        <p><span class="text-bold">Note:</span> Please dont remove the qr Code placeholder path <strong><i><em>/include/images/qr-code.svg</em></i></strong> </p>
                                        <p><span class="text-bold">Note:</span> Please don't remove the placeholder text for number (571) 350-0755</p>
                                    </div>

                                    <textarea name="editor1" id="editor1" rows="50" cols="80">
                                        <p>(571) 350-0755</p>
                                        <img style="width: 100px; margin-top: -12px;" id="QrCodeImageEditor" src="/include/images/qr-code.svg" slot="icon" />
                                    </textarea>

                                    <div class="modal-footer">
                                        <button type="button" name="submitQrTemplateButton" id="submitQrTemplateButton" class="btn btn-primary" onclick="addQrCodeTemplate()">Save</button>
                                        <button type="button" class="btn btn-secondary" onclick="addQrTemplate('cancel')">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- View template Modal -->
                        <div class="modal fade" id="TemplatePreviewModal" tabindex="-1" role="dialog" aria-labelledby="addEditQrCode" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-area" role="document" style="min-height:90vh">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addEditQrCode">Template</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="TemplatePreviewContainer">
                                            <!-- <img src="/include/images/QrCodeTemplate (1).png" alt=""> -->
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="toast-container position-fixed p-3" id="qr_template_toastMessage" style="display:none;z-index: 99999; top: 20px; right: 0px;width:300px">
                            <div id="qr_template_Toast" class="toast bg-danger text-white" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="toast-header d-flex justify-content-between  ">
                                    <strong class="me-auto">Error</strong>
                                    <button onclick="closeQrTemplateToastMessage()" type="button" class="close " data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="toast-body" id="qr_template_Message">
                                    An error occurred! Please check the details and try again.
                                </div>
                            </div>
                        </div>

                        <div id="downloadAreaPreview" style="display:none">
                        </div>

                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
        <!-- Main Footer -->
        <?php include 'shared/footer.php'; ?>
    </div>

    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="/js/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="js/dashboard.js"></script>
    <!-- AdminLTE App -->
    <script src="/js/adminlte.min.js"></script>
    <script src="/js/sessionRedirection.js"></script>
    <!-- Responsive Extension JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="/js/settings/qrCodeTemplates.js"></script>
    <!-- Bootstrap switch -->
    <script src="js/bootstrap-switch/js/bootstrap-switch.min.js"></script>


</body>

</html>