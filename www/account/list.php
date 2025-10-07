<?php

 session_start();

   $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(2);
         header ("Location:http://5130.co.kr/login/logout.php");
         exit;
   }
   
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
header("Expires: 0"); // rfc2616 - Section 14.21   
//header("Refresh:0");  // reload refresh   

function Console_log($data){
    echo "<script>console.log( 'PHP_Console: " . $data . "' );</script>";
}

?>

 <!DOCTYPE HTML>
 <html>
 <head>
 <meta charset="UTF-8">
  <title> 주일기업 통합정보시스템 </title> 
 <link rel="stylesheet" type="text/css" href="../css/common.css">
 <link rel="stylesheet" type="text/css" href="../css/account.css">
 <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

 </head>
 		<script>
		window.history.forward(0);
		history.navigationMode = 'compatible'; // 오페라, 사파리 뒤로가기 막기
		function _no_Back(){
		window.history.forward(0);
		}
		</script>
 <?php
  if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	 $search=$_REQUEST["search"];
   if(isset($_REQUEST["list"]))   //목록표에 제목,이름 등 나오는 부분
	 $list=$_REQUEST["list"];
    else
		  $list=0;
	  
  require_once("../lib/mydb.php");
  $pdo = db_connect();	
 // $find="firstord";	    //검색할때 고정시킬 부분 저장 ex) 전체/공사담당/건설사 등
 if(isset($_REQUEST["page"])) // $_REQUEST["page"]값이 없을 때에는 1로 지정 
 {
    $page=$_REQUEST["page"];  // 페이지 번호
 }
  else
  {
    $page=1;	 
  }
  
if(isset($_REQUEST["scale"])) // $_REQUEST["scale"]값이 없을 때에는 20로 지정 
 {
    $scale=$_REQUEST["scale"];  // 페이지 번호
 }
  else
  {
    $scale=20;	   // 한 페이지에 보여질 게시글 수
  }   
   
  $page_scale = 10;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     
 
   if(isset($_REQUEST["find"]))   //목록표에 제목,이름 등 나오는 부분
	 $find=$_REQUEST["find"];
 
   if(isset($_REQUEST["process"]))   //공사진행현황에 대한 내용
	 $process=$_REQUEST["process"];	 
	      else 
	          $process="전체";		   

$all_check=$_REQUEST["all_check"];	 
	  
if(isset($_REQUEST["asprocess"]))
	 $asprocess=$_REQUEST["asprocess"];	 
	      else 
	          $asprocess="전체";	


$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	 

		if($fromdate=="")
		{
			$fromdate=substr(date("Y-m-d",time()),0,4) ;
			$fromdate=$fromdate . "-01-01";
			Console_log($fromdate); 
		}
		if($todate=="")
		{
			$todate=substr(date("Y-m-d",time()),0,4) . "-12-31" ;
			$Transtodate=strtotime($todate.'+1 days');
			$Transtodate=date("Y-m-d",$Transtodate);
			Console_log($Transtodate); 		
		}
			else
			{
			$Transtodate=strtotime($todate.'+1 days');
			$Transtodate=date("Y-m-d",$Transtodate);
			Console_log($Transtodate); 		
			}
	
if($all_check=='on') {
    		$fromdate= "1950-01-01";
			$Transtodate= "2050-01-01";
}
  else
  {

			$fromdate=substr(date("Y-m-d",time()),0,4) ;
			$fromdate=$fromdate . "-01-01";

			$todate=substr(date("Y-m-d",time()),0,4) . "-12-31" ;
			$Transtodate=strtotime($todate.'+1 days');
			$Transtodate=date("Y-m-d",$Transtodate);
 		
	
  }
		  
if(isset($_REQUEST["year"]))
		 $year=$_REQUEST["year"];	 
	 

$process="전체";  // 기본 전체로 정한다.
//$a="  where (endworkday between date('$fromdate') and date('$todate')) order by num desc limit $first_num, $scale";   // 시공완료일을 기준으로 잡았을때
//$b="  where (endworkday between date('$fromdate') and date('$todate')) order by num desc ";  
//$c="  and (endworkday between date('$fromdate') and date('$todate')) order by num desc limit $first_num, $scale";    //연결해서 이어지는 문장
//$d="  and (endworkday between date('$fromdate') and date('$todate')) order by num desc ";                            // 연결해서 이어지는 전체레코드 수 파악용


$a="  where (regist_day between date('$fromdate') and date('$Transtodate')) or (workday between date('$fromdate') and date('$Transtodate')) order by num desc limit $first_num, $scale";    //연결해서 이어지는 문장
$b="  where (regist_day between date('$fromdate') and date('$Transtodate')) or (workday between date('$fromdate') and date('$Transtodate')) order by num desc ";    //연결해서 이어지는 문장
$c="  and ((regist_day between date('$fromdate') and date('$Transtodate')) or (workday between date('$fromdate') and date('$Transtodate'))) order by num desc limit $first_num, $scale";    //연결해서 이어지는 문장
$d="  and ((regist_day between date('$fromdate') and date('$Transtodate')) or (workday between date('$fromdate') and date('$Transtodate'))) order by num desc ";    //연결해서 이어지는 문장
    

$e="   order by num desc limit $first_num, $scale";    // 계산서발행, 입금약속일 등 시공일 기준일자가 아닌 검색이 필요할때 
$f="   order by num desc ";  					 // 계산서발행, 입금약속일 등 시공일 기준일자가 아닌 검색이 필요할때  전체 수


