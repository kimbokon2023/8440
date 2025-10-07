<?php
  if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	 $search=$_REQUEST["search"];
	 else
		 $search='';

  if(isset($_REQUEST["Bigsearch"]))   //목록표에 제목,이름 등 나오는 부분
	 $Bigsearch=$_REQUEST["Bigsearch"];	 	
	  else
		  $Bigsearch='';	 	
 	  
 if(isset($_REQUEST["bad_choice"])) // $_REQUEST["bad_choice"]값이 없을 때에는 1로 지정 
 {
    $bad_choice=$_REQUEST["bad_choice"];  // 페이지 번호
 }
  else
  {
    $bad_choice='';	 
  }	 

  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     
   
 
require_once("../lib/mydb.php");
$pdo = db_connect();	  

?>