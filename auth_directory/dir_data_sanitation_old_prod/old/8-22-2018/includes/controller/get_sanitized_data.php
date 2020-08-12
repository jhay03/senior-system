<?php
include('../../../../connection.php');
include('vendor/autoload.php');

spl_autoload_register(function ($class_name) {
    include '../class/'.$class_name . '.php';
});


$district = $_GET['district'];
$mdname = $_GET['mdname'];
$dataSanitation = new DataSanitation();
$data = $dataSanitation->getSanitizedData($pdoConnSanitationResultDB, explode(',', $district), $mdname);
$new = [];
foreach ($data as $key => $value) {
  $new[] = array(
      $value['raw_id'],
      $value['raw_doctor'],
      $value['raw_corrected_name'],
      $value['raw_license'],
      $value['raw_address'],
      $value['raw_branchname'],
      $value['raw_lbucode'],
      $value['sanitized_by'],
      $value['date_sanitized']
    );
}

$msg = array(
"data" => $new
);
$json = $msg;
header('content-type: application/json');
echo json_encode($json,JSON_PRETTY_PRINT);


 ?>
