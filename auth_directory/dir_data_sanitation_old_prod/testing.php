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
  <link rel="stylesheet" href="includes/dependencies/jQueryUI/jquery-ui.css">
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
  <link href="../../plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet">
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
  <style media="screen">
    .select2-container .select2-selection--multiple .select2-selection__rendered {
      display: inline-block;
      overflow: hidden;
      padding-left: 8px;
      font-size: 12px;
      text-overflow: ellipsis;
      white-space: nowrap;
      display: block;
      max-height: 300px;
      overflow-y: auto;
      -ms-overflow-style: -ms-autohiding-scrollbar;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      color: #333;
      line-height: 22px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
      color: #fff;
      background-color: #f0ad4e;
      border-color: #eea236;
      padding: 1px 10px;
      font-size: 12px;
    }
    .select2-results__option {
      padding: 6px;
      user-select: none;
      -webkit-user-select: none;
      font-size: 12px;
  }
  /* .dataTables_filter { display: none; } */
  table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after, table.dataTable thead .sorting_asc_disabled:after, table.dataTable thead .sorting_desc_disabled:after{
    display: none;
  }
  </style>
</head>
<div id="modalOtherDoctor" class="modal fade in" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Assign to Doctor</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
            <label>Doctor:</label>
              <input id="doctor_list_other" name="doctor_list_other" placeholder="Lastname, firstname"  class="form-control">
              <input  type="hidden" id="md_code_other" name="md_code_other" class="form-control">
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id="btnConfirmAssignment">Assign</button>
      </div>
    </div>
  </div>
</div>
<div id="modalAddDoctor" class="modal fade in" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #00c0ef; color: #fff">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Doctor</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
            <label>MD Name:</label>
              <input id="txtMDname" name="txtMDname" placeholder="Lastname, firstname"  class="form-control">
              <span class="help-block" id="txtMDnameError" style="color:red;display:none;">*This is a required field</span>
          </div>
          <div class="form-group">
            <label>MD Code:</label>
              <input id="txtMDcode" name="txtMDcode" class="form-control">
          </div>
          <div class="form-group">
            <label>MD Status:</label>
              <select class="form-control" id="txtMDstatus" name="txtMDstatus">
                <option value="FOR RECRUITMENT">FOR RECRUITMENT</option>
                <option value="IPG">IPG</option>
                <option value="IPG-D">IPG-D</option>
                <option value="JEDI">JEDI</option>
                <option value="PROS">PROS</option>
                <option value="JEDI-IPGD">JEDI-IPGD</option>
                <option value="JEDI-NEPHRO">JEDI-NEPHRO</option>
                <option value="NEPHRO">NEPHRO</option>
                <option value="NON-GROUP">NON-GROUP</option>
                <option value="NON-PROS">NON-PROS</option>
                <option value="PADAWAN">PADAWAN</option>
                <option value="PROS">PROS</option>
                <option value="REMOVED">REMOVED</option>
              </select>
          </div>
          <div class="form-group">
            <label>MD Universe:</label>
              <input id="txtMDuniverse" name="txtMDuniverse" class="form-control">
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id="btnAddDoctor">Save</button>
      </div>
    </div>
  </div>
</div>


<div id="modalConfirmation" class="modal fade in" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="background-color: #008d4c; color: #fff">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Confirmation:</h4>
        <h4 class="modal-title" id="lblConfirmDistrict"></h4>
        <h4 class="modal-title" id="lblConfirmDoctor"></h4>
      </div>
      <div class="modal-body">
        <table id="example3" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th style="white-space: nowrap;">ID</th>
              <th style="white-space: nowrap;"><input type="checkbox" id="raw_doctor" name="options[]" value="raw_doctor"> Doctor</th>
              <th style="white-space: nowrap;"><input type="checkbox" id="raw_license" name="options[]" value="raw_license"> LN</th>
              <th style="white-space: nowrap;"><input type="checkbox" id="raw_address"  value="raw_address"> Location</th>
              <th style="white-space: nowrap;"><input type="checkbox" id="raw_branchname"  value="raw_branchname"> Branch Name</th>
              <th style="white-space: nowrap;"><input type="checkbox" id="raw_lbucode"  value="raw_lbucode"> LBA</th>
              <th style="white-space: nowrap;">SANITIZED BY</th>
              <th style="white-space: nowrap;">DATE</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>

        <table id="tableUnclean" style="display: none;">
          <thead>
            <tr>
            <tr>
              <th style="white-space: nowrap;">ID</th>
              <th style="white-space: nowrap;">Doctor</th>
              <th style="white-space: nowrap;">LN</th>
              <th style="white-space: nowrap;">Location</th>
              <th style="white-space: nowrap;">Branch Name</th>
              <th style="white-space: nowrap;">LBA</th>
          </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id="btnConfirm">Confirm</button>
      </div>
    </div>
  </div>
