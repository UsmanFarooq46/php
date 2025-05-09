<?php
try {

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    require 'db.php';
    require_once 'include/auth/auth_functions.php';
    isPersistLogin();
    if (!currentUserHasAccess($pdo, 'command_center', 'read')) {
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
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invisible Intercom | Support Tickets</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="css/adminlte.min.css">
    <!-- For Modals -->
    <link rel="stylesheet" href="css/bootstrap-4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="css/select2-bootstrap4.min.css">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="include/images/favicon.ico">
    <!-- jsGrid -->
    <link rel="stylesheet" href="css/jsgrid.min.css">
    <link rel="stylesheet" href="css/jsgrid-theme.min.css">
    <!-- Custom -->
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/property-builder/command_center.css">
    <link rel="stylesheet" href="css/property-builder/hardware.css">

    <!-- Bootstrap Switch  -->
    <link rel="stylesheet" href="js/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
    <!-- Bootstrap Switch  -->
</head>

<body class="hold-transition sidebar-mini" id="body_body">
    <style>
        .dataTables_info{
            display: none !important;
        }
        .previous{
            display: none !important;
        }
        .next{
            display: none !important;
        }
    </style>
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
            <div class="content">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="px-2 py-3"></h3>
                    <div class="d-flex justify-content-end mb-3">
                        <div class="" id="changeCommandViewEleContainer">
                        </div>
                    </div>
                </div>

                <!-- <div class="justify-content-end" id="command_center_search_container" style="display: none;">
                    <div class="form-group pr-2 d-flex align-items-center">
                        <label for="search_command_value" class="mx-2">Search:</label>
                        <input type="text" name="search_command_value" oninput="searchCommandCenter()" id="search_command_value" class="form-control">
                    </div>
                </div> -->

                <div class="isLoading justify-content-center m-5" style="display: none;" id="command_center_loading_indicator">
                    <img src="/include/images/loader.gif" style="width:100px;margin-left:5px" alt="" srcset="">
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Command Center</h3>
                        <!-- Search bar for Operators -->
                        <div class="card-tools">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" id="searchCommandCenterInput" onkeyup="searchTable('command_center_complete_data_table', 'searchCommandCenterInput')" class="form-control float-right" placeholder="Search Command Center">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="door_controls_container">
                            <!-- <div class="card-body table-responsive" id="door_controls_container">
                            </div> -->
                        </div>
                    </div>
                </div>

                <div class="toast-container position-fixed p-3" id="commandToastMessage" style="display:none;z-index: 99999; top: 20px; right: 0px;width:300px">
                    <div id="commandErrorToast" class="toast bg-danger text-white TostOnTopRight" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header d-flex justify-content-between  ">
                            <strong class="me-auto">Error</strong>
                            <button onclick="closeCommandCenterToastMessage()" type="button" class="close " data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="toast-body" id="commandErrorMessage">
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Main Footer -->
        <?php include 'shared/footer.php'; ?>
    </div>
    <!-- /.content -->
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
        <div class="p-3">
            <h5>Title</h5>
            <p>Sidebar content</p>
        </div>
    </aside>
    <!-- /.control-sidebar -->

    <div class="modal fade" id="testing_hardware_sms_device_command" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <!-- <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div> -->
                <div class="modal-body">
                    <div class="d-flex flex-column justify-content-center align-items-center">
                        <div id="smsTestingStatusCommand">

                        </div>
                        <img src="/include/images/loader.gif" width="30" alt="" srcset="">
                    </div>
                </div>
            </div>
        </div>
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
    <!-- <script src="/js/property_builder/init_command_center.js"></script> -->
    <script src="js/dashboard.js"></script>
    <script src="/js/settings/preferences.js"></script>
    <script src="js/dashboard_helper.js"></script>
    <script src="js/support_tickets.js"></script>
    <script src="js/property_builder/command_center.js"></script>
    <!-- <script src="js/checkSession.js"></script> -->
    <!-- Select2 -->
    <script src="js/select2.full.min.js"></script>
    <!-- jsGrid -->
    <script src="js/jsgrid.min.js"></script>
    <script src="js/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <!-- DataTables Library -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <!-- Responsive Extension JS -->
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <!-- Encryption -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
    <script>
        window.SECRET_KEY = "8F7A3D9C1E6B4F8D2A5C7E0F3B1D9E2C";
    </script>

</body>

</html>