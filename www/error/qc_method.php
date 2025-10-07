<?php require_once(includePath('session.php')); ?> 
   
<?php include getDocumentRoot() . '/load_header.php' ?>
		
<title> 품질불량 관리기법/교육 </title> <div id="cookiedisplay"> </div>
	    
</head>			
<?php include getDocumentRoot() . '/myheader.php'; ?>  

<?php   
if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {          
		  $_SESSION["url"]=$_SESSION["WebSite"] . 'error/index.php?user_name=' . $user_name; 	
         header("Location:".$_SESSION["WebSite"]."login/login_form.php"); 
         exit;
   }      
  
if($user_name=='소현철' ||$user_name=='김보곤' ||$user_name=='최장중' ||$user_name=='이경묵')
  $admin = 1;

$tablename = 'error';

require_once("../lib/mydb.php");
$pdo = db_connect();

// 서버의 정보를 읽어와 랜덤으로 메인화면 꾸미기
 				
$sql="select * from {$DB}.error order by num desc " ; 					

$numarr = array();

try{  
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
              include "_row.php";
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  
 
// 랜던하게 유튜브 주소자료 추출 

$youtube_arr = array();

array_push($youtube_arr, "https://www.youtube.com/embed/VPwhUEc84pg");
array_push($youtube_arr, "https://www.youtube.com/embed/NcFf9JhcHDQ");
array_push($youtube_arr, "https://www.youtube.com/embed/aXB5XNmG-TE");
array_push($youtube_arr, "https://www.youtube.com/embed/5ulG8-brBng");

$youtubeURL = $youtube_arr[rand(0,count($youtube_arr)-1)];  
    	
?>


<form name="board_form" id="board_form"  method="post"  >  
<div class="container">

 <div class="card">			
	  <div class="card-body">   
            <div class="row">
				<div class="col-sm-9">
				 <div class="card">			
					<div class="card-body">   				
							<div class="d-flex justify-content-center mt-2 mb-2">
								<!-- 품질불량 관리기법-->
								<div id="Materialshow" class="mb-5" >
									<h3 class="text-center text-primary"> 품질불량 관리기법 </h3>
								</div>											
							</div>                    	
						   <div class="d-flex justify-content-center mt-2 mb-2">
							<div id="Material" >
								<section class="page-section" >
									<div class="container">
																	
										<div class="row text-center">
										 <?php include '8d.php'; ?>
										</div>
										<div class="row text-left">
										 <?php include 'fmea.php'; ?>
										<img src="../img/qm1.jpg">
										<img src="../img/qm2.jpg">
										<img src="../img/qm3.jpg">
										<img src="../img/qm4.jpg">
										<img src="../img/qm5.jpg">
										<img src="../img/qm6.jpg">

										 
										</div>
									</div>
								</section>		
							</div>	
							</div>	
				<div class="d-flex px-1 px-lg-1 mt-1 justify-content-center">
					<h5 class="mb-1 text-secondary"> 미래기업 직원여러분의 지속적 관심/분석/개선이 불량감소에 큰 도움이 됩니다. </h5>			
					</div>										        
				</div>	
				</div>	
				</div>	
				<div class="col-sm-3">	
				 <div class="card">			
					<div class="card-body">   
					<!-- youtube section-->    
						 <div class="d-flex mt-1 mb-1 justify-content-center">
							<h5> 품질관련 교육영상 </h5>
						 </div>
						 <div class="d-flex mt-1 mb-2 justify-content-center">
							<div class="embed-responsive embed-responsive-16by9">
							  <iframe class="embed-responsive-item" src="<?=$youtubeURL?>"  frameborder="0" allowfullscreen></iframe>
							 </div>      
						</div>					
						</div>					
					</div>					
				</div>					        
			</div>			
			</div>	
			</div>	
			</div>	
<div class="container-fluid">
 <?php

  if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	 $search=$_REQUEST["search"];

  require_once("../lib/mydb.php");
  $pdo = db_connect();	
  
	 
if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     
 
 // 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	 

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
 				
if($mode=="search" || $mode==""){
	if($search==""){
		$sql="select * from {$DB}.error order by num desc " ; 									
			 }
	if($search=="결재상신 1차결재"){
		$sql="select * from {$DB}.error where approve = '결재상신' or  approve = '1차결재'  order by num desc  " ; 									
		$search = null;
			 }
	elseif($search!="") {
							  $sql ="select * from {$DB}.error where (reporter like '%$search%') or (place like '%$search%')  or (content like '%$search%')  or (method like '%$search%')  or (involved like '%$search%')  or (approve like '%$search%') ";
							  $sql .=" order by   occur desc  ";									  
					}				
}  
					
try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			include "_row.php"; 				  
		}		 
	 } catch (PDOException $Exception) {
		print "오류: ".$Exception->getMessage();
	}  
  						 
	 ?> 
     		    
	<input id="view_table" name="view_table" type='hidden' value='<?=$view_table?>' >
	<input type="hidden" id="voc_alert" name="voc_alert" value="<?=$voc_alert?>" size="5" > 	
	<input type="hidden" id="ma_alert" name="ma_alert" value="<?=$ma_alert?>" size="5" > 
	<input type="hidden" id="order_alert" name="order_alert" value="<?=$order_alert?>" size="5" > 								

		
<!-- Footer-->
<? include "footer.php" ?>  		
</div>
 
</form>

</body>
</html>

<script>
var dataTable; // DataTables 인스턴스 전역 변수
var errorpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

// 서버에 작업 기록
$(document).ready(function(){
	saveLogData('품질불량 관리기법'); 
});

</script> 
</body>
</html>


