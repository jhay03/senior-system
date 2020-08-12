<?php
  session_start();
  include('../../connection.php');
  require("includes/php-functions.php") ;
  if(!isset($_SESSION['authUser'])){
    header('Location:../../logout.php');
  }


// $get = mysqli_query($mysqli,
// "SELECT * FROM `sanitation_result1` WHERE raw_id IN ('1450', '1451', '1452', '1528', '3646', '3647')
// ");
//
// while ($row_get = mysqli_fetch_assoc($get) ) {
//
//     $id = $row_get['raw_id'] ;
//     $dr = $row_get['raw_doctor'] ;
//     $license = $row_get['raw_license'] ;
//     $address = $row_get['raw_address'] ;
//
//     $array_result[] = array( $dr, $license, $address) ;
//
// }
//
// echo "<pre>";
// print_r($array_result) ;
// echo "</pre>";
// echo "<hr>";
//
//
//
// foreach ($array_result as $key => $value) {
//
//    $new_value[]  = implode("||", $value);
//
// }
//
// print_r($new_value);
// $new_value = array_unique($new_value) ;
//
// print_r($new_value);
//
// foreach ($new_value as $value) {
//
//               $new = explode("||", $value);
//               $dr = $new[0] ;
//               $license = $new[1] ;
//               $address = $new[2] ;
//
//               echo "$dr $license $address ";
//               echo "<br>";
// }

// $get = mysqli_query($mysqli,
// "SELECT * FROM `sanitation_result1` WHERE raw_id IN ('1450', '1451', '1452', '1528', '3646', '3647')
// ");
//
// while ($row_get = mysqli_fetch_assoc($get) ) {
//
//     $id = $row_get['raw_id'] ;
//     $dr = $row_get['raw_doctor'] ;
//     $license = $row_get['raw_license'] ;
//     $address = $row_get['raw_address'] ;
//
//     $array_result[] = array( $dr, $license, $address) ;
//
// }

// echo "<pre>";
// print_r(sanitizeRule($mysqli, $array_result) ) ;
// echo "</pre>";


