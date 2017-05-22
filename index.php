
<?php session_start(); /* Starts the session */

if(!isset($_SESSION['UserData']['Username'])){
   // header("location:login.php");
    exit;
}
?>
<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Notifications </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="orangeApp/orange/bootstrap/css/bootstrap.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="orangeApp/orange/dist/css/sampleApp.min.css">

    <link rel="stylesheet" href="orangeApp/orange/dist/css/skins/skin-black.min.css">

    <script src="web/js/jquery1.4.js"></script>
    <script src="web/js/sampleApp.js"></script>
    <script src="web/js/jsrender.js"></script>
    <script src="web/js/notify.js"></script>

    <!--
    JS render templates section
    these templates will be rendered from sampleApp.js
     -->
    <!-- employee new users template -->
    <script id="newUsers" type="text/x-jsrender">

    <div class="item" id="item_">
        <img src="orangeApp/orange/dist/img/notification_icon.png" alt="user image" class="offline">
        <p class="message">
            <a href="#" class="name"> <small class="text-muted pull-right"><i class="fa fa-clock-o"></i></small> {{:employeeName}} </a>{{:employeeName}} has joined on {{:createdDate}} </p>
    </div>

</script>
    <!-- employee on leave template -->
    <script id="onLeave" type="text/x-jsrender">

    <div class="item" id="item_56"> <img src="orangeApp/orange/dist/img/notification_icon.png" alt="user image" class="offline">
        <p class="message">
            <a href="#" class="name"> <small class="text-muted pull-right"><i class="fa fa-clock-o"></i></small> {{:employeeName}} </a>{{:employeeName}} is on {{:type}} Leave from {{:fromDate}} to {{:toDate}} </p>
    </div>

</script>
    <!-- employee leave requests template -->
 <script id="empLeaveRequests" type="text/x-jsrender">

    <div class="item" id="item_56"> <img src="orangeApp/orange/dist/img/notification_icon.png" alt="user image" class="offline">
        <p class="message">
            <a href="#" class="name"> <small class="text-muted pull-right"><i class="fa fa-clock-o"></i></small> {{:employeeName}} </a>{{:employeeName}} has applied for {{:type}} Leave from {{:fromDate}} to {{:toDate}} </p>
    </div>
    </div>

</script>
    <!-- employee employee events template ( update /contact/job/supervisor )-->
 <script id="employeeEvents" type="text/x-jsrender">

    <div class="item" id="item_"{{:id}}> <img src="orangeApp/orange/dist/img/notification_icon.png" alt="user image" class="offline">
        <p class="message">
            <a href="javascript:getNotificationDetails({{:id}},'{{:event}}')" class="name"> <small class="text-muted pull-right"><i class="fa fa-clock-o"></i>2017-05-03 10:14:18am</small> {{:name}} </a> {{:msg}} </p>
    </div>
</script>

    <!-- employee notification template -->
<script id="employeeNotification" type="text/x-jsrender">

    <div class="panel panel-info">
    <div class="panel-heading">Notification</div>
    <div class="panel-body">
        <ul>
            <li>Name : {{:fullName}}</li>
            <li>Mobile :{{:mobile}} </li>
            <li>Work Email :{{:workEmail}} </li>
            <li>Other Email :{{:otherEmail}} </li>
            <li>Work Phone :{{:workPhone}} </li>
        </ul>
    </div>

</div>

</script>

    <!-- employee supervisor template -->
 <script id="supervisorNotification" type="text/x-jsrender">

    <div class="panel panel-info">
    <div class="panel-heading">Notification</div>
    <div class="panel-body">
        <ul>
            <li>Supervisor Name : {{:name}}</li>
            <li>Reporting Method :{{:reportingMethod}} </li>
        </ul>
    </div>
</div>
</script>

    <!-- employee job details template -->
 <script id="jobDetailsNotification" type="text/x-jsrender">

    <div class="panel panel-info">
    <div class="panel-heading">Notification</div>
    <div class="panel-body">
        <ul>
            <li>Status : {{:status}}</li>
            <li>Job Title :{{:title}} </li>
             <li>Unit : {{:subunit}}</li>
            <li>Location :{{:location}} </li>
             <li>Location :{{:category}} </li>
        </ul>
    </div>
</div>
</script>



</head>

