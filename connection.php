<?php
define ("DB_SERVER", "localhost"); // set database host
define ("DB_USER", "root"); // set database user
define ("DB_PASSWORD",""); // set database password
define ("DB_NAME","mdcsenior_db"); // set database name

$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD,DB_NAME);

$server = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mdcsenior_db';
$charset = 'utf8';
$options = array(
    PDO::ATTR_PERSISTENT  => true,
);

try {
  $conn_pdo = new PDO("mysql:host={$server};dbname={$dbname};charset={$charset}",
                         $username,
                         $password,
                         $options);
  $conn_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


} catch (PDOException $e) {

  throw new Exception("Connection failed: ". $e->getMessage());

}

$server = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mdc_senior';
$charset = 'utf8';
$options = array(
    PDO::ATTR_PERSISTENT  => true,
);

try {
  $pdoConnSanitationResultDB = new PDO("mysql:host={$server};dbname={$dbname};charset={$charset}",
                         $username,
                         $password,
                         $options);
  $pdoConnSanitationResultDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // $pdoConnSanitationResultDB->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


} catch (PDOException $e) {

  throw new Exception("Connection failed: ". $e->getMessage());

}


?>
