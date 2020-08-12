<?Php
// require("../../connection.php") ;
class DataSanitationRules
{

  public function createRule($conn_pdo, $category, $assigned_to, $created_by_code, $created_by_name, $array_rules)
  {
    if ($category == "decision_page") {

      $status = 1 ;
      $rule_code = $this->generateRuleCode();

      try {
        $conn_pdo->beginTransaction();
        //RULES DETAILS TABLE
        $sql = "INSERT INTO rules_details ( rule_code, details_column_name, details_value_optr, details_value, details_condition_optr )
            		                   VALUES ( :rule_code, :details_column_name, :details_value_optr, :details_value, :details_condition_optr )";
        $stmt = $conn_pdo->prepare($sql);

        foreach ($array_rules as $key => $value) {
          $stmt->bindParam(':rule_code', $rule_code , PDO::PARAM_STR);
          $stmt->bindParam(':details_column_name', $value['table_name'], PDO::PARAM_STR);
          $stmt->bindParam(':details_value_optr', $value['condition'], PDO::PARAM_STR);
          $stmt->bindParam(':details_value', $value['value'], PDO::PARAM_STR);
          $stmt->bindParam(':details_condition_optr', $value['conditional'], PDO::PARAM_STR);
          $stmt->execute();
        }

        //RULES TABLES
        $sql = "INSERT INTO rules_tbl ( rule_code, rule_assign_to, rule_created_by, status )
                                VALUES ( :rule_code, :rule_assign_to, :rule_created_by, :status )";
        $stmt = $conn_pdo->prepare($sql);
        $stmt->bindParam(':rule_code',  $rule_code, PDO::PARAM_STR);
        $stmt->bindParam(':rule_assign_to',  $assigned_to, PDO::PARAM_STR);
        $stmt->bindParam(':rule_created_by',  $created_by_code, PDO::PARAM_STR);
        $stmt->bindParam(':status',  $status, PDO::PARAM_STR);
        $stmt->execute();

        //INSERT RULES LOGS HERE
        $rule_count = count($array_rules);
        $action = 'INSERT';
        $log = " $created_by_name HAS CREATED A RULE WITH RULE ID $rule_code WITH $rule_count CRITERIAS HAS BEEN ASSIGNED TO " . $assigned_to ;

        $sql = "INSERT INTO rules_log ( created_by, action, details, rule_code )
                                VALUES ( :created_by, :action, :details, :rule_code )";
        $stmt = $conn_pdo->prepare($sql);
        $stmt->bindParam(':created_by', $created_by_code, PDO::PARAM_STR);
        $stmt->bindParam(':action', $action, PDO::PARAM_STR);
        $stmt->bindParam(':details', $log, PDO::PARAM_STR);
        $stmt->bindParam(':rule_code', $rule_code, PDO::PARAM_STR);
        $stmt->execute();


        $conn_pdo->commit();

      } catch (PDOException $e) {
        $conn_pdo->rollBack();
        throw new Exception("Connection failed: ". $e->getMessage());
      }



    } else { // rules page

      $status = 2; // PENDING FOR APPROVAL IF NOT TEAM MEMBER


      $sql = "INSERT INTO rules_details
        (
          rule_code,
          details_column_name,
          details_value_optr,
          details_value,
          details_condition_optr
        )
        VALUES
        (
          :rule_code,
          :details_column_name,
          :details_value_optr,
          :details_value,
          :details_condition_optr
        )
        ";
        $stmt = $conn_pdo->prepare($sql)  ;

      $counter = 0;
      $counter_condition = 1;
      $array_count = count($array_rules) ;


      $rule_code = $this->generateRuleCode() ;

      for ($i=1; $i <= $array_count ; $i++) {


              $stmt->bindParam(':rule_code', $rule_code , PDO::PARAM_STR);
              $stmt->bindParam(':details_column_name', $array_rules['table_name'][$counter] , PDO::PARAM_STR);
              $stmt->bindParam(':details_value_optr', $array_rules['condition'][$counter] , PDO::PARAM_STR);
              $stmt->bindParam(':details_value', $array_rules['value'][$counter] , PDO::PARAM_STR);
              $stmt->bindParam(':details_condition_optr', $array_rules['conditional'][$counter] , PDO::PARAM_STR);
              $stmt->execute();

              // echo $array_rules['table_name'][$counter];
              // echo "<br>";
              $counter++;
              $counter_condition++;
      }

      $sql2 = "INSERT INTO rules_tbl
        (
          rule_code,
          rule_assign_to,
          rule_created_by,
          status
        )
        VALUES
        (
          :rule_code,
          :rule_assign_to,
          :rule_created_by,
          :status
        )
        ";
        $stmt2 = $conn_pdo->prepare($sql2)  ;

        $stmt2->bindParam(':rule_code',  $rule_code , PDO::PARAM_STR);
        $stmt2->bindParam(':rule_assign_to',  $assigned_to , PDO::PARAM_STR);
        $stmt2->bindParam(':rule_created_by',  $created_by_code , PDO::PARAM_STR);
        $stmt2->bindParam(':status',  $status , PDO::PARAM_STR);
        $stmt2->execute();


      }

  }// public function create rule

  public function generateRuleCode()
  {
    return hexdec( abs( crc32( uniqid()) ) ) .  str_shuffle(rand(1, 787879) ) ;
  }


} // CLASS END

// $category = "rules_page" ;
// $assigned_to = "GO, LUIS RAYMOND";
//
// $array_rules['value'][] = "LUIS RAYMOND";
// $array_rules['value'][] = "401969";
// $array_rules['value'][] = "MD x";
//
// $array_rules['table_name'][] = "raw_doctor";
// $array_rules['table_name'][] = "raw_license";
// $array_rules['table_name'][] = "raw_address";
//
// $array_rules['conditional'][] = ""; // FIRST VALUE OF ARRAY SHOULD ALWAYS BE NULL / BLANK
// $array_rules['conditional'][] = "and";
// $array_rules['conditional'][] = "and";
//
// $array_rules['condition'][] = "=";
// $array_rules['condition'][] = "=";
// $array_rules['condition'][] = "=";
//
// $created_by_code = "BK-986748";
// $created_by_name = "MALLARI, AEROLLE JANE Y.";
//
// $cmd = new DataSanitationRules() ;
//
// $cmd->createRule($conn_pdo, $category , $assigned_to, $created_by_code, $created_by_name, $array_rules) ;


?>
