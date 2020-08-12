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

// echo $fuzz->ratio('TORDESILLA-ATIENZA, MA. CHRISTINA', 'LORENZO CHACON');
// echo $fuzz->ratio('TORDESILLA-ATIENZA, MA. CHRISTINA', 'MA. CRISTINA ATIENZA');

$district = $_GET['district'];
$mdname = $_GET['mdname'];
$dataSanitation = new DataSanitation();
$districtDataRows = $dataSanitation->getDataPerDistrict($pdoConnSanitationResultDB, explode(',', $district));
// var_dump($districtDataRows);
//FOREACH THEN GET DATA FOR SPECIFIED MD
// print_r($districtDataRows);
$new = [];
foreach ($districtDataRows as $key => $value) {

  // $name = $var = explode(' ', $mdname);
  // foreach ($name as $split_name) {
    if($fuzz->partialRatio($mdname, $value['raw_doctor']) >= $dataSanitation->percent){
      // echo $value['raw_doctor'] . '<br/>';
      $new[] = array(
          "raw_id" => $value['raw_id'],
          "raw_doctor" => $value['raw_doctor'],
          "raw_license" => $value['raw_license'],
          "raw_address" => $value['raw_address'],
          "raw_branchname" => $value['raw_branchname'],
          "raw_lbucode" => $value['raw_lbucode'],
          "0" => $value['raw_id'],
          "1" => $value['raw_doctor'],
          "2" => $value['raw_license'],
          "3" => $value['raw_address'],
          "4" => $value['raw_branchname'],
          "5" => $value['raw_lbucode']
        );
    }
  // }
  // if($fuzz->ratio($mdname, $value['raw_doctor']) >= $dataSanitation->percent){
  //   // echo $value['raw_doctor'] . '<br/>';
  //   $new[] = array(
  //     "raw_id" => $value['raw_id'],
  //     "raw_doctor" => $value['raw_doctor'],
  //     "raw_license" => $value['raw_license'],
  //     "raw_address" => $value['raw_address'],
  //     "raw_lbucode" => $value['raw_lbucode'],
  //     "raw_branchname" => $value['raw_branchname'],
  //     "0" => $value['raw_id'],
  //     "1" => $value['raw_doctor'],
  //     "2" => $value['raw_license'],
  //     "3" => $value['raw_address'],
  //     "4" => $value['raw_lbucode'],
  //     "5" => $value['raw_branchname']
  //     );
  // }else {
  //   // echo $fuzz->ratio($mdname, $value['raw_doctor']). '<br/>';
  // }


}


$msg = array(
"count" => number_format(count($new)),
"md" => $mdname
);
$json = $msg;
header('content-type: application/json');
echo json_encode($json,JSON_PRETTY_PRINT);


 ?>
