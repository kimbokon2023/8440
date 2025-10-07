<?php
   
$num=$_REQUEST["num"];	

if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	 $search=$_REQUEST["search"];
	 
if(isset($_REQUEST["mode"]))
	$mode=$_REQUEST["mode"];
else 
	$mode="not";     

$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];  		  
		  
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");
$pdo = db_connect();  
  
$sum=array();

  
?>