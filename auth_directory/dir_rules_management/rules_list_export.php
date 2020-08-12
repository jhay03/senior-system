<?php
 header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
 header("Content-disposition: attachment; filename=rules_list.xls");
 header("Cache-Control: max-age=0;");
 header("Content-Type: text/plain");

ini_set('upload_max_filesize', '64M');
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);
set_time_limit(0);

session_start();
include('../../connection.php');
require("includes/php-functions.php") ;
if(!isset($_SESSION['authUser'])){
  header('Location:../../logout.php');
}

$user_full_name = $_SESSION['authUser'] ;
$user_name = $_SESSION['auth_usercode'] ;
$position = $_SESSION['authRole'] ;

$sql = "SELECT CONCAT('_',rule_code) as rulecode,rule_code, rule_assign_to, status, auth_fullname, rule_date_created
      FROM rules_tbl as a
      LEFT JOIN auth_users_tbl as b
          ON  a.rule_created_by = b.auth_usercode
      GROUP BY rule_code
          ";
$query=mysqli_query($mysqli, $sql) or die("kass-grid-data.php: get employees");

$data_text = "";
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
  $nestedData=array();

  $rule_code = $row['rule_code'] ;
  $rulecode = $row['rulecode'] ;
  $status = $row['status'] ;
  $name = $row['auth_fullname'] ;
  $rule_date_created = $row['rule_date_created'] ;

  $get_rule_info = mysqli_query($mysqli,
  $s="SELECT
          UPPER(details_column_name) as details_column_name,
          UPPER(details_value_optr) as details_value_optr,
          details_value,
          UPPER(details_condition_optr) as details_condition_optr
  FROM rules_details as a
  WHERE rule_code = '$rule_code'
  ");

  $rule_context = "";
  while ($row_rule_info = mysqli_fetch_assoc($get_rule_info) ) {
        $details_column_name = str_replace("RAW_" , "" , $row_rule_info['details_column_name']) ;
        $details_value_optr =   $row_rule_info['details_value_optr']  ;
        $details_value =  "'" . $row_rule_info['details_value'] . "'" ;
        $details_condition_optr =   $row_rule_info['details_condition_optr']  ;
        $rule_info = $details_column_name . " " . $details_value_optr . " " . $details_value . " " . $details_condition_optr ;
        $rule_context .= $rule_info . " " ;

  }


  $data_text .= $row["rule_assign_to"] . " \t";
  $data_text .= $rule_code . " \t";
  $data_text .= $rulecode . " \t";
  $data_text .= $rule_context . " \t";
  $data_text .= $name . " \t";
  $data_text .= $rule_date_created . " \t";

  if ($status == 0) $data_text .= "DELETED \t";
  elseif ($status == 1) $data_text .= "APPROVED \t";
  elseif ($status == 2) $data_text .= "PENDING \t";
  elseif ($status == 3) $data_text .= "DECLINED \t";

  $data_text .= "\n" ;


}

echo "ASSIGNED TO \t";
echo "OLD RULE CODE \t";
echo "RULE CODE \t";
echo "RULE \t";
echo "CREATED BY \t";
echo "DATE CREATED \t";
echo "RULE STATUS \t";
echo "\n";
echo $data_text;
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";

echo "<script>window.close();</script>";

?>
