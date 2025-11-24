<?php
require_once __DIR__ . '/../common/functions.php';

// Environment detection
$isLocal = strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ||
           strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false ||
           strpos($_SERVER['HTTP_HOST'], '::1') !== false;
$baseUrl = $isLocal ? 'http://localhost' : 'http://8440.co.kr';

if(session_status() !== PHP_SESSION_ACTIVE) 
   session_start();

// Initialize session variables with null safety
$level = $_SESSION["level"] ?? null;
if(!isset($_SESSION["level"]) || $level > 5) {
    /*   alert("관리자 승인이 필요합니다."); */
    sleep(2);
    header("Location:" . $baseUrl . "/login/login_form.php");
    exit;
}
   
ini_set('display_errors','1');
  
$titlemessage = '원자재 기간별 수불보고서';
 ?>
   
 <?php include getDocumentRoot() . '/load_header.php' ?>
  
<title>  <?=$titlemessage?> </title> 

<style>
/* 모바일 최적화 스타일 */
@media (max-width: 768px) {
    /* 컨테이너 및 카드 최적화 */
    .container {
        padding: 0.75rem 0.5rem !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    .card {
        margin: 0.5rem auto !important;
        border-radius: 0.5rem !important;
        width: calc(100% - 1rem) !important;
        max-width: calc(100% - 1rem) !important;
        box-sizing: border-box !important;
    }
    
    .card-header {
        padding: 0.75rem 0.5rem !important;
    }
    
    .card-body {
        padding: 0.75rem 0.5rem !important;
    }
    
    /* 제목 최적화 */
    h5 {
        font-size: 1.1rem !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        text-align: center !important;
        margin-bottom: 0.75rem !important;
        padding: 0 0.5rem !important;
    }
    
    H5 {
        font-size: 1.1rem !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        text-align: center !important;
        margin-bottom: 0.75rem !important;
        padding: 0 0.5rem !important;
    }
    
    /* 버튼 최적화 */
    .btn {
        font-size: 0.875rem !important;
        padding: 0.5rem 0.75rem !important;
        white-space: nowrap !important;
        min-height: 40px !important;
        box-sizing: border-box !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }
    
    /* d-flex 컨테이너 안의 버튼은 자동 크기 */
    .d-flex .btn,
    .d-flex.justify-content-center .btn,
    .d-flex.align-items-center .btn,
    .flex-wrap .btn {
        width: auto !important;
        max-width: none !important;
        margin: 0.25rem !important;
        flex-shrink: 0 !important;
    }
    
    .btn-sm {
        font-size: 0.8rem !important;
        padding: 0.4rem 0.6rem !important;
        min-height: 36px !important;
    }
    
    /* flex-wrap이 있는 버튼 그룹 */
    .flex-wrap .btn {
        white-space: normal !important;
        word-wrap: break-word !important;
    }
    
    /* 기간 설정 UI 최적화 */
    .d-flex.justify-content-center {
        flex-wrap: wrap !important;
        gap: 0.5rem !important;
        justify-content: center !important;
        align-items: center !important;
    }
    
    .d-flex.justify-content-center > * {
        flex: 0 0 auto !important;
        width: auto !important;
        max-width: none !important;
    }
    
    /* setdate.php 기간 설정 최적화 */
    #showdate {
        width: 100% !important;
        max-width: 100% !important;
        margin-bottom: 0.5rem !important;
        box-sizing: border-box !important;
    }
    
    #showframe {
        width: calc(100% - 1rem) !important;
        max-width: calc(100% - 1rem) !important;
        margin: 0.5rem auto !important;
        box-sizing: border-box !important;
    }
    
    #showframe .card {
        width: 100% !important;
        max-width: 100% !important;
    }
    
    #showframe .card-body {
        padding: 0.5rem !important;
    }
    
    #showframe .card-body .d-flex {
        flex-wrap: wrap !important;
        gap: 0.25rem !important;
        justify-content: center !important;
    }
    
    #showframe .btn-sm {
        font-size: 0.75rem !important;
        padding: 0.35rem 0.5rem !important;
        flex: 1 1 auto !important;
        min-width: calc(50% - 0.125rem) !important;
        max-width: calc(50% - 0.125rem) !important;
        box-sizing: border-box !important;
    }
    
    /* 날짜 입력 필드 최적화 */
    input[type="date"] {
        width: 100% !important;
        max-width: 100% !important;
        font-size: 0.875rem !important;
        padding: 0.5rem !important;
        margin-bottom: 0.5rem !important;
        box-sizing: border-box !important;
    }
    
    /* 검색 입력 필드 최적화 */
    input[type="text"][id="search"],
    input[type="text"][name="search"] {
        width: 100% !important;
        max-width: 100% !important;
        font-size: 0.875rem !important;
        padding: 0.5rem !important;
        margin-bottom: 0.5rem !important;
        box-sizing: border-box !important;
    }
    
    /* 기간 설정 컨테이너 최적화 */
    .inputWrap {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* 날짜 구분자 최적화 */
    .date-separator,
    span:contains("~") {
        display: block !important;
        text-align: center !important;
        margin: 0.25rem 0 !important;
        font-size: 0.875rem !important;
    }
    
    /* 통계 정보 최적화 */
    .input-group-text {
        font-size: 0.8rem !important;
        padding: 0.4rem 0.5rem !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        line-height: 1.4 !important;
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* Grid 숨기기 및 카드 컨테이너 표시 */
    #grid {
        display: none !important;
    }
    
    #mobile-grid-cards {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        padding: 0 0.25rem !important;
    }
    
    .mobile-grid-card {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        margin: 0.5rem auto 0.75rem auto !important;
        padding: 0.75rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        width: calc(100% - 0.5rem) !important;
        max-width: calc(100% - 0.5rem) !important;
        overflow-x: hidden !important;
        overflow-y: visible !important;
        box-sizing: border-box !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .mobile-grid-card-item {
        display: flex;
        flex-direction: column;
        margin-bottom: 0.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #f0f0f0;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .mobile-grid-card-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .mobile-grid-card-label {
        font-weight: bold;
        font-size: 0.75rem;
        color: #666;
        margin-bottom: 0.25rem;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    .mobile-grid-card-value {
        font-size: 0.9rem;
        color: #333;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        padding-left: 0 !important;
        overflow: hidden !important;
    }
    
    /* 모바일 테이블 카드 스타일 */
    .mobile-table-cards {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        padding: 0 0.25rem !important;
    }
    
    .mobile-table-cards .mobile-grid-card {
        margin: 0.5rem auto 0.75rem auto !important;
        width: calc(100% - 0.5rem) !important;
        max-width: calc(100% - 0.5rem) !important;
    }
    
    /* 텍스트 오버플로우 방지 */
    * {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        box-sizing: border-box !important;
    }
    
    /* 카드 내부 모든 텍스트 요소 줄바꿈 강제 */
    .card *,
    .mobile-grid-card *,
    .mobile-table-cards * {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
        max-width: 100% !important;
    }
    
    /* span 요소 줄바꿈 처리 */
    span {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
        display: inline-block !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* 카드 내부 span 특별 처리 */
    .card span,
    .mobile-grid-card span,
    .mobile-table-cards span {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    /* 테이블 셀 내부 텍스트 줄바꿈 */
    table.table tbody tr td {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        word-break: break-word !important;
        white-space: normal !important;
    }
    
    /* 통계 텍스트 최적화 */
    h5 span.input-group-text {
        font-size: 0.8rem !important;
        padding: 0.75rem 0.5rem !important;
        line-height: 1.6 !important;
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        margin-bottom: 0.5rem !important;
        background-color: #f8f9fa !important;
        border-radius: 0.375rem !important;
        word-break: break-word !important;
    }
    
    h5 span.input-group-text.text-primary {
        background-color: #e7f3ff !important;
    }
    
    /* 통계 정보 컨테이너 최적화 */
    .d-flex.mt-1.mb-2.justify-content-center {
        flex-direction: column !important;
        align-items: stretch !important;
        padding: 0.5rem !important;
    }
    
    /* CSV 다운로드 버튼 최적화 */
    #downloadcsvBtn {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0.5rem 0 !important;
        box-sizing: border-box !important;
    }
    
    /* 버튼이 있는 컨테이너 최적화 */
    .d-flex.justify-content-center:has(#downloadcsvBtn),
    .d-flex.justify-content-center:has(button) {
        flex-wrap: wrap !important;
        gap: 0.5rem !important;
    }
    
    /* jQuery DataTable 숨기기 */
    .dataTables_length,
    .dataTables_filter {
        display: none !important;
    }
    
    /* Tabulator 숨기기 */
    .tabulator {
        display: none !important;
    }
    
    /* 일반 HTML 테이블을 카드 형식으로 변환 */
    table.dataTable,
    table.table {
        width: 100% !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
    }
    
    table.dataTable tbody,
    table.table tbody {
        display: block !important;
        width: 100% !important;
    }
    
    table.dataTable tbody tr,
    table.table tbody tr {
        display: block !important;
        width: calc(100% - 0.5rem) !important;
        max-width: calc(100% - 0.5rem) !important;
        margin: 0.5rem auto 0.75rem auto !important;
        background: #fff !important;
        border: 1px solid #ddd !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
        padding: 0.75rem !important;
        box-sizing: border-box !important;
    }
    
    table.dataTable tbody tr td,
    table.table tbody tr td {
        display: flex !important;
        width: 100% !important;
        padding: 0.5rem 0.4rem !important;
        text-align: left !important;
        border: none !important;
        border-bottom: 1px solid #f0f0f0 !important;
        box-sizing: border-box !important;
        flex-wrap: wrap !important;
        align-items: center !important;
    }
    
    table.dataTable tbody tr td:last-child,
    table.table tbody tr td:last-child {
        border-bottom: none !important;
    }
    
    table.dataTable thead,
    table.table thead {
        display: none !important;
    }
    
    table.dataTable tbody tr td::before,
    table.table tbody tr td::before {
        content: attr(data-label) !important;
        font-weight: bold !important;
        font-size: 0.75rem !important;
        color: #666 !important;
        margin-right: 0.5rem !important;
        min-width: 80px !important;
        flex-shrink: 0 !important;
    }
}

/* PC 화면 */
@media (min-width: 769px) {
    #mobile-grid-cards {
        display: none !important;
    }
    
    #grid {
        display: block !important;
    }
}

/* 모달 최적화 */
@media (max-width: 768px) {
    .modal-dialog {
        margin: 0.5rem !important;
        max-width: calc(100% - 1rem) !important;
    }
    
    .modal-dialog.modal-lg {
        margin: 0 !important;
        max-width: 100% !important;
    }
    
    .modal-content {
        border-radius: 0.5rem !important;
    }
    
    .modal-header {
        padding: 0.75rem 0.5rem !important;
        min-height: 50px !important;
        flex-wrap: wrap !important;
        gap: 0.25rem !important;
    }
    
    .modal-title {
        font-size: 1rem !important;
        flex: 1 1 auto !important;
        min-width: 0 !important;
        word-wrap: break-word !important;
    }
    
    .modal-header .btn-close {
        margin: 0 !important;
        padding: 0.5rem !important;
    }
    
    .modal-body {
        padding: 0.75rem 0.5rem !important;
        font-size: 0.9rem !important;
        max-width: 100% !important;
        overflow-x: hidden !important;
        box-sizing: border-box !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .modal-body .fs-3 {
        font-size: 1rem !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    .modal-footer {
        padding: 0.75rem 0.5rem !important;
        flex-wrap: wrap !important;
        gap: 0.25rem !important;
    }
    
    .modal-footer .btn {
        padding: 0.5rem 0.75rem !important;
        font-size: 0.875rem !important;
        min-height: 40px !important;
        flex: 1 1 auto !important;
        min-width: 0 !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        margin-bottom: 0.25rem !important;
    }
}
</style>

 </head>
<?php
// Initialize request variables with null safety
$search = $_REQUEST["search"] ?? null;
$separate_date = $_REQUEST["separate_date"] ?? null;
$display_sel = $_REQUEST["display_sel"] ?? 'doughnut';
$list = $_REQUEST["list"] ?? 0;
$find = $_REQUEST["find"] ?? null;	  
  require_once("../lib/mydb.php");
  $pdo = db_connect();	  

$mode = $_REQUEST["mode"] ?? "";     
   
  if($separate_date=="") $separate_date="2";
 
// 기간을 정하는 구간
$fromdate = $_REQUEST["fromdate"] ?? null;
$todate = $_REQUEST["todate"] ?? null;
$start = $_REQUEST["start"] ?? null;	   // 처음실행할때 당월 데이터를 출력하기 위함.
if($start=='start')  
	{
			$year=substr(date("Y-m-d",time()),0,4) ;
			$month=substr(date("Y-m-d",time()),5,2) ;			
			$fromdate=$year . "-" . $month . "-" . "01" ;			
			// print date("Y-m-d",time());
			$todate=$year . "-" . $month . "-" . "31" ;
			$Transtodate=strtotime($todate.'+1 days');
			$Transtodate=date("Y-m-d",$Transtodate);	
	}
	else
	{
		if($fromdate=="")
		{
			$fromdate=substr(date("Y-m-d",time()),0,4) ;
			$fromdate=$fromdate . "-01-01";
		}
		if($todate=="")
		{
			$todate=substr(date("Y-m-d",time()),0,4) . "-12-31" ;
			$Transtodate=strtotime($todate.'+1 days');
			$Transtodate=date("Y-m-d",$Transtodate);
		}
			else
			{
			$Transtodate=strtotime($todate);
			$Transtodate=date("Y-m-d",$Transtodate);
			}
	}		
  
$sql="select * from mirae8440.steelsource"; 					 // 자재DB에서 정보를 받아온다.

try{  
	$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  
   $counter=0;
   $steelsource_num=array();
   $steelsource_item=array();
   $steelsource_spec=array();
   $steelsource_take=array();   
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
	   $counter++;
	   
 			  $steelsource_num[$counter]=$row["num"];			  
 			  $steelsource_item[$counter]=$row["item"];
 			  $steelsource_spec[$counter]=$row["spec"];
		      $steelsource_take[$counter]=$row["take"];   
	
	 } 	 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

$SettingDate="indate ";  //입고자재 배열 누적
$common="   where (outdate between date('$fromdate') and date('$Transtodate')) and (which='1') " ;  // 입고자재
 // 전체합계(입고부분)를 산출하는 부분 
$input_sum_title=array(); 
$input_sum=array();

$sql="select * from mirae8440.steel " .$common; 	
 
 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
              $num=$row["num"];			  
			  $item=$row["item"];			  
			  $spec=$row["spec"];
			  $steelnum=$row["steelnum"];			  
			  $company=$row["company"];
			  $comment=$row["comment"];
			  $which=$row["which"];	 	
			  
			  $tmp=$item . $spec;
	
        for($i=1;$i<=$rowNum;$i++) {			 			  

	          $input_sum_title[$i]=$steelsource_item[$i] . $steelsource_spec[$i];
			  if($which=='1' and $tmp==$input_sum_title[$i])
				    $input_sum[$i]=$sum[$i] + (int)$steelnum;		// 입고숫자 더해주기 합계표	
     // $sum[$i]=(float)-1;				
		           } 

			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  


 // 전체합계(출고부분)를 처리하는 부분 

$SettingDate="outdate ";  //입고자재 배열 누적
$common="   where (outdate between date('$fromdate') and date('$Transtodate')) and (which='2') " ; 	 // 출고자재
$output_sum_title=array(); 
$output_sum=array();
 
	 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

              $num=$row["num"];
			  
			  $item=$row["item"];			  
			  $spec=$row["spec"];
			  $steelnum=$row["steelnum"];			  
			  $company=$row["company"];
			  $comment=$row["comment"];
			  $which=$row["which"];	 	
			  
			  $tmp=$item . $spec;
	
        for($i=1;$i<=$rowNum;$i++) {
			 			  
 			  
	          $output_sum_title[$i]=$steelsource_item[$i] . $steelsource_spec[$i];
			  if($which=='2' and $tmp==$output_sum_title[$i])
				    $output_sum[$i]=$sum[$i] - (int)$steelnum;			
		           }		  

			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  

  // outdate는 원자재의 입출고일을 말한다. 
  if($mode=="search"){
		  if($search==""){
							 $sql="select * from mirae8440.steel where (outdate between date('$fromdate') and date('$Transtodate')) and (which='$separate_date')  " . $a; 					
	                       			
			     }
			 elseif($search!=""&&$find!="all")  { // 각 필드별로 검색어가 있는지 쿼리주는 부분						
							  $sql ="select * from mirae8440.steel where ($find like '%$search%') ";
							  $sql .=" and (outdate between date('$fromdate') and date('$Transtodate')) and (which='$separate_date')  ";

						}	   
				   
            elseif($search!=""&&$find=="all") { // 각 필드별로 검색어가 있는지 쿼리주는 부분						
							  $sql ="select * from mirae8440.steel where ((outdate like '%$search%')  or (outworkplace like '%$search%') ";
							  $sql .="or (item like '%$search%') or (spec like '%$search%') or (company like '%$search%') or (model like '%$search%')  or (comment like '%$search%')) and (outdate between date('$fromdate') and date('$Transtodate')) and (which='$separate_date')  ";

						}

               }
  if($mode=="") {
							 $sql="select * from mirae8440.steel where (outdate between date('$fromdate') and date('$Transtodate')) and (which='$separate_date')  "; 				
					
                }	
?>
		 
<body >

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog  modal-lg modal-center" >
    
      <!-- Modal content-->
      <div class="modal-content modal-lg">
        <div class="modal-header">          
			<h4 class="modal-title"> 부족(마이너스) 상태 알림 </h4>
        </div>
        <div class="modal-body">		
           <div class="row gx-4 gx-lg-4 align-items-center">		  
				   <br>
				   <div id="alertmsg" class="fs-3" > </div> <br>
				  <br>		  									
				</div>
			</div>		  
        <div class="modal-footer">
          <button id="closeModalBtn" type="button" class="btn btn-default btn-sm " data-dismiss="modal">닫기</button>
        </div>
		</div>
		</div>
	</div>      

<form name="board_form" id="board_form" method="post" action="list_materialinout.php?mode=search&search=<?=$search?>&find=<?=$find?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&up_fromdate=<?=$up_fromdate?>&up_todate=<?=$up_todate?>&separate_date=<?=$separate_date?>&view_table=<?=$view_table?>&display_sel=<?=$display_sel?>">  

<div class="container">
<div class="card">
<div class="card-body">  

<div class="card">
<div class="card-body">  
 	<div class="d-flex mb-3 mt-4 justify-content-center align-items-center"> 		 
		<H5>
			 <?=$titlemessage?>
		</H5>		 
	</div>	
    
	<div class="row"> 		  
		<div class="d-flex mt-1 mb-2 justify-content-center align-items-center flex-wrap"> 		
		<!-- 기간설정 칸 -->
		 <div class="w-100 mb-2 mb-md-0" style="flex: 1 1 auto; min-width: 0;">
		 <?php include getDocumentRoot() . '/setdate.php' ?>
		 </div>
		 <div class="w-100 w-md-auto">
		 <button  type="button" class="btn btn-danger btn-sm w-100" id="downloadcsvBtn"> CSV파일 다운로드 </button>
		 </div>
		</div>
	</div>	  
					  
		<div class="d-flex mt-1 mb-2 justify-content-center align-items-center "> 					  
		<?php
		//  입고물량 누계				
		$sql="select * from mirae8440.steel where (outdate between date('$fromdate') and date('$Transtodate')) and (which='1')  ";  // 입고자재통계					
		$output_item_arr = array();	
		$output_weight_arr = array();	
		$input_make_name = array();	 // 입고물량의 임시이름 키값으로 사용함.
		$input_item = array();	 // 입고물량 배열
		$input_spec = array();	 // 입고물량 배열
		$input_arr_num = array();	 // 입고물량 배열
		$output_arr_num = array();	 // 출고물량 배열
		$sum_arr = array();	
		$sum = array();	
		$temp_arr = array();	
		$count=0;  

		$total=0;
		try{  
		$stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
		$total_row=$stmh->rowCount();	  

		   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			  $num=$row["num"];
			  $outdate=$row["outdate"];			
			  $indate=$row["indate"];
			  $outworkplace=$row["outworkplace"];
			  $item=$row["item"];			  
			  $spec=$row["spec"];
			  $steelnum=$row["steelnum"];			  
			  $company=$row["company"];
			  $comment=$row["comment"];
			  $which=$row["which"];	 	
			  $model=$row["model"];	 	
			  $temp_arr = explode("*", $spec);		
			  // 키값을 이름조합으로 만듬
			  $tmpName = $item . $spec;  // 재질 + 규격으로 고유이름을 만듬.

			 $found = 0;
			 for($i=0;$i<=$count;$i++)	{  // 값은 재질,규격이 있으면 누적함. 아니면 별도로 담음
					if($tmpName==$input_make_name[$i]) {
						$input_arr_num[$i] += (int) $steelnum;  // 같을때 수량 누적
						$found = 1;
						break;
						}
				  }							  
			 if(!$found) {  // 찾지 못했을때
						array_push( $input_make_name , $tmpName );
						array_push( $input_item , $item );
						array_push( $input_spec , $spec );
						array_push( $input_arr_num ,(int)$steelnum );				
						array_push( $output_arr_num , '0' );				
						array_push( $sum , '0' );													
						$total++;							  
				  }
														 
			  
			  $output_weight_arr[$count]=floor(($temp_arr[0] * $temp_arr[1] * $temp_arr[2] * 7.93 * (int)$steelnum)/1000000) ; //편의상 비중은 7.93으로 함. 별차이 없음
			  
							switch ($item) {
								case   "304 HL"     :   $output_item_arr[0] += $output_weight_arr[$count]; break;
								case   "304 MR"     :   $output_item_arr[1] += $output_weight_arr[$count]; break;	

								case   "PO"     :   $output_item_arr[3] += $output_weight_arr[$count]; break;	
								case   "EGI"     :   $output_item_arr[4] += $output_weight_arr[$count]; break;
								case   "CR"     :   $output_item_arr[5] += $output_weight_arr[$count]; break;									
								default:  $output_item_arr[2] += $output_weight_arr[$count];break;	
							}	
						  $count++;		
						  $start_num--;  
						 } 
			  } catch (PDOException $Exception) {
			  print "오류: ".$Exception->getMessage();
			  }  
			  
			  print "<br>";
			  print " <h5 span class='input-group-text'> 입고물량 : 304 HL " . number_format($output_item_arr[0]) . "KG, &nbsp;&nbsp;   ";
			  print "  304 MR " . number_format($output_item_arr[1]) . "KG, &nbsp;&nbsp;   " ; 
			  print "  기타SUS " . number_format($output_item_arr[2]) . "KG,  &nbsp;&nbsp;  " ; 
			  print " PO " . number_format($output_item_arr[3]) . "KG, &nbsp;&nbsp;    " ; 
			  print "  EGI " . number_format($output_item_arr[4]) . "KG, &nbsp;&nbsp;   " ; 
			  print " CR " . number_format($output_item_arr[5]) . "KG </h5> <br> </div>" ; 

                //  print "total input : " . $total . "EA" ;
				// print_r($input_arr_num);

				 //  출고물량 누계				
				$sql="select * from mirae8440.steel where (outdate between date('$fromdate') and date('$Transtodate')) and (which='2')  ";  // 출고자재통계					
				$$output_arr_num = array();	
				$output_item_arr = array();	
				$output_weight_arr = array();	
				$input_arr = array();	
				$material = array();	
				$grid_item = array();	
				$count=0;   
				 try{  
				  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
				  $total_row=$stmh->rowCount();	  
					
					   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
						  $num=$row["num"];
						  $outdate=$row["outdate"];			
						  $indate=$row["indate"];
						  $outworkplace=$row["outworkplace"];
						  $item=$row["item"];			  
						  $spec=$row["spec"];
						  $steelnum=$row["steelnum"];			  
						  $company=$row["company"];
						  $comment=$row["comment"];
						  $which=$row["which"];	 	
						  $model=$row["model"];	 	
						  $temp_arr = explode("*", $spec);	
						  $tmpName = $item . $spec;  // 재질 + 규격으로 고유이름을 만듬.						  

							$found = 0;
							for($i=0;$i<=$total;$i++)	{  // 값은 재질,규격이 있으면 누적함. 아니면 별도로 담음
									if(trim($tmpName)==trim($input_make_name[$i]))
										{
										// print $total . " match total : ". $tmpName . "   " . $input_make_name[$i] . "<br>" ;
										$output_arr_num[$i] += (int) $steelnum;  // 같을때 수량 누적
										$found = 1;
										break;
										}
							     }		
							if(!$found) {  // 찾지 못했을때
										// print $total . " wrong match total : ". $tmpName . "   " . $input_make_name[$i] . "<br>" ;
										array_push( $input_make_name , $tmpName );
										array_push( $input_item , $item );
										array_push( $input_spec , $spec );
										array_push( $input_arr_num , '0' );				
										array_push( $output_arr_num , $steelnum );				
										$total++;										
								  }
							 							  
				  
				  
								   $output_weight_arr[$count]=floor(($temp_arr[0] * $temp_arr[1] * $temp_arr[2] * 7.93 * (int)$steelnum)/1000000) ;
						  
										switch ($item) {
											case   "304 HL"     :   $output_item_arr[0] += $output_weight_arr[$count]; break;
											case   "304 MR"     :   $output_item_arr[1] += $output_weight_arr[$count]; break;	

											case   "PO"     :   $output_item_arr[3] += $output_weight_arr[$count]; break;	
											case   "EGI"     :   $output_item_arr[4] += $output_weight_arr[$count]; break;
											case   "CR"     :   $output_item_arr[5] += $output_weight_arr[$count]; break;									
											default:  $output_item_arr[2] += $output_weight_arr[$count];break;	
										}	
								
								$count++;	// 출고 자료 카운트
							 } 
						  } catch (PDOException $Exception) {
						  print "오류: ".$Exception->getMessage();
						  }  
						  
						  print ' <div class="d-flex mt-1 mb-2 justify-content-center align-items-center "> 	';
						  print " <h5 span class='input-group-text text-primary'> 출고물량 : 304 HL " . number_format($output_item_arr[0]) . "KG, &nbsp;&nbsp;   ";
						  print "  304 MR " . number_format($output_item_arr[1]) . "KG, &nbsp;&nbsp;   " ; 
						  print "  기타SUS " . number_format($output_item_arr[2]) . "KG,  &nbsp;&nbsp;  " ; 
						  print " PO " . number_format($output_item_arr[3]) . "KG, &nbsp;&nbsp;    " ; 
						  print "  EGI " . number_format($output_item_arr[4]) . "KG, &nbsp;&nbsp;   " ; 
						  print " CR " . number_format($output_item_arr[5]) . "KG </h5> <br> " ; 

                      // print "출고 총 아이템수량 : " . $total . "매" ;
                      // print "출고 총 아이템수량 count 배열 : " . count($input_make_name) . "매" ;
                       
					   $totalinput = 0 ;
					   $totaloutput = 0 ;
                       for($i=0;$i<=count($input_make_name);$i++)	{  // 값은 재질,규격이 있으면 누적함. 아니면 별도로 담음
								$material[$i] = $input_item[$i];	
								$grid_item[$i] = $input_spec[$i];	
								$input_num[$i] = $input_arr_num[$i];	
								$output_num[$i] =$output_arr_num[$i];	
								$sum[$i] = $input_arr_num[$i] -$output_arr_num[$i];
								$totalinput += $input_num[$i];
								$totaloutput += $output_num[$i];
						 }	
                       // print "총입고수량 합계 : " . $totalinput . "<br>";
                       // print "총출고수량 합계 : " . $totaloutput . "<br>";

			 ?>	                
	</div>       
	</div>  	
	</div>  	
	</div>  	
	<div class="card">
	<div class="card-body">  
		<style>
		
		 #grid {
			 width : 1000px;
		 }
		 </style>    
		 <div class="d-flex mt-1 mb-2 justify-content-center align-items-center "> 	
			<div id="grid" > 	
			</div>
			<div id="mobile-grid-cards" style="display: none; width: 100%;"></div>
		</div>	
	</div>	
	</div>	
	
  </div>
