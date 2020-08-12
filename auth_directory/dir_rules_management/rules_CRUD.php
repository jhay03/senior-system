<?php
require("../../connection.php") ;
session_start();
error_reporting(0);
// $username = "BK-986748" ;

$position = $_SESSION['authRole'] ;
$full_name = $_SESSION['authUser'] ;
$username = $_SESSION['auth_usercode'] ;

if ($_POST['action'] == "add") {

                                  if ($position == "TEAM LEADER") { $status = "1"; }
                                  else { $status = "2"; }

                                  $array_count = count($_POST['table_name']);
                                  $assign_to = mysqli_real_escape_string($mysqli, $_POST['assign_to'] ) ;

                                  $check_assigned_to = mysqli_query($mysqli,
                                  $s="SELECT DISTINCT sanit_mdname
                                  FROM db_sanitation
                                  WHERE sanit_mdname = '$assign_to'
                                  LIMIT 1
                                  ");


                                  if (mysqli_num_rows($check_assigned_to) >= 1) {

                                                $rand = str_shuffle(rand(1, 787879) ); // generate more random
                                                $rule_code =  hexdec( abs( crc32( uniqid()) ) ) . $rand ;
                                                $counter = 0;
                                                $counter_condition = 1;

                                                $log = "" ;
                                                $criteria = "";
                                                for ($i=1; $i <= $array_count ; $i++) {

                                                      mysqli_query($mysqli,
                                                      "INSERT INTO rules_details
                                                      (
                                                        rule_code,
                                                        details_column_name,
                                                        details_value_optr,
                                                        details_value,
                                                        details_condition_optr
                                                      )
                                                       VALUES
                                                      (
                                                        '" . $rule_code . "',
                                                        '" . $_POST["table_name"][$counter] . "',
                                                        '" . $_POST["condition"][$counter] . "',
                                                        '" . mysqli_real_escape_string($mysqli, trim($_POST["value"][$counter]) ) . "',
                                                        '" . $_POST["conditional"][$counter_condition] . "'
                                                      )
                                                      ");



                                                      if ($_POST['condition'][$counter] == "=") {

                                                        $criteria .= " " . $_POST['table_name'][$counter] . " = '" . mysqli_real_escape_string($mysqli, trim($_POST['value'][$counter]) ) . "'" ;

                                                      } else {

                                                        $criteria .= " " . $_POST['table_name'][$counter] . " LIKE '%" .mysqli_real_escape_string($mysqli, trim($_POST["value"][$counter]) ) . "%' " ;

                                                      }

                                                      $criteria .= " " .  $_POST["conditional"][$counter_condition] ;

                                                      $counter_condition++;
                                                      $counter++;
                                                } // FOR



                                                 mysqli_query($mysqli,
                                                 "INSERT INTO rules_tbl
                                                 (
                                                   rule_code,
                                                   rule_assign_to,
                                                   rule_created_by,
                                                   status
                                                 )
                                                 VALUES
                                                 (
                                                   '" .$rule_code . "',
                                                   '" .$assign_to . "',
                                                   '" .$username . "',
                                                   '" . $status . "'
                                                 )
                                                 ");

                                                  $log = " $full_name HAS CREATED A RULE WITH RULE ID $rule_code WITH $array_count CRITERIAS HAS BEEN ASSIGNED TO " . $assign_to ;
                                                 mysqli_query($mysqli,
                                                 "INSERT INTO rules_log
                                                 (
                                                   rule_code,
                                                   created_by,
                                                   action,
                                                   details
                                                 )
                                                 VALUES
                                                 (
                                                       '$rule_code',
                                                       '$username',
                                                       'INSERT',
                                                       '$log'
                                                 )
                                                 ");


                                               if ($position == "TEAM LEADER") {

                                                          mysqli_query($mysqli,
                                                          $s="UPDATE sanitation_result1
                                                          SET raw_doctor = '" . $assign_to . "'
                                                          WHERE 1=1
                                                          -- raw_status = ''
                                                          AND $criteria
                                                          ");

                                                }
                                                  echo "1";
                                  } else {
                                                  echo "0";
                                  }

} elseif ($_POST['action'] == "update") {

                                    $rule_code = $_POST['rule_code'] ;
                                    $array_count = count($_POST['table_name']);
                                    $counter = 0;
                                    $counter_condition = 1;
                                    $criteria = "";

                                    $get_assign_to = mysqli_query($mysqli,
                                    "SELECT DISTINCT rule_assign_to
                                    FROM rules_tbl
                                    WHERE rule_code = '$rule_code'
                                    ");
                                    while ($row_assign_to = mysqli_fetch_assoc($get_assign_to) ) {
                                       $assign_to = mysqli_real_escape_string($mysqli, $row_assign_to['rule_assign_to']) ;
                                    }


                                    mysqli_query($mysqli,
                                     "DELETE FROM rules_details
                                    WHERE rule_code = '$rule_code'
                                    ");

                                    mysqli_query($mysqli,
                                    $s="UPDATE rules_tbl
                                    SET status = '1'
                                    WHERE 1=1
                                    AND rule_code = '$rule_code'
                                    ");


                                    for ($i=1; $i <= $array_count ; $i++) {


                                          mysqli_query($mysqli,
                                          $s="INSERT INTO rules_details
                                          (
                                            rule_code,
                                            details_column_name,
                                            details_value_optr,
                                            details_value,
                                            details_condition_optr
                                          )
                                           VALUES
                                          (
                                            '" . $rule_code . "',
                                            '" . $_POST["table_name"][$counter] . "',
                                            '" . $_POST["condition"][$counter] . "',
                                            '" . mysqli_real_escape_string($mysqli, trim($_POST["value"][$counter]) ) . "',
                                            '" . $_POST["conditional"][$counter_condition++] . "'
                                          )
                                          ");

                                          // echo "<pre>$s</pre>";

                                          if ($_POST['condition'][$counter] == "=") {

                                            $criteria .= " " . $_POST['table_name'][$counter] . " = '" . mysqli_real_escape_string($mysqli, trim($_POST['value'][$counter])) . "'" ;

                                          } else {

                                            $criteria .= " " . $_POST['table_name'][$counter] . " LIKE '%" .mysqli_real_escape_string($mysqli, trim($_POST["value"][$counter]) ) . "%' " ;

                                          }

                                          $criteria .= " " .  $_POST["conditional"][$counter_condition] ;

                                          $counter_condition++;
                                          $counter++;

                                    } // FOR

                                    mysqli_query($mysqli,
                                    $s="UPDATE sanitation_result1
                                    SET raw_doctor = '" . $assign_to . "'
                                    WHERE 1=1
                                    AND $criteria
                                    ");





                                    $log = "RULE " . $_POST['rule_code'] . " HAS BEEN UPDATED BY " . $full_name   ;
                                    mysqli_query($mysqli,
                                    "INSERT INTO rules_log
                                    (
                                      rule_code,
                                      created_by,
                                      action,
                                      details
                                    )
                                    VALUES
                                    (
                                          '$rule_code',
                                          '$username',
                                          'UPDATE'
                                          '$log'
                                    )
                                    ");


} elseif ($_POST['action'] == "approval") {

                                    $rule_code = $_POST['rule_code'] ;
                                    $action = $_POST['condition'];

                                    if ($action == "approve") {
                                            $status = "1" ; // APPROVE
                                            $status_interpretation = "APPROVED " ;

                                    } else {

                                            $status = "3";
                                            $status_interpretation = "DECLINED " ;

                                    }

                                    mysqli_query($mysqli,
                                    $s="UPDATE rules_tbl
                                    SET status = '$status'
                                    WHERE rule_code = '" . $_POST['rule_code'] . "'
                                    AND status = '2'
                                    ");


                                    $log = "RULE " . $_POST['rule_code'] . " HAS BEEN $status_interpretation BY " . $full_name  ;
                                    mysqli_query($mysqli,
                                    "INSERT INTO rules_log
                                    (
                                      rule_code,
                                      created_by,
                                      action,
                                      details
                                    )
                                    VALUES
                                    (
                                          '$rule_code',
                                          '$username',
                                          'APPROVED'
                                          '$log'
                                    )
                                    ");



                                    if ($action == "approve") {

                                                $get_assign_to = mysqli_query($mysqli,
                                                "SELECT DISTINCT rule_assign_to
                                                FROM rules_tbl
                                                WHERE rule_code = '$rule_code'
                                                ");
                                                while ($row_assign_to = mysqli_fetch_assoc($get_assign_to) ) {
                                                   $assign_to = mysqli_real_escape_string($mysqli, $row_assign_to['rule_assign_to']) ;
                                                }


                                                $get_rules_list = mysqli_query($mysqli,
                                                $s="SELECT *
                                                FROM rules_details
                                                WHERE rule_code = '$rule_code'
                                                ");

                                                $criteria = "";
                                                while ($row_rules_list = mysqli_fetch_assoc($get_rules_list) ) {

                                                      $value = $row_rules_list['details_value'] ;
                                                      $condition = $row_rules_list['details_value_optr'] ;
                                                      $conditional = $row_rules_list['details_condition_optr'] ;
                                                      $table_name = $row_rules_list['details_column_name'] ;

                                                      if ($condition == "=") {

                                                        $criteria .= " " . $table_name . " = '" . mysqli_real_escape_string($mysqli, trim($value) ) . "'" ;

                                                      } else {

                                                        $criteria .= " " . $table_name . " LIKE '%" .mysqli_real_escape_string($mysqli, trim($value) ) . "%' " ;

                                                      }

                                                      $criteria .= " " .  $conditional ;



                                                } // while

                                                mysqli_query($mysqli,
                                                $s="UPDATE sanitation_result1
                                                SET raw_doctor = '" . $assign_to . "'
                                                WHERE 1=1
                                                AND $criteria
                                                ");

                                    }


} elseif ($_POST['action'] == "delete") {

                                    $status = "0" ;
                                    $status_interpretation = "DELETED";

                                    mysqli_query($mysqli,
                                    $s="UPDATE rules_tbl
                                    SET status = '$status'
                                    WHERE rule_code = '" . $_POST['rule_code'] . "'
                                    -- AND status = '2'
                                    ");

                                    $log = "RULE " . $_POST['rule_code'] . " HAS BEEN $status_interpretation BY " . $full_name  ;
                                    mysqli_query($mysqli,
                                    "INSERT INTO rules_log
                                    (
                                      rule_code,
                                      created_by,
                                      action,
                                      details
                                    )
                                    VALUES
                                    (
                                          '" . $_POST['rule_code'] . "',
                                          '$username',
                                          'DELETE'
                                          '$log'
                                    )
                                    ");

}

exit();
?>


<table id="rules-table" class="display table table-bordered table-condensed" style="width:100%; font-size:12px ;">
                <thead  >
                    <tr>
                        <th>ASSIGNED TO</th>
                        <th>RULE</th>
                        <!-- <th>LICENSE #</th>
                        <th>ADDRESS</th>
                        <th>BRANCH</th>
                        <th>LBA</th> -->
                        <th>EDIT/DELETE</th>
                    </tr>
                </thead>
                <tbody>


                    <?Php


                    $get_rule_code = mysqli_query($mysqli,
                    "SELECT b.rule_code, rule_assign_to
                    FROM rules_details as a
                    LEFT JOIN rules_tbl as b
                          ON a.rule_code = b.rule_code
                    WHERE 1=1
                    AND b.status = 1
                    GROUP BY b.rule_code
                    ORDER BY rule_assign_to ASC
                    ");

                    if (mysqli_num_rows($get_rule_code) > 0) {

                            while ($row_rule_code = mysqli_fetch_assoc($get_rule_code)) {

                                  $rule_code = $row_rule_code['rule_code'] ;
                                  $assigned_to = $row_rule_code['rule_assign_to'] ;

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

                                        $rule_info =  $details_column_name . " " . $details_value_optr . " " . $details_value . " " . $details_condition_optr ;

                                        $rule_context .= $rule_info . " " ;

                                  } // while rule info

                                  echo "<tr>";
                                  echo "<td>$assigned_to</td>";
                                  echo "<td>$rule_context</td>";
                                  ?>
                                  <td>

                                    <center>
                                    <a href="#"
                                    style="font-size:10px;"
                                    class="btn btn-success"
                                    data-toggle='modal'
                                    data-target='#modalEdit'
                                    data-id='<?Php echo $rule_code; ?>'
                                    ><i class="fa fa-edit"></i></a>

                                    <a href="#"
                                    style="font-size:10px;"
                                    data-id='<?Php echo $rule_code; ?>'
                                    class="btn btn-danger"><i class="fa fa-trash-alt"></i></a>
                                  </center>
                                  </td>
                                  <?Php
                                  echo "</tr>";

                            } // row rule code

                    }



                    ?>
                </tbody>
</table>

<script>

// --------------------------------------- DATATABLE
// --------------------------------------- DATATABLE
$('#rules-table').DataTable({
  scrollX:        true,
  scrollY:        "40vh",
   scrollCollapse: true,
   paging:         false
});
// --------------------------------------- DATATABLE
// --------------------------------------- DATATABLE

</script>
