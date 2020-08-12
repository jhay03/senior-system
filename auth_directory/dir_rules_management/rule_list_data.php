<?php include('../../connection.php');
 session_start();
$user_full_name = $_SESSION['authUser'] ;
$user_name = $_SESSION['auth_usercode'] ;
$position = $_SESSION['authRole'] ;

// storing  request (ie, get/post) global array to a variable
$requestData= $_REQUEST;


$columns = array(
// datatable column index  => database column name
  0 =>'rule_code',
  1 =>'rule_assign_to',
  2 => 'rule',
  3 => 'auth_fullname',
  4 => 'rule_date_created',
  5 => 'status'
);

  //DATE_FORMAT(STR_TO_DATE(time_stamp,'%m/%d/%Y %h:%i:%s %p'), '%m/%d/%Y %h:%i:%s %p' ) as
// getting total number records without any search
$sql = "SELECT rule_code
      FROM rules_tbl as a
      GROUP BY rule_code
          ";
$query=mysqli_query($mysqli, $sql) or die("kass-grid-data.php: get employees");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


  // DATE_FORMAT(STR_TO_DATE(time_stamp,'%m/%d/%Y %h:%i:%s %p'), '%m/%d/%Y %h:%i:%s %p' ) as
$sql = "SELECT a.rule_code as rule_code, rule_assign_to, status, auth_fullname , rule_date_created";
$sql.=" FROM rules_tbl as a ";
$sql.=" LEFT JOIN auth_users_tbl as b
            ON a.rule_created_by = b.auth_usercode ";
$sql.= "LEFT JOIN rules_details as c ";
$sql.= "    ON a.rule_code = c.rule_code ";
$sql .= "WHERE 1=1";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
  $sql.=" AND ( rule_assign_to LIKE '%".$requestData['search']['value']."%' ";
  $sql.=" OR details_column_name LIKE '%".$requestData['search']['value']."%' ";
  $sql.=" OR rule_date_created LIKE '%".$requestData['search']['value']."%' ";
  $sql.=" OR details_value LIKE '%".$requestData['search']['value']."%' ";
  $sql.=" OR details_value_optr LIKE '%".$requestData['search']['value']."%' ";
  $sql.=" OR details_condition_optr LIKE '%".$requestData['search']['value']."%' ";

  $sql.=" OR auth_fullname LIKE '%".$requestData['search']['value']."%' )";
}
$query=mysqli_query($mysqli, $sql) or die("kass-grid-data.php: get employees");
if( !empty($requestData['search']['value']) )  $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.


 $sql.= " GROUP BY a.rule_code ";
 $sql.= " ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
//$sql.=  "  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";


/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
$query=mysqli_query($mysqli, $sql) or die("kass-grid-data.php: get employees");
// if (!$totalFiltered)  $totalFiltered = mysqli_num_rows($query);
// if( !empty($requestData['search']['value']) )  $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.


$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
  $nestedData=array();

  $rule_code = $row['rule_code'] ;
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
        $details_value_optr =  "<b>" . $row_rule_info['details_value_optr'] . "</b>" ;
        $details_value =  "'" . $row_rule_info['details_value'] . "'" ;
        $details_condition_optr =  "<b>" . $row_rule_info['details_condition_optr'] . "</b>" ;
        $rule_info = $details_column_name . " " . $details_value_optr . " " . $details_value . " " . $details_condition_optr ;
        $rule_context .= $rule_info . " " ;

  }

  $nestedData[] = $rule_code;
  $nestedData[] = $row["rule_assign_to"];
  $nestedData[] = $rule_context;
  $nestedData[] = $name;
  $nestedData[] = $rule_date_created;


  if ($position == "TEAM LEADER") {

        if ($status == 0) $nestedData[] = "<span class='badge bg-red'>DELETED</span>";
        elseif ($status == 1) $nestedData[] = "<span class='badge bg-green'>APPROVED</span>";
        elseif ($status == 2) $nestedData[] = "<span class='badge bg-grey'>PENDING</span>";
        elseif ($status == 3) $nestedData[] = "<span class='badge bg-orange'>DECLINED</span>";

        $buttons = "";
        $buttons .= '<a href="#"
        style="font-size:10px;"
        class="btn btn-xs btn-success"
        data-toggle="modal"
        data-id=' . $rule_code .'
        data-target="#modalEdit"
        ><i class="fa fa-edit"></i></a>';

        if ($status == 1) {

          $buttons .= '<a href="#"
                      style="font-size:10px;"
                      data-id='. $rule_code . '
                      class="btn btn-xs btn-danger"><i class="fa fa-trash-alt"></i>
                      </a>';

        } else {

          $buttons .= '<a href="#"
                      style="font-size:10px;"
                      data-id=' . $rule_code . '
                      class="btn btn-xs btn-default"><i class="fa fa-ban"></i>
                      </a>';
        }
        $nestedData[] = $buttons;

  } else { // NOT TL

        if ($status == 0) $nestedData[] = "<span class='badge bg-red'>DELETED</span>";
        elseif ($status == 1) $nestedData[] = "<span class='badge bg-green'>APPROVED</span>";
        elseif ($status == 2) $nestedData[] = "<span class='badge bg-grey'>PENDING</span>";
        elseif ($status == 3) $nestedData[] = "<span class='badge bg-orange'>DECLINED</span>";

  }



  $data[] = $nestedData;
}



$json_data = array(
      "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
      "recordsTotal"    => intval( $totalData ),  // total number of records
      "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
      "data"            => $data   // total data array
      );

echo json_encode($json_data);  // send data as json format

?>
