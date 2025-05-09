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


                        <div class="qr_design">
                            <div class="configurations pt-5">
                                <h3><i class="fas fa-arrow-left " style="cursor:pointer" onclick="window.location='/dashboard.php?tab=property_builder&active=6'"></i> Download Qr Code</h3>
                            </div>
                            <div class="configurations_sizing">
                                <div class="buttons d-flex">
                                    <button class="btn btn-dark ml-2" onclick="DownloadRightNow()">Download Now</button>
                                    <button class="btn btn-dark ml-2" onclick="getImage()">Edit with Image editor</button>
                                </div>
                            </div>


                            <div class="area_to_download">
                                <div id="qrCodeWrap">
                                    <div id="downloadArea">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div id="imageEditor">

                        </div>

                        <div class="toast-container position-fixed p-3" id="toastMessage" style="display:none;z-index: 99999; top: 20px; right: 0px;width:300px">
                            <div id="myToast" class="toast bg-danger text-white" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="toast-header d-flex justify-content-between  ">
                                    <strong class="me-auto">Error</strong>
                                    <button onclick="closeToastMessage()" type="button" class="close " data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="toast-body" id="ErrorMessage">
                                    An error occurred! Please check the details and try again.
                                </div>
                            </div>
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
    <script src="js/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="js/adminlte.min.js"></script>
    <!-- Custom JS -->
    <script src="js/dashboard.js"></script>
    <script src="js/dashboard_helper.js"></script>
    <!-- <script src="/js/dashboard/cameraImages.js"></script> -->
    <script src="js/settings/image_editor.js"></script>
    <script src="js/sessionRedirection.js"></script>
    <!-- <script src="/js/select2.full.min.js"></script> -->


</body>

</html>