</div>

<div id="modalUnclassified" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="background-color: #d33724; color: #fff">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Unclassified</h4>
      </div>
      <div class="modal-body">
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>
            <tr>
              <th style="white-space: nowrap;">ID</th>
              <th style="white-space: nowrap;">Doctor</th>
              <th style="white-space: nowrap;">LN</th>
              <th style="white-space: nowrap;">Location</th>
              <th style="white-space: nowrap;">Branch Name</th>
              <th style="white-space: nowrap;">LBA</th>
          </tr>
          </thead>
        <tbody>
        </tbody>
      </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" id="btnUnclassified">Unclassified</button>
      </div>
    </div>
  </div>
</div>



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
        <li><a href="../dir_sanitation_management/team_roles.php"><i class="fa fa-users"></i> <span>Team Roles</span></a></li>
        <li><a href="../dir_sanitation_management/district_assignment.php"><i class="fa fa-user-tag"></i> <span>District Assignment</span></a></li>
        <?php }?>
        <li class="header">RULES MANAGEMENT</li>
        <li><a href="../dir_rules_management/rules_list.php"><i class="fa fa-plus"></i> <span>Add New Rule</span></a></li>
        <?php if($_SESSION['authRole'] == "TEAM LEADER"){?>
        <li><a href="../dir_rules_management/rules_list.php"><i class="fa fa-tasks"></i> <span>Rules List</span></a></li>
        <?php }?>
        <li class="header">DATA SANITATION</li>
        <li>
          <a href="../dir_data_sanitation/data-sanitation.php"><i class="fa fa-clipboard-check"></i> <span>Masterlist Sanitation</span></a>
        </li>
        <li class="active">
          <a href="../dir_data_sanitation/data-sanitation-not-masterlist.php" class="bg-blue"><i class="fa fa-clipboard-check"></i> <span>Not Masterlist Sanitation</span></a>
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
        <i class="fa fa-filter text-green"></i> Data Sanitation for Not Masterlist
        <!-- <small>Manage roles of</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">Data Sanitation for Not Masterlist</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Main row -->
      <div class="row">
        <div class="col-lg-12">
            <div class="box box-success">
              <div class="box-header with-border">
                <div class="col-lg-12">
                     <div class="pull-left">
                       <form class="form-inline">
                         <div class="form-group">
                           <label>District:</label>
                           <select class="form-control select2" name="district_list[]" id="district_list" multiple="multiple" style="width: 500px;">
                           </select>
                           <center><i class="fa fa-spinner fa-spin" id="district_list_spinner" style="display:none;"></i></center>
                         </div>
                         <div class="form-group" style="display:none;">
                           <label style="padding-left:20px;">Doctor:</label>
                             <input id="doctor_list"  name="doctor_list" placeholder="Lastname, firstname"  class="form-control" style="width: 300px;">
                             <input  type="hidden" id="md_code" name="md_code" class="form-control">

                         </div>
                         <button type="button" class="btn btn-success" id="btnSearchDataSanitation">Search</button>
                       </form>
                     </div>
                </div>
                <div class="col-lg-12">
                <div id='lblDistrictDetails2'></div>
                <div id='lblDistrictDetails'></div>
                <h3 id="lblSimilarRows"></h3>
                <div class="pull-left">
                  <button type="button" class="btn btn-danger btn-xs" id="btnGetTableDataUnclassified">Unclassified</button>
                </div>
                <div class="pull-right">
                  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalAddDoctor">Add Doctor</button>
                  <button type="button" class="btn btn-success" id="btnGetTableData" disabled style="display:none;">Assign to: <span id="lblMDname"></span> </button>
                  <button type="button" class="btn btn-success" id="btnAssignOther" data-toggle="modal" data-target="#modalOtherDoctor" disabled>Assign to Doctor</button>
                </div>
              </div>
              </div>
              <div class="box-body">
                <div>
					             Show/Hide Column:
                       <select class="selectpicker" id="selectColShowHide" multiple data-selected-text-format="count">
                            <option value="0">ID</option>
                            <option value="1">Doctor</option>
                            <option value="2">Sanitized Name</option>
                            <option value="3">LN</option>
                            <option value="4">Location</option>
                            <option value="5">Branch Name</option>
                            <option value="6">LBA</option>
                            <option value="7">Hospital Code</option>
                            <option value="8">SAR Code</option>
                            <option value="9">Amount</option>
                            <option value="10">Sanitized by</option>
                            <option value="11">Sanitized Date</option>
                        </select>
				         </div>
                 <center><i class="fa fa-spinner fa-spin" id="table_loading" style="display:none;"></i></center>
                 <br>
                <table id="example2" style="width:100%" class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="white-space: nowrap;">ID</th>
                      <th style="white-space: nowrap;" >Doctor</th>
                      <th style="white-space: nowrap;" >Sanitized Name</th>
                      <th style="white-space: nowrap;">LN</th>
                      <th style="white-space: nowrap;">Location</th>
                      <th style="white-space: nowrap;">Branch Name</th>
                      <th style="white-space: nowrap;">LBA</th>
                      <th style="white-space: nowrap;">Hospital Code</th>
                      <th style="white-space: nowrap;">SAR Code</th>
                      <th style="white-space: nowrap;">Amount</th>
                      <th style="white-space: nowrap;">Sanitized by</th>
                      <th style="white-space: nowrap;">Sanitized Date</th>
                    </tr>
                    <tr>
                      <th style="white-space: nowrap;"></th>
                      <th style="white-space: nowrap;"></th>
                      <th style="white-space: nowrap;"></th>
                      <th style="white-space: nowrap;"></th>
                      <th style="white-space: nowrap;"></th>
                      <th style="white-space: nowrap;"></th>
                      <th style="white-space: nowrap;"></th>
                      <th style="white-space: nowrap;"></th>
                      <th style="white-space: nowrap;"></th>
                      <th style="white-space: nowrap;"></th>
                      <th style="white-space: nowrap;"></th>
                      <th style="white-space: nowrap;"></th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
        </div>
      </div>

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
<script src="includes/dependencies/jQueryUI/1.12.1/jquery-ui.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="../../dependencies/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../../plugins/bootstrap-select/js/bootstrap-select.min.js"></script>
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

