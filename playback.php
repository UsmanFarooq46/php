<?php

try {
    session_start();
    require 'db.php';
    require_once 'include/auth/auth_functions.php';

    if (!currentUserHasAccess($pdo, 'property_builder', 'read')) {
        if (isset($_SESSION['userid'])) {
            echo "Access denied.";
            header("refresh:5;url=/dashboard.php");
        } else {
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
    <link rel="stylesheet" href="/css/property-builder/playback.css">
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

    <!-- Date picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/air-datepicker@3.3.2/air-datepicker.min.css">
    <!-- Air Datepicker CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/air-datepicker@3.4.0/air-datepicker.css">

    <!-- Air Datepicker JS -->
    <script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.4.0/air-datepicker.js"></script>
    <script src="js/dashboard/streaming.min.js"></script>
    <script src="/js/settings/common_player.js"></script>
    <script src="/js/settings/data_config_player.js"></script>
    <!-- CryptoJs for encryption -->
    <script src="js/dashboard/streaming.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
    <script>
        window.SECRET_KEY = "8F7A3D9C1E6B4F8D2A5C7E0F3B1D9E2C";
    </script>
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
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="py-3">

                        <div class="d-flex align-items-center pb-4">
                            <button type="button" onclick="backToCamera()" class="btn btn-block btn-dark" style="width:auto;">Back</button>
                        </div>   

                        <div id="filter_and_table">
                            <form>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="file_type">File type</label>
                                            <select name="file_type" id="file_type" class="form-control">
                                                <option value="" disabled>Select file type</option>
                                                <option value="2">Motion detection</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="playback_date">Date</label>
                                            <input type="date" id='playback_date' name="playback_date" class="form-control" title="Start Date" placeholder="Start Date">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="playback_start">Start time</label>
                                            <input type="time" value="00:00" name="playback_start" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="playback_end">End time</label>
                                            <input type="time" value="23:59" name="playback_end" class="form-control" />
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end align-items-end">
                                    <button type="button" onclick="clearSystemEvents()" class="btn btn-secondary mr-2" id="systemEventsClearButton" style="display:none">Clear filter</button>
                                    <button type="button" onclick="getPlaybackRecords()" class="btn btn-primary">Search</button>
                                </div>
                            </form>

                            <div class="pt-4" id="playback_table_container" class="playback_table">
                            </div>
                        </div>


                        <!-- =================== time line area start ====================  -->
                        <div class="container_all">
                            <div class="area_preview">
                                <div class="preview-image-container" style="position: relative;">
                                    <!-- Loader (Spinner) -->
                                    <div id="loader" style="width: 34px;height: 44px;position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display: none;">
                                        <img src="/include/images/loader.gif" alt="Loading..." />
                                    </div>

                                    <!-- Actual Image -->
                                    <img id="previewImage" src="" alt="Streaming Preview"
                                        style="display: none; width: 100%; height: auto; border-radius: 8px;"
                                        onload="hideLoader()"
                                        onerror="handleImageError()" />
                                </div>

                                <div class="preview-controls">
                                    <button id="previousButton" onclick="showPreviousImage()" class="btn-preview">
                                        <i class="fas fa-step-backward"></i>
                                    </button>
                                    <button id="startButton" onclick="startStreamingImages()" class="btn-preview">
                                        <i class="fas fa-play"></i>
                                    </button>
                                    <button id="stopButton" style="display: none;" onclick="stopStreamingImages()" class="btn-preview">
                                        <i class="fas fa-pause"></i>
                                    </button>
                                    <button id="nextButton" onclick="showNextImage()" class="btn-preview">
                                        <i class="fas fa-step-forward"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="timeline-container">
                                <div class="timeline-clickable" id="timelineClickable"></div>
                                <div class="timeline" id="timeline">
                                    <div id="timeSelector" class="time-selector" style="display: none;">
                                        <div id="currentTime" class="current-time">2025-03-26 15:44:21</div>
                                    </div>
                                </div>
                                <div class="time-markers" id="timeMarkers"></div>
                            </div>

                        </div>
                        <!-- =================== time line area ends ====================  -->
                        <div id="playback_streaming" style="display: none;">
                            <button onclick="stopVideoAndBack()">Stop and back</button>.
                            <input type="hidden" name="playback_record" id="playback_record" value="">

                            <div id="playback_streaming_record">

                            </div>

                            <div id="pluginContainer" class="lshide" style="min-height: 338px;">
                                <div class="pluginDiv c265"> <canvas id="c265" class="c265" muted="" style="transform: none;"></canvas> <canvas class="vlayer disable-select" oncontextmenu="return false;"></canvas> <canvas class="imgCapture"></canvas> </div>
                            </div>
                        </div>

                        <div class="modal fade" id="playbackRecordLoaderModal" tabindex="-1" role="dialog" aria-labelledby="addEditQrCode" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-area" role="document" style="min-height:90vh">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div style="width: 11rem;height: 11rem;">
                                            <img style="width: 100%;height: 100%;object-fit: contain;" src="/include/images/loader.gif" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="toast-container position-fixed p-3" id="camera_playback_toastMessage" style="display:none;z-index: 99999; top: 20px; right: 0px;width:300px">
                            <div id="camera_playback_Toast" class="toast bg-danger text-white" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="toast-header d-flex justify-content-between  ">
                                    <strong class="me-auto">Error</strong>
                                    <button onclick="close_camera_playback_toast_message()" type="button" class="close " data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="toast-body" id="playback_Message">
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
    <script src="/js/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="js/dashboard.js"></script>
    <script src="js/callFlow.js"></script>
    <script src="js/dashboard_helper.js"></script>
    <script src="/js/settings/preferences.js"></script>
    <!-- AdminLTE App -->
    <script src="/js/adminlte.min.js"></script>

    <script src="/js/settings/lcpts_player.js"></script>
    <script src="/js/settings/data_config_player.js"></script>
    <script src="/js/settings/camera_sockets.js"></script>
    <script src="/js/settings/ls_player.min.js"></script>
    <script src="/js/sessionRedirection.js"></script>
    <script src="/js/settings/playback.js"></script>
    <!-- Responsive Extension JS -->
    <script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.3.2/air-datepicker.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <!-- Bootstrap switch -->
    <script src="js/bootstrap-switch/js/bootstrap-switch.min.js"></script>


</body>

</html>