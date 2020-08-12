<?php
/************************ YOUR DATABASE CONNECTION START HERE   ****************************/

ini_set('upload_max_filesize', '64M');
ini_set('memory_limit', '-1');
set_time_limit(0);                
ignore_user_abort(true);

include'../../connection.php';

if ( isset($_POST["submit"]) ) {
    ini_set('upload_max_filesize', '32M');
    ini_set('memory_limit', '512M');
    ini_set('max_execution_time', '600000');
    if ( isset($_FILES["file"])) {
    //if there was an error uploading the file
    if ($_FILES["file"]["error"] > 0) {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
    else {
    if (file_exists($_FILES["file"]["name"])) {
    unlink($_FILES["file"]["name"]);
    }
    $inputFileName = $_FILES["file"]["name"];
    move_uploaded_file($_FILES["file"]["tmp_name"],"uploaded_data/".$inputFileName);
    }
    } else {
    echo "No file selected <br />";
    }
  }

/************************ YOUR DATABASE CONNECTION END HERE  ****************************/
require_once '../../plugins/Classes/PHPExcel.php';
require_once '../../plugins/Classes/PHPExcel/IOFactory.php';

$mysqli -> query("TRUNCATE db_sanitation");

$inputFileType = PHPExcel_IOFactory::identify("uploaded_data/".$inputFileName);

                $objReader = PHPExcel_IOFactory::createReader($inputFileType);  

                $objReader->setReadDataOnly(true);
try {
	$objPHPExcel = PHPExcel_IOFactory::load("uploaded_data/".$inputFileName);
} catch(Exception $e) {
	die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
}


$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,false,true,true);
$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet

$myquery = "";
$myquery = "INSERT INTO db_sanitation VALUES ";
for($i=2;$i<=$arrayCount;$i++){
$mduniverse     = $mysqli -> real_escape_string($allDataInSheet[$i]["A"]);
$group          = $mysqli -> real_escape_string($allDataInSheet[$i]["B"]);
$md_name        = $mysqli -> real_escape_string($allDataInSheet[$i]["C"]);
$key            = $mysqli -> real_escape_string($allDataInSheet[$i]["D"]);
$mdcode         = $mysqli -> real_escape_string($allDataInSheet[$i]["E"]);
$myquery .= "('',
                '$mduniverse',
                '$group', 
                '$md_name',  
                '$key',
                '$mdcode')";
    if($i !== $arrayCount && ($i % 200) != 0){
        $myquery .= ",";
    }elseif(($i % 200) == 0 || $i == $arrayCount){
        //echo $myquery;
        $insertTable=$mysqli -> query($myquery);
        $myquery = "";
        $myquery .= "INSERT INTO db_sanitation VALUES ";
    }else{
        $myquery .= "";
    }

}
$del = $mysqli -> query("DELETE FROM db_sanitation WHERE sanit_mdname=''");


unlink("uploaded_data/".$inputFileName);
header('Location:masterlist_db.php');
?>