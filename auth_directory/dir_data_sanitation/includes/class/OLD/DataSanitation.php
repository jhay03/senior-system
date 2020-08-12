<?php

/**
 *
 */
class DataSanitation extends DataSanitationRules
{

  public $percent = 60;

  public function getDistrictDetails($conn_pdo, $district, $conn_pdo2)
  {
    try {
        $array_result = [];
        $sql = "SELECT district_name,district_rowcount FROM district_assignment WHERE district_name = :district_name";
        $stmt = $conn_pdo->prepare($sql);
        $stmt->bindParam(':district_name', $district, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetchAll();
        if($data){
          foreach ($data as $row) {
              $array_result[] = array(
              "rowsCount" => $this->countDistrictRow($conn_pdo2, $district), //$row['district_rowcount'],
              "districtname" => $row['district_name']
            );
          }
        }

        return $array_result;
      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
  }

  public function countDistrictRow($conn_pdo, $district)
  {
    try {
        $array_result = [];
        $sql = "SELECT COUNT(raw_id) as TOTAL FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = ''";
        $stmt = $conn_pdo->prepare($sql);
        $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetchAll();
        if($data){
          foreach ($data as $row) {
              $data = $row['TOTAL'];
          }
        }

        return $data;
      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
  }

  public function getAllDistrictPerMember($conn_pdo, $user_code)
  {
    try {
        $array_result = [];
        $sql = "SELECT district_name FROM district_assignment WHERE user_code = :user_code";
        $stmt = $conn_pdo->prepare($sql);
        $stmt->bindParam(':user_code', $user_code, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetchAll();
        if($data){
          foreach ($data as $row) {
              $array_result[] = array(
              "id" => $row['district_name'],
              "text" => $row['district_name']
            );
          }
        }
        return $array_result;
      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
  }

  public function getAllMD($conn_pdo, $get_name)
  {
    try {
        $array_result = [];
        $name ="%".$get_name."%";
        $sql = "SELECT DISTINCT(REPLACE(sanit_mdname, ',', '')) as NAME FROM db_sanitation
                WHERE REPLACE(sanit_mdname, ',', '') LIKE :sanit_mdname ORDER BY sanit_mdname ASC LIMIT 10";
        $stmt = $conn_pdo->prepare($sql);
        $stmt->bindParam(':sanit_mdname', $name, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetchAll();
        if($data){
          foreach ($data as $row) {
              $array_result[] = array(
              "id" => $row['NAME'],
              "value" => $row['NAME']
            );
          }
        }else {
          $array_result[] = array(
          "id" => 'No result',
          "value" => 'No result'
        );
        }

        return $array_result;
      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
  }

  public function checkMDname($conn_pdo, $get_name)
  {
    try {
        $array_result = [];
        $name ="%".$get_name."%";
        $sql = "SELECT DISTINCT(sanit_mdname) FROM db_sanitation
                WHERE sanit_mdname = :sanit_mdname LIMIT 1";
        $stmt = $conn_pdo->prepare($sql);
        $stmt->bindParam(':sanit_mdname', $get_name, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetchAll();
        if($data){
          foreach ($data as $row) {
              $array_result[] = array(
              "result" => 1,
              "value" => $row['sanit_mdname']
            );
          }
        }else {
          $array_result[] = array(
          "result" => '0',
          "value" => 'No result'
        );
        }

        return $array_result;
      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
  }

  public function getMDlist($conn_pdo, $user_code)
  {
    try {
        $array_result = [];
        $sql = "SELECT sanit_mdname FROM db_sanitation WHERE sanit_group IN ('JEDI','PADAWAN','IPG') ORDER BY sanit_mdname ASC LIMIT 10";
        $stmt = $conn_pdo->prepare($sql);
        $stmt->bindParam(':user_code', $user_code, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetchAll();
        if($data){
          foreach ($data as $row) {
              $array_result[] = array(
              "id" => $row['sanit_mdname'],
              "text" => $row['sanit_mdname']
            );
          }
        }

        // $sql = "SELECT sanit_mdname FROM db_sanitation WHERE sanit_group NOT IN ('JEDI','PADAWAN','IPG') ORDER BY sanit_mdname ASC";
        // $stmt = $conn_pdo->prepare($sql);
        // $stmt->bindParam(':user_code', $user_code, PDO::PARAM_STR);
        // $stmt->execute();
        // $data = $stmt->fetchAll();
        // if($data){
        //   foreach ($data as $row) {
        //       $array_result[] = array(
        //       "id" => $row['sanit_mdname'],
        //       "text" => $row['sanit_mdname']
        //     );
        //   }
        // }


        return $array_result;
      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
  }

  public function getDataPerDistrict($conn_pdo, $district)
  {
    try {

        $sql = "SELECT raw_id, raw_doctor, raw_license, raw_address, raw_branchname, raw_lbucode FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = ''";
        $stmt = $conn_pdo->prepare($sql);
        $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetchAll();
        //$result = $stmt->fetchAll();
        // foreach ($result as $key => $value) {
        //   $data[] = array(
        //     array( $value['raw_doctor']),
        //     array( $value['raw_license']),
        //     array( $value['raw_address']),
        //     array( $value['raw_lbucode']),
        //     array( $value['raw_branchname'])
        //   );
        // }
        return $data;
      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
  }

  public function searchDataSanitation($conn_pdo, $data, $district, $category, $filtered_md, $filtered_ln, $filtered_loc, $filtered_branch, $filtered_lba)
  {
    try {
            $array_result = [];
            if($category == 'md_name'){

              $sql = "SELECT raw_id, raw_doctor, raw_license, raw_address, raw_branchname, raw_lbucode FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND TRIM(raw_doctor) = :raw_doctor ORDER BY raw_doctor ASC";
              $stmt = $conn_pdo->prepare($sql);
              $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
              $stmt->bindParam(':raw_doctor', $filtered_md, PDO::PARAM_STR);
              $stmt->execute();
              $array_result = $stmt->fetchAll();

            } else if ($category == 'license') {

              $sql = "SELECT raw_id, raw_doctor, raw_license, raw_address, raw_branchname, raw_lbucode FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND TRIM(raw_doctor) =:raw_doctor AND TRIM(raw_license) = :raw_license ORDER BY raw_doctor ASC";
              $stmt = $conn_pdo->prepare($sql);
              $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
              $stmt->bindParam(':raw_license', $filtered_ln, PDO::PARAM_STR);
              $stmt->bindParam(':raw_doctor', $filtered_md, PDO::PARAM_STR);
              $stmt->execute();
              $array_result = $stmt->fetchAll();

            } else if ($category == 'location') {

              $sql = "SELECT raw_id, raw_doctor, raw_license, raw_address, raw_branchname, raw_lbucode FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND TRIM(raw_doctor) =:raw_doctor AND TRIM(raw_license) = :raw_license  AND TRIM(raw_address) = :raw_address ORDER BY raw_doctor ASC";
              $stmt = $conn_pdo->prepare($sql);
              $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
              $stmt->bindParam(':raw_doctor', $filtered_md, PDO::PARAM_STR);
              $stmt->bindParam(':raw_license', $filtered_ln, PDO::PARAM_STR);
              $stmt->bindParam(':raw_address', $filtered_loc, PDO::PARAM_STR);
              $stmt->execute();
              $array_result = $stmt->fetchAll();

            } else if ($category == 'md_branch') {

              $sql = "SELECT raw_id, raw_doctor, raw_license, raw_address, raw_branchname, raw_lbucode FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND TRIM(raw_doctor) =:raw_doctor AND TRIM(raw_license) = :raw_license  AND TRIM(raw_address) = :raw_address AND TRIM(raw_branchname) = :raw_branchname ORDER BY raw_doctor ASC";
              $stmt = $conn_pdo->prepare($sql);
              $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
              $stmt->bindParam(':raw_doctor', $filtered_md, PDO::PARAM_STR);
              $stmt->bindParam(':raw_license', $filtered_ln, PDO::PARAM_STR);
              $stmt->bindParam(':raw_address', $filtered_loc, PDO::PARAM_STR);
              $stmt->bindParam(':raw_branchname', $filtered_branch, PDO::PARAM_STR);
              $stmt->execute();
              $array_result = $stmt->fetchAll();

            } else if ($category == 'md_lba') {

              $sql = "SELECT raw_id, raw_doctor, raw_license, raw_address, raw_branchname, raw_lbucode FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND TRIM(raw_doctor) =:raw_doctor AND TRIM(raw_license) = :raw_license  AND TRIM(raw_address) = :raw_address AND TRIM(raw_branchname) = :raw_branchname AND TRIM(raw_lbucode) = :raw_lbucode ORDER BY raw_doctor ASC";
              $stmt = $conn_pdo->prepare($sql);
              $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
              $stmt->bindParam(':raw_doctor', $filtered_md, PDO::PARAM_STR);
              $stmt->bindParam(':raw_license', $filtered_ln, PDO::PARAM_STR);
              $stmt->bindParam(':raw_address', $filtered_loc, PDO::PARAM_STR);
              $stmt->bindParam(':raw_branchname', $filtered_branch, PDO::PARAM_STR);
              $stmt->bindParam(':raw_lbucode', $filtered_lba, PDO::PARAM_STR);
              $stmt->execute();
              $array_result = $stmt->fetchAll();

            }

        return $array_result;//array_map('trim',$array_result);
      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
  }

  public function getFilteredGroupPerDistrict($filtered_by, $conn_pdo, $data, $district, $category, $md, $fuzz, $filtered_md, $license, $loc, $branch, $lba)
  {
    try {
            $array_result = [];

            if($category == 'md_name'){
              // $filtered_md = "%$filtered_md%";
              $sql = "SELECT raw_id, raw_doctor, $filtered_by FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND TRIM(raw_doctor) = :raw_doctor ORDER BY raw_doctor ASC";
              $stmt = $conn_pdo->prepare($sql);
              $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
              $stmt->bindParam(':raw_doctor', $filtered_md, PDO::PARAM_STR);
              $stmt->execute();
              $data = $stmt->fetchAll();
              if($data){
                foreach ($data as $row) {
                  $name = $var = explode(' ', $md);
                  foreach ($name as $split_name) {

                    if($fuzz->ratio($split_name, $row['raw_doctor']) >= $this->percent){
                      $array_result[] = array(
                        "id" => trim($row[$filtered_by]),
                        "text" => trim($row[$filtered_by])
                      );
                    }

                  }

                }
              }

            } else if ($category == 'license') {

              $sql = "SELECT raw_id, raw_doctor, $filtered_by FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND TRIM(raw_doctor) = :raw_doctor AND TRIM(raw_license) = :raw_license ORDER BY raw_doctor ASC";
              $stmt = $conn_pdo->prepare($sql);
              $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
              $stmt->bindParam(':raw_doctor', $filtered_md, PDO::PARAM_STR);
              $stmt->bindParam(':raw_license', $license, PDO::PARAM_STR);
              $stmt->execute();
              $data = $stmt->fetchAll();
              if($data){
                foreach ($data as $row) {
                  $name = $var = explode(' ', $md);
                  foreach ($name as $split_name) {

                    if($fuzz->ratio($split_name, $row['raw_doctor']) >= $this->percent){
                      $array_result[] = array(
                        "id" => trim($row[$filtered_by]),
                        "text" => trim($row[$filtered_by])
                      );
                    }
                  }

                }
              }

            } else if ($category == 'location') {

              $sql = "SELECT raw_id, raw_doctor, $filtered_by FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND TRIM(raw_doctor) = :raw_doctor AND TRIM(raw_license) = :raw_license AND TRIM(raw_address) = :raw_address ORDER BY raw_doctor ASC";
              $stmt = $conn_pdo->prepare($sql);
              $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
              $stmt->bindParam(':raw_doctor', $filtered_md, PDO::PARAM_STR);
              $stmt->bindParam(':raw_license', $license, PDO::PARAM_STR);
              $stmt->bindParam(':raw_address', $loc, PDO::PARAM_STR);
              $stmt->execute();
              $data = $stmt->fetchAll();
              if($data){
                foreach ($data as $row) {
                  $name = $var = explode(' ', $md);
                  foreach ($name as $split_name) {
                    if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
                      $array_result[] = array(
                        "id" => trim($row[$filtered_by]),
                        "text" => trim($row[$filtered_by])
                      );
                    }
                  }
                }
              }

            } else if ($category == 'md_branch') {

              $sql = "SELECT raw_id, raw_doctor, $filtered_by FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND TRIM(raw_doctor) = :raw_doctor AND TRIM(raw_license) = :raw_license AND TRIM(raw_address) = :raw_address AND TRIM(raw_branchname) = :raw_branchname ORDER BY raw_doctor ASC";
              $stmt = $conn_pdo->prepare($sql);
              $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
              $stmt->bindParam(':raw_doctor', $filtered_md, PDO::PARAM_STR);
              $stmt->bindParam(':raw_license', $license, PDO::PARAM_STR);
              $stmt->bindParam(':raw_address', $loc, PDO::PARAM_STR);
              $stmt->bindParam(':raw_branchname', $branch, PDO::PARAM_STR);
              $stmt->execute();
              $data = $stmt->fetchAll();
              if($data){
                foreach ($data as $row) {
                  $name = $var = explode(' ', $md);
                  foreach ($name as $split_name) {
                  if($fuzz->ratio($split_name, $row['raw_doctor']) >= $this->percent){
                    $array_result[] = array(
                      "id" => trim($row[$filtered_by]),
                      "text" => trim($row[$filtered_by])
                    );
                    }
                }
                }
              }

            } else if ($category == 'md_lba') {

              $sql = "SELECT raw_id, raw_doctor, $filtered_by FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND TRIM(raw_doctor) = :raw_doctor AND TRIM(raw_license) = :raw_license AND TRIM(raw_address) = :raw_address AND TRIM(raw_branchname) = :raw_branchname AND TRIM(raw_lbucode) = :raw_lbucode ORDER BY raw_doctor ASC";
              $stmt = $conn_pdo->prepare($sql);
              $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
              $stmt->bindParam(':raw_doctor', $filtered_md, PDO::PARAM_STR);
              $stmt->bindParam(':raw_license', $license, PDO::PARAM_STR);
              $stmt->bindParam(':raw_address', $loc, PDO::PARAM_STR);
              $stmt->bindParam(':raw_branchname', $branch, PDO::PARAM_STR);
              $stmt->bindParam(':raw_lbucode', $lba, PDO::PARAM_STR);
              $stmt->execute();
              $data = $stmt->fetchAll();
              if($data){
                foreach ($data as $row) {
                  $name = $var = explode(' ', $md);
                  foreach ($name as $split_name) {
                    if($fuzz->ratio($split_name, $row['raw_doctor']) >= $this->percent){
                      $array_result[] = array(
                        "id" => trim($row[$filtered_by]),
                        "text" => trim($row[$filtered_by])
                      );
                    }
                  }
                }
              }

            } else {

              $sql = "SELECT raw_id, raw_doctor, $filtered_by FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' ORDER BY raw_doctor ASC";
              $stmt = $conn_pdo->prepare($sql);
              $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
              $stmt->execute();
              $data = $stmt->fetchAll();
              if($data){
                foreach ($data as $row) {
                  $name = $var = explode(' ', $md);
                  foreach ($name as $split_name) {
                    if($fuzz->ratio($split_name, $row['raw_doctor']) >= $this->percent){
                      $array_result[] = array(
                        "id" => trim($row[$filtered_by]),
                        "text" => trim($row[$filtered_by])
                      );
                    }
                  }

                }
              }

            }


        return $array_result;
      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
  }

  public function updateDataSanitationResult1($conn_pdo, $raw_id, $md, $conn_pdo2, $authUser, $auth_usercode)
  {
    try {
        $array_result = [];
        $placeholders = str_repeat('?,', count($raw_id)-1).'?';
        $md_details = $this->getMdDetails($conn_pdo2, $md);
        $md_code = 'N/A';
        $md_class = 'N/A';
        $md_universe = 'N/A';
        foreach ($md_details as $key => $value) {
          $md_class = $value['class'];
          $md_code = $value['md_code'];
          $md_universe = $value['sanit_universe'];
        }

        $sql = "UPDATE sanitation_result1
                SET
                 raw_status = ?,
                 raw_mdcode = ?,
                 raw_corrected_name = ?,
                 raw_universe = ?
                WHERE raw_id IN ($placeholders)";
        $stmt = $conn_pdo->prepare($sql);
        $params = array_merge([$md_class], [$md_code], [$md], [$md_universe], $raw_id);
        $stmt->execute($params);
        if($stmt->errorCode() == 0){

          $rule_code = $this->generateRuleCode();
          $this->createRule($conn_pdo2, 'decision_page', $md, $auth_usercode, $authUser, $this->getRuleData($conn_pdo, $raw_id, 'raw_doctor'), $rule_code);
          $this->createRule($conn_pdo2, 'decision_page', $md, $auth_usercode, $authUser, $this->getRuleData($conn_pdo, $raw_id, 'raw_license'), $rule_code);
          $this->createRule($conn_pdo2, 'decision_page', $md, $auth_usercode, $authUser, $this->getRuleData($conn_pdo, $raw_id, 'raw_address'), $rule_code);
          $array_result[] = array(
                "success" => 1,
                "md" => $md
          );


        }else{
          $errors = $stmt->errorInfo();
          $array_result[] = array(
                "success" => $errors[2],
                "md" => $md
          );

        }
        return $array_result;
      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
  }

  public function getRuleData($conn_pdo, $raw_id, $category)
  {
    try {
      //GET ALL DATA THEN GROUP BY
      $placeholders = str_repeat('?,', count($raw_id)-1).'?';
      $sql = "SELECT raw_doctor, raw_license, raw_address FROM sanitation_result1 WHERE raw_id IN ($placeholders) GROUP BY $category";
      $stmt = $conn_pdo->prepare($sql);
      $stmt->execute($raw_id);
      $data = $stmt->fetchAll();
      if($data){
        $counter = 0;
        foreach ($data as $row) {

          if($counter == 0){
            $array_result[] = array(
              "conditional" => '',
              "table_name" => $category,
              "condition" => '=',
              "value" => $row[$category]
            );
          }
          else {
            $array_result[] = array(
              "conditional" => 'and',
              "table_name" => $category,
              "condition" => '=',
              "value" => $row[$category]
            );
          }
          $counter += 1;

        }
      }
    return $array_result;
    } catch (PDOException $e) {
        throw new Exception("Connection failed: ". $e->getMessage());
    }

  }

  public function getMdDetails($conn_pdo, $md)
  {
    try {
        $array_result = [];
        $sql = "SELECT sanit_group, sanit_mdcode, sanit_universe FROM db_sanitation WHERE sanit_mdname = :sanit_mdname LIMIT 1";
        $stmt = $conn_pdo->prepare($sql);
        $stmt->bindParam(':sanit_mdname', $md, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetchAll();
        if($data){
          foreach ($data as $row) {
              $array_result[] = array(
                'class' => $row['sanit_group'],
                'md_code' => $row['sanit_mdcode'],
                'sanit_universe' => $row['sanit_universe']
            );
          }
        }

        return $array_result;
      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
  }

  public function setUnclasified($conn_pdo, $raw_id)
  {
    try {
        $array_result = [];
        $placeholders = str_repeat('?,', count($raw_id)-1).'?';
        $status = 'UNCLASSIFIED';

        $sql = "UPDATE sanitation_result1
                SET
                 raw_status = ?
                WHERE raw_id IN ($placeholders)";
        $stmt = $conn_pdo->prepare($sql);
        $params = array_merge([$status], $raw_id);
        $stmt->execute($params);
        if($stmt->errorCode() == 0){
          $array_result[] = array(
                "success" => 1
          );
        }else{
          $errors = $stmt->errorInfo();
          $array_result[] = array(
                "success" => 0
          );

        }
        return $array_result;
      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
  }




} // end class


// //OLD FIRST CLASS MODULES
// public function getGroupMDPerDistrict($filtered_by, $conn_pdo, $data, $district, $category, $md, $fuzz, $filtered_md, $license, $loc, $branch, $lba)
// {
//   try {
//           $array_result = [];
//
//           if($category == 'md_name'){
//
//             $sql = "SELECT raw_id, raw_doctor, $filtered_by FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND raw_doctor = :raw_doctor";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_doctor', $filtered_md, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row[$filtered_by],
//                     "text" => $row[$filtered_by]
//                   );
//                 }
//               }
//             }
//
//           } else if ($category == 'license') {
//
//             $sql = "SELECT raw_id, raw_doctor, $filtered_by FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND raw_doctor = :raw_doctor AND raw_license = :raw_license";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_doctor', $filtered_md, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_license', $license, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row[$filtered_by],
//                     "text" => $row[$filtered_by]
//                   );
//                 }
//               }
//             }
//
//           } else if ($category == 'location') {
//
//             $sql = "SELECT raw_id, raw_doctor, $filtered_by FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND raw_doctor = :raw_doctor AND raw_license = :raw_license AND raw_address = :raw_address";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_doctor', $filtered_md, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_license', $license, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_address', $loc, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row[$filtered_by],
//                     "text" => $row[$filtered_by]
//                   );
//                 }
//               }
//             }
//
//           } else if ($category == 'md_branch') {
//
//             $sql = "SELECT raw_id, raw_doctor, $filtered_by FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND raw_doctor = :raw_doctor AND raw_license = :raw_license AND raw_address = :raw_address AND raw_branchname = :raw_branchname";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_doctor', $filtered_md, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_license', $license, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_address', $loc, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_branchname', $branch, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row[$filtered_by],
//                     "text" => $row[$filtered_by]
//                   );
//                 }
//               }
//             }
//
//           } else if ($category == 'md_lba') {
//
//             $sql = "SELECT raw_id, raw_doctor, $filtered_by FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND raw_doctor = :raw_doctor AND raw_license = :raw_license AND raw_address = :raw_address AND raw_branchname = :raw_branchname AND raw_lbucode = :raw_lbucode";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_doctor', $filtered_md, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_license', $license, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_address', $loc, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_branchname', $branch, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_lbucode', $lba, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row[$filtered_by],
//                     "text" => $row[$filtered_by]
//                   );
//                 }
//               }
//             }
//
//           } else {
//
//             $sql = "SELECT raw_id, raw_doctor, $filtered_by FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = ''";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row[$filtered_by],
//                     "text" => $row[$filtered_by]
//                   );
//                 }
//
//               }
//             }
//
//           }
//
//
//       return $array_result;
//     } catch (PDOException $e) {
//         throw new Exception("Connection failed: ". $e->getMessage());
//     }
// }
//
// public function getGroupLNPerDistrict($conn_pdo, $data, $district, $category, $md, $fuzz)
// {
//   try {
//           $array_result = [];
//
//           if($category == 'md_name'){
//
//             $sql = "SELECT raw_id, raw_license, raw_doctor FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND raw_doctor = :raw_doctor";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_doctor', $data, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row['raw_license'],
//                     "text" => $row['raw_license']
//                   );
//                 }
//               }
//             }
//
//           } else if ($category == 'license') {
//
//             $sql = "SELECT raw_id, raw_license, raw_doctor FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND raw_license = :raw_license";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_license', $data, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row['raw_license'],
//                     "text" => $row['raw_license']
//                   );
//                 }
//               }
//             }
//
//           } else if ($category == 'location') {
//
//             $sql = "SELECT raw_id, raw_license, raw_doctor FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND raw_address = :raw_address";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_address', $data, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row['raw_license'],
//                     "text" => $row['raw_license']
//                   );
//                 }
//               }
//             }
//
//           } else if ($category == 'md_branch') {
//
//             $sql = "SELECT raw_id, raw_license, raw_doctor FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND raw_branchname = :raw_branchname";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_branchname', $data, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row['raw_license'],
//                     "text" => $row['raw_license']
//                   );
//                 }
//               }
//             }
//
//           } else if ($category == 'md_lba') {
//
//             $sql = "SELECT raw_id, raw_license, raw_doctor FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND raw_lbucode = :raw_lbucode";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_lbucode', $data, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row['raw_license'],
//                     "text" => $row['raw_license']
//                   );
//                 }
//               }
//             }
//
//           } else {
//
//             // $sql = "SELECT COUNT(raw_id) as TOTAL_COUNT, raw_license, raw_doctor FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' GROUP BY raw_license";
//             $sql = "SELECT raw_id, raw_license, raw_doctor FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = ''";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row['raw_license'],
//                     "text" => $row['raw_license']
//                   );
//                 }
//               }
//             }
//
//           }
//
//
//       return $array_result;
//     } catch (PDOException $e) {
//         throw new Exception("Connection failed: ". $e->getMessage());
//     }
// }
// public function getGroupLocPerDistrict($conn_pdo, $data, $district, $category, $md, $fuzz)
// {
//   try {
//           $array_result = [];
//
//           if($category == 'md_name'){
//
//             $sql = "SELECT raw_id, raw_address, raw_doctor FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND raw_doctor = :raw_doctor ";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_doctor', $data, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row['raw_address'],
//                     "text" => $row['raw_address']
//                   );
//                 }
//               }
//             }
//
//           } else if ($category == 'license') {
//
//             $sql = "SELECT raw_id, raw_doctor, raw_address FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND raw_license = :raw_license";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_license', $data, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row['raw_address'],
//                     "text" => $row['raw_address']
//                   );
//                 }
//               }
//             }
//
//           } else if ($category == 'location') {
//
//             $sql = "SELECT raw_id, raw_doctor, raw_address FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND raw_address = :raw_address";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_address', $data, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row['raw_address'],
//                     "text" => $row['raw_address']
//                   );
//                 }
//               }
//             }
//
//           } else if ($category == 'md_branch') {
//
//             $sql = "SELECT raw_id, raw_doctor, raw_address FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND raw_branchname = :raw_branchname";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_branchname', $data, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row['raw_address'],
//                     "text" => $row['raw_address']
//                   );
//                 }
//               }
//             }
//
//           } else if ($category == 'md_lba') {
//
//             $sql = "SELECT raw_id, raw_doctor, raw_address FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND raw_lbucode = :raw_lbucode";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_lbucode', $data, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row['raw_address'],
//                     "text" => $row['raw_address'] . ' - ' . $row['TOTAL_COUNT'] . ' Record(s)'
//                   );
//                 }
//               }
//             }
//
//           } else {
//
//             // $sql = "SELECT COUNT(raw_id) as TOTAL_COUNT, raw_address, raw_doctor FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' GROUP BY raw_address";
//             $sql = "SELECT raw_id, raw_address, raw_doctor FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = ''";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               $counter = 0;
//               $previousVal;
//               foreach ($data as $row) {
//
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//
//                   $array_result[] = array(
//                     "id" => $row['raw_address'],
//                     "text" => $row['raw_address']
//                   );
//
//                 }
//
//               }
//             }
//           }
//
//
//       return $array_result;
//     } catch (PDOException $e) {
//         throw new Exception("Connection failed: ". $e->getMessage());
//     }
// }
// public function getGroupBranchPerDistrict($conn_pdo, $data, $district, $category, $md, $fuzz)
// {
//   try {
//           $array_result = [];
//
//           if($category == 'md_name'){
//
//             $sql = "SELECT raw_id, raw_branchname, raw_doctor FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND raw_doctor = :raw_doctor";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_doctor', $data, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row['raw_branchname'],
//                     "text" => $row['raw_branchname']
//                   );
//                 }
//               }
//             }
//
//           } else if ($category == 'license') {
//
//             $sql = "SELECT raw_id, raw_branchname, raw_doctor FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND raw_license = :raw_license";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_license', $data, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row['raw_branchname'],
//                     "text" => $row['raw_branchname']
//                   );
//                 }
//               }
//             }
//
//
//           } else if ($category == 'location') {
//
//             $sql = "SELECT raw_id, raw_branchname, raw_doctor FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND raw_address = :raw_address";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_address', $data, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row['raw_branchname'],
//                     "text" => $row['raw_branchname']
//                   );
//                 }
//               }
//             }
//
//           } else if ($category == 'md_branch') {
//
//             $sql = "SELECT raw_id, raw_branchname, raw_doctor FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND raw_branchname = :raw_branchname";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_branchname', $data, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row['raw_branchname'],
//                     "text" => $row['raw_branchname']
//                   );
//                 }
//               }
//             }
//
//           } else if ($category == 'md_lba') {
//
//             $sql = "SELECT raw_id, raw_branchname, raw_doctor  FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND raw_lbucode = :raw_lbucode";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_lbucode', $data, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row['raw_branchname'],
//                     "text" => $row['raw_branchname']
//                   );
//                 }
//               }
//             }
//
//           } else {
//
//             // $sql = "SELECT COUNT(raw_id) as TOTAL_COUNT, raw_branchname, raw_doctor FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' GROUP BY raw_branchname";
//             $sql = "SELECT raw_id, raw_doctor, raw_license, raw_address, raw_branchname, raw_lbucode FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = ''";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row['raw_branchname'],
//                     "text" => $row['raw_branchname']
//                   );
//                 }
//               }
//             }
//           }
//
//
//       return $array_result;
//     } catch (PDOException $e) {
//         throw new Exception("Connection failed: ". $e->getMessage());
//     }
// }
// public function getGroupLBAPerDistrict($conn_pdo, $data, $district, $category, $md, $fuzz)
// {
//   try {
//           $array_result = [];
//
//           if($category == 'md_name'){
//
//             $sql = "SELECT raw_id, raw_lbucode, raw_doctor FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND raw_doctor = :raw_doctor";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_doctor', $data, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row['raw_lbucode'],
//                     "text" => $row['raw_lbucode']
//                   );
//                 }
//               }
//             }
//
//           } else if ($category == 'license') {
//
//             $sql = "SELECT raw_id, raw_lbucode, raw_doctor FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND raw_license = :raw_license";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_license', $data, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row['raw_lbucode'],
//                     "text" => $row['raw_lbucode']
//                   );
//                 }
//               }
//             }
//
//
//           } else if ($category == 'location') {
//
//             $sql = "SELECT raw_id, raw_lbucode, raw_doctor FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND raw_address = :raw_address";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_address', $data, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row['raw_lbucode'],
//                     "text" => $row['raw_lbucode']
//                   );
//                 }
//               }
//             }
//
//           } else if ($category == 'md_branch') {
//
//             $sql = "SELECT raw_id, raw_lbucode, raw_doctor FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND raw_branchname = :raw_branchname";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_branchname', $data, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row['raw_lbucode'],
//                     "text" => $row['raw_lbucode']
//                   );
//                 }
//               }
//             }
//
//           } else if ($category == 'md_lba') {
//
//             $sql = "SELECT raw_id, raw_lbucode, raw_doctor FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' AND raw_lbucode = :raw_lbucode";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->bindParam(':raw_lbucode', $data, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                   $array_result[] = array(
//                     "id" => $row['raw_lbucode'],
//                     "text" => $row['raw_lbucode']
//                   );
//                 }
//               }
//             }
//
//           } else {
//
//             // $sql = "SELECT COUNT(raw_id) as TOTAL_COUNT, raw_lbucode FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = '' GROUP BY raw_lbucode";
//             $sql = "SELECT raw_id, raw_doctor, raw_license, raw_address, raw_branchname, raw_lbucode FROM sanitation_result1 WHERE raw_district = :raw_district AND raw_status = ''";
//             $stmt = $conn_pdo->prepare($sql);
//             $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
//             $stmt->execute();
//             $data = $stmt->fetchAll();
//             if($data){
//               foreach ($data as $row) {
//                 if($fuzz->ratio($md, $row['raw_doctor']) >= $this->percent){
//                     $array_result[] = array(
//                       "id" => $row['raw_lbucode'],
//                       "text" => $row['raw_lbucode']
//                     );
//                 }
//               }
//             }
//           }
//
//
//       return $array_result;
//     } catch (PDOException $e) {
//         throw new Exception("Connection failed: ". $e->getMessage());
//     }
// }

 ?>