</form>

<script>
$(document).ready(function(){
	// this_month();

			 $("#downloadcsvBtn").click(function(){  
               Do_gridexport();			   
			 });	


			 class CustomTextEditor {
			  constructor(props) {
				const el = document.createElement('input');
				const { maxsource_take } = props.columnInfo.editor.options;

				el.type = 'text';
				el.maxsource_take = maxsource_take;
				el.value = String(props.value);

				this.el = el;
			  }

			  getElement() {
				return this.el;
			  }

			  getValue() {
				return this.el.value;
			  }

			  mounted() {
				this.el.select();
			  }
			}	  
								  
			var count = "<? echo $total; ?>"; 
			var material = <?php echo json_encode($material);?> ;	
			var grid_item = <?php echo json_encode($grid_item);?> ;	
			var input_num = <?php echo json_encode($input_num);?> ;	
			var output_num = <?php echo json_encode($output_num);?> ;	
			var sum = <?php echo json_encode($sum);?> ;	
			
			let row_count = count;
			const COL_COUNT = 5;
			
			const data = [];
			const columns = [];
			
		  if(count>0) {
			for (let i = 0; i < row_count; i += 1) {
			  const row = { name: i };
			  for (let j = 0; j < COL_COUNT; j += 1) {
				row[`material`] = material[i] ;	                   				
				row[`grid_item`] = grid_item[i] ;	                   				
				row['input_num'] = input_num[i] ;
				row['output_num'] = output_num[i] ;
				row['sum'] = sum[i] ;

			  }
				data.push(row);
			}		 
		  
		      // 모바일 여부 확인
		      var isMobile = window.innerWidth <= 768;
		      var bodyHeight = isMobile ? 400 : 450;
		      
		      // grid를 전역 변수로 선언
		      window.grid = new tui.Grid({
			  el: document.getElementById('grid'),
			  data: data,
			  bodyHeight: bodyHeight,
			   columns: [ 				   
				{
				  header: '재질구분',
				  name: 'material',
				  sortingType: 'desc',
				  sortable: true,
				  width:230,
				  editor: {
					type: CustomTextEditor,
					options: {
					  maxsource_take: 40
					}
				  },	 		
				  align: 'center'
				},
				{
				  header: '규격',
				  name: 'grid_item',
				  sortingType: 'desc',
				  sortable: true,
				  width:200,
				  editor: {
					type: CustomTextEditor,
					options: {
					  maxsource_take: 40
					}
				  },	 		
				  align: 'center'
				},						
				{
				  header: '입고수량',
				  name: 'input_num',
				  width:150,						  
				  editor: {
					type: CustomTextEditor,
					options: {
					  maxsource_take: 10
					} 			
				  }	,  
					align: 'center'
				  // sortingType: 'desc',
				  // sortable: true,          
				  // editingEvent :  'Click'		  
				},
				{
				  header: '출고수량',
				  name: 'output_num',
				  width:150,
				  // sortingType: 'desc',
				  // sortable: true,
				  editor: {
					type: CustomTextEditor,
					options: {
					  maxsource_take: 10
					}
				  }	, 		  
				  align: 'center'
				},
				{
				  header: '수불합계(잔량파악)',
				  name: 'sum',
				  width:150,
				  // sortingType: 'desc',
				  // sortable: true,
				  editor: {
					type: CustomTextEditor,
					options: {
					  maxsource_take: 10
					}
				  }	, 		  
				  align: 'center'
				}							
			  ],
			 columnOptions: {
					resizable: true
				  },
			  // pageOptions: {
				// useClient: false,
				// perPage: 20
			  // },					  
		
		});	
		
// grid 색상등 꾸미기
	var Grid = tui.Grid; // or require('tui-grid')
	Grid.applyTheme('default', {
			selection: {
				background: '#e6eef5',
				border: '#fdfcfc'
			  },
			  scrollbar: {
				background: '#e6eef5',
				thumb: '#d9d9d9',
				active: '#c1c1c1'
			  },
			  row: {
				hover: {
				  background: '#ccc'
				}
			  },
			  cell: {
				normal: {
				  background: '#fbfbfb',
				  border: '#e6eef5',
				  showVerticalBorder: true
				},
				header: {
				  background: '#e6eef5',
				  border: '#fdfcfc',
				  showVerticalBorder: true
				},
				rowHeader: {
				  border: '#e6eef5',
				  showVerticalBorder: true
				},
				editable: {
				  background: '#fbfbfb'
				},
				selectedHeader: {
				  background: '#e6eef5'
				},
				focused: {
				  border: '#e6eef5'
				},
				disabled: {
				  text: '#e6eef5'
				}
			  }	
	});
	
	// 모바일 카드 렌더링 함수
	function renderMobileGridCards() {
		if (!window.grid) return;
		
		var isMobile = window.innerWidth <= 768;
		var cardsContainer = document.getElementById('mobile-grid-cards');
		var gridContainer = document.getElementById('grid');
		
		if (!isMobile) {
			// PC 화면: Grid 표시, 카드 숨김
			if (gridContainer) {
				gridContainer.style.display = '';
			}
			if (cardsContainer) {
				cardsContainer.style.display = 'none';
			}
			return;
		}
		
		// 모바일 화면: Grid 숨김, 카드 표시
		if (gridContainer) {
			gridContainer.style.display = 'none';
		}
		if (!cardsContainer) return;
		
		cardsContainer.style.display = 'block';
		cardsContainer.innerHTML = '';
		
		try {
			var gridData = window.grid.getData();
			
			if (!gridData || gridData.length === 0) {
				cardsContainer.innerHTML = '<div class="text-center py-4 text-muted">데이터가 없습니다.</div>';
				return;
			}
			
			// 컬럼 매핑
			var columnMap = [
				{ name: 'material', label: '재질구분' },
				{ name: 'grid_item', label: '규격' },
				{ name: 'input_num', label: '입고수량' },
				{ name: 'output_num', label: '출고수량' },
				{ name: 'sum', label: '수불합계(잔량파악)' }
			];
			
			gridData.forEach(function(rowData, index) {
				var card = document.createElement('div');
				card.className = 'mobile-grid-card';
				
				var cardHtml = '';
				
				columnMap.forEach(function(colInfo) {
					var value = rowData[colInfo.name];
					if (value === null || value === undefined || value === '') {
						value = '0';
					}
					
					var displayValue = value;
					
					cardHtml += '<div class="mobile-grid-card-item">';
					cardHtml += '<div class="mobile-grid-card-label">' + colInfo.label + '</div>';
					cardHtml += '<div class="mobile-grid-card-value">' + displayValue + '</div>';
					cardHtml += '</div>';
				});
				
				if (cardHtml === '') {
					cardHtml = '<div class="text-muted">데이터 없음</div>';
				}
				
				card.innerHTML = cardHtml;
				cardsContainer.appendChild(card);
			});
		} catch (error) {
			console.error('모바일 카드 렌더링 오류:', error);
			cardsContainer.innerHTML = '<div class="text-center py-4 text-danger">데이터를 불러오는 중 오류가 발생했습니다.</div>';
		}
	}
	
	// 화면 크기 변경 시 카드/그리드 전환
	function updateGridDisplay() {
		renderMobileGridCards();
	}
	
	// Grid 렌더링 완료 후 모바일 카드 렌더링
	setTimeout(function() {
		updateGridDisplay();
	}, 300);
	
	// 리사이즈 이벤트
	window.addEventListener('resize', function() {
		updateGridDisplay();
	});
	
	// 페이지 로드 완료 후에도 한 번 더 확인
	window.addEventListener('load', function() {
		setTimeout(function() {
			updateGridDisplay();
		}, 500);
	});
	
	// jQuery DataTable이 있는 경우 모바일에서 숨기기 및 카드 변환
	function handleDataTables() {
		if (window.innerWidth <= 768) {
			// Show entries와 Live Search 숨기기
			$('.dataTables_length, .dataTables_filter').hide();
			
			// DataTable이 있는 경우 카드 형식으로 변환
			if ($.fn.DataTable && $('.dataTable').length > 0) {
				$('.dataTable').each(function() {
					var table = $(this);
					var tableId = table.attr('id') || 'datatable-' + Math.random().toString(36).substr(2, 9);
					var cardsContainerId = 'mobile-cards-' + tableId;
					
					// 이미 카드 컨테이너가 있으면 스킵
					if ($('#' + cardsContainerId).length > 0) return;
					
					// 테이블 숨기기
					table.hide();
					
					// 카드 컨테이너 생성
					var cardsContainer = $('<div id="' + cardsContainerId + '" class="mobile-table-cards"></div>');
					table.after(cardsContainer);
					
					// 테이블 데이터를 카드로 변환
					table.find('tbody tr').each(function() {
						var row = $(this);
						var card = $('<div class="mobile-grid-card"></div>');
						
						row.find('td').each(function(index) {
							var cell = $(this);
							var header = table.find('thead th').eq(index).text() || '항목 ' + (index + 1);
							var value = cell.text().trim();
							
							if (value) {
								var cardItem = $('<div class="mobile-grid-card-item"></div>');
								cardItem.append('<div class="mobile-grid-card-label">' + header + '</div>');
								cardItem.append('<div class="mobile-grid-card-value">' + value + '</div>');
								card.append(cardItem);
							}
						});
						
						if (card.children().length > 0) {
							cardsContainer.append(card);
						}
					});
				});
			}
		} else {
			// PC 화면: Show entries와 Live Search 표시
			$('.dataTables_length, .dataTables_filter').show();
			$('.mobile-table-cards').remove();
			$('.dataTable').show();
		}
	}
	
	// Tabulator가 있는 경우 모바일에서 카드 변환
	function handleTabulator() {
		if (window.innerWidth <= 768) {
			$('.tabulator').each(function() {
				var tabulatorEl = $(this);
				var tabulatorId = tabulatorEl.attr('id') || 'tabulator-' + Math.random().toString(36).substr(2, 9);
				var cardsContainerId = 'mobile-cards-' + tabulatorId;
				
				// 이미 카드 컨테이너가 있으면 스킵
				if ($('#' + cardsContainerId).length > 0) return;
				
				// Tabulator 숨기기
				tabulatorEl.hide();
				
				// 카드 컨테이너 생성
				var cardsContainer = $('<div id="' + cardsContainerId + '" class="mobile-table-cards"></div>');
				tabulatorEl.after(cardsContainer);
				
				// Tabulator 데이터 가져오기
				try {
					var table = tabulatorEl[0].tabulator;
					if (table) {
						var data = table.getData();
						var columns = table.getColumns();
						
						data.forEach(function(rowData) {
							var card = $('<div class="mobile-grid-card"></div>');
							
							columns.forEach(function(column) {
								var field = column.getField();
								var title = column.getDefinition().title || field;
								var value = rowData[field];
								
								if (value !== null && value !== undefined && value !== '') {
									var cardItem = $('<div class="mobile-grid-card-item"></div>');
									cardItem.append('<div class="mobile-grid-card-label">' + title + '</div>');
									cardItem.append('<div class="mobile-grid-card-value">' + value + '</div>');
									card.append(cardItem);
								}
							});
							
							if (card.children().length > 0) {
								cardsContainer.append(card);
							}
						});
					}
				} catch (error) {
					console.error('Tabulator 카드 변환 오류:', error);
				}
			});
		} else {
			// PC 화면: Tabulator 표시
			$('.tabulator').show();
			$('.mobile-table-cards').remove();
		}
	}
	
	// 일반 HTML 테이블을 카드 형식으로 변환
	function handleHtmlTables() {
		if (window.innerWidth <= 768) {
			$('table:not(.dataTable):not(.tabulator-table)').each(function() {
				var table = $(this);
				var tableId = table.attr('id') || 'table-' + Math.random().toString(36).substr(2, 9);
				var cardsContainerId = 'mobile-cards-' + tableId;
				
				// 이미 카드 컨테이너가 있으면 스킵
				if ($('#' + cardsContainerId).length > 0) return;
				
				// 테이블 숨기기
				table.hide();
				
				// 카드 컨테이너 생성
				var cardsContainer = $('<div id="' + cardsContainerId + '" class="mobile-table-cards"></div>');
				table.after(cardsContainer);
				
				// 헤더 정보 수집
				var headers = [];
				table.find('thead th, thead td').each(function() {
					headers.push($(this).text().trim());
				});
				
				// 테이블 데이터를 카드로 변환
				table.find('tbody tr').each(function() {
					var row = $(this);
					var card = $('<div class="mobile-grid-card"></div>');
					
					row.find('td').each(function(index) {
						var cell = $(this);
						var header = headers[index] || '항목 ' + (index + 1);
						var value = cell.text().trim();
						
						if (value) {
							var cardItem = $('<div class="mobile-grid-card-item"></div>');
							cardItem.append('<div class="mobile-grid-card-label">' + header + '</div>');
							cardItem.append('<div class="mobile-grid-card-value">' + value + '</div>');
							card.append(cardItem);
						}
					});
					
					if (card.children().length > 0) {
						cardsContainer.append(card);
					}
				});
			});
		} else {
			// PC 화면: 테이블 표시
			$('table:not(.dataTable):not(.tabulator-table)').show();
			$('.mobile-table-cards').remove();
		}
	}
	
	// 모든 테이블 처리 함수 통합
	function handleAllTables() {
		handleDataTables();
		handleTabulator();
		handleHtmlTables();
	}
	
	// 초기 로드 및 리사이즈 이벤트
	$(document).ready(function() {
		setTimeout(function() {
			handleAllTables();
		}, 500);
	});
	
	$(window).on('resize', function() {
		setTimeout(function() {
			handleAllTables();
		}, 100);
	});
		
		
		
		
		function Do_gridexport() { 	
		
		    const data = window.grid ? window.grid.getData() : [];
				console.log(data);			
				console.log(data.length);

			let csvContent = "data:text/csv;charset=utf-8,\uFEFF";   // 한글파일은 뒤에,\uFEFF  추가해서 해결함.
			
			let row_count = count;
			const COL_COUNT = 5;						
			
			for (let i = 0; i < row_count; i += 1) {
			  let row = "";			  
			   row += (window.grid ? window.grid.getValue(i, 'material') : material[i] || '') + ',' ;
			   row += (window.grid ? window.grid.getValue(i, 'grid_item') : grid_item[i] || '') + ',' ;
			   row += (window.grid ? window.grid.getValue(i, 'input_num') : input_num[i] || '') + ',' ;
			   row += (window.grid ? window.grid.getValue(i, 'output_num') : output_num[i] || '') + ',' ;
			   row += (window.grid ? window.grid.getValue(i, 'sum') : sum[i] || '') + ',' ;
			   
			   csvContent += row + "\r\n";
			}		 		  

			// data.forEach(function(rowArray) {
				// let row = rowArray.join(",");
				// csvContent += row + "\r\n";
			// });
			
			// let csvContent = "data:text/csv;charset=utf-8,\uFEFF" + data.map(e => e.join(",")).join("\n");  // 간결한 표현식
			
			var encodedUri = encodeURI(csvContent);
			var link = document.createElement("a");
			link.setAttribute("href", encodedUri);
			link.setAttribute("download", "steel_InOut_report.csv");
			document.body.appendChild(link); 
			link.click();

			}    //csv 파일 export		
    } // end of grid	 count>0 구문			
});

</script>    
</div>
</body>
</html>