if($asprocess=="전체")   // 상단에 설정된 전체/계약금미입력/입금약속일/기성청구/계산서발행/결재완납 선택 라디오버튼
{	
  if($mode=="search" || $list==1){
		  if($search==""){
			     if($process=="전체")
				    {
					 $sql="select * from chandj.work " . $a; 					
	                  $sqlcon = "select * from chandj.work " . $b;   // 전체 레코드수를 파악하기 위함.
					}
					 else   // 진행현황이 다를때 처리구문
					 {
					 $sql="select * from chandj.work where (work_state like '%$process%')" . $c;
	                  $sqlcon = "select * from chandj.work where (work_state like '%$process%') " . $d;    // 전체 레코드수를 파악하기 위함.					 						 
					 }
			       }
			 elseif($search!=""&&$find!="all"&&$process=="전체")
			    {
         			$sql="select * from chandj.work where ($find like '%$search%') " . $c;
         			$sqlcon="select * from chandj.work where ($find like '%$search%') " . $d;
                 }
			 elseif($search!=""&&$find!="all"&&$process!="전체")    // 진행현황에 대한 처리 추가
			    {
         			$sql="select * from chandj.work where ($find like '%$search%') and (work_state like '%$process%') ". $c;
         			$sqlcon="select * from chandj.work where ($find like '%$search%') and (work_state like '%$process%') " . $d;
                 }	      				 
             elseif($search!="") { // 각 필드별로 검색어가 있는지 쿼리주는 부분
			   if($process=="전체") 
			   {   
					  $sql ="select * from chandj.work where ((condate like '%$search%' ) or (workplacename like '%$search%' ) or (chargedperson like '%$search%' )";
					  $sql .="or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (cablestaff like '%$search%' ) or (address like '%$search%' ))  " . $c;
					  
					  $sqlcon ="select * from chandj.work where ((condate like '%$search%' ) or (workplacename like '%$search%' ) or (chargedperson like '%$search%' )";
					  $sqlcon .="or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (cablestaff like '%$search%' ) or (address like '%$search%')) " . $d;
			   //limit를 사용해 레코드 개수를 한 페이지당 출력하는 수로 제한			   
			        }    
					else
					{
					  $sql ="select * from chandj.work where ((condate like '%$search%' ) or (workplacename like '%$search%' ) or (chargedperson like '%$search%' )";
					  $sql .="or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (cablestaff like '%$search%' ) or (address like '%$search%' )) and (work_state like '%$process%') " . $c;
					  
					  $sqlcon ="select * from chandj.work where ((condate like '%$search%' ) or (workplacename like '%$search%' ) or (chargedperson like '%$search%' )";
					  $sqlcon .="or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (cablestaff like '%$search%' ) or (address like '%$search%')) and (work_state like '%$process%') " . $d;
			   //limit를 사용해 레코드 개수를 한 페이지당 출력하는 수로 제한
					}               
               }
           }
     else
	 {
          					 $sql="select * from chandj.work " . $a;
          					 $sqlcon="select * from chandj.work " . $b;
   }     		

if($mode=="search" || $list==1){
		  if($search==""){
			     if($process=="전체")
				    {
					 $sql="select * from chandj.work " . $a; 					
	                  $sqlcon = "select * from chandj.work " . $b;   // 전체 레코드수를 파악하기 위함.
					}
					 else   // 진행현황이 다를때 처리구문
					 {
					  $sql="select * from chandj.work where (work_state like '%$process%')" . $c;
					  $sqlcon = "select * from chandj.work where (work_state like '%$process%')" . $d;
					 }
			       }
			 elseif($search!=""&&$find!="all"&&$process=="전체")
			    {
         			$sql="select * from chandj.work where ($find like '%$search%')" . $c;						
         			$sqlcon="select * from chandj.work where ($find like '%$search%')" . $d;						
                 }
			 elseif($search!=""&&$find!="all"&&$process!="전체")    // 진행현황에 대한 처리 추가
			    {
         			$sql="select * from chandj.work where ($find like '%$search%') and (work_state like '%$process%') " . $c ;						
         			$sqlcon="select * from chandj.work where ($find like '%$search%') and (work_state like '%$process%') " . $d ;						
                 }	      				 
             elseif($search!="") { // 각 필드별로 검색어가 있는지 쿼리주는 부분
			   if($process=="전체") 
			   {   
					  $sql ="select * from chandj.work where ((condate like '%$search%' ) or (workplacename like '%$search%' ) or (chargedperson like '%$search%' )";
					  $sql .="or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (cablestaff like '%$search%' ) or (address like '%$search%' )) " . $c;
					  
					  $sqlcon ="select * from chandj.work where ((condate like '%$search%' ) or (workplacename like '%$search%' ) or (chargedperson like '%$search%' )";
					  $sqlcon .="or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (cablestaff like '%$search%' ) or (address like '%$search%')) " . $d;
			   //limit를 사용해 레코드 개수를 한 페이지당 출력하는 수로 제한			   
			        }    
					else
					{
					  $sql ="select * from chandj.work where ((condate like '%$search%' ) or (workplacename like '%$search%' ) or (chargedperson like '%$search%' )";
					  $sql .="or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (cablestaff like '%$search%' ) or (address like '%$search%' )) and (work_state like '%$process%')  " . $c;
					  
					  $sqlcon ="select * from chandj.work where ((condate like '%$search%' ) or (workplacename like '%$search%' ) or (chargedperson like '%$search%' )";
					  $sqlcon .="or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (cablestaff like '%$search%' ) or (address like '%$search%')) and (work_state like '%$process%') " . $d;
			   //limit를 사용해 레코드 개수를 한 페이지당 출력하는 수로 제한
					}               
               }
           }
     else
	 {
          			$sql="select * from chandj.work " . $a;          
          			$sqlcon="select * from chandj.work  " . $b;  
   }     
}   
elseif($asprocess=="계약금미입력")    //계약금 미입력
{
          					 $sql="select * from chandj.work where (sum_estimate is null) " . $c;
          					 $sqlcon="select * from chandj.work where (sum_estimate is null) " . $d;
}
elseif($asprocess=="입금약속일")    //입금약속일 검색
{
          					 $sql="select * from chandj.work where (promiseday <> '0000-00-00') " . " order by promiseday asc limit $first_num, $scale";
          					 $sqlcon="select * from chandj.work where (promiseday <> '0000-00-00') " . " order by promiseday asc";
}
elseif($asprocess=="기성청구")    //기성청구 검색일때
{
          					 $sql = "select * from chandj.work where (claimdate1 between '$fromdate' and '$Transtodate') or ";
          					 $sql .="(claimdate2 between '$fromdate' and '$Transtodate') or " ;
          					 $sql .="(claimdate3 between '$fromdate' and '$Transtodate') or " ;
          					 $sql .="(claimdate4 between '$fromdate' and '$Transtodate') or " ;
          					 $sql .="(claimdate5 between '$fromdate' and '$Transtodate') or " ;
          					 $sql .="(claimdate6 between '$fromdate' and '$Transtodate') " .  $e;
							 
          					 $sqlcon ="select * from chandj.work where (claimdate1 between '$fromdate' and '$Transtodate') or ";
          					 $sqlcon .="(claimdate2 between '$fromdate' and '$Transtodate') or " ;
          					 $sqlcon .="(claimdate3 between '$fromdate' and '$Transtodate') or " ;
          					 $sqlcon .="(claimdate4 between '$fromdate' and '$Transtodate') or " ;
          					 $sqlcon .="(claimdate5 between '$fromdate' and '$Transtodate') or " ;
          					 $sqlcon .="(claimdate6 between '$fromdate' and '$Transtodate') " . $f;

}
elseif($asprocess=="계산서발행")    //계산서 발행 검색일때
{
          					 $sql = "select * from chandj.work where (billdate1 between '$fromdate' and '$Transtodate') or ";
          					 $sql .="(billdate2 between '$fromdate' and '$Transtodate') or " ;
          					 $sql .="(billdate3 between '$fromdate' and '$Transtodate') or " ;
          					 $sql .="(billdate4 between '$fromdate' and '$Transtodate') or " ;
          					 $sql .="(billdate5 between '$fromdate' and '$Transtodate') or " ;
          					 $sql .="(billdate6 between '$fromdate' and '$Transtodate') " . $e;
							 
          					 $sqlcon ="select * from chandj.work where (billdate1 between '$fromdate' and '$Transtodate') or ";
          					 $sqlcon .="(billdate2 between '$fromdate' and '$Transtodate') or " ;
          					 $sqlcon .="(billdate3 between '$fromdate' and '$Transtodate') or " ;
          					 $sqlcon .="(billdate4 between '$fromdate' and '$Transtodate') or " ;
          					 $sqlcon .="(billdate5 between '$fromdate' and '$Transtodate') or " ;
          					 $sqlcon .="(billdate6 between '$fromdate' and '$Transtodate') " . $f;							 
}
elseif($asprocess=="결재완납")    //계산서 발행 검색일때
{
          					 $sql = "select * from chandj.work where (sum_estimate > 0) and (sum_receivable = 0)" . $c;          					 
							 $sqlcon = "select * from chandj.work where (sum_estimate > 0) and (sum_receivable = 0)" . $d; 
}
elseif($asprocess=="미수금")    //계산서 발행 검색일때
{
          					 $sql = "select * from chandj.work where (sum_estimate > 0) and (sum_receivable > 0)" . $c;          					 
							 $sqlcon = "select * from chandj.work where (sum_estimate > 0) and (sum_receivable > 0)" . $d; 
}
elseif($asprocess=="실적신고")    //계산서 발행 검색일때
{
          					 $sql = "select * from chandj.work where (endworkday between '$fromdate' and '$Transtodate') and ";
          					 $sql .="(firstord=secondord)  " . $e;
							 
          					 $sqlcon ="select * from chandj.work where (endworkday between '$fromdate' and '$Transtodate') and ";
          					 $sqlcon .="(firstord=secondord)  " . $f;				
}

