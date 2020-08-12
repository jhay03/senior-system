<?php
session_start();
include('../../../../connection.php');
include '../class/DataSanitationRules.php';
include '../class/DataSanitation.php';

$value = $_SERVER['argv'];
date_default_timezone_set('Asia/Manila');
$current_date = date("Y-m-d h:i:s");
$time_start = microtime_float();
$dataSanitation = new DataSanitation();
$result = $dataSanitation->getRulesDetails($conn_pdo, $value[1]);

if($result == '0'){
  file_put_contents('log.txt', 'NO|rule details|Rule code:|' . $value[1] . '|MD:|' . $value[2]  . '|Sanitized_by:|' . $value[3] . PHP_EOL , FILE_APPEND | LOCK_EX);
}else {
  $count = $dataSanitation->countRuleDetailsMatch($pdoConnSanitationResultDB, $result);
  if($count > 0){
    //get raw id
    $raw_id = $dataSanitation->getRawID($pdoConnSanitationResultDB, $result);
    //update raw_corrected_name, sanitized_by, sanitized_date
    $md_details = $dataSanitation->getMdDetails($conn_pdo, $value[2]);
    $md_code = 'N/A';
    $md_class = 'N/A';
    $md_universe = 'N/A';
    foreach ($md_details as $key => $md_details_value) {
        $md_class = $md_details_value['class'];
        $md_code = $md_details_value['md_code'];
        $md_universe = $md_details_value['sanit_universe'];
    }

    foreach ($raw_id as $key => $id) {
      //update
      $dataSanitation->applyRules($pdoConnSanitationResultDB, $md_class, $md_code, $value[2], $md_universe, $value[3], $id);
      file_put_contents('log.txt', 'YES|rule details|raw_id:|' . $id . '|MD CODE:|' . $md_code . '|MD:|' . $value[2]  . '|Sanitized_by:|' . $value[3] . PHP_EOL , FILE_APPEND | LOCK_EX);
    }
    $time_end = microtime_float();
    $time = number_format($time_end - $time_start, 3);
    file_put_contents('log.txt', 'YES|rule details|Rule code:|' . $value[1] . '|Affected data:|' . $count . '|MD:|' . $value[2]  . '|Sanitized_by:|' . $value[3] . PHP_EOL , FILE_APPEND | LOCK_EX);
    file_put_contents('log.txt', 'DATETIME|' . $current_date. PHP_EOL , FILE_APPEND | LOCK_EX);
    file_put_contents('log.txt', 'DURATION|' . $time. ' seconds' . PHP_EOL , FILE_APPEND | LOCK_EX);
    file_put_contents('log.txt', '---------------------------------------------------------------------------------------------------------------------------' . PHP_EOL , FILE_APPEND | LOCK_EX);
    //push update(realtime)
    $dataSanitation->getDataForBroadCast($raw_id, $value[2], $value[3]);
  }else {
    $time_end = microtime_float();
    $time = number_format($time_end - $time_start, 3);
    file_put_contents('log.txt', 'YES|rule details|Rule code:|' . $value[1] . '|Affected data:|' . $count . '|MD:|' . $value[2]  . '|Sanitized_by:|' . $value[3] . PHP_EOL , FILE_APPEND | LOCK_EX);
    file_put_contents('log.txt', 'DATETIME|' . $current_date. PHP_EOL , FILE_APPEND | LOCK_EX);
    file_put_contents('log.txt', 'DURATION|' . $time. ' seconds' . PHP_EOL , FILE_APPEND | LOCK_EX);
    file_put_contents('log.txt', '---------------------------------------------------------------------------------------------------------------------------' . PHP_EOL , FILE_APPEND | LOCK_EX);
  }



}

function microtime_float(){
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

// file_put_contents('log.txt', 'No rule details - Rule code: ' . $value[1] . ' MD: ' . $value[2]  . ' Sanitized_byx: ' . $value[3] . PHP_EOL , FILE_APPEND | LOCK_EX);

//else {
//
//   $count = $dataSanitation->countRuleDetailsMatch($pdoConnSanitationResultDB, $result);
//   if($count > 0){
//     //get raw id
//     $raw_id = $dataSanitation->getRawID($pdoConnSanitationResultDB, $result);
//     //update raw_corrected_name, sanitized_by, sanitized_date
//     $md_details = $dataSanitation->getMdDetails($conn_pdo, $value[2]);
//     $md_code = 'N/A';
//     $md_class = 'N/A';
//     $md_universe = 'N/A';
//     foreach ($md_details as $key => $value) {
//       $md_class = $value['class'];
//       $md_code = $value['md_code'];
//       $md_universe = $value['sanit_universe'];
//     }
//     date_default_timezone_set('Asia/Manila');
//     $current_date = date("Y-m-d h:i:s");
//     foreach ($raw_id as $key => $id) {
//       //update
//       $dataSanitation->applyRules($pdoConnSanitationResultDB, $md_class, $md_code, $value[2], $md_universe, $value[3], $id)
//     }
//     //push update(realtime)
//     $dataSanitation->getDataForBroadCast($raw_id, $value[2], $value[3]);
//     file_put_contents('log.txt', 'Rule code: ' . $value[1] . ' | Affected data: ' . $count . 'MD: ' . $value[2]  . 'Sanitized_by: ' . $value[3] . PHP_EOL , FILE_APPEND | LOCK_EX);
//   }else {
//     file_put_contents('log.txt', 'Rule code: ' . $value[1] . ' | Affected data: ' . $count . 'MD: ' . $value[2]  . 'Sanitized_by: ' . $value[3] . PHP_EOL , FILE_APPEND | LOCK_EX);
//   }
//
//
//
// }




 ?>
