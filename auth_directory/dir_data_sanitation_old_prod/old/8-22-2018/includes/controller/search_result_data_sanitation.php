<?php
include('../../../../connection.php');
spl_autoload_register(function ($class_name) {
    include '../class/'.$class_name . '.php';
});

$dataSanitation = new DataSanitation();
$data = $_GET['data'];
$district = $_GET['district'];
$category = $_GET['category'];
$filtered_md = $_GET['filtered_md'];
$filtered_ln = $_GET['filtered_ln'];
$filtered_loc = $_GET['filtered_loc'];
$filtered_branch = $_GET['filtered_branch'];
$filtered_lba = $_GET['filtered_lba'];
$data = $dataSanitation->searchDataSanitation($pdoConnSanitationResultDB, $data, explode(',', $district), $category, $filtered_md, $filtered_ln, $filtered_loc, $filtered_branch, $filtered_lba);

// var_dump($data);

// foreach ($data as $key => $value) {
//   $new[] = array(
//       "raw_id" => $value['raw_id'],
//       "raw_doctor" => trim($value['raw_doctor']),
//       "raw_license" => trim($value['raw_license']),
//       "raw_address" => trim($value['raw_address']),
//       "raw_branchname" => trim($value['raw_branchname']),
//       "raw_lbucode" => trim($value['raw_lbucode']),
//       "0" => $value['raw_id'],
//       "1" => trim($value['raw_doctor']),
//       "2" => trim($value['raw_license']),
//       "3" => trim($value['raw_address']),
//       "4" => trim($value['raw_branchname']),
//       "5" => trim($value['raw_lbucode'])
//     );
// }

$msg = array(
"data" => $data
);
$json = $msg;
header('content-type: application/json');
echo json_encode($json,JSON_PRETTY_PRINT);



 ?>
