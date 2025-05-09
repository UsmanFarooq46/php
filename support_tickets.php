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
try {
    //code...
    require realpath(dirname(__FILE__) . '/include/support_tickets/tickets_essentials.php');
} catch (\Throwable $th) {
    //throw $th;
}
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
    <script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.6/dist/purify.min.js"></script>
</head>

<body class="hold-transition sidebar-mini" id="body_body">
    <div class="wrapper">

        <!-- Navbar -->
        <?php include 'shared/header.nav.php'; ?>
        <!-- /.navbar -->

        <!-- Modals Start-->
        <?php include 'shared/modal.php'; ?>
        <!-- Filter Modal -->
        <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-bold">Filter</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="filterCategory">Category</label>
                                <select class="form-control" id="filterCategory">
                                    <option value="" selected disabled>Select an option</option>
                                    <?php foreach ($categories as $key => $value) : ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-lg-6">
                                    <label for="filterTicketType">Type*</label>
                                    <select class="form-control" id="filterTicketType">
                                        <option value="" selected disabled>Select an option</option>
                                        <?php foreach ($ticketType as $key => $value) : ?>
                                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="filterPriority">Priority</label>
                                    <select class="form-control" id="filterPriority">
                                        <option value="" selected disabled>Select an option</option>
                                        <?php foreach ($priorities as $key => $value) : ?>
                                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php endforeach; ?>

                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-lg-6">
                                    <label for="filterOwner">Owner</label>
                                    <select class="form-control" id="filterOwner">
                                        <option value="" selected disabled>Select an option</option>
                                        <?php foreach ($users as $user) : ?>
                                            <option value="<?php echo $user['id']; ?>"><?php echo $user['firstname'] . ' ' . $user['lastname']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="filterProperties">Properties</label>
                                    <select class="form-control" id="filterProperties">
                                        <option value="" selected disabled>Select an option</option>
                                        <?php foreach ($properties as $property) : ?>
                                            <option value="<?php echo $property['id']; ?>"><?php echo $property['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                            </div>

                            <button type="button" onclick="filterTickets()" class="btn btn-dark btn-block">Filter</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End FIlter Modal -->

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
                    <div class="row py-3" id="display-div">
                        <div class="col-12">

                            <div class="text-right" id="filterDiv">
                                <button class="btn btn-dark mr-3" data-toggle="modal" data-target="#filterModal"><i class="fas fa-filter"></i> Filter</button>
                            </div>
                            <button type="button" onclick="addNewTicket()" class="btn btn-dark my-3"><i class="fas fa-plus"></i> Add new</button>
                            <!-- <div class="card card-primary card-outline card-outline-tabs" id="support_ticket_data"> -->
                            <div class="card card-dark card-outline card-outline-tabs" id="support_ticket_data">
                                <div class="card-header p-0 border-bottom-0">
                                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="open_tickets-tab" data-toggle="pill" href="#open_tickets" role="tab" aria-controls="open_tickets" aria-selected="true">Open tickets</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="inProgressTickets-tab" data-toggle="pill" href="#inProgressTickets" role="tab" aria-controls="inProgressTickets" aria-selected="false">In progress tickets</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="Action_required" data-toggle="pill" href="#action_required_tab" role="tab" aria-controls="action_required_tab" aria-selected="false">Action is required</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="closed_tickets" data-toggle="pill" href="#closed_tickets_tab" role="tab" aria-controls="closed_tickets_tab" aria-selected="false">Closed tickets</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="All_tickets" data-toggle="pill" href="#All_tickets_tab" role="tab" aria-controls="All_tickets_tab" aria-selected="false">All tickets</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-four-tabContent">
                                        <div class="tab-pane fade show active" id="open_tickets" role="tabpanel" aria-labelledby="open_tickets-tab">
                                            <div class="table-responsive p-0" id="open_tickets_table">
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="inProgressTickets" role="tabpanel" aria-labelledby="inProgressTickets-tab">
                                            <div class="table-responsive p-0" id="In_progress_tickets">
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="action_required_tab" role="tabpanel" aria-labelledby="Action_required">
                                            <div class="table-responsive p-0" id="action_required_blocked">
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="closed_tickets_tab" role="tabpanel" aria-labelledby="closed_tickets">
                                            <div class="table-responsive p-0" id="closed_tickets_table">
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="All_tickets_tab" role="tabpanel" aria-labelledby="All_tickets">
                                            <div class="table-responsive p-0" id="all_tickets_table">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card -->
                            </div>

                            <div class="card card-dark" id="support_ticket_form" style="display:none">
                                <div class="card-header">
                                    <h3 class="card-title">Add Ticket</h3>
                                </div>
                                <form id="ticketForm">
                                    <div class="card-body">
                                        <!-- Subject and Type in one -->
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="subject">Subject*</label>
                                                <input type="text" class="form-control" id="subject" placeholder="Enter subject">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="ticketType">Type*</label>
                                                <select class="form-control" id="ticketType">
                                                    <option value="" selected disabled>Select an option</option>
                                                    <?php foreach ($ticketType as $key => $value) : ?>
                                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Category and Priority in one row -->
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="Category">Category</label>
                                                <select class="form-control" id="Category">
                                                    <option value="" selected disabled>Select an option</option>
                                                    <?php foreach ($categories as $key => $value) : ?>
                                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="priority">Priority*</label>
                                                <select class="form-control" id="Priority">
                                                    <option value="" selected disabled>Select an option</option>
                                                    <?php foreach ($priorities as $key => $value) : ?>
                                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Description in one row -->
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="description">Description</label>
                                                <textarea class="form-control" id="description" rows="3" placeholder="Description"></textarea>
                                            </div>
                                        </div>

                                        <!-- Owner and Properties in one row -->
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="Owner">Owner*</label>
                                                <select class="form-control" id="Owner">

                                                    <option value="" selected disabled>Select an option</option>
                                                    <?php foreach ($users as $user) : ?>
                                                        <option value="<?php echo $user['id']; ?>"><?php echo $user['firstname'] . ' ' . $user['lastname']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="formError"></div>
                                    </div>
                                    <!-- /.card-body -->

                                    <div class="card-footer text-right">
                                        <button type="button" onclick="cancelEditing()" class="btn btn-secondary mx-2">Cancel</button>
                                        <button type="button" id="ticketSubmitButton" onclick="submitTicketForm()" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
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
    <script src="js/sessionRedirection.js"></script>
    <script src="js/dashboard.js"></script>
    <script src="/js/settings/preferences.js"></script>
    <!-- <script src="js/checkSession.js"></script> -->
    <!-- Select2 -->
    <script src="js/select2.full.min.js"></script>
    <!-- jsGrid -->
    <script src="js/jsgrid.min.js"></script>


</body>

</html>