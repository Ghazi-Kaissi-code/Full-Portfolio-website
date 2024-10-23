<?php
//CONFIGURATION
$dsn = "mysql:host=localhost; dbname=cms1";
$username ="root";
$password = "";

try{
    //NEW INSTANCE 
  $pdo = new PDO($dsn,$username,$password);
  //FOR ERRORS
  $pdo->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
  //MESSAGE
  //echo "connected succesfully!";


}catch(PDOExecption $e){
    //IF NOT WORKING
   echo "connection lost " . $e->getMessage();
}
?>