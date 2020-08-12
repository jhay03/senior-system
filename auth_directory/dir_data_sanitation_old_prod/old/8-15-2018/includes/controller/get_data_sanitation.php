<?php
include('../../../../connection.php');
include('vendor/autoload.php');
// include('fuzzywuzzy-master/lib/Process.php');
// include('fuzzywuzzy-master/lib/Diff/SequenceMatcher.php');


spl_autoload_register(function ($class_name) {
    include '../class/'.$class_name . '.php';
});


use FuzzyWuzzy\Fuzz;
use FuzzyWuzzy\Process;

$fuzz = new Fuzz();
$process = new Process($fuzz); // $fuzz is optional here, and can be omitted.

// $var = explode(' ', 'GO LUIS RAYMOND');
// foreach ($var as $value) {
//   echo $value . '<br />';
// }
// // echo $fuzz->ratio('GO LUIS RAYMOND', 'GO');
// // // echo $fuzz->ratio('fuzzy wuzzy was a bear', 'wuzzy fuzzy was a bear');
// // echo '<br />';
// // echo $fuzz->tokenSetRatio('GO LUIS RAYMOND', 'GO');
// // // echo $fuzz->tokenSortRatio('fuzzy wuzzy was a bear', 'wuzzy fuzzy was a bear');
//
// exit();
// // echo $fuzz->ratio('TORDESILLA-ATIENZA, MA. CHRISTINA', 'MA. CRISTINA ATIENZA');

$district = $_GET['district'];
$mdname = $_GET['mdname'];
$dataSanitation = new DataSanitation();
$districtDataRows = $dataSanitation->getDataPerDistrict($pdoConnSanitationResultDB, explode(',', $district));
// var_dump($districtDataRows);
//FOREACH THEN GET DATA FOR SPECIFIED MD


$new = [];
foreach ($districtDataRows as $key => $value) {

    if($fuzz->partialRatio($mdname, $value['raw_doctor']) >= $dataSanitation->percent){

      if(empty($value['sanitized_by'])){
        $value['date_sanitized'] = '';
      }
      $new[] = array(
          $value['raw_id'],
          $value['raw_doctor'],
          $value['raw_corrected_name'],
          $value['raw_license'],
          $value['raw_address'],
          $value['raw_branchname'],
          $value['raw_lbucode'],
          $value['raw_hospcode'],
          $value['raw_sarcode'],
          $value['raw_amount'],
          $value['sanitized_by'],
          $value['date_sanitized']
        );

      // $new[] = array(
      //     "raw_id" => $value['raw_id'],
      //     "raw_doctor" => trim($value['raw_doctor']),
      //     "raw_license" => trim($value['raw_license']),
      //     "raw_address" => trim($value['raw_address']),
      //     "raw_branchname" => trim($value['raw_branchname']),
      //     "raw_lbucode" => trim($value['raw_lbucode']),
      //     "raw_hospcode" => trim($value['raw_hospcode']),
      //     "raw_sarcode" => trim($value['raw_sarcode']),
      //     "raw_amount" => trim($value['raw_amount']),
      //     "0" => $value['raw_id'],
      //     "1" => trim($value['raw_doctor']),
      //     "2" => trim($value['raw_license']),
      //     "3" => trim($value['raw_address']),
      //     "4" => trim($value['raw_branchname']),
      //     "5" => trim($value['raw_lbucode']),
      //     "6" => trim($value['raw_hospcode']),
      //     "7" => trim($value['raw_sarcode']),
      //     "8" => trim($value['raw_amount'])
      //   );

    }



}


$msg = array(
"data" => $new
);
$json = $msg;
header('content-type: application/json');
echo json_encode($json,JSON_PRETTY_PRINT);


 ?>
