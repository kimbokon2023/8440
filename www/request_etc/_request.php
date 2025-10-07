<?php

if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	 $search=$_REQUEST["search"];
if(isset($_REQUEST["separate_date"]))   //출고일 완료일
	 $separate_date=$_REQUEST["separate_date"];	 
	 
if(isset($_REQUEST["list"]))   //목록표에 제목,이름 등 나오는 부분
	 $list=$_REQUEST["list"];
    else
		  $list=0;	  
 
$page = isset($page) && is_numeric($page) ? $page : 1;
$scale = isset($scale) && is_numeric($scale) ? $scale : 50; // or any default value for $scale
   
  $page_scale = 20 ;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale ;   // 리스트에 표시되는 게시글의 첫 순번.
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     
 
 $cursort=$_REQUEST["cursort"];    // 현재 정렬모드 지정
 
 isset($_REQUEST["done_check_val"]) ? $done_check_val=$_REQUEST["done_check_val"] :  $done_check_val='0';    // 입고완료제외
 $done_check=$_REQUEST["done_check"];    // 입고완료제외

 if($separate_date=="") $separate_date="1";
 
 // 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	


require_once("../lib/mydb.php");
$pdo = db_connect();	

?>