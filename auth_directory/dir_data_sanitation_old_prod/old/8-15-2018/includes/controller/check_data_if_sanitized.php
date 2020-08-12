<?php
include('../../../../connection.php');
spl_autoload_register(function ($class_name) {
    include '../class/'.$class_name . '.php';
});

$dataSanitation = new DataSanitation();
$id = json_decode($_POST['id']);

$data = $dataSanitation->checkDataIfSanitized($pdoConnSanitationResultDB, $id);
$new = [];
// var_dump($data);
if($data){
  foreach ($data as $key => $value) {
    $new[] = array(
         $value['raw_id'],
        $value['raw_doctor'],
        $value['raw_license'],
        $value['raw_address'],
        $value['raw_branchname'],
        $value['raw_lbucode'],
        $value['sanitized_by'],
        $value['date_sanitized']
      );
  }
}


// if($data['raw_status'] == ""){
  // $new[] = array(
  //     '<span style="background-color: cyan; color: #000">' . $data['raw_id'] . '</span>',
  //     trim($data['raw_doctor']),
  //     trim($data['raw_license']),
  //     trim($data['raw_address']),
  //     trim($data['raw_branchname']),
  //     trim($data['raw_lbucode']),
  //     trim($data['sanitized_by']),
  //     trim($data['date_sanitized'])
  //   );
// }else {
//   $new[] = array(
//     '<span background-color: cyan;>' . $data['raw_id'] . '</span>',
//     trim($data['raw_doctor']),
//     trim($data['raw_license']),
//     trim($data['raw_address']),
//     trim($data['raw_branchname']),
//     trim($data['raw_lbucode']),
//     trim($data['sanitized_by']),
//     trim($data['date_sanitized'])
//     );
// }

$msg = array(
"data" => $new
);
$json = $msg;
header('content-type: application/json');
echo json_encode($json,JSON_PRETTY_PRINT);



 ?>
