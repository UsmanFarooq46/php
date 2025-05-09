<?php
try {
    session_start();
    require 'db.php';
    require_once 'include/auth/auth_functions.php';
    isPersistLogin();
    if (!currentUserHasAccess($pdo, 'events', 'read')) {
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
    <script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.6/dist/purify.min.js"></script>

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
        /* Custom styles for tab colors */
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
        $user_assign = in_array($_SESSION['layer'], ['super_admin', 'admin', 'dealer', 'sub_dealer', 'organization admin']);
        
        $activeTab = "system-events";
        if (isset($_GET["active"])) {
            switch ($_GET["active"]) {
                case 1:
                    $activeTab = "system-events";
                    break;
                case 2:
                    $activeTab = "org-events";
                    break;
                case 3:
                    $activeTab = "admin-events";
                    break;
                case 4:
                    $activeTab = "calls-event";
                    break;
                case 5:
                    $activeTab = "message-event";
                    break;
                case 6:
                    $activeTab = "call";
                    break;
                case 7:
                    $activeTab = "sms";
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
                    <a onclick="RedirectToActiveTabEvents('system-events')" class="nav-link <?php echo $activeTab == 'system-events' ? 'active' : ''; ?>" id="system-events-tab" data-toggle="tab" href="#schedule" role="tab" aria-controls="system-events" aria-selected="<?php echo $activeTab == 'system-events' ? 'true' : 'false'; ?>">System</a>
                </li>
                <?php if($user_assign): ?>
                    <li class="nav-item">
                        <a onclick="RedirectToActiveTabEvents('org-events')" class="nav-link <?php echo $activeTab == 'org-events' ? 'active' : ''; ?>" id="org-events-tab" data-toggle="tab" href="#schedule" role="tab" aria-controls="org-events" aria-selected="<?php echo $activeTab == 'org-events' ? 'true' : 'false'; ?>">Organization</a>
                    </li>
                <?php endif; 
                if(($_SESSION['layer'] == "super_admin") || ($_SESSION['layer'] == "admin")) { ?>
                    <li class="nav-item">
                        <a onclick="RedirectToActiveTabEvents('admin-events')" class="nav-link <?php echo $activeTab == 'admin-events' ? 'active' : ''; ?>" id="admin-events-tab" data-toggle="tab" href="#schedule" role="tab" aria-controls="admin-events" aria-selected="<?php echo $activeTab == 'admin-events' ? 'true' : 'false'; ?>">Admin</a>
                    </li>
                <?php } ?>
                <li class="nav-item">
                    <a onclick="RedirectToActiveTabEvents('calls-event')" class="nav-link <?php echo $activeTab == 'calls-event' ? 'active' : ''; ?>" id="calls-event-tab" data-toggle="tab" href="#calls-event" role="tab" aria-controls="calls-event" aria-selected="<?php echo $activeTab == '' ? 'true' : 'false'; ?>">Calls</a>
                </li>
                <li class="nav-item">
                    <a onclick="RedirectToActiveTabEvents('message-event')" class="nav-link <?php echo $activeTab == 'message-event' ? 'active' : ''; ?>" id="message-event-tab" data-toggle="tab" href="#message-event" role="tab" aria-controls="message-event" aria-selected="<?php echo $activeTab == '' ? 'true' : 'false'; ?>">Messages</a>
                </li>
                <li class="nav-item">
                    <a onclick="RedirectToActiveTabEvents('call')" class="nav-link <?php echo $activeTab == 'call-block' ? 'active' : ''; ?>" id="call-block-tab" data-toggle="tab" href="#call-block" role="tab" aria-controls="call-block" aria-selected="<?php echo $activeTab == '' ? 'true' : 'false'; ?>">Call Block</a>
                </li>
                <li class="nav-item">
                    <a onclick="RedirectToActiveTabEvents('sms')" class="nav-link <?php echo $activeTab == 'sms-block' ? 'active' : ''; ?>" id="sms-block-tab" data-toggle="tab" href="#sms-block" role="tab" aria-controls="sms-block" aria-selected="<?php echo $activeTab == '' ? 'true' : 'false'; ?>">SMS Block</a>
                </li>
            </ul>
            <div class="tab-content">
                <span id="success_message"></span>
                <div class="tab-pane fade <?php echo $activeTab == 'system-events' ? 'show active' : ''; ?>" id="system-events" role="tabpanel" aria-labelledby="system-events-tab">
                    <div class="content" id="events_content">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="px-2 py-3">System Events</h3>
                        </div>

                        <div class="isLoading justify-content-center m-5" style="display: none;" id="command_center_loading_indicator">
                            <img src="/include/images/loader.gif" style="width:100px;margin-left:5px" alt="" srcset="">
                        </div>

                        <div class="">
                            <!-- <div class="card table-responsive" style="height: 80vh;" id="events_container">
                            </div> -->

                            <div class="card p-4">
                                <div class="card-body table-responsive p-0">
                                    <div id="filter_form_container" class="py-2">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group pr-2">
                                                    <label for="door_name">Search By text</label>
                                                    <input type="text" id="searchEventsInput" class="form-control float-right" placeholder="Search">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="event_modules">Modules</label>
                                                    <select name="event_modules" id="event_modules" class="form-control">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="system_event_start_date">Start date</label>
                                                    <input type="date" id='system_event_start_date' name="system_event_start_date" class="form-control" title="Start Date" placeholder="Start Date">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="system_event_end_date">End date</label>
                                                    <input type="date" id='system_event_end_date' name="system_event_end_date" class="form-control" title="End Date" placeholder="End Date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end align-items-end">
                                            <button type="button" onclick="clearSystemEvents()" class="btn btn-secondary mr-2" id="systemEventsClearButton" style="display:none">Clear filter</button>
                                            <button type="button" onclick="searchSystemEvents()" class="btn btn-secondary">Search</button>
                                        </div>
                                    </div>
                                    <div id="events_container"></div>
                                </div>
                            </div>


                        </div>

                    </div>
                </div>
                <div class="tab-pane fade <?php echo $activeTab == 'org-events' ? 'show active' : ''; ?>" id="org-events" role="tabpanel" aria-labelledby="org-events-tab">
                    <div class="content" id="events_content">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="px-2 py-3">Organization Events</h3>
                        </div>

                        <div class="isLoading justify-content-center m-5" style="display: none;" id="command_center_loading_indicator">
                            <img src="/include/images/loader.gif" style="width:100px;margin-left:5px" alt="" srcset="">
                        </div>

                        <div class="">

                            <div class="card">
                                <div class="card-body">
                                    <div class="">
                                        <table class="table table-hover table-bordered" id="org_events_table">
                                            <thead>
                                                <tr>
                                                    <!-- <th>ID</th> -->
                                                    <th>Action</th>
                                                    <th>Module</th>
                                                    <th>User</th>
                                                    <th>Old Value</th>
                                                    <th>New Value</th>
                                                    <th>Time Stamp</th>
                                                    <th>Detail</th>
                                                </tr>
                                            </thead>
                                            <tbody id="org_events_container"></tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>
                </div>
                <div class="tab-pane fade <?php echo $activeTab == 'org-events' ? 'show active' : ''; ?>" id="admin-events" role="tabpanel" aria-labelledby="admin-events-tab">
                    <div class="content" id="events_content">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="px-2 py-3">Admin Events</h3>
                        </div>

                        <div class="isLoading justify-content-center m-5" style="display: none;" id="command_center_loading_indicator">
                            <img src="/include/images/loader.gif" style="width:100px;margin-left:5px" alt="" srcset="">
                        </div>

                        <div class="">

                            <div class="card">
                                <div class="card-body">
                                    <div class="">
                                        <table class="table table-hover table-bordered" id="admin_events_table">
                                            <thead>
                                                <tr>
                                                    <th>Action</th>
                                                    <th>Module</th>
                                                    <th>User</th>
                                                    <th>Old Value</th>
                                                    <th>New Value</th>
                                                    <th>Time Stamp</th>
                                                    <th>Detail</th>
                                                </tr>
                                            </thead>
                                            <tbody id="admin_events_container"></tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>
                </div>
                <div class="tab-pane fade <?php echo $activeTab == 'calls-event' ? 'show active' : ''; ?>" id="calls-event" role="tabpanel" aria-labelledby="calls-event-tab">
                    
                    
                    <div class="content" id="call_events">
                        <!-- <div class="d-flex justify-content-between align-items-center">
                            <h3 class="px-2 py-3">Calls Events</h3>
                        </div> -->

                        <div class="">

                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Calls Events</h3>
                                    <!-- Search bar -->
                                    <div class="card-tools">
                                        <div class="input-group input-group-sm" style="width: 250px;">
                                            <input type="text" id="searchCallsEventInput" onkeyup="searchTable('call_events_table', 'searchCallsEventInput')" class="form-control float-right" placeholder="Search Call">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-default">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-1 text-right">
                                            <label for="start_date" >Start</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="date" id='call_log_start_date' name="start_date" class="form-control" title="Start Date" placeholder="Start Date" value="<?php echo date('Y-m-01'); ?>" required onchange="RedirectToActiveTabEvents('calls-event')">
                                        </div>
                                        <div class="col-md-1 text-right">
                                            <label for="end_date">End</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="date" id='call_log_end_date' name="end_date" class="form-control" title="End Date" placeholder="End Date" value="<?php echo date('Y-m-d'); ?>" required onchange="RedirectToActiveTabEvents('calls-event')">
                                        </div>
                                    </div>
                                    <br>
                                    <input type="hidden" name="call_log_page_number" id="call_log_page_number" value="0">
                                    <input type="hidden" name="next_btn_call_event_token" id="next_btn_call_event_token">
                                    <input type="hidden" name="previous_btn_call_event_token" id="previous_btn_call_event_token">
                                    <input type="hidden" name="call_event_page" id="call_event_page" value="0">
                                    <div class="">
                                        <table class="table table-hover table-bordered" id="call_events_table">
                                            <thead>
                                                <tr>
                                                    <th>Call</th>
                                                    <th>From</th>
                                                    <th>To</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <?php if($user_assign): ?>
                                                        <th>Price</th>
                                                    <?php endif; ?>
                                                    <th>Duration</th>
                                                    <th>Action</th>

                                                </tr>
                                            </thead>
                                            <tbody id="call_events_container"></tbody>

                                        </table>
                                        <div class="text-right">
                                            <button class="btn btn-default showHide" id="call_previous_btn" onclick="RedirectToActiveTabEvents('calls-event', 'previous')">Previous</button>
                                            <button class="btn btn-default showHide" id="call_next_btn" onclick="RedirectToActiveTabEvents('calls-event', 'next')">Next</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="card-body table-responsive p-0" style="height: 80vh;" id="call_events_container">
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade <?php echo $activeTab == 'message-event' ? 'show active' : ''; ?>" id="message-event" role="tabpanel" aria-labelledby="message-event-tab">
                    
                    
                    <div class="content" id="message_events">
                        <!-- <div class="d-flex justify-content-between align-items-center">
                            <h3 class="px-2 py-3">Message Events</h3>
                        </div> -->

                        <div class="">

                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Message Events</h3>
                                    <!-- Search bar -->
                                    <div class="card-tools">
                                        <div class="input-group input-group-sm" style="width: 250px;">
                                            <input type="text" id="searchMessagesEventInput" onkeyup="searchTable('message_events_table', 'searchMessagesEventInput')" class="form-control float-right" placeholder="Search Message">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-default">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-1 text-right">
                                            <label for="start_date" >Start</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="date" id='message_log_start_date' name="message_start_date" class="form-control" title="Start Date" placeholder="Start Date" value="<?php echo date('Y-m-01'); ?>" required onchange="RedirectToActiveTabEvents('message-event')">
                                        </div>
                                        <div class="col-md-1 text-right">
                                            <label for="end_date">End</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="date" id='message_log_end_date' name="end_date" class="form-control" title="End Date" placeholder="End Date" value="<?php echo date('Y-m-d'); ?>" required onchange="RedirectToActiveTabEvents('message-event')">
                                        </div>
                                    </div>
                                    <br>
                                    <input type="hidden" name="next_btn_message_event_token" id="next_btn_message_event_token">
                                    <input type="hidden" name="previous_btn_message_event_token" id="previous_btn_message_event_token">
                                    <input type="hidden" name="message_event_page" id="message_event_page" value="0">
                                    <div class="">
                                        <table class="table table-hover table-bordered" id="message_events_table">
                                            <thead>
                                                <tr>
                                                    <th>Message</th>
                                                    <th>From</th>
                                                    <th>To</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <?php if($user_type != "user"){ ?>
                                                        <th>Price</th>
                                                    <?php } ?>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="message_events_container"></tbody>

                                        </table>
                                        <div class="text-right">
                                            <button class="btn btn-default showHide" id="message_previous_btn" onclick="RedirectToActiveTabEvents('message-event', 'previous')">Previous</button>
                                            <button class="btn btn-default showHide" id="message_next_btn" onclick="RedirectToActiveTabEvents('message-event', 'next')">Next</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="card-body table-responsive p-0" style="height: 80vh;" id="call_events_container">
                                </div> -->
                            </div>


                        </div>

                    </div>
                </div>
                <div class="tab-pane fade <?php echo $activeTab == 'call-block' ? 'show active' : ''; ?>" id="call-block" role="tabpanel" aria-labelledby="call-block-tab">
                    <div class="content" id="call_block_content">
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- <h3 class="px-2 py-3">Call Block</h3> -->
                            <button type="button" class="btn btn-primary mt-2 mb-2" onclick="block_call_sms_fields('call')">Add New</button>
                        </div>

                        <div class="isLoading justify-content-center m-5" style="display: none;" id="command_center_loading_indicator">
                            <img src="/include/images/loader.gif" style="width:100px;margin-left:5px" alt="" srcset="">
                        </div>

                        <div class="">

                            <div class="card">

                                <div class="card-header">
                                    <h3 class="card-title">Call Block</h3>
                                    <!-- Search bar -->
                                    <div class="card-tools">
                                        <div class="input-group input-group-sm" style="width: 250px;">
                                            <input type="text" id="searchCallsBlockInput" onkeyup="searchTable('call_block_table', 'searchCallsBlockInput')" class="form-control float-right" placeholder="Search Call Block">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-default">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="">
                                        <table class="table table-hover table-bordered" id="call_block_table">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Phone Number</th>
                                                    <th>Property</th>
                                                    <th>Created By</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="call_block_container"></tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>
                </div>

                <div class="tab-pane fade <?php echo $activeTab == 'sms-block' ? 'show active' : ''; ?>" id="sms-block" role="tabpanel" aria-labelledby="sms-block-tab">
                    <div class="content" id="sms_block_content">
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- <h3 class="px-2 py-3">SMS Block</h3> -->
                            <button type="button" class="btn btn-primary mt-2 mb-2" onclick="block_call_sms_fields('sms')">Add New</button>
                        </div>

                        <div class="isLoading justify-content-center m-5" style="display: none;" id="command_center_loading_indicator">
                            <img src="/include/images/loader.gif" style="width:100px;margin-left:5px" alt="" srcset="">
                        </div>

                        <div class="">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">SMS Block</h3>
                                    <!-- Search bar -->
                                    <div class="card-tools">
                                        <div class="input-group input-group-sm" style="width: 250px;">
                                            <input type="text" id="searchMessageBlockInput" onkeyup="searchTable('sms_block_table', 'searchMessageBlockInput')" class="form-control float-right" placeholder="Search SMS Block">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-default">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="">
                                        <table class="table table-hover table-bordered" id="sms_block_table">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Phone Number</th>
                                                    <th>Property</th>
                                                    <th>Created By</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="sms_block_container"></tbody>

                                        </table>
                                    </div>
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
    <div class="modal fade" id="delete_block_number_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="delete_number_modal_content"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        <p>Are you sure you want to unblock <span id="confirm_number_block"></span></p>
                        <div class="modal-footer">
                            <input type="hidden" name="removed_number_type_modal" id="removed_number_type_modal">
                            <input type="hidden" name="number_remove_number" id="number_remove_number">
                            <input type="hidden" name="is_log_hidden_unblock" id="is_log_hidden_unblock">
                            <input type="hidden" name="removed_number_type_modal" id="removed_number_id_modal">
                            <button type="button" name="assign" onclick="remove_from_block()" class="btn btn-danger">Confirm</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>  
                </div> 
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirm_block_number_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        <p>Are you sure you want to block <span id="confirm_number_block_number"></span></p>
                        <div class="modal-footer">
                            <button type="button" name="assign" onclick="block_number()" class="btn btn-danger">Confirm</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>  
                </div> 
            </div>
        </div>
    </div>

    <div class="modal fade" id="call_sms_block_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <div id="error_sms_call_block_message"></div>
                <div class="modal-header">
                    <h5 class="modal-title" id="call_sms_block_modal_heading"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" id="call_block_name" class="form-control" placeholder="Please add Name">
                            <p id="call_name_block_error_message"></p>
                        </div>
                        <div class="form-group">
                            <label>Number</label>
                            <input type="text" name="number" class="form-control" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" maxlength="12" oninput="formatPhoneNumber(this)" id="call_block_number" onpaste="handlePaste(event)" placeholder="Please add Number">
                            <p id="call_number_block_error_message"></p>
                        </div>
                        <input type="hidden" id="call_sms_block_number_type">
                        <div class="modal-footer">
                            <button onclick="block_number('modal')" type="button" name="assign" class="btn btn-primary">Add</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                </div> 
            </div>
        </div>
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