var is_sanitized;
$("#selectColShowHide").selectpicker('val', [0,1,2,3,4,5,6,7,8,9,10,11])

  $(function () {
    $("#selectColShowHide").on("changed.bs.select", function(e, clickedIndex, newValue, oldValue) {
      var val = $(this).find('option').eq(clickedIndex).val();
      var table = $('#example2').DataTable();
      var column = table.column( val );
      column.visible( ! column.visible() );
    });
  });
    //end

  // $('#district').selectpicker();
  $(function() {
      $( "#doctor_list" ).autocomplete({
          source: 'includes/controller/md-list.php',
          // appendTo : $('.modal-body'),
          select: function (event, ui) {
          var md_name = ui.item.value;
          var md_code = ui.item.id;
          document.valueSelectedForAutocomplete = md_name;
            $('#md_code').val(md_code);
          },
          change: function (event, ui) {
              if (!ui.item) {
                  $(this).val("");
                  $('#md_code').val("");

              } else {

              }
          }
      });

      $( "#doctor_list_other" ).autocomplete({
          source: 'includes/controller/md-list.php',
          appendTo : $('.modal-body'),
          select: function (event, ui) {
          var md_name = ui.item.value;
          var md_code = ui.item.id;
          document.valueSelectedForAutocomplete = md_name;
            $('#md_code_other').val(md_code);
          },
          change: function (event, ui) {
              if (!ui.item) {
                  $(this).val("");
                  $('#md_code_other').val("");

              } else {

              }
          }
      });

  });

  getDistrictList('<?php echo $_SESSION['auth_usercode']; ?>');
  function getDistrictList(auth_usercode){
    // $( "#district_list" ).prop( "disabled", true );
    var token = 'BKPI2017';
    var cmd = 'get_district_list';
    var dataString = "token=" + token + "&cmd=" + cmd + "&auth_usercode=" + auth_usercode;
    $.ajax({
      type: "POST",
      url: "includes/controller/index.php",
      data: dataString,
      dataType: 'json',
      beforeSend: function( xhr ) {
        $('#district_list_spinner').show();
      },
      success: function (response) {
          // console.log(response);
          $('#district_list').empty();
          $('#district_list').select2({
            placeholder: "Select District",
            allowClear: false,
            data: response
          });
          $("#district_list > option").prop("selected","selected");
          $("#district_list").trigger("change");
          $('#district_list_spinner').hide();
          // $( "#district_list" ).prop( "disabled", false );

      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(xhr.status);
        console.log(thrownError);
      }
    });
  }

  // getMDList('auth_usercode');
  function getMDList(auth_usercode){
    $( "#doctor_list" ).prop( "disabled", true );
    var token = 'BKPI2017';
    var cmd = 'get_md_list';
    var dataString = "token=" + token + "&cmd=" + cmd + "&auth_usercode=" + auth_usercode;
    $.ajax({
      type: "POST",
      url: "includes/controller/index.php",
      data: dataString,
      dataType: 'json',
      beforeSend: function( xhr ) {
        $('#doctor_list_spinner').show();
      },
      success: function (response) {
          // console.log(response);
          $('#doctor_list').empty();
          $('#doctor_list').select2({
            placeholder: "Select District",
            allowClear: false,
            data: response,
            maximumSelectionLength: 1
          });
          $('#doctor_list_spinner').hide();
          $( "#doctor_list" ).prop( "disabled", false );

      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(xhr.status);
        console.log(thrownError);
      }
    });
  }

  $("#btnSearchDataSanitation").click(function (e){
    e.preventDefault();

    var district_list = $('#district_list').val();
    var doctor_list = $('#doctor_list').val();
    var orig_name = $('#md_code').val();
    if(district_list == ''){
      alert('Select District')
    }else {
      searchDataSanitation(district_list);
    }

   })

  var table;
  //FOR DATATABLE
  setTimeout(function(){
       table = $('#example2').DataTable( {
       scrollY: "45vh",
       scrollX: true,
       deferRender: true,
       paging: false,
       // ajax: "includes/controller/get_data_sanitation.php?district=&mdname=",
       info: true,
       ordering: false,
       scrollCollapse: true,
       responsive: true,
       searching: false,
       autoWidth: true,
       processing: false,
       scroller:   true
    } );
  }, 100);

  function searchDataSanitation(district_list, doctor_list, orig_name){

    $( "#btnGetTableData" ).prop( "disabled", true );
    $( "#btnAssignOther" ).prop( "disabled", true );
    reloadDatatable(district_list, 'doctor_list');
    getDistrictDetails(district_list);
    // //CHECK IF NAME EXISTED
    // var token = 'BKPI2017';
    // var cmd = 'check_md_name';
    // var dataString = "token=" + token + "&cmd=" + cmd + "&doctor_list=" + doctor_list + "&orig_name=" + orig_name;
    // $.ajax({
    //   type: "POST",
    //   url: "includes/controller/index.php",
    //   data: dataString,
    //   dataType: 'json',
    //   beforeSend: function( xhr ) {
    //     // document.body.className = 'skin-blue sidebar-mini sidebar-collapse';
    //     // $( "#btnSearchDataSanitation" ).prop( "disabled", true );
    //     // $('#btnSearchDataSanitation').html('<i class="fa fa-spinner fa-spin"></i> Searching similar data..');
    //     // document.getElementById("btnSearchDataSanitation").style.width = "auto";
    //
    //   },
    //   success: function (response) {
    //     // console.log(orig_name);
    //     // console.log(response);
    //     // if(response[0].result == 1){
    //       reloadDatatable(district_list, 'doctor_list');
    //       getDistrictDetails(district_list);
    //       // getSimilarRowsPerMD(district_list, doctor_list);
    //       // $( "#btnGetTableData" ).prop( "disabled", false );
    //       // $( "#btnAssignOther" ).prop( "disabled", false );
    //       // getSanitizedData(district_list, orig_name)
    //       // $('#modalSettings').modal('hide');
    //     // }else {
    //     //   alert("Doctor name not found!");
    //     // }
    //
    //
    //   }
    // });



  }


  function reloadDatatable(district_list, doctor_list){
    $('#example2').dataTable().fnDestroy();
    var table = $('#example2').DataTable( {
      // scrollY: "45vh",
      // scrollX: true,
      deferRender: true,
      paging: true,
      // ajax: "includes/controller/get_data_sanitation.php?district="+district_list+"&mdname="+doctor_list,
      ajax: {
        "url": "includes/controller/2_get_data_sanitation.php?district="+district_list+"&mdname="+doctor_list,
        "type": "GET",
        beforeSend: function (request) {
          document.body.className = 'skin-blue sidebar-mini sidebar-collapse';
          $( "#btnSearchDataSanitation" ).prop( "disabled", true );
          $('#btnSearchDataSanitation').html('<i class="fa fa-spinner fa-spin"></i> Loading data..');
          document.getElementById("btnSearchDataSanitation").style.width = "auto";
        }
      },
      info: true,
      ordering: false,
      scrollCollapse: true,
      responsive: true,
      autoWidth: true,
      processing: false,
      scroller:   true,
      searching: true,
      order: [[ 1, "asc" ]],
      // search: {"smart": false},
      columnDefs: [ {"targets": [ 0 ], "visible": true, "searchable": true , "width": "1px"} ],
      rowCallback: function(row, data, index){
        if(data[10] != ''){
          $('td', row).css('background-color', 'Cyan');
        }
      },
      initComplete: function () {
        // this.api().columns().every( function (index ) {
        //
        //   if(index == 0){
        //
        //     var column = this;
        //     var select = $('<select class="form-control select2 selectpicker" multiple="multiple" data-width="5px" disabled data-container="body" data-selected-text-format="count>1"></select>')
        //       .appendTo( $(column.header()).empty() );
        //       // .on( 'change', function () {
        //       //
        //       //
        //       //   // var val = $(this).val();
        //       //   // var fVal = val.join("|");
        //       //   // column.search( fVal ? '^'+fVal+'$' : '', true, false ).draw();
        //       //   // var search = $.fn.dataTable.util.throttle(
        //       //   //      function ( fVal ) {
        //       //   //          column.search( fVal ? '^'+fVal+'$' : '', true, false ).draw();
        //       //   //      },
        //       //   //      1000
        //       //   //  );
        //       //   //  search( fVal );
        //       //
        //       // } );
        //
        //     // column.data().unique().sort().each( function ( d, j ) {
        //     //   // var dataCount = column.data()
        //     //   // .filter( function ( value, index ) {
        //     //   //     return value === d ? true : false;
        //     //   // } );
        //     //   select.append( '<option title="'+d+'" value="'+d+'">'+d+'</option>' );
        //     //   // select.append( '<option title="'+d+'" value="'+d+'">'+d+' -  ' + dataCount.count() + '  Record(s)</option>' );
        //     // } );
        //     //
        //     // $('.selectpicker').selectpicker({
        //     //     // actionsBox: true,
        //     //     liveSearch: true,
        //     //     noneSelectedText: 'Search',
        //     //
        //     // });
        //
        //   }else {
        //
        //     if(index == 1 || index == 3){
        //       var column = this;
        //       var select = $('<select class="form-control select2 selectpicker" multiple="multiple" data-width="fit" data-container="body" data-selected-text-format="count>1"></select>')
        //         .appendTo( $(column.header()).empty() )
        //         .on( 'change', function () {
        //
        //           // console.log('column: ' + index);
        //
        //           // $('#table_loading').show();
        //           var val = $(this).val();
        //           var fVal = val.join("|");
        //           // column.search( fVal ? '^'+fVal+'$' : '', true, false ).draw();
        //           var search = $.fn.dataTable.util.throttle(
        //                function ( fVal ) {
        //                    column.search( fVal ? '^'+fVal+'$' : '', true, false ).draw();
        //                },
        //                1000
        //            );
        //            search( fVal );
        //
        //
        //         } );
        //
        //       column.data().unique().sort().each( function ( d, j ) {
        //         // var dataCount = column.data()
        //         // .filter( function ( value, index ) {
        //         //     return value === d ? true : false;
        //         // } );
        //
        //         // select.append( '<option title="'+d+'" value="'+d+'">'+d+' -  ' + dataCount.count() + '  Record(s)</option>' );
        //         select.append( '<option title="'+d+'" value="'+d+'">'+d+'</option>' );
        //       } );
        //
        //       $('.selectpicker').selectpicker({
        //           actionsBox: true,
        //           liveSearch: true,
        //           noneSelectedText: 'Search',
        //
        //       });
        //     }
        //   }
        //
        // } );
        $( "#btnSearchDataSanitation" ).prop( "disabled", false );
        $('#btnSearchDataSanitation').html('Search');
        $( "#btnGetTableData" ).prop( "disabled", false );
        $( "#btnAssignOther" ).prop( "disabled", false );
      }
    } );

    // table.on('draw', function () {
    //
    //   table.columns().indexes().each( function ( idx ) {
    //
    //     var span = $(table.column( idx ).header()).find('span');
    //     var select = $(table.column( idx ).header()).find('select');
    //
    //     if(span.html() === 'Search'){
    //       select.empty(); //remove old list here
    //       if(idx > 0){
    //         if(idx == 1 || idx == 3){
    //           table.column(idx, {filter:'applied'}).data().unique().sort().each( function ( d, j ) {
    //             // // var dataCount = table.column(idx).rows( ':contains('+d+')',{ filter : 'applied'} ).nodes().length;
    //             // var dataCount = table.column(idx, { filter : 'applied'}).data()
    //             // .filter( function ( value, index ) {
    //             //     return value === d ? true : false;
    //             // } );
    //             // select.append( '<option value="'+d+'">'+d+' -  ' + dataCount.count() + '  Record(s)</option>' );
    //             select.append( '<option title="'+d+'" value="'+d+'">'+d+'</option>' );
    //           } );
    //         }
    //       }
    //
    //       $('.selectpicker').selectpicker('refresh');
    //     }
    //
    //   } );
    //   // $('#table_loading').hide();
    //     // console.log("end");
    //   } );

  }


  //GET DATA

  function getDistrictDetails(district){
    $('#lblDistrictDetails').html('');
    var token = 'BKPI2017';
    var cmd = 'get_district_details';
    var dataString = "token=" + token + "&cmd=" + cmd + "&district=" + district;
    $.ajax({
      type: "POST",
      url: "includes/controller/index.php",
      data: dataString,
      dataType: 'json',

      success: function (response) {
        $('#lblDistrictDetails2').html(response[0].rowsCount + ' Unsanitized and '+ response[0].rowsCountSanitized +' Sanitized Records');
      }
    });
  }

  function getSimilarRowsPerMD(district_list, doctor_list){
    $('#lblSimilarRows').html('');
    var token = 'BKPI2017';
    var cmd = 'get_district_details';
    var dataString = "token=" + token + "&cmd=" + cmd + "&district=" + district_list;
    $.ajax({
      type: "POST",
      url: "includes/controller/get_similar_rows_count.php?district="+district_list+"&mdname="+doctor_list,
      data: dataString,
      dataType: 'json',
      success: function (response) {

          $('#lblSimilarRows').html(response.count + ' rows similar to ' + response.md);
          $('#lblMDname').html(response.md);
      }
    });
  }
















  $("#btnGetTableData").click(function (e){
    e.preventDefault();
    $('#lblConfirmDistrict').html('');
    $('#lblConfirmDoctor').html('');

    var district = $('#district_list').val();
    var md = $('#md_code').val();
    for (var i = 0, len = district.length; i < len; i++){
        var para = document.createElement("span");
        var t = document.createTextNode(district[i] + ' | ');
        para.appendChild(t);
        document.getElementById("lblConfirmDistrict").appendChild(para);
    }
    $('#lblConfirmDoctor').html(md);
    is_sanitized = '_doctor';
    prepareSanitationData();
  })

  $("#btnConfirmAssignment").click(function (e){
    e.preventDefault();
    $('#lblConfirmDistrict').html('');
    $('#lblConfirmDoctor').html('');

    var md = $('#doctor_list_other').val();
    if(md == ""){
      alert("Select Doctor");
    }else {
      $('#modalOtherDoctor').modal('hide');
      var district = $('#district_list').val();
      var md = $('#md_code_other').val();
      for (var i = 0, len = district.length; i < len; i++){
          var para = document.createElement("span");
          var t = document.createTextNode(district[i] + ' | ');
          para.appendChild(t);
          document.getElementById("lblConfirmDistrict").appendChild(para);
      }
      $('#lblConfirmDoctor').html(md);
      is_sanitized = 'other_doctor';
      prepareSanitationData();
    }

  })

  function prepareSanitationData(){
    var table = $('#example2').DataTable();

    table.columns().indexes().each( function ( idx ) {

      var span = $(table.column( idx ).header()).find('span');

      if(idx == 1){
        if( span.html() != 'Search'){
          document.getElementById("raw_doctor").checked = true;
        }else {
          document.getElementById("raw_doctor").checked = false;
        }
      }
      if(idx == 3){
        if( span.html() != 'Search'){
          document.getElementById("raw_license").checked = true;
        }else {
          document.getElementById("raw_license").checked = false;
        }
      }
      // if(idx == 4){
      //   if( span.html() != 'Search'){
      //     document.getElementById("raw_address").checked = true;
      //   }else {
      //     document.getElementById("raw_address").checked = false;
      //   }
      // }
      // if(idx == 5){
      //   if( span.html() != 'Search'){
      //     document.getElementById("raw_branchname").checked = true;
      //   }else {
      //     document.getElementById("raw_branchname").checked = false;
      //   }
      // }
      // if(idx == 6){
      //   if( span.html() != 'Search'){
      //     document.getElementById("raw_lbucode").checked = true;
      //   }else {
      //     document.getElementById("raw_lbucode").checked = false;
      //   }
      // }

    });
    // var table_data = table.rows( { filter : 'applied'} ).data();
    // console.log(table_data);

    var raw_id_obj = table.column(0, { filter:'applied' }).data();
    var raw_id_array = $.map(raw_id_obj, function(value, index) {
       return [value];
     });

     // console.log(raw_id_array);

    if(raw_id_array.length > 0){
      $('#modalConfirmation').modal('show');

      //CHECK TO DB IF DATA ALREADY SANITIZED USING ID
      //DISPLAY TO MODAL CONFIRMATION TABLE
      setTimeout(function(){
        $('#example3').DataTable( {
            // data: table_data,
            scrollY: "45vh",
            scrollX: false,
            paging: false,
            info: true,
            ordering: false,
            scrollCollapse: true,
            ajax: {
              "url": "includes/controller/check_data_if_sanitized.php",
              "type": "POST",
              "data": { id: JSON.stringify(raw_id_array) }
            },
            responsive: true,
            searching: false,
            autoWidth: true,
            processing: false,
            rowCallback: function(row, data, index){
              if(data[6] != ''){
                $('td', row).css('background-color', 'Cyan');
              }
            }
        } );

        $('#tableUnclean').DataTable( {
            // data: table_data,
            scrollY: "45vh",
            scrollX: false,
            paging: false,
            info: false,
            ordering: false,
            scrollCollapse: true,
            ajax: {
              'url': "includes/controller/view_unclean_data.php",
              'type': 'POST',
              "data": { id: JSON.stringify(raw_id_array) }
            },
            responsive: false,
            searching: false,
            autoWidth: false,
            processing: false
        } );


      }, 500);

    }else {
      alert("No data to be sanitized!");
    }
  }

   $('#modalConfirmation').on('hidden.bs.modal', function () {
      $('#example3').dataTable().fnDestroy();
      $('#tableUnclean').dataTable().fnDestroy();
  });

   $("#btnConfirm").click(function (e){
     e.preventDefault();
     //check if other doctor
     if(is_sanitized == '_doctor'){
       var doctor_list = $('#doctor_list').val();
       var doctor_orig_name = $('#md_code').val();
       sanitizedData(doctor_list, doctor_orig_name);
     }else {
       var doctor_list = $('#doctor_list_other').val();
       var doctor_orig_name = $('#md_code_other').val();
       sanitizedData(doctor_list, doctor_orig_name);
     }

  })

  function sanitizedData(doctor, md_code){
    var table = $('#tableUnclean').DataTable();
    var doctor_list = doctor;
    var doctor_orig_name = md_code;
    //GET COLUMN INDEX
    //CONVERT OBJECT TO ARRAY
    var raw_id_obj = table.column(0).data();
    var raw_id_array = $.map(raw_id_obj, function(value, index) {
       return [value];
     });

     if(raw_id_array.length > 0){
       var checked = []
       $("input[name='options[]']:checked").each(function ()
       {
             checked.push($(this).val());
       });
         // console.log(checked);
       if(checked.length > 0){

        var token = 'BKPI2017';
        var cmd = 'update_sanitation_table';
        var dataString = "token=" + token + "&cmd=" + cmd + "&raw_id=" + raw_id_array + "&doctor_list=" + doctor_orig_name + "&table_name=" + checked;
        $.ajax({
          type: "POST",
          url: "includes/controller/index.php",
          data: dataString,
          dataType: 'json',
          beforeSend: function( xhr ) {
            // $('#btnConfirmSpinner').show();
            $('#btnConfirm').html('<i class="fa fa fa-spinner fa-spin" id="btnConfirmSpinner"></i>');
          },
          success: function (response) {
            console.log(response);
            $('#btnConfirmSpinner').hide();
            $('#btnConfirm').html('Confirm');
               if(response[0].success == 1){
                 $('#modalConfirmation').modal('hide');

                 var district_list = $('#district_list').val();
                 var doctor_list = $('#doctor_list').val();

                 getDistrictDetails(district_list);
                 // getSimilarRowsPerMD(district_list, doctor_list);

                 reloadDatatable(district_list, doctor_list);
                 // getSanitizedData(district_list, doctor_orig_name);

               }else {
                 console.log(response[0].success);
               }
          }
          ,
          error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(thrownError);
          }
        });

        }else {
          alert("Please select columns for creating rules.");
        }

     }else {
       alert("No data to be sanitized!");
     }


  }

  $('#modalUnclassified').on('hidden.bs.modal', function () {
     $('#example4').dataTable().fnDestroy();
 });

 $("#btnGetTableDataUnclassified").click(function (e){
   e.preventDefault();

   var table = $('#example2').DataTable();
   var table_data = table.rows( { filter : 'applied'} ).data();

   if(table_data.length > 0){
      $('#modalUnclassified').modal('show');
     setTimeout(function(){
       $('#example4').DataTable( {
           data: table_data,
           scrollY: "45vh",
           scrollX: false,
           paging: false,
           info: true,
           ordering: false,
           scrollCollapse: true,
           responsive: true,
           searching: false,
           autoWidth: true,
           processing: true,
       } );
     }, 500);
   }else {
     alert("No data to be unclassified!");
   }


  })

  $("#btnUnclassified").click(function (e){
    e.preventDefault();
    var doctor_list = $('#doctor_list').val();
    //GET COLUMN INDEX
    var table = $('#example4').DataTable();
    //CONVERT OBJECT TO ARRAY
    var raw_id_obj = table.column(0).data();
    var raw_id_array = $.map(raw_id_obj, function(value, index) {
       return [value];
   });
   // console.log(raw_id_array);
    var token = 'BKPI2017';
    var cmd = 'update_unclassified_table';
    var dataString = "token=" + token + "&cmd=" + cmd + "&raw_id=" + raw_id_array + "&doctor_list=" + doctor_list;
      $.ajax({
        type: "POST",
        url: "includes/controller/index.php",
        data: dataString,
        dataType: 'json',
        beforeSend: function( xhr ) {
          // $('#btnConfirmSpinner').show();
          $('#btnUnclassified').html('<i class="fa fa fa-spinner fa-spin" id="btnUnclassifiedSpinner"></i>');
        },
        success: function (response) {
          $('#btnUnclassifiedSpinner').hide();
          $('#btnUnclassified').html('Confirm');
             if(response[0].success == 1){
               alert("Data Successfully updated!");
               $('#modalUnclassified').modal('hide');

               var district_list = $('#district_list').val();
               var doctor_list = $('#doctor_list').val();

               getDistrictDetails(district_list);
               getSimilarRowsPerMD(district_list, doctor_list);
               reloadDatatable(district_list, doctor_list);

             }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          console.log(xhr.status);
          console.log(thrownError);
        }
      });

   })

   var error = 0;

   $("#btnAddDoctor").click(function (e){
     e.preventDefault();
     error = 0
     checkRequired('txtMDname', 'txtMDnameError');
     var txtMDname = assignValue('txtMDname');
     var txtMDuniverse = assignValue('txtMDuniverse');
     var txtMDcode = assignValue('txtMDcode');
     var txtMDstatus = assignValue('txtMDstatus');

     if(error == 0){

       var token = 'BKPI2017';
       var cmd = 'add_md_details';
       var dataString = "token=" + token + "&cmd=" + cmd + "&mdname=" + txtMDname + "&mdCode=" + txtMDcode + "&mdStatus=" + txtMDstatus + "&mdUniverse=" + txtMDuniverse;
         $.ajax({
           type: "POST",
           url: "includes/controller/index.php",
           data: dataString,
           dataType: 'json',
           beforeSend: function( xhr ) {
             $('#btnAddDoctor').html('<i class="fa fa fa-spinner fa-spin"></i> Saving');
           },
           success: function (response) {
             $('#btnAddDoctor').html('Save');
             $('#modalAddDoctor').modal('hide');
           },
           error: function (xhr, ajaxOptions, thrownError) {
             console.log(xhr.status);
             console.log(thrownError);
           }
         });
     }
  })

  function assignValue(id_name){
    id_name = $('#'+id_name).val();
    id_name = id_name.replace(/\s\s+/g, ' ');
    return id_name;
  }


  function checkRequired(id_name, id_error){
    if(($('#'+id_name).val().trim().length > 0) ){
        $('#'+id_error).hide();
     }else{
       $('#'+id_error).show();
       error = 1;
     }
    return error;
  }



} );
</script>
</body>
</html>