function sanitizeRule($mysqli, $array_list) {

      foreach ($array_list as $key => $value) {

         $new_value[]  = implode("||", $value);

      }

      $new_value = array_unique($new_value) ;


      foreach ($new_value as $value) {

                    $new = explode("||", $value);
                    $dr = $new[0] ;
                    $license = $new[1] ;
                    $address = $new[2] ;

                    // echo "$dr $license $address ";

                     $sanitized_result[] = array($dr, $license, $address) ;
      }


      return $sanitized_result;

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
        <li><a href="../index.php"><i class="fa fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
        <?php if($_SESSION['authRole'] == "TEAM LEADER"){?>
        <li class="header">DATABASE MAINTENANCE</li>
        <li><a href="../dir_database_maintenance/masterlist_db.php"><i class="fa fa-database"></i> <span>Masterlist Database</span></a></li>
        <li class="header">SANITATION MANAGEMENT</li>
        <li><a href="../dir_sanitation_management/user_maintenance.php"><i class="fa fa-users-cog"></i> <span>User Maintenance</span></a></li>
        <li><a href="../dir_sanitation_management/team_roles.php"><i class="fa fa-users"></i> <span>Team Roles</span></a></li>
        <li><a href="../dir_sanitation_management/district_assignment.php"><i class="fa fa-user-tag"></i> <span>District Assignment</span></a></li>
        <?php }?>
        <li class="header">RULES MANAGEMENT</li>
        <li><a href="#"><i class="fa fa-plus"></i> <span>Add New Rule</span></a></li>
        <?php if($_SESSION['authRole'] == "TEAM LEADER"){?>
        <li class="active"><a href="#" class="bg-blue"><i class="fa fa-tasks"></i> <span>Rules List</span></a></li>
        <li ><a href="approve.php" ><i class="fa fa-thumbs-up"></i> <span>Rules Approval</span>

            <?Php echo rules_for_approval()  ; ?>

            </a>
       </li>
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
        <i class="fa fa-tasks text-green"></i> Rules List
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

              <table id="rules-table" class="table table-bordered table-striped">
                <thead style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                    <tr>
                        <th style="white-space: nowrap;">RULE CODE</th>
                        <th style="white-space: nowrap;">ASSIGNED TO</th>
                        <th style="white-space: nowrap;">RULE</th>
                        <th style="white-space: nowrap;">CREATED BY</th>
                        <th style="white-space: nowrap;">DATE CREATED</th>
                        <?Php if ($position == "TEAM LEADER") { ?>
                        <th style="white-space: nowrap;">RULE STATUS</th>
                        <th style="white-space: nowrap;"></th>
                        <?Php } else { ?>
                        <th style="white-space: nowrap;">RULE STATUS</th>
                        <?Php } ?>
                    </tr>
                </thead>

              </table>



            </div>



            <!-- /.box-body -->
            <div class="box-footer">
                <a href="#" class="btn btn-success btn-sm" data-toggle="modal" data-target="#AddNewRule"><i class="fa fa-plus"></i> Add New Rule</a>

                  <a href='#' onclick="window.open('rules_list_export.php')" id="viewExportAll"  class="btn btn-default pull-right"  ><i class="fa fa-file"></i> Export to Excel</a>
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

<!-- Bootstrap 3.3.7 -->
<script src="../../dependencies/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="../../dependencies/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../../dependencies/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- daterangepicker -->
<script src="../../dependencies/moment/min/moment.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>

<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>

<!-- AdminLTE for demo purposes -->
  <?Php require("includes/js.php") ?>

<script>

$(document).ready(function(){

// --------------------------------------- DATATABLE
// --------------------------------------- DATATABLE

 //
// Pipelining function for DataTables. To be used to the `ajax` option of DataTables
//
$.fn.dataTable.pipeline = function ( opts ) {
    // Configuration options
    var conf = $.extend( {
        pages: 2,     // number of pages to cache
        url: '',      // script url
        data: null,   // function or object with parameters to send to the server
                      // matching how `ajax.data` works in DataTables
        method: 'GET' // Ajax HTTP method
    }, opts );

    // Private variables for storing the cache
    var cacheLower = -1;
    var cacheUpper = null;
    var cacheLastRequest = null;
    var cacheLastJson = null;

    return function ( request, drawCallback, settings ) {
        var ajax          = false;
        var requestStart  = request.start;
        var drawStart     = request.start;
        var requestLength = request.length;
        var requestEnd    = requestStart + requestLength;

        if ( settings.clearCache ) {
            // API requested that the cache be cleared
            ajax = true;
            settings.clearCache = false;
        }
        else if ( cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper ) {
            // outside cached data - need to make a request
            ajax = true;
        }
        else if ( JSON.stringify( request.order )   !== JSON.stringify( cacheLastRequest.order ) ||
                  JSON.stringify( request.columns ) !== JSON.stringify( cacheLastRequest.columns ) ||
                  JSON.stringify( request.search )  !== JSON.stringify( cacheLastRequest.search )
        ) {
            // properties changed (ordering, columns, searching)
            ajax = true;
        }

        // Store the request for checking next time around
        cacheLastRequest = $.extend( true, {}, request );

        if ( ajax ) {
            // Need data from the server
            if ( requestStart < cacheLower ) {
                requestStart = requestStart - (requestLength*(conf.pages-1));

                if ( requestStart < 0 ) {
                    requestStart = 0;
                }
            }

            cacheLower = requestStart;
            cacheUpper = requestStart + (requestLength * conf.pages);

            request.start = requestStart;
            request.length = requestLength*conf.pages;

            // Provide the same `data` options as DataTables.
            if ( $.isFunction ( conf.data ) ) {
                // As a function it is executed with the data object as an arg
                // for manipulation. If an object is returned, it is used as the
                // data object to submit
                var d = conf.data( request );
                if ( d ) {
                    $.extend( request, d );
                }
            }
            else if ( $.isPlainObject( conf.data ) ) {
                // As an object, the data given extends the default
                $.extend( request, conf.data );
            }

            settings.jqXHR = $.ajax( {
                "type":     conf.method,
                "url":      conf.url,
                "data":     request,
                "dataType": "json",
                "cache":    false,
                "success":  function ( json ) {
                    cacheLastJson = $.extend(true, {}, json);

                    if ( cacheLower != drawStart ) {
                        json.data.splice( 0, drawStart-cacheLower );
                    }
                    if ( requestLength >= -1 ) {
                        json.data.splice( requestLength, json.data.length );
                    }

                    drawCallback( json );
                }
            } );
        }
        else {
            json = $.extend( true, {}, cacheLastJson );
            json.draw = request.draw; // Update the echo for each response
            json.data.splice( 0, requestStart-cacheLower );
            json.data.splice( requestLength, json.data.length );

            drawCallback(json);
        }
    }
};

// Register an API method that will empty the pipelined data, forcing an Ajax
// fetch on the next draw (i.e. `table.clearPipeline().draw()`)
$.fn.dataTable.Api.register( 'clearPipeline()', function () {
    return this.iterator( 'table', function ( settings ) {
        settings.clearCache = true;
    } );
} );


    $('#rules-table').DataTable({
      "processing": true,
      "serverSide": true,
      "deferRender": true,

      "ajax": $.fn.dataTable.pipeline( {
          // url: 'jpi_data.php',
          url :"rule_list_data.php", // json datasource
          pages: 3 // number of pages to cache
      } ),
      error: function(data){  // error handling

      $(".kass-grid-error").html("");
      $(".display").append('<tbody class="kass-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
      $("#employee-grid_processing").css("display","none");

    },
      dom: 'lftip',
      buttons: [
             // 'copy', 'csv',
             // 'pdf',
           { extend: 'excel',

          text: 'Export to Excel'
        }
      ],
      "paging": true,
         scrollY:        '65vh',
         scrollCollapse: true,
       "lengthMenu": [ [10, 25, 50, 100, 250, 2000, 4000], [10, 25, 50, 100 , 250, 2000, 4000] ]
    } );

})
// --------------------------------------- DATATABLE
// --------------------------------------- DATATABLE


</script>

<style media="screen">

.panel-default {
    border-color: #1d1d1d;
    z-index: 999;
}

#rules-table_wrapper .dataTables_length {
  position:absolute; margin-top:2px;
}

</style>


</body>
</html>
