<?php
  session_start();
  include('../../connection.php');
  if(!isset($_SESSION['authUser'])){
    header('Location:../../logout.php');
  }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="shortcut icon" href="../../dist/img/BK LOGO.png">
  <title>MDC Senior System | Dashboard</title>
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
  <!-- Morris chart -->
  <link rel="stylesheet" href="../../dependencies/morris.js/morris.css">
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
  <script type="text/javascript">
      function setValues(obj){
        $('#modify-body').html('<div align="center"><i class="fa fa-spinner fa-pulse text-green fa-fw"></i>Loading data. Please Wait...</div>');
        var unique = $(obj).attr('data-uniqueid');
        //$('#area-title').html(unique);
        var dataString = "uniqueid=" + unique;
        $.ajax({
          type: "GET",
          url: "userInfo.php?f=role",
          data: dataString,
          cache: false,
          success: function (data) {
            console.log(data);
            setTimeout(function (){
              $("#modify-body").html(data);
            }, 1000);
          },
          error: function(err) {
            console.log(err);
          }
        });
      }
      function deleteValue(obj){
          var username    =  $(obj).attr('data-name');
          var uniqueid    =  $(obj).attr('data-uniqueid');

          $("#deluser").html('You are about to remove <b class="text-blue">' + username + '</b> on the list.');
          document.getElementById('delete_userid').value = uniqueid;
      }
  </script>

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
        <li><a href="../index.php"><i class="fa fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
        <?php if($_SESSION['authRole'] == "TEAM LEADER"){?>
        <li class="header">SANITATION MANAGEMENT</li>
        <li><a href="../dir_sanitation_management/user_maintenance.php"><i class="fa fa-users-cog"></i> <span>User Maintenance</span></a></li>
        <li class="active"><a href="../dir_sanitation_management/team_roles.php" class="bg-blue"><i class="fa fa-users"></i> <span>Team Roles</span></a></li>
        <li><a href="../dir_sanitation_management/district_assignment.php"><i class="fa fa-user-tag"></i> <span>District Assignment</span></a></li>
        <li><a href="../dir_export_report/export_report.php"><i class="fa fa-download"></i> <span>Export Data</span></a></li>
        <?php }?>
        <li class="header">RULES MANAGEMENT</li>
        <li><a href="../dir_rules_management/add_new_rule.php"><i class="fa fa-plus"></i> <span>Add New Rule</span></a></li>
        <?php if($_SESSION['authRole'] == "TEAM LEADER"){?>
        <li><a href="../dir_rules_management/rules_list.php"><i class="fa fa-tasks"></i> <span>Rules List</span></a></li>
        <?php }?>
        <li class="header">DATA SANITATION</li>
        <li>
          <a href="../dir_data_sanitation/data-sanitation.php"><i class="fa fa-clipboard-check"></i> <span>Assigned To Me</span>
            <span class="pull-right-container">
              <span class="label label-success pull-right">4 entries</span>
            </span>
          </a>
        </li>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users text-green"></i> Team Roles
        <small>Manage roles of</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">Team Roles</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-lg-12">
          <div class="box box-success">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                    <tr>
                        <th style="white-space: nowrap;">MEMBER NAME</th>
                        <th style="white-space: nowrap;">ROLE</th>
                        <th style="white-space: nowrap;width:10%;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                      $userQuery = $mysqli -> query("SELECT * FROM auth_users_tbl");
                        while($userRes = $userQuery -> fetch_assoc()){
                    ?>
                    <tr>
                        <td style="white-space: nowrap;"><?php echo $userRes['auth_fullname'];?></td>
                        <td style="white-space: nowrap;"><?php echo $userRes['auth_role'];?></td>
                        <td style="white-space: nowrap;text-align: center;">
                          <a href="#" class="btn btn-warning btn-xs" title="Modify" data-toggle="modal" data-target="#editModal" onclick="setValues(this)" data-uniqueid="<?php echo $userRes['auth_id'];?>"><i class="fa fa-user-edit"></i> Modify</a>
                        </td>
                    </tr>
                    <?php }?>
                </tbody>
              </table>
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

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" style="width: 25%">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" style="display: inline-block;">Member Information</h3>
                <button class="btn-sm close" type="button" data-dismiss="modal">Close</button>
            </div>
            <form enctype="multipart/form-data" method="POST" action="function_users.php?action=update&return=role">
                <div id="modify-body" class="modal-body" style="font-size: 12px;">

                </div>
                <div class="modal-footer" align="right">
                    <button class="btn btn-success">Apply Changes</button>
                    <a href="#" class="btn btn-default" data-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

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
<!-- DataTables -->
<script src="../../dependencies/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../../dependencies/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="../../dependencies/raphael/raphael.min.js"></script>
<script src="../../dependencies/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="../../dependencies/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="../../plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="../../plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
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
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../../dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>
</body>
</html>
