<?php
  session_start();
  include('../../connection.php');
  require("includes/php-functions.php") ;
  if(!isset($_SESSION['authUser'])){
    header('Location:../../logout.php');
  }

  $user_full_name = $_SESSION['authUser'] ;
  $user_name = $_SESSION['auth_usercode'] ;
  $position = $_SESSION['authRole'] ;
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="shortcut icon" href="../../dist/img/BK LOGO.png">
  <title>MDC Senior System | Rules List</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../../dependencies/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../dependencies/Ionicons/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../dependencies/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../dist/css/skins/skin-blue.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="../../dependencies/select2/dist/css/select2.min.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="../../dependencies/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="../../dependencies/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../../dependencies/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="../../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="index.php" class="logo" style="background-color: #256188;">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>BK</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg" align="left" style="font-size:14px;font-weight:bold;">
		<img src="../../dist/img/BK LOGO.png" class="img-circle" alt="User Image" style="width:40px;">
		MDC SENIOR SYSTEM
	</span>

    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a class="pull-left" data-toggle="push-menu" role="button" style="margin-left: 10px;margin-top: 15px;color: #FFFFFF;cursor: pointer;"><i class="fa fa-bars"></i> </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="../../dist/img/admin.png" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $_SESSION['authUser'];?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="../../dist/img/admin.png" class="img-circle" alt="User Image">

                <p>
                  <?php echo $_SESSION['authUser'];?>
                  <small><?php echo $_SESSION['authRole'];?></small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-right">
                  <a href="../../logout.php" class="btn btn-warning btn-flat"><i class="fa fa-sign-out-alt"></i> Sign out</a>
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

      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li><a href="index.php" ><i class="fa fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
        <?php if($_SESSION['authRole'] == "TEAM LEADER"){?>
        <li class="header">DATABASE MAINTENANCE</li>
        <li><a href="../dir_database_maintenance/masterlist_db.php"><i class="fa fa-database"></i> <span>Masterlist Database</span></a></li>
        <li class="header">SANITATION MANAGEMENT</li>
        <li><a href="../dir_sanitation_management/user_maintenance.php"><i class="fa fa-users-cog"></i> <span>User Maintenance</span></a></li>
        <li><a href="dir_sanitation_management/team_roles.php"><i class="fa fa-users"></i> <span>Team Roles</span></a></li>
        <li><a href="dir_sanitation_management/district_assignment.php"><i class="fa fa-user-tag"></i> <span>District Assignment</span></a></li>
        <?php }?>
        <li class="header">RULES MANAGEMENT</li>
        <li   ><a href="index.php"><i class="fa fa-plus "></i> <span>Add New Rule</span></a></li>
        <?php if($_SESSION['authRole'] == "TEAM LEADER"){?>
        <li><a href="rules_list.php"><i class="fa fa-tasks"></i> <span>Rules List</span></a></li>
        <li class="active"><a href="#"  class="bg-blue"><i class="fa fa-thumbs-up"></i> <span>Rules Approval</span>

          <?Php echo rules_for_approval()  ; ?>


            </a>
        </li>
        <?php }?>
        <li class="header">DATA SANITATION</li>
        <li>
          <a href="../dir_data_sanitation/data-sanitation.php"><i class="fa fa-clipboard-check"></i> <span>Masterlist Sanitation</span></a>
        </li>
        <li>
          <a href="../dir_data_sanitation/data-sanitation-not-masterlist.php"><i class="fa fa-clipboard-check"></i> <span>Not Masterlist Sanitation</span></a>
        </li>
        <li class="header">REPORTS</li>
        <li><a href="../dir_reports/reports.php"><i class="fa fa-folder"></i> <span>MDC SC Report</span></a></li>



      </ul>

    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-tasks text-green"></i> Approve Rules
        <small>Manage rules</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">Rules List</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-lg-12">
          <div class="box box-success">
            <!-- /.box-header -->
            <p id='changes'></p>
            <div class="box-body">


              <table id="rules-table-approve" class="table table-bordered table-striped">
                <thead style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                    <tr>
                        <th style="white-space: nowrap;">ASSIGNED TO</th>
                        <th style="white-space: nowrap;">RULE</th>
                        <th style="white-space: nowrap;"></th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                      $get_rule_code = mysqli_query($mysqli,"SELECT b.rule_code,rule_assign_to,status FROM rules_details as a LEFT JOIN rules_tbl as b ON a.rule_code = b.rule_code WHERE 1=1 AND status = '2' GROUP BY b.rule_code ORDER BY rule_assign_to, status ASC  ");

                        if (mysqli_num_rows($get_rule_code) > 0) {
                          while ($row_rule_code = mysqli_fetch_assoc($get_rule_code)) {
                            $rule_code = $row_rule_code['rule_code'] ;
                            $assigned_to = $row_rule_code['rule_assign_to'] ;
                            $status = $row_rule_code['status'] ;

                            $get_rule_info = mysqli_query($mysqli,
                            $s="SELECT
                                    UPPER(details_column_name) as details_column_name,
                                    UPPER(details_value_optr) as details_value_optr,
                                    details_value,
                                    UPPER(details_condition_optr) as details_condition_optr
                            FROM rules_details
                            WHERE rule_code = '$rule_code'
                            ");

                            $rule_context = "";
                            while ($row_rule_info = mysqli_fetch_assoc($get_rule_info) ) {
                                  $details_column_name = str_replace("RAW_" , "" , $row_rule_info['details_column_name']) ;
                                  $details_value_optr =  "<b>" . $row_rule_info['details_value_optr'] . "</b>" ;
                                  $details_value =  "'" . $row_rule_info['details_value'] . "'" ;
                                  $details_condition_optr =  "<b>" . $row_rule_info['details_condition_optr'] . "</b>" ;
                                  $rule_info = $details_column_name . " " . $details_value_optr . " " . $details_value . " " . $details_condition_optr ;
                                  $rule_context .= $rule_info . " " ;

                            }
                    ?>
                    <tr>
                        <td style="white-space: nowrap;"><?php echo $assigned_to;?></td>
                        <td style="white-space: nowrap;"><?php echo $rule_context;?></td>



                        </td>
                        <td style="white-space: nowrap;">
                          <center>
                                  <a href="#"
                                  style="font-size:10px;"
                                  class="btn btn-xs btn-success "
                                  id="test"
                                  data-toggle='modal'
                                  data-id='<?Php echo $rule_code; ?>'
                                  data-action='approve'
                                  ><i class="fa fa-check"></i></a>


                                  <a href="#"
                                  style="font-size:10px;"
                                  data-id='<?Php echo $rule_code; ?>'
                                  data-action='delete'
                                  class="btn btn-xs btn-danger"><i class="fa fa-trash-alt"></i>
                                  </a>


                          </center>
                        </td>
                    </tr>
                    <?php }}?>
                </tbody>
              </table>

              <div class="display-me">

              </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
            </div>
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">

      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.0.0
    </div>
    <strong>Copyright &copy; 2018 <a href="#">MDC Senior System</a>.</strong> All rights
    reserved.
  </footer>


</div>
<!-- ./wrapper -->


<?php include("modals/modals.php") ; ?>
<!-- jQuery 3 -->
<script src="../../dependencies/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../../dependencies/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="../../dependencies/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="../../dependencies/select2/dist/js/select2.full.min.js"></script>
<!-- DataTables -->
<script src="../../dependencies/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../../dependencies/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- jQuery Knob Chart -->
<script src="../../dependencies/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../../dependencies/moment/min/moment.min.js"></script>
<script src="../../dependencies/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="../../dependencies/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="../../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="../../dependencies/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../../dependencies/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
  <?Php require("includes/js.php") ?>


<script>

// --------------------------------------- DATATABLE
// --------------------------------------- DATATABLE
$('#rules-table-approve').DataTable({
  // scrollX:        true,
  // scrollY:        "40vh",
  //  scrollCollapse: true,
  //  paging:         false
  'paging'      : true,
  'lengthChange': false,
  'searching'   : true,
  'ordering'    : true,
  'info'        : true,
  'autoWidth'   : false
});
// --------------------------------------- DATATABLE
// --------------------------------------- DATATABLE


</script>

</body>
</html>