$total_sum=0;  //미수금합 초기화

/* 	$sql="select * from chandj.work where (regist_day between date('$fromdate') and date('$todate')) order by num desc limit $first_num, $scale" ;          // 쿼리성공한 것
    $sqlcon="select * from chandj.work   where (regist_day between date('$fromdate') and date('$todate')) order by num desc " ; */
	 try{  
	  $allstmh = $pdo->query($sqlcon);         // 검색 조건에 맞는 쿼리 전체 개수
      $temp2=$allstmh->rowCount();  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();
	      
				$total_row = $temp2;     // 전체 글수	 
 		
         					 
		 $total_page = ceil($total_row / $scale); // 검색 전체 페이지 블록 수
		 $current_page = ceil($page/$page_scale); //현재 페이지 블록 위치계산			 
	  //   print "$page&nbsp;$total_page&nbsp;$current_page&nbsp;$search&nbsp;$mode";
			 
			?>
		 
<body onload="_no_Back();" onpageshow="if(event.persisted)_no_Back();">
 <div id="wrap">
   <div id="header">
	 <?php include "../lib/top_login2.php"; ?>
   </div>
   <div id="menu">
	 <?php include "../lib/top_menu2.php"; ?>
   </div>
   <div id="content">			 
   <div id="col2">
   <form id="board_form" name="board_form" method="post" action="list.php?mode=search&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&scale=<?=$scale?>&all_check=<?=$all_check?>">  
      <div id="title"><h1>경리부 List </h1></div>
      <div id="title_sub1">
	  <input type="hidden" id="all_check" name="all_check" value="<?=$all_check?>" > 
	 <?php
	    if($all_check!="on") {
			 ?>
			 <input type="checkbox" id="click_check" name="click_check"  > 시공완료일 무시 전체선택  
			<?php
             		}    ?>	   
	 <?php
	    if($all_check=="on") {
			 ?>
			 <input type="checkbox" id="click_check" name="click_check" checked > 시공완료일 무시 전체선택  
			<?php

             		}    ?>	   		
	  
	  
	  
	  </div>
	  <div id="title_2nd">		
<!--	 <div id class="blink"  style="white-space:nowrap">  <font color=brown> ***** 조건검색 ***** </font> </div>  -->
	 <?php
	    if($asprocess=="전체") {
			 ?>
			<div id="title2_sub">전체 <input type="radio" checked name=asprocess value="전체">
			&nbsp;&nbsp;   계약금미입력 <input type="radio" name=asprocess value="계약금미입력">
			&nbsp;&nbsp;   입금약속일<input type="radio" name=asprocess value="입금약속일">
			&nbsp;&nbsp;   기성청구<input type="radio" name=asprocess value="기성청구">
			&nbsp;&nbsp;   계산서발행<input type="radio" name=asprocess value="계산서발행">	  
			&nbsp;&nbsp;   결재완납<input type="radio" name=asprocess value="결재완납">	  		
			&nbsp;&nbsp;   미수금<input type="radio"  name=asprocess value="미수금">	
			&nbsp;&nbsp;   실적신고<input type="radio" name=asprocess value="실적신고">										
			</div>
			<?php
             		}    ?>	 
	 <?php
	    if($asprocess=="계약금미입력") {
			 ?>
			<div id="title2_sub">전체 <input type="radio" name=asprocess value="전체">			 
			&nbsp;&nbsp;   계약금미입력 <input type="radio" checked name=asprocess value="계약금미입력">
			&nbsp;&nbsp;   입금약속일<input type="radio" name=asprocess value="입금약속일">
			&nbsp;&nbsp;   기성청구<input type="radio" name=asprocess value="기성청구">
			&nbsp;&nbsp;   계산서발행<input type="radio" name=asprocess value="계산서발행">	  
			&nbsp;&nbsp;   결재완납<input type="radio" name=asprocess value="결재완납">	  		
			&nbsp;&nbsp;   미수금<input type="radio"  name=asprocess value="미수금">	
			&nbsp;&nbsp;   실적신고<input type="radio" name=asprocess value="실적신고">										
			</div>
			<?php
             		}    ?>
	 <?php
	    if($asprocess=="입금약속일") {
			 ?>
			<div id="title2_sub">전체 <input type="radio" name=asprocess value="전체">			 
			&nbsp;&nbsp;   계약금미입력 <input type="radio" name=asprocess value="계약금미입력">			
			&nbsp;&nbsp;   입금약속일<input type="radio" checked name=asprocess value="입금약속일">
			&nbsp;&nbsp;   기성청구<input type="radio" name=asprocess value="기성청구">
			&nbsp;&nbsp;   계산서발행<input type="radio" name=asprocess value="계산서발행">	  
			&nbsp;&nbsp;   결재완납<input type="radio" name=asprocess value="결재완납">	  		
			&nbsp;&nbsp;   미수금<input type="radio"  name=asprocess value="미수금">	
			&nbsp;&nbsp;   실적신고<input type="radio" name=asprocess value="실적신고">										
			</div>
			<?php
             		}    ?>
	 <?php
	    if($asprocess=="기성청구") {
			 ?>
			<div id="title2_sub">전체 <input type="radio" name=asprocess value="전체">			 
			&nbsp;&nbsp;   계약금미입력 <input type="radio" name=asprocess value="계약금미입력">						 
			&nbsp;&nbsp;   입금약속일<input type="radio" name=asprocess value="입금약속일">
			&nbsp;&nbsp;   기성청구<input type="radio"  checked  name=asprocess value="기성청구">
			&nbsp;&nbsp;   계산서발행<input type="radio" name=asprocess value="계산서발행">	  
			&nbsp;&nbsp;   결재완납<input type="radio" name=asprocess value="결재완납">	  		
			&nbsp;&nbsp;   미수금<input type="radio"  name=asprocess value="미수금">	
			&nbsp;&nbsp;   실적신고<input type="radio" name=asprocess value="실적신고">										
			</div>
			<?php
             		}    ?>
	 <?php
	    if($asprocess=="계산서발행") {
			 ?>
			<div id="title2_sub">전체 <input type="radio" name=asprocess value="전체">			 
			&nbsp;&nbsp;   계약금미입력 <input type="radio" name=asprocess value="계약금미입력">			
			&nbsp;&nbsp;   입금약속일<input type="radio" name=asprocess value="입금약속일">
			&nbsp;&nbsp;   기성청구<input type="radio" name=asprocess value="기성청구">
			&nbsp;&nbsp;   계산서발행<input type="radio"  checked name=asprocess value="계산서발행">	  		
			&nbsp;&nbsp;   결재완납<input type="radio" name=asprocess value="결재완납">	  		
			&nbsp;&nbsp;   미수금<input type="radio"  name=asprocess value="미수금">	
			&nbsp;&nbsp;   실적신고<input type="radio" name=asprocess value="실적신고">										
			</div>
			<?php
             		}    ?>					
	 <?php
	    if($asprocess=="결재완납") {
			 ?>
			<div id="title2_sub">전체 <input type="radio" name=asprocess value="전체">			 
			&nbsp;&nbsp;   계약금미입력 <input type="radio" name=asprocess value="계약금미입력">			
			&nbsp;&nbsp;   입금약속일<input type="radio" name=asprocess value="입금약속일">
			&nbsp;&nbsp;   기성청구<input type="radio" name=asprocess value="기성청구">
			&nbsp;&nbsp;   계산서발행<input type="radio" name=asprocess value="계산서발행">	  		
			&nbsp;&nbsp;   결재완납<input type="radio"  checked name=asprocess value="결재완납">	  		
			&nbsp;&nbsp;   미수금<input type="radio"  name=asprocess value="미수금">
			&nbsp;&nbsp;   실적신고<input type="radio" name=asprocess value="실적신고">								  		
			</div>
			<?php
             		}    ?>		
	 <?php
	    if($asprocess=="미수금") {
			 ?>
			<div id="title2_sub">전체 <input type="radio" name=asprocess value="전체">			 
			&nbsp;&nbsp;   계약금미입력 <input type="radio" name=asprocess value="계약금미입력">			
			&nbsp;&nbsp;   입금약속일<input type="radio" name=asprocess value="입금약속일">
			&nbsp;&nbsp;   기성청구<input type="radio" name=asprocess value="기성청구">
			&nbsp;&nbsp;   계산서발행<input type="radio" name=asprocess value="계산서발행">	  
			&nbsp;&nbsp;   결재완납<input type="radio" name=asprocess value="결재완납">	  		
			&nbsp;&nbsp;   미수금<input type="radio"  checked name=asprocess value="미수금">		
			&nbsp;&nbsp;   실적신고<input type="radio" name=asprocess value="실적신고">							
			</div>
			<?php
             		}    ?>			
	 <?php
	    if($asprocess=="실적신고") {
			 ?>
			<div id="title2_sub">전체 <input type="radio" name=asprocess value="전체">			 
			&nbsp;&nbsp;   계약금미입력 <input type="radio" name=asprocess value="계약금미입력">			
			&nbsp;&nbsp;   입금약속일<input type="radio" name=asprocess value="입금약속일">
			&nbsp;&nbsp;   기성청구<input type="radio" name=asprocess value="기성청구">
			&nbsp;&nbsp;   계산서발행<input type="radio" name=asprocess value="계산서발행">	  
			&nbsp;&nbsp;   결재완납<input type="radio" name=asprocess value="결재완납">	  		
			&nbsp;&nbsp;   미수금<input type="radio"  name=asprocess value="미수금">
			&nbsp;&nbsp;   실적신고<input type="radio"  checked name=asprocess value="실적신고">				
			</div>
			<?php
             		}    ?>							
	  

	  <div id="title_board1"> 미수금합 : </div>  <div id="title_board2"> </div>
	  
		  </div>   
      <br>
      <div id="list_search">
        <div id="list_search1">▷ 총 <?= $total_row ?> 개의 자료 파일이 있습니다.</div>
       <div id="list_search1111"> 
       <input id="preyear" type='button' onclick='pre_year()' value='전년도'>	 
	   <input id ="premonth" type='button' onclick='pre_month()' value='전월'>	 
       <input type="text" id="fromdate" name="fromdate" size="12" value="<?=$fromdate?>" placeholder="기간 시작일">부터	   
       <input type="text" id="todate" name="todate" size="12"  value="<?=$todate?>" placeholder="기간 끝">까지
      <input id ="thisyear" type='button' onclick='this_year()' value='당해년도'>	  
	   <input id ="thismonth" type='button' onclick='this_month()' value='당월'>	&nbsp;&nbsp;   &nbsp;&nbsp; 
								
       </div>								
					
        <div id="list_search2"><img src="../img/select_search.gif"></div>
        <div id="list_search3">
        <select name="find">
           <?php		  
		      if($find=="")
			  {			?>	  
           <option value='all'>전체</option>
           <option value='workplacename'>현장명</option>
           <option value='firstord'>발주처</option>
           <option value='chargedperson'>공사담당</option>
           <option value='firstord'>건설사</option>
           <option value='secondord'>발주처</option>
           <option value='worker'>시공팀</option>
           <option value='cablestaff'>결선팀</option>
		   <?php
			  } ?>		
		  <?php		  
		      if($find=="all")
			  {			?>	  
           <option value='all' selected>전체</option>
           <option value='workplacename'>현장명</option>
           <option value='firstord'>발주처</option>
           <option value='chargedperson'>공사담당</option>
           <option value='firstord'>건설사</option>
           <option value='secondord'>발주처</option>
           <option value='worker'>시공팀</option>
           <option value='cablestaff'>결선팀</option>
		   <?php
			  } ?>
		  <?php
		      if($find=="workplacename")
			  {			?>	  
           <option value='all' >전체</option>
           <option value='workplacename' selected>현장명</option>
           <option value='firstord'>발주처</option>
           <option value='chargedperson'>공사담당</option>
           <option value='firstord'>건설사</option>
           <option value='secondord'>발주처</option>
           <option value='worker'>시공팀</option>
           <option value='cablestaff'>결선팀</option>
		   <?php
			  } ?>			  
		  <?php
		      if($find=="firstord")
			  {			?>	  
           <option value='all'>전체</option>
           <option value='workplacename'>현장명</option>
           <option value='firstord' selected>발주처</option>
           <option value='chargedperson'>공사담당</option>
           <option value='firstord'>건설사</option>
           <option value='secondord'>발주처</option>
           <option value='worker'>시공팀</option>
           <option value='cablestaff'>결선팀</option>
		   <?php
			  } ?>			  
		  <?php
		      if($find=="chargedperson")
			  {			?>	  
           <option value='all'>전체</option>
           <option value='workplacename'>현장명</option>
           <option value='firstord'>발주처</option>
           <option value='chargedperson' selected>공사담당</option>
           <option value='firstord'>건설사</option>
           <option value='secondord'>발주처</option>
           <option value='worker'>시공팀</option>
           <option value='cablestaff'>결선팀</option>
		   <?php
			  } ?>	  			  
		  <?php
		      if($find=="firstord")
			  {			?>	  
           <option value='all'>전체</option>
           <option value='workplacename'>현장명</option>
           <option value='firstord'>발주처</option>
           <option value='chargedperson' >공사담당</option>
           <option value='firstord' selected>건설사</option>
           <option value='secondord'>발주처</option>
           <option value='worker'>시공팀</option>
           <option value='cablestaff'>결선팀</option>
		   <?php
			  } ?>				  
			   <?php
		   if($find=="secondord")
			  {			?>	  
           <option value='all'>전체</option>
           <option value='workplacename'>현장명</option>
           <option value='firstord'>발주처</option>
           <option value='chargedperson' >공사담당</option>
           <option value='firstord'>건설사</option>
           <option value='secondord' selected>발주처</option>
           <option value='worker'>시공팀</option>
           <option value='cablestaff'>결선팀</option>
		   <?php
			  } ?>		
              <?php			  
		   if($find=="worker")
			  {			?>	  
           <option value='all'>전체</option>
           <option value='workplacename'>현장명</option>
           <option value='firstord'>발주처</option>
           <option value='chargedperson' >공사담당</option>
           <option value='firstord'>건설사</option>
           <option value='secondord' >발주처</option>
           <option value='worker' selected>시공팀</option>
           <option value='cablestaff'>결선팀</option>
		   <?php
			  } ?>	
              <?php			  
		   if($find=="cablestaff")
			  {			?>	  
           <option value='all'>전체</option>
           <option value='workplacename'>현장명</option>
           <option value='firstord'>발주처</option>
           <option value='chargedperson' >공사담당</option>
           <option value='firstord'>건설사</option>
           <option value='secondord' >발주처</option>
           <option value='worker' >시공팀</option>
           <option value='cablestaff' selected>결선팀</option>
		   <?php
			  } ?>				  
        </select>
				
		</div> <!-- end of list_search3 -->
		<?php
			?>
        <div id="list_search4"><input type="text" name="search" value="<?=$search?>"></div>
		<?php
		    $search=$search;
		?>	
        <div id="list_search5"><input type="image" src="../img/list_search_button.gif"></div>
		
		 <div id="list_search6">
		&nbsp;&nbsp;	화면표시 목록수 : &nbsp;
		
		 <select name="scale" id="scale">
						  <option value="<?=$scale?>"><?=$scale?></option> 
					<?php   // 화면표시목록 숫자
					
					  $options = array(); 
					  $options[1] = '20'; 
					  $options[2] = '50'; 
					  $options[3] = '100'; 
					  $options[4] = '200'; 
					  $options[5] = '500'; 
					  $options[6] = '1000'; 					
				
			  if (! empty($options)) { 
			foreach ($options as $key => $val) { 
			$sel="";
			if($steeltype==$val)
				$sel="selected";
			?> 
			<option value="<?php echo $val;?>" <?php echo $sel;?> > <?php echo $val;?></option> 
			<?php 
			} 
			} 
			?> 
			</select> 
		

		
	
		
      </div> <!-- end of list_search -->

      <div class="clear"></div>
      <div id="aclist_top_title">
      <ul>
         <li id="account_list1"><img src="../img/list_title1.png"></li>
         <li id="account_list2"><img src="../img/list_title2.png"></li>     <!-- 공사현장명 -->
         <li id="account_list3"><img src="../img/list_title3.png"></li>
         <li id="account_list4"><img src="../img/aclist_title4.png"></li>
         <li id="account_list5"><img src="../img/list_title5.png"></li>		 <!-- 공사담담  -->
         <li id="account_list6"><img src="../img/aclist_title5.png"></li>
         <li id="account_list7"><img src="../img/aclist_title6.png"></li>
         <li id="account_list8"><img src="../img/aclist_title7.png"></li>    
		 <li id="account_list14"><img src="../img/aclist_title13.png"></li>     <!-- 기성확정 --> 
         <li id="account_list9"><img src="../img/aclist_title8.png"></li>     

         <li id="account_list10"><img src="../img/aclist_title9.png"></li>     
         <li id="account_list11"><img src="../img/aclist_title10.png"></li>     
         <li id="account_list12"><img src="../img/aclist_title11.png"></li>     
         <li id="account_list13"><img src="../img/aclist_title12.png"></li>     
      </ul>
      </div> <!-- end of list_top_title -->
      <div id="list_content">
			<?php  
		  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		  else 
			$start_num=$total_row-($page-1) * $scale;
	    

		  
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			  $item_num=$row["num"];
			  $item_id=$row["id"];
			  $item_name=$row["chargedperson"];
			  $item_nick=$row["nick"];
			  $item_hit=$row["hit"];
			  $item_man=$row["chargedperson"];
			  $item_date=$row["regist_day"];
			  $item_date=substr($item_date, 0, 10);
			  $item_orderco=$row["secondord"];
			  $item_orderman=$row["secondordman"];
			  $sum_estimate=number_format($row["sum_estimate"]);
			  $sum_receivable=number_format($row["sum_receivable"]);
			  $sum_bill=number_format($row["sum_bill"]);
			  
			  $total_sum = $total_sum + $row["sum_receivable"];  // 미수금합			  

			  
			  
			  $promiseday=$row["promiseday"];
			  if($promiseday=='0000-00-00') $promiseday=""; 	
				
			  $aa=[];
			  $aa[0]=$row["claimdate1"];
			  $aa[1]=$row["claimdate2"];
			  $aa[2]=$row["claimdate3"];
			  $aa[3]=$row["claimdate4"];
			  $aa[4]=$row["claimdate5"];
			  $aa[5]=$row["claimdate6"];
              
			  $confirm_claimdate="";
			  $confirm_claimfix="";			  
			  for($i=5;$i>-1;$i--)
			  {				   
				  if($aa[$i]!= '0000-00-00')
				  {
				    $confirm_claimdate=$aa[$i];
					break;
				  }
			  }		
			  
			  $bb=[];
			  $bb[0]=$row["claimamount1"];
			  $bb[1]=$row["claimamount2"];
			  $bb[2]=$row["claimamount3"];
			  $bb[3]=$row["claimamount4"];
			  $bb[4]=$row["claimamount5"];
			  $bb[5]=$row["claimamount6"];
		      
			  $ee=[]; 
			  $ee[0]=$row["claimfix1"];
			  $ee[1]=$row["claimfix2"];
			  $ee[2]=$row["claimfix3"];
			  $ee[3]=$row["claimfix4"];
			  $ee[4]=$row["claimfix5"];
			  $ee[5]=$row["claimfix6"];		
              
			  $confirm_claimamount="";
			  
			  for($i=5;$i>-1;$i--)
			  {				   
				  if($bb[$i]!= "")
				  {
				    $confirm_claimamount=$bb[$i];
				    $confirm_claimfix=$ee[$i];
					break;
				  }
			  }	
			  
			  $cc=[];
			  $cc[0]=$row["billdate1"];
			  $cc[1]=$row["billdate2"];
			  $cc[2]=$row["billdate3"];
			  $cc[3]=$row["billdate4"];
			  $cc[4]=$row["billdate5"];
			  $cc[5]=$row["billdate6"];

			  $dd=[];
			  $dd[0]=$row["bill1"];
			  $dd[1]=$row["bill2"];
			  $dd[2]=$row["bill3"];
			  $dd[3]=$row["bill4"];
			  $dd[4]=$row["bill5"];
			  $dd[5]=$row["bill6"];			  
			  
	              
			  
			  
			  $confirm_billdate="";
			  $confirm_billamount="";

			  
			  for($i=5;$i>-1;$i--)
			  {				   
				  if($cc[$i]!= '0000-00-00')
				  {
				    $confirm_billdate=$cc[$i];
					$confirm_billamount=$dd[$i];
					break;
				  }
			  }	
			  	  
			  
			  $item_subject=str_replace(" ", "&nbsp;", $row["workplacename"]);
			  
			  $state_work=0;
			  if($row["checkbox"]==0) $state_work=1;
			  if(substr($row["workday"],0,2)=="20") $state_work=2;
			  if(substr($row["endworkday"],0,2)=="20") $state_work=3;
			  if(substr($row["cableday"],0,2)=="20") $state_work=4;
			  if(substr($row["endcableday"],0,2)=="20") $state_work=5;

			  $item_subject=$row["workplacename"];
              $item_subject=mb_substr($item_subject,0,15,'utf-8');
			  if(mb_strlen($item_subject,'utf-8')>=15)
							$item_subject=$item_subject . "...";   // 글자수가 초과하면 ...으로 표기됨		

              if($asday=='0000-00-00') $asday=""; 			  
              if($asproday=='0000-00-00') $asproday=""; 	
              if($confirm_billdate=='0000-00-00') $confirm_billdate=""; 	
              if($sum_bill==0) $sum_bill=""; 	
			  			  
			  $font="black";
			  switch ($state_work) {
                            case 1: $state_str="착공전"; $font="black";break;				  
							case 2: $state_str="시공중"; $font="blue"; break;
							case 3: $state_str="결선대기"; $font="brown"; break;
							case 4: $state_str="결선중"; $font="purple"; break;
							case 5: $state_str="결선완료"; $font="red";break;							
							default: $font="grey"; $state_str="계약전"; 
						}

              $state_as=0;    // AS 색상 등 표현하는 계산 
			  if(substr($row["asday"],0,2)=="20") $state_as=1;
			  if(substr($row["asproday"],0,2)=="20") $state_as=2;
			  if(substr($row["asendday"],0,2)=="20") $state_as=3;			  
			  
			  $font_as="black";
			  switch ($state_as) {
							case 1: $state_astext="접수완료"; $font_as="blue"; break;
							case 2: $state_astext="처리예약"; $font_as="grey"; break;
							case 3: $state_astext="처리완료"; $font_as="red"; break;							
							default: $state_astext=""; 
						}
						
						
						
			  $sql="select * from chandj.work_ripple where parent=$item_num";
			  $allstmh = $pdo->query($sql); 
			  $num_ripple=$allstmh->rowCount(); 
			 ?>
				<div id="subject_item" >
			  <div id="subject_item1"><?= $start_num ?></div>
				<div id="subject_item2"> <a href="view.php?num=<?=$item_num?>&page=<?=$page?>&find=<?=$find?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&scale=<?=$scale?>&all_check=<?=$all_check?>" style="font-size:13px" ><?= $item_subject ?>&nbsp;</a>
			<?php		
				 if($num_ripple)
				 print "[<font color=red><b>$num_ripple</b></font>]";
			  ?>
				</div>				
				<div id="subject_item3"><?=substr($item_orderco,0,25)?>&nbsp;</div>
				<div id="subject_item4" style="color:<?=$font?>;"><?= $state_str?>&nbsp;</div>				
				<div id="subject_item5"><?=$item_man?>&nbsp;</div>
				<div id="subject_item6"><?=$sum_estimate?>&nbsp;</div>
				<div id="subject_item7"><?=$confirm_claimdate?>&nbsp;</div>
				<div id="subject_item8"><?= $confirm_claimamount?>&nbsp;</div>
				<div id="subject_item14"><?= $confirm_claimfix?>&nbsp;</div>
				<div id="subject_item9"><?= $promiseday?>&nbsp;</div>
				<div id="subject_item10"><?= $confirm_billdate?>&nbsp;</div>
				<div id="subject_item11"><b><?= $confirm_billamount?>&nbsp;</b></div>
				<div id="subject_item12"><b><?= $sum_bill?>&nbsp;</b></div>
				<div id="subject_item13"><b><?= $sum_receivable?>&nbsp;</b></div>
			  </div> 

			<?php
			$start_num--;

			 } 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
   // 페이지 구분 블럭의 첫 페이지 수 계산 ($start_page)
      $start_page = ($current_page - 1) * $page_scale + 1;
   // 페이지 구분 블럭의 마지막 페이지 수 계산 ($end_page)
      $end_page = $start_page + $page_scale - 1;  
 ?>
 
      <div id="page_button">
	<div id="page_num">  
 <?php
      if($page!=1 && $page>$page_scale)
      {
        $prev_page = $page - $page_scale;    
        // 이전 페이지값은 해당 페이지 수에서 리스트에 표시될 페이지수 만큼 감소
        if($prev_page <= 0) 
            $prev_page = 1;  // 만약 감소한 값이 0보다 작거나 같으면 1로 고정
        print "<a href=list.php?page=$prev_page&search=$search&find=$find&list=1&process=$process&asprocess=$asprocess&fromdate=$fromdate&todate=$todate>◀ </a>";
      }
    for($i=$start_page; $i<=$end_page && $i<= $total_page; $i++) 
      {        // [1][2][3] 페이지 번호 목록 출력
        if($page==$i) // 현재 위치한 페이지는 링크 출력을 하지 않도록 설정.
           print "<font color=red><b>[$i]</b></font>"; 
        else 
           print "<a href=list.php?page=$i&search=$search&find=$find&list=1&process=$process&asprocess=$asprocess&fromdate=$fromdate&todate=$todate>[$i]</a>";
  }

      if($page<$total_page)
      {
        $next_page = $page + $page_scale;
        if($next_page > $total_page) 
            $next_page = $total_page;
        // netx_page 값이 전체 페이지수 보다 크면 맨 뒤 페이지로 이동시킴
        print "<a href=list.php?page=$next_page&search=$search&find=$find&list=1&process=$process&asprocess=$asprocess&fromdate=$fromdate&todate=$todate> ▶</a><p>";    // 페이지 이동할때 아래에 찍히는 ...1,2,3 등 태그처리
      }
 ?>			
        </div>
     </div>

