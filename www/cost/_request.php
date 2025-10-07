<?php

if(isset($_REQUEST["Bigsearch"]))   //목록표에 제목,이름 등 나오는 부분
	 $Bigsearch=$_REQUEST["Bigsearch"];
	 else
		 $Bigsearch=$firstItem;
	 
if(isset($_REQUEST["search"]))  
	 $search=$_REQUEST["search"];
   else
	   $search="";
if(isset($_REQUEST["separate_date"]))   //출고일 완료일
	 $separate_date=$_REQUEST["separate_date"];	 
	 else
		 $separate_date="";	 
	 
	 
if(isset($_REQUEST["list"]))   //목록표에 제목,이름 등 나오는 부분
	 $list=$_REQUEST["list"];
    else
		  $list=0;	  
 
 if(isset($_REQUEST["page"])) // $_REQUEST["page"]값이 없을 때에는 1로 지정 
 {
    $page=$_REQUEST["page"];  // 페이지 번호
 }
  else
  {
    $page=1;	 
  }
 
 
if(isset($_REQUEST["scale"])) // $_REQUEST["scale"]값이 없을 때에는 1로 지정 
 {
    $scale=$_REQUEST["scale"];  // 페이지 번호
 }
  else
  {
    $scale=50;	 
  }
   
  $page_scale = 20 ;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = intval(($page-1) * $scale) ;   // 리스트에 표시되는 게시글의 첫 순번.
	 
  if(isset($_REQUEST["mywrite"]))
     $mywrite=$_REQUEST["mywrite"];
  else 
     $mywrite="";  
 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     

  if($separate_date=="") $separate_date="1";
  
  if(isset($_REQUEST["fromdate"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $fromdate=$_REQUEST["fromdate"];
  else
   $fromdate="";

  if(isset($_REQUEST["todate"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $todate=$_REQUEST["todate"];
  else
   $todate="";

  
  if(isset($_REQUEST["num"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $num=$_REQUEST["num"];
  else
   $num="";

  
  if(isset($_REQUEST["find"]))  //수정 버튼을 클릭해서 호출했는지 체크
	$find=$_REQUEST["find"];
  else
	$find="";

  if(isset($_REQUEST["process"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $process=$_REQUEST["process"];
  else
   $process="전체";

require_once("../lib/mydb.php");
$pdo = db_connect();	

?>