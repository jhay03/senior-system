<?Php
// require("../../connection.php") ;
class DataSanitationRules
{

  public function createRule($conn_pdo, $category, $assigned_to, $created_by_code, $created_by_name, $array_rules)
  {
    if ($category == "decision_page") {

      $status = 1 ;
      // $rule_code = $this->generateRuleCode();

      try {
        $conn_pdo->beginTransaction();
        //RULES DETAILS TABLE
        $sql = "INSERT INTO rules_details ( rule_code, details_column_name, details_value_optr, details_value, details_condition_optr )
            		                   VALUES ( :rule_code, :details_column_name, :details_value_optr, :details_value, :details_condition_optr )";
        $stmt = $conn_pdo->prepare($sql);

        foreach ($array_rules as $key => $value) {
          $stmt->bindValue(':rule_code', $value['rule_code'] , PDO::PARAM_STR);
          $stmt->bindValue(':details_column_name', $value['table_name'], PDO::PARAM_STR);
          $stmt->bindValue(':details_value_optr', $value['condition'], PDO::PARAM_STR);
          $stmt->bindValue(':details_value', $value['value'], PDO::PARAM_STR);
          $stmt->bindValue(':details_condition_optr', $value['conditional'], PDO::PARAM_STR);
          $stmt->execute();
        }


        // //RULES TABLES
        // $sql = "INSERT INTO rules_tbl ( rule_code, rule_assign_to, rule_created_by, status )
        //                         VALUES ( :rule_code, :rule_assign_to, :rule_created_by, :status )";
        // $stmt = $conn_pdo->prepare($sql);
        // $stmt->bindParam(':rule_code',  $rule_code, PDO::PARAM_STR);
        // $stmt->bindParam(':rule_assign_to',  $assigned_to, PDO::PARAM_STR);
        // $stmt->bindParam(':rule_created_by',  $created_by_code, PDO::PARAM_STR);
        // $stmt->bindParam(':status',  $status, PDO::PARAM_STR);
        // $stmt->execute();
        //
        // //INSERT RULES LOGS HERE
        // $rule_count = count($array_rules);
        // $action = 'INSERT';
        // $log = " $created_by_name HAS CREATED A RULE WITH RULE ID $rule_code WITH $rule_count CRITERIAS HAS BEEN ASSIGNED TO " . $assigned_to ;
        //
        // $sql = "INSERT INTO rules_log ( created_by, action, details, rule_code )
        //                         VALUES ( :created_by, :action, :details, :rule_code )";
        // $stmt = $conn_pdo->prepare($sql);
        // $stmt->bindParam(':created_by', $created_by_code, PDO::PARAM_STR);
        // $stmt->bindParam(':action', $action, PDO::PARAM_STR);
        // $stmt->bindParam(':details', $log, PDO::PARAM_STR);
        // $stmt->bindParam(':rule_code', $rule_code, PDO::PARAM_STR);
        // $stmt->execute();


        $conn_pdo->commit();
        return 'succes';
      } catch (PDOException $e) {
        $conn_pdo->rollBack();
        throw new Exception("Connection failed: ". $e->getMessage());
      }

    }

  }// public function create rule

  public function insertRulesAndLogs($conn_pdo, $category, $assigned_to, $created_by_code, $created_by_name, $rule_count, $rule_code)
  {
    $status = 1 ;

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
    // $rule_count = count($array_rules);
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
  }

  public function generateRuleCode()
  {
    return hexdec( abs( crc32( uniqid()) ) ) .  str_shuffle(rand(1, 787879) ) ;
  }


} // CLASS END




?>