<div id="write_button">
    <a href="list.php?&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&list=1&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>"><img src="../img/list.png"></a>&nbsp;
	<?php
   if(isset($_SESSION["userid"]))
   {
  ?>
   <a href="write_form.php"> <img src="../img/write.png"></a>
  <?php
   }
  ?>
      </div>
     </div>
	</form>
    </div> <!-- end of col2 -->
   </div> <!-- end of content -->
  </div> <!-- end of wrap -->
			<script>
			function blinker() {
				$('.blinking').fadeOut(500);
				$('.blinking').fadeIn(500);
			}
			setInterval(blinker, 1000);
			</script>
  </body>
  <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script>   
  $(function() {
     $( "#id_of_the_component" ).datepicker({ dateFormat: 'yy-mm-dd'}); 
});  
</script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>    

$(document).ready(function(){
    $("#click_check").change(function(){
        if($("#click_check").is(":checked")){
             $("#all_check").val("on");
        }else{
             $("#all_check").val("");
        }
    });
});






$(function () {
            $("#fromdate").datepicker({ dateFormat: 'yy-mm-dd'});
            $("#todate").datepicker({ dateFormat: 'yy-mm-dd'});
			
});

function pre_year(){   // 전년도 추출
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 

today = mm+'/'+dd+'/'+yyyy;
yyyy=yyyy-1;
frompreyear = yyyy+'-01-01';
topreyear = yyyy+'-12-31';	

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
}  

