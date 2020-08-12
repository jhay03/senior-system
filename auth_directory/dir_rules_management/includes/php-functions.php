<?Php

function rules_for_approval() {

          global $mysqli;

          $get_list = mysqli_query($mysqli,
          "SELECT COUNT(DISTINCT rule_code) as id
          FROM rules_tbl
          WHERE status = '2'
          ");

          while ($row_get_list = mysqli_fetch_assoc($get_list)) {
             $list_num = $row_get_list['id'] ;
          }

          if ($list_num >= 1) {

                  return '
                  <span class="pull-right-container">
                  <span class="label label-primary pull-right"> ' . $list_num .
                  '</span>
                  </span>';
          } else {
                  return null;

          }

}



?>
