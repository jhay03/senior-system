<?php
include('../../../../connection.php');
spl_autoload_register(function ($class_name) {
    include '../class/'.$class_name . '.php';
});

$dataSanitation = new DataSanitation();
$id = json_decode($_POST['id']);

$data = $dataSanitation->viewUncleanData($pdoConnSanitationResultDB, $id);

// var_dump($data);
$new = [];
foreach ($data as $key => $value) {
  $new[] = array(
      $value['raw_id'],
      $value['raw_doctor'],
      $value['raw_license'],
      $value['raw_address'],
      $value['raw_branchname'],
      $value['raw_lbucode']
    );
}


$msg = array(
"data" => $new
);
$json = $msg;
header('content-type: application/json');
echo json_encode($json,JSON_PRETTY_PRINT);



 ?>
