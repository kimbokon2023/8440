<?php
 session_start();
	  
// mysql 테이블 생성하기	  
require_once("./lib/mydb.php");
$pdo = db_connect();	  		  

$sql="CREATE TABLE mirae8440.steelcompany(num int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY, company varchar(20) NULL ) ";


if($pdo->query($sql))
print "Table MyGuests created successfully" ;

//  else     
  // echo "Error creating table: " . $pdo->error;


$pdo->close();

 ?>

