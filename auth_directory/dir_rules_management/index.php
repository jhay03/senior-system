<?Php
// print_r($_POST) ;
session_start();
require("../../connection.php") ;

require("includes/php-functions.php") ;

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
  <title>MDC Senior System | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <?Php require("includes/css.php") ?>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

  <?Php require("includes/js-library.php") ?>

  <!-- Google Font -->

</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php require("includes/header.php") ?>

  <?php // require("includes/sidebar-menu.php") ?>

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
        <li  class="active" ><a href="#" class="bg-blue"><i class="fa fa-plus "></i> <span>Add New Rule</span></a></li>
        <?php if($_SESSION['authRole'] == "TEAM LEADER"){?>
        <li><a href="dir_rules_management/rules_list.php"><i class="fa fa-tasks"></i> <span>Rules List</span></a></li>
        <li><a href="dir_rules_management/rules_list.php"><i class="fa fa-thumbs-up"></i> <span>Rules Approval</span>

          <?Php echo rules_for_approval()  ; ?>


            </a>
        </li>
        <?php }?>
        <li class="header">DATA SANITATION</li>
        <li>
          <a href="dir_data_sanitation/data-sanitation.php"><i class="fa fa-clipboard-check"></i> <span>Assigned To Me</span>
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
        <i class="fa fa-tachometer-alt text-green"></i> MDC SENIOR CITIZEN SANITATION RULES
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="../index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Rules Management</li>
      </ol>
    </section>

    <section class="content-header">

    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->

      <form  method="post" onsubmit="return confirm('Add New Rule?');">
            <!-- SELECT2 EXAMPLE -->
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title">Existing Rules</h3>

                <div class="box-tools pull-right">
                  <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> -->
                  <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button> -->

                  <a href="#" class="btn btn-success"
                  class="btn btn-success"
                  data-toggle='modal'
                  data-target='#AddNewRule'
                  style="font-size:12px !important"><i class="fa fa-plus"> </i> <b>ADD NEW RULE</b></a>
                </div>
              </div>
              <!-- /.box-header -->
              <div class="box-body"  >
                 <!-- style="overflow: auto; max-height:50vh; scroll:auto" -->
                <div class="row">
                  <div class="col-md-8" style="display:none">


                                          <div class="form-group">
                                            <div class="col-md-4">
                                                  <label>IF</label>
                                                  <select id=".select2" class="form-control select2 if" name="criteria1" style="width: 100%;">
                                                          <option disabled selected="selected">Select Option...</option>
                                                          <?Php

                                                          $option_list = array('MD', 'LICENSE', 'BRANCH CODE', 'ADD') ;


                                                          foreach ($option_list as $row_md) {
                                                              echo "<option value='$row_md'>$row_md</option>";
                                                          }

                                                          ?>

                                                  </select>
                                            </div>

                                            <div class="col-md-3">

                                                  <label>IS</label>
                                                  <select class="form-control select2 is" name="operator1" style="width: 100%;">
                                                    <option disabled selected="selected">Select Conditional...</option>
                                                  <?Php

                                                  $option_list = array('EQUAL TO', 'LIKE') ;


                                                  foreach ($option_list as $options) {
                                                      echo "<option value='$options'>$options</option>";
                                                  }

                                                  ?>

                                                  </select>

                                            </div>

                                            <div class="col-md-5">

                                                  <label>&nbsp;</label>
                                                  <input type="text" class="form-control val" style="width:100%" name="value1" value="" placeholder="Value...">
                                                  <!-- <select   class="form-control select2" name="value1" style="width: 100%;"> -->
                                                    <!-- <option disabled selected="selected">Select Format...</option> -->
                                                  <?Php

                                                  $option_list = array('SOME MD', 'SOME MD2') ;


                                                  ?>



                                            </div>




                  </div>

                  <!-- <div id="display-new-rule"></div> -->

                  <div class="clearfix">

                  </div>
                  <br>
                  <br>
                  <!-- <button class="btn btn-default" id="add-new-rule" style="float:right" type="button"  ><i class="fa fa-plus-circle"></i> ADD CONDITION</button> -->

                  </div>
                  <!-- /.col -->
                  <div class="col-md-4" style="display:none">

                    <div class="form-group">
                      <label>Assign To</label>
                      <select id="s-search" class="form-control select2" name="assign-to" style="width: 100%; ">
                        <option disabled selected="selected">Select OPERATOR...</option>
                      <?Php

                      $option_list = array('SOME MD', 'SOME MD2') ;

                      foreach ($option_list as $options) {
                          echo "<option value='$options'>$options</option>";
                      }

                      ?>
                      </select>
                    </div>
                    <!-- /.form-group -->
                    <div class="clearfix">

                    </div>
                    <div class="form-group">
                      <br>
                      <button class="btn btn-primary"   style="float:right; margin-right:14px;" type="submit" name="button">
                        <i class="fa fa-check"></i> ADD RULE</button>
                    </div>
                    <!-- /.form-group -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
      </form>


                        <div id="refresh-table">
                                      <table id="rules-table" class="display table table-bordered table-condensed" style="width:100%; ">
                                                      <thead style="background-color:#d7ebf9 ; color: #2779aa"  >
                                                          <tr>
                                                              <th>ASSIGNED TO</th>
                                                              <th>RULE</th>
                                                              <!-- <th>LICENSE #</th>
                                                              <th>ADDRESS</th>
                                                              <th>BRANCH</th>
                                                              <th>LBA</th> -->

                                                              <?Php if ($position == "TEAM LEADER") { ?>
                                                              <th>RULE STATUS</th>
                                                              <th> <?php for ($i=0; $i <35 ; $i++) { echo "&nbsp;" ; } ?> </th>
                                                              <?Php } else { ?>
                                                              <th>RULE STATUS</th>
                                                              <?Php }   ?>
                                                          </tr>
                                                      </thead>
                                                      <tbody>


                                                          <?Php
                                                          // $assigned_to = "GO, LUIS RAYMOND" ;
                                                          // $md_name = "FLORES, BERWYN VIANNELY" ;
                                                          // $license = "12345768" ;
                                                          // $address = "QUEZON CITY" ;
                                                          // $branch = "MERCURY DRUG" ;
                                                          // $lba = "400" ;

                                                          $get_rule_code = mysqli_query($conn,
                                                          "SELECT
                                                                  b.rule_code,
                                                                  rule_assign_to,
                                                                  status
                                                          FROM rules_details as a
                                                          LEFT JOIN rules_tbl as b
                                                                ON a.rule_code = b.rule_code
                                                          WHERE 1=1
                                                          -- AND b.status = 1
                                                          GROUP BY b.rule_code
                                                          ORDER BY rule_assign_to, status ASC
                                                          ");

                                                          if (mysqli_num_rows($get_rule_code) > 0) {

                                                                  while ($row_rule_code = mysqli_fetch_assoc($get_rule_code)) {

                                                                        $rule_code = $row_rule_code['rule_code'] ;
                                                                        $assigned_to = $row_rule_code['rule_assign_to'] ;
                                                                        $status = $row_rule_code['status'] ;

                                                                        $get_rule_info = mysqli_query($conn,
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

                                                                        } // while rule info

                                                                        echo "<tr>";
                                                                        echo "<td>$assigned_to</td>";
                                                                        echo "<td><div style='width:70% !important'><p>$rule_context</p></div></td>";

                                                                       if ($position == "TEAM LEADER") { ?>


                                                                        <?Php

                                                                        if ($status == "0") { echo "<td ><span class='badge bg-red'>DELETED</span></td>" ; }
                                                                        elseif ($status == "1") { echo "<td ><span class='badge bg-green'>APPROVED</span></td>" ; }
                                                                        elseif ($status == "2") { echo "<td ><span class='badge bg-grey'>PENDING</span></td>" ; }

                                                                        ?>

                                                                        <td>
                                                                          <center>

                                                                          <a href="#"
                                                                          style="font-size:10px;"
                                                                          class="btn btn-success"
                                                                          data-toggle='modal'
                                                                          data-id='<?Php echo $rule_code; ?>'
                                                                          data-target='#modalEdit'
                                                                          ><i class="fa fa-edit"></i></a>

                                                                          <?php if ($status == 0 ) { ?>

                                                                          <a href="#"
                                                                          style="font-size:10px;"
                                                                          data-id='<?Php echo $rule_code; ?>'
                                                                          class="btn btn-danger"><i class="fa fa-trash-alt"></i>
                                                                          </a>

                                                                        <?php } else { ?>

                                                                          <a href="#"
                                                                          style="font-size:10px;"
                                                                          data-id='<?Php echo $rule_code; ?>'
                                                                          class="btn btn-default"><i class="fa fa-ban"></i>
                                                                          </a>

                                                                        <?php }  ?>

                                                                        </center>
                                                                        </td>


                                                                        <?Php

                                                                      } else {

                                                                        if ($status == "0") { echo "<td ><span class='badge bg-red'>DELETED</span></td>" ; }
                                                                        elseif ($status == "1") { echo "<td ><span class='badge bg-green'>APPROVED</span></td>" ; }
                                                                        elseif ($status == "2") { echo "<td ><span class='badge bg-grey'>PENDING</span></td>" ; }

                                                                      }

                                                                        echo "</tr>";

                                                                  } // row rule code

                                                          }



                                                          ?>
                                                      </tbody>
                                    </table>

                                    <div class="display-me">

                                    </div>
                      </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">

                <!-- <center>
                  <a href="#" class="btn btn-success"
                  class="btn btn-success"
                  data-toggle='modal'
                  data-target='#AddNewRule'
                  style="font-size:16px !important"><i class="fa fa-plus"> </i> ADD NEW RULE</a>
                </center> -->

              </div>




            </div>
            <!-- /.box -->


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


<?php include("includes/js.php") ; ?>
<!-- <script src="functions.js"></script> -->





</body>
</html>
