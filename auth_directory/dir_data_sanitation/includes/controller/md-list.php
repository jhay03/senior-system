<?php
include('../../../../connection.php');
spl_autoload_register(function ($class_name) {
    include '../class/'.$class_name . '.php';
});

$dataSanitation = new DataSanitation();
$name = $_GET['term'];
$class = $_GET['class'];

$data = $dataSanitation->getAllMD($conn_pdo, $name, $class);
header('content-type: application/json');
echo json_encode($data,JSON_PRETTY_PRINT);

 ?>
