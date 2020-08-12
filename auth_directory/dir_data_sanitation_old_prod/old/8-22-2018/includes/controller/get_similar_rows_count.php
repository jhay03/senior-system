<?php
include('../../../../connection.php');
ini_set('memory_limit', '2048M');
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
      $new[] = array(
          $value['raw_id']
        );
    }


}


$msg = array(
"count" => number_format(count($new)),
"md" => $mdname
);
$json = $msg;
header('content-type: application/json');
echo json_encode($json,JSON_PRETTY_PRINT);


 ?>
