<?php

try {
    session_start();
    //code...
    require 'db.php';
    require 'include/auth/auth_functions.php';

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    isPersistLogin();

    if (!currentUserHasAccess($pdo, 'dashboard', 'read')) {
        if (isset($_SESSION['userid'])) {
            echo "Access denied.";
            header( "refresh:5;url=/dashboard.php" );
        }else {
            header("Location: /login.php");
        }
        exit();
    }

    if (!empty($_SESSION['alert'])) {
        $alertType = $_SESSION['alert']['type'];
        $message = $_SESSION['alert']['message'];
    
        echo "
        <div class='alert alert-$alertType alert-dismissible fade show' role='alert' id='alertBox'>
            $message
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
            </button>
        </div>
        <script>
            // Use JavaScript to remove the alert after 30 seconds
            setTimeout(function() {
                var alertBox = document.getElementById('alertBox');
                if (alertBox) {
                    alertBox.classList.remove('show'); // Hide the alert
                    alertBox.classList.add('fade');   // Ensure fade-out effect
                    setTimeout(function() {
                        alertBox.remove(); // Remove the alert from the DOM
                    }, 500); // Allow fade-out animation time
                }
            }, 10000); // 10 seconds
        </script>
        ";
    
        unset($_SESSION['alert']);
    }
    
    
} catch (\Throwable $th) {
    
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
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
    <title>Invisible Intercom</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="css/adminlte.min.css">
    <!-- For Modals -->
    <link rel="stylesheet" href="css/bootstrap-4.min.css">
    <!-- Select2 -->
    <!-- <link rel="stylesheet" href="css/select2-bootstrap4.min.css"> -->
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <link href="https://raw.githack.com/ttskch/select2-bootstrap4-theme/master/dist/select2-bootstrap4.css" rel="stylesheet">
    <!-- Responsive Extension CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <!-- <link rel="icon" type="image/x-icon" href="include/images/favicon.ico"> -->
    <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="include/images/favicon.ico">
    <!-- jsGrid -->
    <link rel="stylesheet" href="css/jsgrid.min.css">
    <link rel="stylesheet" href="css/jsgrid-theme.min.css">

    <!-- Slider -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">

    <!-- Custom -->
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/property-builder/qr_code.css">
    <link rel="stylesheet" href="css/property-builder/hardware.css">
    <link rel="stylesheet" href="js/fullcalendar/main.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">

    <link rel="stylesheet" href="js/fullcalendar/daterangepicker.css">
    <link rel="stylesheet" href="css/icheck-bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <!-- <script src="js/fullcalendar/main.js"></script> -->
    <!-- Qr code -->
    <script src="https://unpkg.com/@bitjson/qr-code@1.0.2/dist/qr-code.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html-to-image@latest/dist/html-to-image.min.js"></script>
    <!-- Bootstrap Switch  -->
    <link rel="stylesheet" href="js/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
    <!-- image editor -->
    <link rel="stylesheet" href="https://uicdn.toast.com/tui-image-editor/latest/tui-image-editor.css">
    <link rel="stylesheet" href="https://uicdn.toast.com/tui-color-picker/latest/tui-color-picker.css">

    <!-- Cropper.js CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <!-- CryptoJs for encryption -->
    <script src="js/dashboard/streaming.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.6/dist/purify.min.js"></script>
    <script>window.SECRET_KEY = "8F7A3D9C1E6B4F8D2A5C7E0F3B1D9E2C";</script>
    </head>

<body class="hold-transition sidebar-mini" id="body_body">
    <style>
        .table{
            width: 100% !important;
        }
        .showHide{
            display: none;
        }
        .previous{
            display: none !important;
        }
        .next{
            display: none !important;
        }
        .dataTables_info{
            display: none !important;
        }
        .table-responsive {
            overflow-x: hidden !important;
            overflow-y: hidden !important;
        }
        
    </style>
    <div class="wrapper">

        <!-- Navbar -->
        <?php include 'shared/header.nav.php'; ?>
        <!-- /.navbar -->

        <!-- Modals Start-->
        <?php
        include 'shared/modal.php';
        ?>
        <!-- End Modals -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar" id="sidebar-con">
            <?php
            include 'shared/aside.nav.php';
            ?>
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                        <div  id="display-div">

                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
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
    <!-- Cropper.js JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <!-- Custom JS -->
    <script src="js/dashboard.js"></script>
    <script src="js/callFlow.js"></script>
    <script src="js/dashboard_helper.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script> <!--Time picker for schedule -->
    <script src="js/dashboard/streaming.min.js"></script>
    <script src="/js/settings/preferences.js"></script>
    <!-- <script src="js/dashboard/cameraImages.js"></script> -->
    <script src="js/sessionRedirection.js"></script>
    <!-- Select2 -->
    <script src="js/select2.full.min.js"></script>
    <!-- jsGrid -->
    <script src="js/jsgrid.min.js"></script>
    <!-- Slider -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="js/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <!-- DataTables Library -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <!-- Responsive Extension JS -->
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <?php
    if (isset($_GET["tab"]) && isset($_GET["active"])) {
        if ($_GET["tab"] == "property_builder") {
            echo '<script>resumeTab("include/property_builder/property_builder.php?active=' . $_GET["active"] . '");</script>';
        }
            }else if (isset($_GET["tab"])) {
                
                if ($_GET["tab"] == "my_numbers") {
                    echo "<script>resumeTab('include/signalwire/my_numbers.php');</script>";
                }
                if ($_GET["tab"] == "my_properties") {
                    echo "<script>resumeTab('include/properties/my_properties.php');</script>";
                }
                if ($_GET["tab"] == "property_builder") {
                    if(isset($_GET['call_flow']) &&  $_GET['call_flow'] == "active"){
                        echo "<script>resumeTab('include/property_builder/property_builder.php', 'call_flow');</script>";
                    }
                    else{
                        echo "<script>resumeTab('include/property_builder/property_builder.php');</script>";
                    }
                }
                if ($_GET["tab"] == "integrations") {
                    echo "<script>resumeTab('shared/coming_soon.php');</script>";
                }
                if ($_GET["tab"] == "roles") {
                    echo "<script>resumeTab('include/properties/rolestbl.php');</script>";
                }
                if ($_GET["tab"] == "admin_setup") {
                    echo "<script>resumeTab('include/admin/admin_setup.php');</script>";
                }
                if ($_GET["tab"] == "dealer_setup") {
                    echo "<script>resumeTab('include/admin/dealer_setup.php');</script>";
                }
                if ($_GET["tab"] == "users") {
                    echo "<script>resumeTab('include/users/users.php');</script>";;
                }
                if ($_GET["tab"] == "visitors") {
                    echo "<script>resumeTab('include/visitors/visitorstbl.php');</script>";;
                }
                if ($_GET["tab"] == "payment_invoice") {
                    echo "<script>resumeTab('include/payment_invoice/payment_invoice.php');</script>";
                }
            }
        ?>


</body>

</html>