<body class="hold-transition skin-black sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="#" class="logo">
            <img src="orangeApp/orange/dist/img/logo2.png" alt="User Image">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>A</b>LT</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Admin</b>LTE</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <img src="orangeApp/orange/dist/img/menu_icon1.png" class="user-image" alt="User Image">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Messages: style can be found in dropdown.less-->

                    <!-- /.messages-menu -->


                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <img src="orangeApp/orange/dist/img/orange.png" class="user-image" alt="User Image">
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs">Notifications</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img src="orangeApp/orange/dist/img/orange.png" class="img-circle" alt="User Image">

                                <p>
                                    Notification Viewer
                                    <small>Based on Orange Open Source 4.0 API platform</small>
                                </p>
                            </li>
                            <!-- Menu Body -->
                            <li class="user-body">
                                <div class="row">
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Followers</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Sales</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Friends</a>
                                    </div>
                                </div>
                                <!-- /.row -->
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat">Profile</a>
                                </div>
                                <div class="pull-right">
                                    <a href="#" class="btn btn-default btn-flat">Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <!-- Sidebar user panel (optional) -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="orangeApp/orange/dist/img/orange.png" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>Notifications</p>
                    <!-- Status -->
                    <a href="#"><i class="fa fa-circle text-success"></i> Active</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">
                <li class="header">HEADER</li>
                <!-- Optionally, you can add icons to the links -->
                <li class="active"><a href="#"><i class="fa fa-link"></i> <span>Dashboard</span></a></li>


            </ul>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Notifications
                <small>Dashboard</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Your Page Content Here -->
            <!-- Info Boxes -->
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box" onclick="goBack()">
                        <span class="info-box-icon bg-aqua"><img src="orangeApp/orange/dist/img/events_icon.png" class="user-image" alt="User Image"></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Notifications</span>
                            <span class="info-box-number" id="empEvents"><small></small></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box" onclick="showOnLeaveToday()">
                        <span class="info-box-icon bg-red"><img src="orangeApp/orange/dist/img/leave_icon.png" class="user-image" alt="User Image"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text"><small>On Leave Today</small></span>
                            <span class="info-box-number " id="leaveToday"></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <!-- fix for small devices only -->
                <div class="clearfix visible-sm-block"></div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box" onclick="showLeaveRequests()">
                        <span class="info-box-icon bg-green"><img src="orangeApp/orange/dist/img/leave_request.png" class="user-image" alt="User Image"></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Leave Requests</span>
                            <span class="info-box-number" id="leaveRequests"></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box" onclick="showNewMembers()">
                        <span class="info-box-icon bg-yellow"><img src="orangeApp/orange/dist/img/new_user.png" class="user-image" alt="User Image"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">New Members</span>
                            <span class="info-box-number" id="newlyJoined"></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>

            <!-- Notification Box -->
            <div class="box box-success">
                <div style="cursor: move;" class="box-header ui-sortable-handle">
                    <i class="fa fa-flag-o"></i>

                    <h3 class="box-title"> Notifications
                    </h3>

                    <div data-original-title="Status" class="box-tools pull-right" data-toggle="tooltip" title="">
                        <div class="btn-group" data-toggle="btn-toggle">

                        </div>
                    </div>
                </div>
                <div style="position: relative; overflow: hidden; width: auto; height: 250px;" class="slimScrollDiv">
                    <div style="overflow: auto; width: auto; height: 250px;" class="box-body chat" id="chat-box">
                        <!-- Notification item -->
                        <div id='notificationItemsContainer'>  <!-- item container -->

                        </div>
                        <button class="backButton backButtonStyle"  onclick="goBack()" >Back</button>
                    </div>  <!-- /.item container -->
                    <div
                        style="background: none repeat scroll 0% 0% rgb(0, 0, 0); width: 7px; position: absolute; top: 25px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 187.126px;"
                        class="slimScrollBar"></div>
                    <div
                        style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: none repeat scroll 0% 0% rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"
                        class="slimScrollRail"></div>
                </div>
                <!-- /.chat -->
                <div class="box-footer">

                </div>
            </div>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
            Notification Dashboard
        </div>
        <!-- Default to the left -->
        <strong>Notifications dashboard © 2005 - <script>document.write(new Date().getFullYear())</script> <br> <a target="_blank" href="https://www.orangehrm.com/">OrangeHRM, Inc</a></strong>.All rights reserved

    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
            <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Home tab content -->
            <div class="tab-pane active" id="control-sidebar-home-tab">
                <h3 class="control-sidebar-heading">Recent Activity</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:;">
                            <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                                <p>Will be 23 on April 24th</p>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->

                <h3 class="control-sidebar-heading">Tasks Progress</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:;">
                            <h4 class="control-sidebar-subheading">
                                Custom Template Design
                                <span class="pull-right-container">
                  <span class="label label-danger pull-right">70%</span>
                </span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->

            </div>
            <!-- /.tab-pane -->
            <!-- Stats tab content -->
            <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
            <!-- /.tab-pane -->
            <!-- Settings tab content -->
            <div class="tab-pane" id="control-sidebar-settings-tab">
                <form method="post">
                    <h3 class="control-sidebar-heading">General Settings</h3>

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Report panel usage
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Some information about this general settings option
                        </p>
                    </div>
                    <!-- /.form-group -->
                </form>
            </div>
            <!-- /.tab-pane -->
        </div>
    </aside>
    <!-- /.control-sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- Bootstrap 3.3.6 -->
<script src="orangeApp/orange/bootstrap/js/bootstrap.min.js"></script>

<script src="orangeApp/orange/dist/js/app.min.js"></script>

</body>
</html>
