<?php

/**
 *
 */
class DataSanitation extends DataSanitationRules
{

  public $percent = 90;

  public function getDistrictDetails($conn_pdo, $district, $conn_pdo2)
  {
    try {
        $array_result = [];
        $placeholders = str_repeat('?,', count($district)-1).'?';
        $sql = "SELECT district_name,district_rowcount FROM district_assignment WHERE district_name IN ($placeholders)";
        $stmt = $conn_pdo->prepare($sql);
        $stmt->execute($district);
        $data = $stmt->fetchAll();
        if($data){
          foreach ($data as $row) {
              $array_result[] = array(
              "rowsCount" => $this->countDistrictRow($conn_pdo2, $district), //$row['district_rowcount'],
              "rowsCountSanitized" => $this->countSanitizedDistrictRow($conn_pdo2, $district),
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
        $placeholders = str_repeat('?,', count($district)-1).'?';
        $sql = "SELECT COUNT(raw_id) as TOTAL FROM sanitation_result1 WHERE raw_district IN ($placeholders) AND sanitized_by = ''";
        $stmt = $conn_pdo->prepare($sql);
        $stmt->execute($district);
        $data = $stmt->fetchAll();
        if($data){
          foreach ($data as $row) {
              $data = number_format($row['TOTAL']);
          }
        }

        return $data;
      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
  }

  public function countSanitizedDistrictRow($conn_pdo, $district)
  {
    try {
        $array_result = [];
        $placeholders = str_repeat('?,', count($district)-1).'?';
        $sql = "SELECT COUNT(raw_id) as TOTAL FROM sanitation_result1 WHERE raw_district IN ($placeholders) AND sanitized_by != ''";
        $stmt = $conn_pdo->prepare($sql);
        $stmt->execute($district);
        $data = $stmt->fetchAll();
        if($data){
          foreach ($data as $row) {
              $data = number_format($row['TOTAL']);
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
        $sql = "SELECT DISTINCT(REPLACE(sanit_mdname, ',', '')) as NAME, sanit_mdname AS ORIG_NAME FROM db_sanitation
                WHERE REPLACE(sanit_mdname, ',', '') LIKE :sanit_mdname ORDER BY sanit_mdname ASC LIMIT 10";
        $stmt = $conn_pdo->prepare($sql);
        $stmt->bindParam(':sanit_mdname', $name, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetchAll();
        if($data){
          foreach ($data as $row) {
              $array_result[] = array(
              "id" => $row['ORIG_NAME'],
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

  public function checkMDname($conn_pdo, $get_name, $orig_name)
  {
    try {
        $array_result = [];
        $name ="%".$orig_name."%";
        $sql = "SELECT DISTINCT(sanit_mdname) FROM db_sanitation
                WHERE sanit_mdname = :sanit_mdname LIMIT 1";
        $stmt = $conn_pdo->prepare($sql);
        $stmt->bindParam(':sanit_mdname', $orig_name, PDO::PARAM_STR);
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
        $placeholders = str_repeat('?,', count($district)-1).'?';
        $sql = "SELECT raw_id, raw_doctor, raw_license, raw_address, raw_branchname, raw_lbucode, raw_hospcode, raw_sarcode, raw_amount,
                       raw_corrected_name, sanitized_by, date_sanitized
                FROM sanitation_result1
                WHERE raw_district IN($placeholders) ";
        $stmt = $conn_pdo->prepare($sql);
        $stmt->execute($district);
        // $data = $stmt->fetch(PDO::FETCH_ASSOC);
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

  public function getSanitizedData($conn_pdo, $district, $mdname)
  {
    try {
        $placeholders = str_repeat('?,', count($district)-1).'?';
        $sql = "SELECT raw_id, raw_doctor, raw_license, raw_address, raw_branchname, raw_lbucode, raw_corrected_name, sanitized_by, date_sanitized,
                       raw_corrected_name, sanitized_by, date_sanitized
                FROM sanitation_result1
                WHERE raw_district IN($placeholders)
                AND raw_corrected_name = ?
                AND sanitized_by != ''";
        $stmt = $conn_pdo->prepare($sql);
        $params = array_merge($district, [$mdname]);
        $stmt->execute($params);
        $data = $stmt->fetchAll();
        // foreach ($data as $row) {
        //   $array_result[] = array(
        //       "raw_id" => $row['raw_id'],
        //       "raw_doctor" => $row['raw_doctor'],
        //       "raw_corrected_name" => $row['raw_corrected_name'],
        //       "raw_license" => $row['raw_license'],
        //       "raw_address" => $row['raw_address'],
        //       "raw_branchname" => $row['raw_branchname'],
        //       "raw_lbucode" => $row['raw_lbucode'],
        //       "sanitized_by" => $row['sanitized_by'],
        //       "date_sanitized" => $row['date_sanitized']
        //     );
        //   }
        return $data;
      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
  }

  public function checkDataIfSanitized($conn_pdo, $id)
  {
    try {
            $array_result = [];
            $placeholders = str_repeat('?,', count($id)-1).'?';

            $sql = "SELECT raw_id, raw_doctor, raw_license, raw_address, raw_branchname, raw_lbucode, sanitized_by, date_sanitized, raw_status
                    FROM sanitation_result1
                    WHERE raw_id IN ($placeholders) ORDER BY raw_doctor ASC";
            $stmt = $conn_pdo->prepare($sql);
            $params = array_merge($id);
            $stmt->execute($params);
            // $array_result = $stmt->fetch(PDO::FETCH_ASSOC);
            // $data = $stmt->fetch(PDO::FETCH_ASSOC);
            $data = $stmt->fetchAll();
            foreach ($data as $row) {
              $array_result[] = array(
                  "raw_id" => $row['raw_id'],
                  "raw_doctor" => $row['raw_doctor'],
                  "raw_license" => $row['raw_license'],
                  "raw_address" => $row['raw_address'],
                  "raw_branchname" => $row['raw_branchname'],
                  "raw_lbucode" => $row['raw_lbucode'],
                  "sanitized_by" => $row['sanitized_by'],
                  "date_sanitized" => $row['date_sanitized'],
                  "raw_status" => $row['raw_status']
                );
              }

        return $array_result;
      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
  }

  public function viewUncleanData($conn_pdo, $id)
  {
    try {
            $array_result = [];
            $placeholders = str_repeat('?,', count($id)-1).'?';

            $sql = "SELECT raw_id, raw_doctor, raw_license, raw_address, raw_branchname, raw_lbucode, sanitized_by, date_sanitized, raw_status
                    FROM sanitation_result1
                    WHERE raw_id IN ($placeholders) AND raw_status = '' ORDER BY raw_doctor ASC";
            $stmt = $conn_pdo->prepare($sql);
            $params = array_merge($id);
            $stmt->execute($params);
            // $array_result = $stmt->fetch(PDO::FETCH_ASSOC);
            // $data = $stmt->fetch(PDO::FETCH_ASSOC);
            $data = $stmt->fetchAll();
            foreach ($data as $row) {
              $array_result[] = array(
                  "raw_id" => $row['raw_id'],
                  "raw_doctor" => $row['raw_doctor'],
                  "raw_license" => $row['raw_license'],
                  "raw_address" => $row['raw_address'],
                  "raw_branchname" => $row['raw_branchname'],
                  "raw_lbucode" => $row['raw_lbucode']
                );
              }

        return $array_result;
      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
  }

  public function searchDataSanitation($conn_pdo, $data, $district, $category, $filtered_md, $filtered_ln, $filtered_loc, $filtered_branch, $filtered_lba)
  {
    try {
            $array_result = [];
            $placeholders = str_repeat('?,', count($district)-1).'?';

            if($category == 'md_name'){

              $sql = "SELECT raw_id, raw_doctor, raw_license, raw_address, raw_branchname, raw_lbucode
                      FROM sanitation_result1
                      WHERE raw_district IN ($placeholders) AND raw_status = ''
                      AND TRIM(raw_doctor) = ? ORDER BY raw_doctor ASC";
              $stmt = $conn_pdo->prepare($sql);
              $params = array_merge($district, [$filtered_md]);
              $stmt->execute($params);
              $array_result = $stmt->fetchAll();

            } else if ($category == 'license') {

              $sql = "SELECT raw_id, raw_doctor, raw_license, raw_address, raw_branchname, raw_lbucode
                      FROM sanitation_result1
                      WHERE raw_district IN ($placeholders) AND raw_status = ''
                      AND TRIM(raw_doctor) = ? AND TRIM(raw_license) = ? ORDER BY raw_doctor ASC";
              $stmt = $conn_pdo->prepare($sql);
              $params = array_merge($district, [$filtered_md], [$filtered_ln]);
              $stmt->execute($params);
              $array_result = $stmt->fetchAll();

            } else if ($category == 'location') {

              $sql = "SELECT raw_id, raw_doctor, raw_license, raw_address, raw_branchname, raw_lbucode
                      FROM sanitation_result1
                      WHERE raw_district IN ($placeholders) AND raw_status = ''
                      AND TRIM(raw_doctor) = ? AND TRIM(raw_license) = ?
                      AND TRIM(raw_address) = ? ORDER BY raw_doctor ASC";
              $stmt = $conn_pdo->prepare($sql);
              $params = array_merge($district, [$filtered_md], [$filtered_ln], [$filtered_loc]);
              $stmt->execute($params);
              $array_result = $stmt->fetchAll();

            } else if ($category == 'md_branch') {

              $sql = "SELECT raw_id, raw_doctor, raw_license, raw_address, raw_branchname, raw_lbucode
                      FROM sanitation_result1
                      WHERE raw_district IN ($placeholders) AND raw_status = ''
                      AND TRIM(raw_doctor) = ? AND TRIM(raw_license) = ?
                      AND TRIM(raw_address) = ? AND TRIM(raw_branchname) = ? ORDER BY raw_doctor ASC";
              $stmt = $conn_pdo->prepare($sql);
              $params = array_merge($district, [$filtered_md], [$filtered_ln], [$filtered_loc], [$filtered_branch]);
              $stmt->execute($params);
              $array_result = $stmt->fetchAll();

            } else if ($category == 'md_lba') {

              $sql = "SELECT raw_id, raw_doctor, raw_license, raw_address, raw_branchname, raw_lbucode
                      FROM sanitation_result1
                      WHERE raw_district IN ($placeholders) AND raw_status = ''
                      AND TRIM(raw_doctor) = ? AND TRIM(raw_license) = ?
                      AND TRIM(raw_address) = ? AND TRIM(raw_branchname) = ?
                      AND TRIM(raw_lbucode) = ? ORDER BY raw_doctor ASC";
              $stmt = $conn_pdo->prepare($sql);
              $params = array_merge($district, [$filtered_md], [$filtered_ln], [$filtered_loc], [$filtered_branch], [$filtered_lba]);
              $stmt->execute($params);
              $array_result = $stmt->fetchAll();

            }

        return $array_result;//array_map('trim',$array_result);
      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
  }

  public function getCount($conn_pdo, $val)
  {
    // $sql = "SELECT COUNT(raw_id) AS TOTAL FROM sanitation_result1";
    // $stmt = $conn_pdo->prepare($sql);
    // // $stmt->bindParam(':raw_district', $district, PDO::PARAM_STR);
    // $stmt->execute();
    // $data = $stmt->fetchAll();
    // if($data){
    //   foreach ($data as $row) {
    //     // $name = $var = explode(' ', $md);
    //     // foreach ($name as $split_name) {
    //     //   if($fuzz->ratio($split_name, $row['raw_doctor']) >= $this->percent){
    //     //     $array_result[] = array(
    //     //       "id" => trim($row[$filtered_by]),
    //     //       "text" => trim($row[$filtered_by])
    //     //     );
    //     //   }
    //     // }
    //     return $array_result = $row['TOTAL'];
    //   }
    // }
    return 1;
  }

  public function getFilteredGroupPerDistrict($filtered_by, $conn_pdo, $data, $district, $category, $md, $fuzz, $filtered_md, $license, $loc, $branch, $lba)
  {
    try {
            $array_result = [];
            $placeholders = str_repeat('?,', count($district)-1).'?';
            if($category == 'md_name'){

              $sql = "SELECT raw_id, raw_doctor, $filtered_by
                      FROM sanitation_result1
                      WHERE raw_district IN ($placeholders) AND raw_status = ''
                      AND TRIM(raw_doctor) = ? ORDER BY raw_doctor ASC";
              $stmt = $conn_pdo->prepare($sql);
              $params = array_merge($district, [$filtered_md]);
              $stmt->execute($params);
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

              $sql = "SELECT raw_id, raw_doctor, $filtered_by
                      FROM sanitation_result1
                      WHERE raw_district IN ($placeholders) AND raw_status = ''
                      AND TRIM(raw_doctor) = ? AND TRIM(raw_license) = ? ORDER BY raw_doctor ASC";
              $stmt = $conn_pdo->prepare($sql);
              $params = array_merge($district, [$filtered_md], [$license]);
              $stmt->execute($params);
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

              $sql = "SELECT raw_id, raw_doctor, $filtered_by
                      FROM sanitation_result1
                      WHERE raw_district IN ($placeholders) AND raw_status = ''
                      AND TRIM(raw_doctor) = ? AND TRIM(raw_license) = ?
                      AND TRIM(raw_address) = ? ORDER BY raw_doctor ASC";
              $stmt = $conn_pdo->prepare($sql);
              $params = array_merge($district, [$filtered_md], [$license], [$loc]);
              $stmt->execute($params);
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

              $sql = "SELECT raw_id, raw_doctor, $filtered_by
                      FROM sanitation_result1
                      WHERE raw_district IN ($placeholders) AND raw_status = ''
                      AND TRIM(raw_doctor) = ? AND TRIM(raw_license) = ?
                      AND TRIM(raw_address) = ? AND TRIM(raw_branchname) = ? ORDER BY raw_doctor ASC";
              $stmt = $conn_pdo->prepare($sql);
              $params = array_merge($district, [$filtered_md], [$license], [$loc], [$branch]);
              $stmt->execute($params);
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

              $sql = "SELECT raw_id, raw_doctor, $filtered_by
                      FROM sanitation_result1
                      WHERE raw_district IN ($placeholders) AND raw_status = ''
                      AND TRIM(raw_doctor) = ? AND TRIM(raw_license) = ?
                      AND TRIM(raw_address) = ? AND TRIM(raw_branchname) = ?
                      AND TRIM(raw_lbucode) = ? ORDER BY raw_doctor ASC";
              $stmt = $conn_pdo->prepare($sql);
              $params = array_merge($district, [$filtered_md], [$license], [$loc], [$branch], [$lba]);
              $stmt->execute($params);
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

              $sql = "SELECT raw_id, raw_doctor, $filtered_by
                      FROM sanitation_result1
                      WHERE raw_district IN ($placeholders) AND raw_status = '' ORDER BY raw_doctor ASC";
              $stmt = $conn_pdo->prepare($sql);
              $params = array_merge($district);
              $stmt->execute($params);
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
                 raw_universe = ?,
                 sanitized_by = ?
                WHERE raw_id IN ($placeholders)";
        $stmt = $conn_pdo->prepare($sql);
        $params = array_merge([$md_class], [$md_code], [$md], [$md_universe], [$authUser], $raw_id);
        $stmt->execute($params);
        if($stmt->errorCode() == 0){

          $response = [];
          $data_result = $this->getDataForRules($conn_pdo, $raw_id);
          foreach ($data_result as $key => $value) {
            for ($i=0; $i < 3; $i++) {
              if($i == 0){
                $array_rule_details[] = array(
                  "rule_code" => $value[0],
                  "conditional" => 'and',
                  "table_name" => 'raw_doctor',
                  "condition" => '=',
                  "value" => $value[1]
                );
                  $this->createRule($conn_pdo2, 'decision_page', $md, $auth_usercode,  $authUser, $array_rule_details) ;
                  unset($array_rule_details);


              }elseif ($i == 1) {
                $array_rule_details[] = array(
                  "rule_code" => $value[0],
                  "conditional" => 'and',
                  "table_name" => 'raw_license',
                  "condition" => '=',
                  "value" => $value[2]
                );
                $this->createRule($conn_pdo2, 'decision_page', $md, $auth_usercode,  $authUser, $array_rule_details) ;
                unset($array_rule_details);


              }elseif ($i == 2) {
                $array_rule_details[] = array(
                  "rule_code" => $value[0],
                  "conditional" => 'and',
                  "table_name" => 'raw_address',
                  "condition" => '=',
                  "value" => $value[3]
                );
                $this->createRule($conn_pdo2, 'decision_page', $md, $auth_usercode,  $authUser, $array_rule_details) ;
                unset($array_rule_details);


              }
            }
            $this->updateRulesDetailsLastEntry($conn_pdo2, $value[0]);
            $this->insertRulesAndLogs($conn_pdo2, '$category', $md, $auth_usercode, $authUser, 3, $value[0]);
          }




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

  public function updateRulesDetailsLastEntry($conn_pdo2, $rule_code)
  {
    //UPDATE details_condition_optr LAST INSERTED SAME RULES
    $details_condition_optr = '';
    $sql = "UPDATE rules_details SET details_condition_optr = :details_condition_optr
            WHERE rule_code = :rule_code ORDER BY details_id DESC LIMIT 1";
    $stmt = $conn_pdo2->prepare($sql);
    $stmt->bindParam(':details_condition_optr', $details_condition_optr , PDO::PARAM_STR);
    $stmt->bindParam(':rule_code', $rule_code , PDO::PARAM_STR);
    $stmt->execute();
    //END
  }

  public function getDataForRules($conn_pdo, $raw_id)
  {
    try {
      $placeholders = str_repeat('?,', count($raw_id)-1).'?';
      $sql = "SELECT raw_doctor, raw_license, raw_address FROM sanitation_result1 WHERE raw_id IN ($placeholders)";
      $stmt = $conn_pdo->prepare($sql);
      $stmt->execute($raw_id);
      $data = $stmt->fetchAll();
      if($data){
        $counter = 0;
        foreach ($data as $row) {
          // $array_result[] = array( $row['raw_doctor'], $row['raw_license'], $row['raw_address']) ;
          //ADD RULES MODULE
          // $rule_code = $this->generateRuleCode();
          $array_result[] = array(
            "raw_doctor" => $row['raw_doctor'],
            "raw_license" => $row['raw_license'],
            "raw_address" => $row['raw_address']
          );
        }
      }
    return $this->removeDuplicate($array_result);
    } catch (PDOException $e) {
        throw new Exception("Connection failed: ". $e->getMessage());
    }
  }

  public function removeDuplicate($array_list)
  {
    foreach ($array_list as $key => $value) {
       $new_value[]  = implode("||", $value);
    }
    $new_value = array_unique($new_value);
    foreach ($new_value as $value) {
        $new = explode("||", $value);

        $rule_code = $this->generateRuleCode();
        $dr = $new[0] ;
        $license = $new[1] ;
        $address = $new[2] ;
        $sanitized_result[] = array($rule_code, $dr, $license, $address) ;
    }
    return $sanitized_result;

  }

  public function getRuleData($conn_pdo, $raw_id, $category, $conn_pdo2)
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

          // $this->insertRulesAndLogs($conn_pdo2, 'decision_page', $md, $auth_usercode, $authUser, $this->getRuleData($conn_pdo, $raw_id, 'raw_address'), $rule_code);

          if($counter == 0){
            $array_result[] = array(
              "conditional" => 'and',
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

  public function updatelastRule($conn_pdo, $rule_code)
  {
    $sql = "UPDATE rules_details
            SET
             details_condition_optr = ''
            WHERE rule_code = '14885024788631958' ORDER BY details_id DESC LIMIT 1";
    $stmt = $conn_pdo->prepare($sql);
    $stmt->execute();
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

  public function getTest($conn_pdo, $doctor, $ln, $loc)
  {
    try {
        $array_result = [];
        $sql = "SELECT raw_id, raw_doctor, raw_license, raw_address, raw_branchname, raw_lbucode
                FROM sanitation_result1
                WHERE raw_doctor = :raw_doctor
                AND raw_license = :raw_license
                AND raw_address = :raw_address ";
        $stmt = $conn_pdo->prepare($sql);
        $stmt->bindParam(':raw_doctor', $doctor, PDO::PARAM_STR);
        $stmt->bindParam(':raw_license', $ln, PDO::PARAM_STR);
        $stmt->bindParam(':raw_address', $loc, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetchAll();
        $array_result = $data;
        return $array_result;
      } catch (PDOException $e) {
          throw new Exception("Connection failed: ". $e->getMessage());
      }
  }




} // end class

 ?>