function pre_month(){
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();
if(dd<10) {
    dd='0'+dd;
} 

mm=mm-1;
if(mm<1) {
    mm='12';
} 
if(mm<10) {
    mm='0'+mm;
} 
if(mm>=12) {
    yyyy=yyyy-1;
} 

frompreyear = yyyy+'-'+mm+'-01';
topreyear = yyyy+'-'+mm+'-31';

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
} 

function this_year(){   // 당해년도
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 

today = mm+'/'+dd+'/'+yyyy;
frompreyear = yyyy+'-01-01';
topreyear = yyyy+'-12-31';	

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
fromdate1=frompreyear;
todate1=topreyear;
} 
function this_month(){   // 당해월
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd;
} 

if(mm<10) {
    mm='0'+mm;
} 

frompreyear = yyyy+'-'+mm+'-01';
topreyear = yyyy+'-'+mm+'-31';

    document.getElementById("fromdate").value = frompreyear;
    document.getElementById("todate").value = topreyear;
} 

$("#scale").change( function() {
	
// alert("목록수 변경 클릭");

process_list();

});

function process_list(){   // 목록표시수 클릭시

// document.getElementById('search').value=null; 

document.getElementById('board_form').submit();  // form의 검색버튼 누른 효과  

} 

  </script>

<?php
if($mode==""&&$fromdate==null)  
{
  echo ("<script language=javascript> this_year();</script>");  // 당해월 화면에 초기세팅하기
}

?>
<script> 

    //콤마찍기
    function comma(str) {
        str = String(str);
        return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
    }

  // 미수금합 구한값 화면에 출력하기
var total_sum = '<?php echo $total_sum ;?>';
$("#title_board2").text(comma(total_sum)+"원");

</script>  
  
  </html>