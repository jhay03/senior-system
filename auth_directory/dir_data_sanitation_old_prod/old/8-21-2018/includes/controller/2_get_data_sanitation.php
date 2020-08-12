<?php
include('../../../../connection.php');
ini_set('memory_limit', '2048M');
spl_autoload_register(function ($class_name) {
    include '../class/'.$class_name . '.php';
});



$district = $_GET['district'];
$mdname = $_GET['mdname'];
$dataSanitation = new DataSanitation();
$districtDataRows = $dataSanitation->getDataPerDistrict($pdoConnSanitationResultDB, explode(',', $district));
// var_dump($districtDataRows);
//FOREACH THEN GET DATA FOR SPECIFIED MD


$new = [];
foreach ($districtDataRows as $key => $value) {

    // if($fuzz->partialRatio($mdname, $value['raw_doctor']) >= $dataSanitation->percent){

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


    // }



}


$msg = array(
"data" => $new
);
$json = $msg;
header('content-type: application/json');
echo json_encode($json,JSON_PRETTY_PRINT);


 ?>
