<?php
try {
    session_start();
    require 'db.php';
    require_once 'include/auth/auth_functions.php';
    isPersistLogin();
    if (!currentUserHasAccess($pdo, 'dashboard', 'read')) {
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

<?php
    // Fixes quarks mode
    require 'include/support_tickets/tickets_essentials.php';
?>

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
    <style>
        .user_profile_image {
            width: 90px;
            height: 90px;
            border-radius: 500px;
            border: 1px solid black;
            padding: 7px;
        }

        .user_profile_image img {
            object-fit: contain;
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini" id="body_body">
    <div class="wrapper">

        <!-- Navbar -->
        <!-- Navbar -->
        <?php include 'shared/header.nav.php'; ?>
        <!-- /.navbar -->

        <!-- Modals Start-->
        <?php include 'shared/modal.php'; ?>
        <!-- End Modals -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar" id="sidebar-con">
            <?php
            include 'shared/aside.nav.php';
            ?>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row py-3" id="display-div">
                        <div class="col-12 col-lg-8">
                            <div class="card">
                                <div class="card-header" style="border-bottom: 0px solid rgba(0, 0, 0, .125);">
                                    <h4 class="font-weight-bold">Details: <span class="font-weight-normal"><?php echo $TicketData->subject; ?></span></h4>
                                </div>

                                <div class="card-body" style="margin-top:-20px">
                                    <div class="row">
                                        <div class="col-6 col-lg-6 col-xl-3">Type: <span class="text-bold text-break"> <?php echo $TicketData->type; ?></span></div>
                                        <div class="col-6 col-lg-6 col-xl-3">Category: <span class="text-bold text-break"><?php echo $TicketData->category; ?></span></div>
                                        <div class="col-6 col-lg-6 col-xl-3">Priority: <span class="text-bold text-break"><?php echo $TicketData->priority; ?></span> </div>
                                        <div class="col-3 col-lg-6 col-xl-3 d-flex align-items-center">
                                            Status:
                                            <span class="font-weight-bold" id="statusText"><?php echo $TicketData->status; ?></span>
                                            <div id="statusDropdown" class="mt-1 px-1">
                                                <select class="form-control" id="statusSelect" onchange="changeTicketStatus('<?php echo $itemId; ?>')">
                                                    <option value="" selected disabled>Select an option</option>
                                                    <?php foreach ($ticketStatuses as $key => $value) : ?>
                                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <i class="fas fa-edit ml-2 " role="button" id="editStatusIcon" onclick="toggleDropdown('<?php echo htmlspecialchars($TicketData->status); ?>')"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="card py-4">
                                <div class="card-header" style="border-bottom: 0px solid rgba(0, 0, 0, .125);">
                                    <h4 class="text-bold">Description</h4>
                                </div>

                                <div class="card-body" style="margin-top:-20px">
                                    <?php echo $TicketData->description; ?>
                                </div>

                            </div>

                            <div class="card py-4">
                                <div class="card-header" style="border-bottom: 0px solid rgba(0, 0, 0, .125);">
                                    <h4 class="text-bold">Comments</h4>
                                </div>

                                <div class="card-body" style="margin-top:-25px">
                                    <div class="form-group">
                                        <div class="input-group rounded-pill">
                                            <input type="text" id="commentText" class="form-control px-4 py-2" id="subject" placeholder="Enter Comments...">
                                            <div class="input-group-append" role="button" onclick="addComment('<?php echo $itemId; ?>')">
                                                <span class="input-group-text " id="inputIcon"><i class="fas fa-paper-plane"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <?php foreach ($commentsData as $comment) : ?>
                                        <div class="comment">
                                            <div class="d-flex align-items-center my-3">
                                                <div class="user rounded-circle border bg-primary" style="padding: 6px 13px; margin: 3px;">
                                                    <?php echo strtoupper(substr($comment['created_by_name'], 0, 1)); ?>
                                                </div>
                                                <div class="text-white rounded" style="border: 1px solid; background-color: #5c5c5c; padding: 2px 9px;">
                                                    <?php echo htmlspecialchars($comment['comment']); ?>
                                                </div>
                                            </div>

                                        </div>
                                    <?php endforeach; ?>


                                </div>

                                <!-- <div class="card-body" style="margin-top:-20px">
                                    <?php echo $TicketData->description; ?>
                                </div> -->

                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="card">
                                <div class="card-header" style="border-bottom: 0px solid rgba(0, 0, 0, .125);">
                                    <h4 class="text-bold">User</h4>
                                </div>

                                <div class="card-body" style="margin-top:-20px">
                                    <div class="user_profile_image">
                                        <img src="./include/images/user_profile_placeholder.svg" alt="">
                                    </div>

                                    <div class="d-flex pt-2">
                                        <p class="m-0 p-0 pr-3">Name:</p>
                                        <p class="m-0 p-0 text-bold text-break"><?php echo $TicketData->ownerData->firstname . ' ' . $TicketData->ownerData->lastname; ?></p>
                                    </div>

                                    <div class="d-flex pt-2">
                                        <p class="m-0 p-0 pr-3">Email:</p>
                                        <p class="m-0 p-0 text-bold text-break"><?php echo $TicketData->ownerData->email ?></p>
                                    </div>

                                </div>

                                <div class="card-header" style="border-bottom: 0px solid rgba(0, 0, 0, .125);">
                                    <h5 class="text-bold">Property</h5>
                                </div>

                                <div class="card-body" style="margin-top:-20px">
                                    <div class="d-flex pt-2">
                                        <p class="m-0 p-0 pr-3">Name:</p>
                                        <p class="m-0 p-0 text-bold text-break"><?php echo $TicketData->propertyData->name ?></p>
                                    </div>
                                </div>

                            </div>

                            <div class="card">
                                <div class="card-header" style="border-bottom: 0px solid rgba(0, 0, 0, .125);">
                                    <h4 class="text-bold">Dates</h4>
                                </div>

                                <div class="card-body" style="margin-top:-20px">
                                    <div class="d-flex justify-content-between align-items-center pt-4">
                                        <p class="m-0 p-0 pr-3 text-bold">Created at:</p>
                                        <p class="m-0 p-0">
                                            <?php
                                            $dateTime = new DateTime($TicketData->created_at);
                                            $dateOnly = $dateTime->format('Y-m-d');
                                            echo $dateOnly;
                                            ?>

                                        </p>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center pt-4">
                                        <p class="m-0 p-0 pr-3 text-bold">Updated at:</p>
                                        <p class="m-0 p-0">
                                            <?php
                                            $dateTime = new DateTime($TicketData->updated_at);
                                            $dateOnly = $dateTime->format('Y-m-d');
                                            echo $dateOnly;
                                            ?>
                                        </p>
                                    </div>
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

    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="js/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="js/adminlte.min.js"></script>
    <!-- Custom JS -->
    <script src="js/support_tickets.js"></script>
    <script src="js/dashboard_helper.js"></script>
    <script src="js/dashboard.js"></script>
    <script src="/js/settings/preferences.js"></script>
    <!-- <script src="js/checkSession.js"></script> -->
    <!-- Select2 -->
    <script src="js/select2.full.min.js"></script>
    <!-- jsGrid -->
    <script src="js/jsgrid.min.js"></script>


</body>

</html>