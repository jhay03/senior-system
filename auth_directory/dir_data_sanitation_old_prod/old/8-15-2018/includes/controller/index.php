<?php
session_start();
include('../../../../connection.php');
include('vendor/autoload.php');
spl_autoload_register(function ($class_name) {
    include '../class/'.$class_name . '.php';
});
use FuzzyWuzzy\Fuzz;
use FuzzyWuzzy\Process;

$fuzz = new Fuzz();
$process = new Process($fuzz);

// $dataSanitation = new DataSanitation();
// $doctor = 'ALBA';
// $ln = '86554';
// $loc = 'CGH                                 x';
// $result = $dataSanitation->getTest($pdoConnSanitationResultDB, $doctor, $ln, $loc);
//
// header('content-type: application/json');
// echo json_encode($result, JSON_PRETTY_PRINT);
//
// exit();


if(isset($_POST['cmd'])){

  $cmd = $_POST['cmd'];

  if ($cmd == 'get_filtered_group_data') {

    $dataSanitation = new DataSanitation();
    $data = $_POST['data'];
    $district = $_POST['district'];
    $category = $_POST['category'];
    $md = $_POST['md'];
    $filtered_md = $_POST['filtered_md'];
    $license= $_POST['license'];
    $loc = $_POST['loc'];
    $branch = $_POST['branch'];
    $lba = $_POST['lba'];
    $filtered_by = $_POST['filtered_by'];
    $md_list = $dataSanitation->getFilteredGroupPerDistrict($filtered_by, $pdoConnSanitationResultDB, $data, explode(',', $district), $category, $md, $fuzz, $filtered_md, $license, $loc, $branch, $lba);
    $count = count($md_list);
    if($count > 0){

      foreach ($md_list as $key => $value) {
        $arr[$value['id']][$key] = $value;
      }

      $x = null;
      foreach ($arr as $key => $value) {
        if($x != $key){
          $array_result[] = array(
            "id" => $key,
            "text" => $key . '- ' .  count($value) . ' Record(s)'
          );
        }
        $x = $key;
      }
    }else {
      $array_result[] = array(
        "id" => '',
        "text" => ''
      );
    }


    header('content-type: application/json');
    echo json_encode($array_result, JSON_PRETTY_PRINT);

  }else if($cmd == 'get_group_md_by_district'){

    $dataSanitation = new DataSanitation();
    $data = $_POST['data'];
    $district = $_POST['district'];
    $category = $_POST['category'];
    $md = $_POST['md'];
    $filtered_md = $_POST['filtered_md'];
    $license= $_POST['license'];
    $loc = $_POST['loc'];
    $branch = $_POST['branch'];
    $lba = $_POST['lba'];
    $filtered_by = $_POST['filtered_by'];
    $md_list = $dataSanitation->getGroupMDPerDistrict($filtered_by, $pdoConnSanitationResultDB, $data, $district, $category, $md, $fuzz, $filtered_md, $license, $loc, $branch, $lba);
    $count = count($md_list);
    if($count > 0){

      foreach ($md_list as $key => $value) {
        $arr[$value['id']][$key] = $value;
      }

      $x = null;
      foreach ($arr as $key => $value) {
        if($x != $key){
          $array_result[] = array(
            "id" => $key,
            "text" => $key . '- ' .  count($value) . ' Record(s)'
          );
        }
        $x = $key;
      }
    }else {
      $array_result[] = array(
        "id" => '',
        "text" => ''
      );
    }


    header('content-type: application/json');
    echo json_encode($array_result, JSON_PRETTY_PRINT);

  }else if ($cmd == 'get_group_ln_by_district') {
    $dataSanitation = new DataSanitation();
    $data = $_POST['data'];
    $district = $_POST['district'];
    $category = $_POST['category'];
    $md = $_POST['md'];
    $ln_list = $dataSanitation->getGroupLNPerDistrict($pdoConnSanitationResultDB, $data, $district, $category, $md, $fuzz);
    $count = count($ln_list);
    if($count > 0){

      foreach ($ln_list as $key => $value) {
        $arr[$value['id']][$key] = $value;
      }

      $x = null;
      foreach ($arr as $key => $value) {
        if($x != $key){
          $array_result[] = array(
            "id" => $key,
            "text" => $key . '- ' .  count($value) . ' Record(s)'
          );
        }
        $x = $key;
      }
    }else {
      $array_result[] = array(
        "id" => '',
        "text" => ''
      );
    }

    header('content-type: application/json');
    echo json_encode($array_result, JSON_PRETTY_PRINT);

  }else if ($cmd == 'get_group_loc_by_district') {
    $dataSanitation = new DataSanitation();
    $data = $_POST['data'];
    $district = $_POST['district'];
    $category = $_POST['category'];
    $md = $_POST['md'];
    $loc_list = $dataSanitation->getGroupLocPerDistrict($pdoConnSanitationResultDB, $data, $district, $category, $md, $fuzz);
    $count = count($loc_list);
    if($count > 0){

      foreach ($loc_list as $key => $value) {
        $arr[$value['id']][$key] = $value;
      }

      $x = null;
      foreach ($arr as $key => $value) {
        if($x != $key){
          $array_result[] = array(
            "id" => $key,
            "text" => $key . '- ' .  count($value) . ' Record(s)'
          );
        }
        $x = $key;
      }
    }else {
      $array_result[] = array(
        "id" => '',
        "text" => ''
      );
    }

    header('content-type: application/json');
    echo json_encode($array_result, JSON_PRETTY_PRINT);

  }else if ($cmd == 'get_group_branch_by_district') {
    $dataSanitation = new DataSanitation();
    $data = $_POST['data'];
    $district = $_POST['district'];
    $category = $_POST['category'];
    $md = $_POST['md'];
    $branch_list = $dataSanitation->getGroupBranchPerDistrict($pdoConnSanitationResultDB, $data, $district, $category, $md, $fuzz);
    $count = count($branch_list);
    if($count > 0){

      foreach ($branch_list as $key => $value) {
        $arr[$value['id']][$key] = $value;
      }

      $x = null;
      foreach ($arr as $key => $value) {
        if($x != $key){
          $array_result[] = array(
            "id" => $key,
            "text" => $key . '- ' .  count($value) . ' Record(s)'
          );
        }
        $x = $key;
      }
    }else {
      $array_result[] = array(
        "id" => '',
        "text" => ''
      );
    }

    header('content-type: application/json');
    echo json_encode($array_result, JSON_PRETTY_PRINT);

  }else if ($cmd == 'get_group_lba_by_district') {
    $dataSanitation = new DataSanitation();
    $data = $_POST['data'];
    $district = $_POST['district'];
    $category = $_POST['category'];
    $md = $_POST['md'];
    $lba_list = $dataSanitation->getGroupLBAPerDistrict($pdoConnSanitationResultDB, $data, $district, $category, $md, $fuzz);
    $count = count($lba_list);
    if($count > 0){

      foreach ($lba_list as $key => $value) {
        $arr[$value['id']][$key] = $value;
      }

      $x = null;
      foreach ($arr as $key => $value) {
        if($x != $key){
          $array_result[] = array(
            "id" => $key,
            "text" => $key . '- ' .  count($value) . ' Record(s)'
          );
        }
        $x = $key;
      }
    }else {
      $array_result[] = array(
        "id" => '',
        "text" => ''
      );
    }

    header('content-type: application/json');
    echo json_encode($array_result, JSON_PRETTY_PRINT);

  }else if ($cmd == 'search_data_sanitation_by_md_name') {
    $dataSanitation = new DataSanitation();
    $data = $_POST['data'];
    $district = $_POST['district'];
    $category = $_POST['category'];
    $result = $dataSanitation->searchDataSanitation($pdoConnSanitationResultDB, $data, $district, $category);

    header('content-type: application/json');
    echo json_encode($result, JSON_PRETTY_PRINT);

  }else if ($cmd == 'get_district_list') {
    $dataSanitation = new DataSanitation();
    $auth_usercode = $_POST['auth_usercode'];
    $result = $dataSanitation->getAllDistrictPerMember($conn_pdo, $auth_usercode);

    header('content-type: application/json');
    echo json_encode($result, JSON_PRETTY_PRINT);

  }else if ($cmd == 'get_md_list') {
    $dataSanitation = new DataSanitation();
    $auth_usercode = $_POST['auth_usercode'];
    $result = $dataSanitation->getMDlist($conn_pdo, $auth_usercode);

    header('content-type: application/json');
    echo json_encode($result, JSON_PRETTY_PRINT);

  }else if ($cmd == 'get_district_details') {
    $dataSanitation = new DataSanitation();
    $district = $_POST['district'];
    $result = $dataSanitation->getDistrictDetails($conn_pdo, explode(',', $district), $pdoConnSanitationResultDB);

    header('content-type: application/json');
    echo json_encode($result, JSON_PRETTY_PRINT);

  }else if ($cmd == 'update_sanitation_table') {
    $dataSanitation = new DataSanitation();
    $raw_id = $_POST['raw_id'];
    $md = $_POST['doctor_list'];
    $result = $dataSanitation->updateDataSanitationResult1($pdoConnSanitationResultDB, explode(',', $raw_id), $md, $conn_pdo, $_SESSION['authUser'], $_SESSION['auth_usercode']);

    header('content-type: application/json');
    echo json_encode($result, JSON_PRETTY_PRINT);

  }else if ($cmd == 'update_unclassified_table') {
    $dataSanitation = new DataSanitation();
    $raw_id = $_POST['raw_id'];
    $md = $_POST['doctor_list'];
    $result = $dataSanitation->setUnclasified($pdoConnSanitationResultDB, explode(',', $raw_id));

    header('content-type: application/json');
    echo json_encode($result, JSON_PRETTY_PRINT);

  }else if ($cmd == 'check_md_name') {
    $dataSanitation = new DataSanitation();
    $orig_name = $_POST['orig_name'];
    $md = $_POST['doctor_list'];
    $result = $dataSanitation->checkMDname($conn_pdo, $md, $orig_name);

    header('content-type: application/json');
    echo json_encode($result, JSON_PRETTY_PRINT);

  }elseif ($cmd == 'get_group_count') {

    $dataSanitation = new DataSanitation();
    $count = $dataSanitation->getCount($pdoConnSanitationResultDB, '$val');
    header('content-type: application/json');
    echo json_encode($count, JSON_PRETTY_PRINT);

  }


}


 ?>
