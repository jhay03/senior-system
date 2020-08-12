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
  <link rel="stylesheet" href="../../dependencies/select2/dist/css/select2.min.css">

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
        <li><a href="../index.php"><i class="fa fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
        <?php if($_SESSION['authRole'] == "TEAM LEADER"){?>
        <li class="header">SANITATION MANAGEMENT</li>
        <li><a href="../dir_sanitation_management/user_maintenance.php"><i class="fa fa-users-cog"></i> <span>User Maintenance</span></a></li>
        <li class="active"><a href="../dir_sanitation_management/team_roles.php" class="bg-blue"><i class="fa fa-users"></i> <span>Team Roles</span></a></li>
        <li><a href="../dir_sanitation_management/district_assignment.php"><i class="fa fa-user-tag"></i> <span>District Assignment</span></a></li>
        <?php }?>
        <li class="header">RULES MANAGEMENT</li>
        <li><a href="../dir_rules_management/add_new_rule.php"><i class="fa fa-plus"></i> <span>Add New Rule</span></a></li>
        <?php if($_SESSION['authRole'] == "TEAM LEADER"){?>
        <li><a href="../dir_rules_management/rules_list.php"><i class="fa fa-tasks"></i> <span>Rules List</span></a></li>
        <?php }?>
        <li class="header">DATA SANITATION</li>
        <li>
          <a href="../dir_data_sanitation/index.php"><i class="fa fa-clipboard-check"></i> <span>Assigned To Me</span>
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
        <i class="fa fa-users text-green"></i> Data Sanitation
        <small>Manage roles of</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">Data Sanitation</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-lg-12">
          <div class="box box-success">
            <div class="box-header with-border">
              <h4>200,000 Unsanitized Records in North Luzon</h4>
              <h3>50 Rows Similar to Alenton, Rodgrigo</h3>
              <div class="pull-left">
                <button type="button" class="btn btn-info" id="btnrefresh">VIEW REMAINING</button>
              </div>
            </div>
            <div class="box-body">
              <table id="example2" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>
                      <select class="form-control select2" name="md_name_group[]" id="md_name_group" style="width:100%;">
                        <option value="ALL"> ------ ALL DATA ----- </option>
                      </select>
                      <center><i class="fa fa fa-spinner fa-spin" id="md_name_group_spinner" style="display:none;"></i></center>
                    </th>
                    <th>
                      <select class="form-control select2" name="md_group_ln[]" id="md_group_ln" style="width:100%;">
                        <option value="ALL"> ------ ALL DATA -----</option>
                      </select>
                      <center><i class="fa fa-refresh fa-spin" id="md_group_ln_spinner" style="display:none;"></i></center>
                    </th>
                    <th>
                      <select class="form-control select2" name="md_group_loc[]" id="md_group_loc" style="width:100%;">
                        <option value="ALL"> ------ ALL DATA -----</option>
                      </select>
                      <center><i class="fa fa-refresh fa-spin" id="md_group_loc_spinner" style="display:none;"></i></center>
                    </th>
                    <th>
                      <select class="form-control select2" name="md_group_branch[]" id="md_group_branch" style="width:100%;">
                        <option value="ALL"> ------ ALL DATA -----</option>
                      </select>
                      <center><i class="fa fa-refresh fa-spin" id="md_group_branch_spinner" style="display:none;"></i></center>
                    </th>
                    <th>
                      <select class="form-control select2" name="md_group_lba[]" id="md_group_lba" style="width:100%;">
                        <option value="ALL"> ------ ALL DATA -----</option>
                      </select>
                      <center><i class="fa fa-refresh fa-spin" id="md_group_lba_spinner" style="display:none;"></i></center>
                    </th>
                  </tr>
                  <tr>
                    <th style="white-space: nowrap;">Doctor</th>
                    <th style="white-space: nowrap;">LN</th>
                    <th style="white-space: nowrap;">Location</th>
                    <th style="white-space: nowrap;">Branch</th>
                    <th style="white-space: nowrap;">LBA</th>
                </tr>
                </thead>
              <tbody>
              </tbody>
            </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <div class="pull-left">
                <button type="button" class="btn btn-danger">Unclassified</button>
              </div>
              <div class="pull-right">
                <button type="button" class="btn btn-warning">Assign to Alenton, Rodgrigo</button>
              </div>
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
<script src="../../dependencies/select2/dist/js/select2.min.js"></script>
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
$(document).ready(function() {
  var table;
  //FOR DATATABLE
  setTimeout(function(){
       table = $('#example2').DataTable( {
       scrollY: "45vh",
       scrollX: false,
       paging: false,
       ajax: "includes/controller/get_data_sanitation.php",
       info: false,
       ordering: true,
       scrollCollapse: true,
       responsive: true,
       searching: false,
       autoWidth: true,
       processing: true
    } );
  }, 100);

   getGroupMDname('ALL','ALL','district');

  //GET DATA
  function getGroupMDname(data,category,district){
    $( "#md_name_group" ).prop( "disabled", true );
    var token = 'BKPI2017';
    var cmd = 'get_group_md_by_district';
    var mdName = data;
    var dataString = "token=" + token + "&cmd=" + cmd + "&data=" + data + "&category=" + category + "&district=" + district;
    $.ajax({
      type: "POST",
      url: "includes/controller/index.php",
      data: dataString,
      dataType: 'json',
      beforeSend: function( xhr ) {
        $('#md_name_group_spinner').show();
      },
      success: function (response) {
          // console.log(response);
          $('#md_name_group').empty();
          $('#md_name_group').select2({
            placeholder: "Select MD",
            allowClear: false,
            data: response
          });
          $('#md_name_group_spinner').hide();
          $( "#md_name_group" ).prop( "disabled", false );

      },
      error: function (xhr, ajaxOptions, thrownError) {
        alert(xhr.status);
        alert(thrownError);
      }
    });
  }
  getGroupLN('ALL','ALL','district');
  //GET GROUP LN DATA
  function getGroupLN(data,category,district){
    $( "#md_group_ln" ).prop( "disabled", true );
    var token = 'BKPI2017';
    var cmd = 'get_group_ln_by_district';
    var mdName = data;
    var dataString = "token=" + token + "&cmd=" + cmd + "&data=" + data + "&category=" + category + "&district=" + district;
    $.ajax({
      type: "POST",
      url: "includes/controller/index.php",
      data: dataString,
      dataType: 'json',
      beforeSend: function( xhr ) {
        $('#md_group_ln_spinner').show();
      },
      success: function (response) {
          // console.log(response);
          $('#md_group_ln').empty();
          $('#md_group_ln').select2({
            placeholder: "Select LN",
            allowClear: false,
            data: response
          });
          $('#md_group_ln_spinner').hide();
          $( "#md_group_ln" ).prop( "disabled", false );

      }
    });
  }
  getGroupLoc('ALL','ALL','district');
  //GET GROUP LOCATION DATA
  function getGroupLoc(data,category,district){
    $( "#md_group_loc" ).prop( "disabled", true );
    var token = 'BKPI2017';
    var cmd = 'get_group_loc_by_district';
    var mdName = data;
    var dataString = "token=" + token + "&cmd=" + cmd + "&data=" + data + "&category=" + category + "&district=" + district;
    $.ajax({
      type: "POST",
      url: "includes/controller/index.php",
      data: dataString,
      dataType: 'json',
      beforeSend: function( xhr ) {
        $('#md_group_loc_spinner').show();
      },
      success: function (response) {
          // console.log(response);
          $('#md_group_loc').empty();
          $('#md_group_loc').select2({
            placeholder: "Select Location",
            allowClear: false,
            data: response
          });
          $('#md_group_loc_spinner').hide();
          $( "#md_group_loc" ).prop( "disabled", false );

      },
      error: function (xhr, ajaxOptions, thrownError) {
        alert(xhr.status);
        alert(thrownError);
      }
    });
  }
  getGroupBranch('ALL','ALL','district');
  //GET GROUP BRANCH DATA
  function getGroupBranch(data,category,district){
    $( "#md_group_branch" ).prop( "disabled", true );
    var token = 'BKPI2017';
    var cmd = 'get_group_branch_by_district';
    var mdName = data;
    var dataString = "token=" + token + "&cmd=" + cmd + "&data=" + data + "&category=" + category + "&district=" + district;
    $.ajax({
      type: "POST",
      url: "includes/controller/index.php",
      data: dataString,
      dataType: 'json',
      beforeSend: function( xhr ) {
        $('#md_group_branch_spinner').show();
      },
      success: function (response) {
          // console.log(response);
          $('#md_group_branch').empty();
          $('#md_group_branch').select2({
            placeholder: "Select Branch",
            allowClear: false,
            data: response
          });
          $('#md_group_branch_spinner').hide();
          $( "#md_group_branch" ).prop( "disabled", false );

      }
    });
  }
  getGroupLBA('ALL','ALL','district');
  //GET GROUP BRANCH DATA
  function getGroupLBA(data,category,district){
    $( "#md_group_lba" ).prop( "disabled", true );
    var token = 'BKPI2017';
    var cmd = 'get_group_lba_by_district';
    var mdName = data;
    var dataString = "token=" + token + "&cmd=" + cmd + "&data=" + data + "&category=" + category + "&district=" + district;
    $.ajax({
      type: "POST",
      url: "includes/controller/index.php",
      data: dataString,
      dataType: 'json',
      beforeSend: function( xhr ) {
        $('#md_group_lba_spinner').show();
      },
      success: function (response) {
          // console.log(response);
          $('#md_group_lba').empty();
          $('#md_group_lba').select2({
            placeholder: "Select LBA",
            allowClear: false,
            data: response
          });
          $('#md_group_lba_spinner').hide();
          $( "#md_group_lba" ).prop( "disabled", false );

      }
    });
  }

  //DROP DOWN ON CHANGE PROCESS
  $('#md_name_group').on('select2:select', function (e) {
      var data = $('#md_name_group').val();
      filsterSanitationData(data,'md_name','district');
  });

  $('#md_group_ln').on('select2:select', function (e) {
      var data = $('#md_group_ln').val();
      filsterSanitationData(data,'license','district');
  });

  $('#md_group_loc').on('select2:select', function (e) {
      var data = $('#md_group_loc').val();
      filsterSanitationData(data,'location','district');
  });

  function filsterSanitationData(data,category,district){

    table.ajax.url( 'includes/controller/search_result_data_sanitation.php?data='+data+'&category='+category+'&district='+district ).load();
    // table.columns.adjust().draw();
    // setTimeout(function(){
    // }, 500);

    var cmd = 'search_data_sanitation_by_md_name';
    var district = district;
    var dataString = "data=" + data + "&cmd=" + cmd + "&district=" + district + "&category=" + category;

      $.ajax({
        type: "POST",
        url: "includes/controller/index.php",
        data: dataString,
        dataType: 'json',
        success: function (response) {
           // console.log(response);
          getGroupMDname(data,category,district);
          getGroupLN(data,category,district);
          getGroupLoc(data,category,district);
          getGroupBranch(data,category,district);
          getGroupLBA(data,category,district);

        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
        }
      });

  }

  $("#btnrefresh").click(function (e){
    e.preventDefault();
    getGroupMDname('ALL');
    getGroupLN('ALL');
    getGroupLoc('ALL');
    getGroupBranch('ALL');
    getGroupLBA('ALL');
    table.ajax.url( 'includes/controller/get_data_sanitation.php' ).load();
  })



} );
</script>
</body>
</html>
