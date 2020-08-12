<?php
include('../../../../connection.php');
include '../class/DataSanitationRules.php';
include '../class/DataSanitation.php';

$dataSanitation = new DataSanitation();
$result = $dataSanitation->resetUnsanitizedData($pdoConnSanitationResultDB);

header('content-type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);

 ?>
