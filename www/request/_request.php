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
  $first_num = intval(($page-1) * $scale) ;   // 리스트에 표시되는 게시글의 첫 순번.
	 
  if(isset($_REQUEST["mywrite"]))
     $mywrite=$_REQUEST["mywrite"];
  else 
     $mywrite="";  
 
 $cursort=intval($_REQUEST["cursort"]);    // 현재 정렬모드 지정
 
 isset($_REQUEST["done_check_val"]) ? $done_check_val=$_REQUEST["done_check_val"] :  $done_check_val='0';    // 입고완료제외
 $done_check=$_REQUEST["done_check"];    // 입고완료제외
 
  if($separate_date=="") $separate_date="1";
    
  if(isset($_REQUEST["fromdate"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $fromdate=$_REQUEST["fromdate"];
    
  if(isset($_REQUEST["todate"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $todate=$_REQUEST["todate"];  
  
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

?>