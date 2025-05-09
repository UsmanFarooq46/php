<?php
try {
    session_start();
    require 'db.php';
    require_once 'include/auth/auth_functions.php';
    if (!currentUserHasAccess($pdo, 'settings', 'read')) {
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

    $user_type = $_SESSION["layer"];
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
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
    <title>Invisible Intercom | Events</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/adminlte.min.css">
    <link rel="stylesheet" href="css/bootstrap-4.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <link href="https://raw.githack.com/ttskch/select2-bootstrap4-theme/master/dist/select2-bootstrap4.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <link rel="icon" type="image/x-icon" href="include/images/favicon.ico">
    <link rel="stylesheet" href="css/jsgrid.min.css">
    <link rel="stylesheet" href="css/jsgrid-theme.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">

    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/property-builder/qr_code.css">
    <link rel="stylesheet" href="css/property-builder/hardware.css">
    <link rel="stylesheet" href="js/fullcalendar/main.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    <link rel="stylesheet" href="js/fullcalendar/daterangepicker.css">
    <link rel="stylesheet" href="css/icheck-bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="https://unpkg.com/@bitjson/qr-code@1.0.2/dist/qr-code.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html-to-image@latest/dist/html-to-image.min.js"></script>
    <link rel="stylesheet" href="js/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
    <link rel="stylesheet" href="https://uicdn.toast.com/tui-image-editor/latest/tui-image-editor.css">
    <link rel="stylesheet" href="https://uicdn.toast.com/tui-color-picker/latest/tui-color-picker.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <script src="js/dashboard/streaming.min.js"></script>

</head>

<body class="hold-transition sidebar-mini" id="body_body">
    <style>
        /* Custom styles for tab colors */
        .dataTables_info{
            display: none !important;
        }
        .previous{
            display: none !important;
        }
        .next{
            display: none !important;
        }
        .nav-tabs .nav-link {
            color: black;
            /* Default color for inactive tabs */
        }

        .nav-tabs .nav-link.active {
            color: blue;
            /* Color for active tab */
        }

        .nav-tabs .nav-link:hover {
            color: blue;
            /* Hover color for tabs */
        }

        .nav-tabs .nav-link.active {
            background-color: #f8f9fa;
            /* Background color for active tab */
            border-color: #dee2e6 #dee2e6 #fff;
            /* Border color adjustments */
        }
        .showHide{
            display: none;
        }
    </style>

    <?php

        $activeTab = "referrals";
        if (isset($_GET["active"])) {
            switch ($_GET["active"]) {
                case 1:
                    $activeTab = "referrals";
                    break;
            }
        }
    ?>

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
            <input type="hidden" name="active_tab_val" id="active_tab_val" value="<?php echo $activeTab; ?>">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
            
                <li class="nav-item">
                    <a onclick="RedirectToActiveTabEvents('referrals')" class="nav-link <?php echo $activeTab == 'referrals' ? 'active' : ''; ?>" id="referrals-tab" data-toggle="tab" href="#referrals" role="tab" aria-controls="referrals" aria-selected="<?php echo $activeTab == 'referrals' ? 'true' : 'false'; ?>">Referrals</a>
                </li>
            </ul>
            <div class="tab-content">
                <span id="success_message"></span>
                <div class="tab-pane fade <?php echo $activeTab == 'referrals' ? 'show active' : ''; ?>" id="referrals" role="tabpanel" aria-labelledby="referrals-tab">

                        <div class="content" id="events_content">
                            <div class="d-flex" id="add_call_flow_btn">
                                <button type="button" onclick="showRefferalCode()" class="btn btn-block btn-dark" style="margin:1rem 0 1rem 0rem;width:auto;">Refferal Code</button>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h3 class="card-title">Referrals</h3>
                                        <div class="card-tools">
                                            <div class="input-group input-group-sm" style="width: 250px;">
                                                <input type="text" id="searchReferralInput" onkeyup="searchTable('call_events_container', 'searchReferralInput')" class="form-control float-right" placeholder="Search Referrals">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-default">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="">
                                        <table class="table table-hover table-bordered" id="call_events_table">
                                            <thead style="text-align: center;">
                                                <tr>
                                                    <th>Property Name</th>
                                                    <th>Referred Property</th>
                                                    <th>Referral Code</th>
                                                </tr>
                                            </thead>
                                            <tbody id="call_events_container" style="text-align: center;"></tbody>

                                        </table>
                                    </div>
                                </div>
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

    <div class="modal fade" id="referral_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div id="error_sms_call_block_message"></div>
                <div class="modal-header">
                    <h5 class="modal-title" id="ref_property_name"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <div class="form-group">
                        <label>Refferal Code</label>
                        <input type="text" name="code" id="ref_code" class="form-control" readonly="">
                    </div>
                    <div class="form-group">
                        <label>Refferal Link</label>
                        <input type="text" name="refferal_link" class="form-control" id="ref_link" readonly="">
                    </div>
                </div> 
            </div>
        </div>
    </div>


    <!-- /.control-sidebar -->

    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/adminlte.min.js"></script>
    <script src="js/events.js"></script>
    <script src="/js/settings/preferences.js"></script>
    <script src="js/dashboard_helper.js"></script>
    <script src="js/sessionRedirection.js"></script>
    <script src="js/dashboard.js"></script>
    <script src="js/select2.full.min.js"></script>
    <script src="js/jsgrid.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

</body>

</html>