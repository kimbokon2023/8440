<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

 $mcno=$_REQUEST["mcname"];
    
  // 첫 화면 표시 문구
 $title_message = '사무실 정리정돈 캠페인';     
   
   ?>
   
<?php include getDocumentRoot() . '/load_header.php' ?>

 
<title> <?=$title_message?> </title>  
 
</head> 

<body>

<?php include getDocumentRoot() . "/common/modal.php"; ?>
   
<?php require_once(includePath('myheader.php')); ?>   

<?php

 
 if(!isset($_SESSION["level"]) || $level>8) {
          /*   alert("관리자 승인이 필요합니다."); */
		  $_SESSION["url"]='https://8440.co.kr/qcoffice/laser.php?mcno=' + $mcno ; 	  		 
		 sleep(1);
	          header("Location:https://8440.co.kr/login/login_form.php"); 
         exit;
   }

require_once(includePath('lib/mydb.php'));
$pdo = db_connect(); 

// 배열로 장비점검리스트 불러옴
include "load_DB.php";

// // 배열로 미점검 장비점검리스트 불러옴
// include "load_nocheck.php";

 ?>
  
<div class="container">  
	<div class="card mt-2 mb-2">  
	<div class="card-body">   
 <div class="d-flex mt-3 mb-1 justify-content-center">  			
				<img src="../img/qc-bg.jpg" style="width:100%;" >
 </div>
	<h5 class="fw-bolder mb-4"> 사무실 정비 </h5>
	<div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
				
<?
    
$todate=date("Y-m-d");   // 현재일자 변수지정   

$sql = "select * from mirae8440.myarea order by num";

$nowday=date("Y-m-d");   // 현재일자 변수지정   

$counter=0;
$num_arr=array();
$mcno_arr=array();
$mcname_arr=array();
$mcspec_arr=array();
$mcmaker_arr=array();
$mcmain_arr=array();
$mcsub_arr=array();
$qrcode_arr=array();

 try{  
 
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  

   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	
	  $num=$row["num"];
	  $mcno =$row["mcno"];
	  $mcname =$row["mcname"];
	  $mcspec =$row["mcspec"];
	  $mcmaker =$row["mcmaker"];
	  $mcmain =$row["mcmain"];
	  $mcsub =$row["mcsub"];
	  $qrcode =$row["qrcode"];
		
	  $num_arr[$counter] = $row["num"];
	  $mcno_arr[$counter] = $row["mcno"];
	  $mcname_arr[$counter] = $row["mcname"];
	  $mcspec_arr[$counter] = $row["mcspec"];
	  $mcmaker_arr[$counter] = $row["mcmaker"];
	  $mcmain_arr[$counter] = $row["mcmain"];
	  $mcsub_arr[$counter] = $row["mcsub"];
	  $qrcode_tmp = 'https://8440.co.kr/img/' . $qrcode . '.png' ;
	  $qrcode_arr[$counter] = 'https://8440.co.kr/img/' . $qrcode . '.png' ;
	  // print $qrcode_tmp;
   
      $counter++;	
	 		
				?>			
 <div class="col mb-2">			     
		<div class="card h-100" onclick="choiceMC(<?=$num?>,'<?=$mcmain?>','<?=$mcsub?>','<?=$mcno?>');" >                            
			<!-- Product details-->
			<div class="card-body p-2">
				<div class="text-center ">                                    
					<h5 class="fw-bolder"> <?=$row["mcname"]?> </h5>
				</div>
				<div class="text-center ">                                    
					<span class="fw-bolder"> <?=$row["mcspec"]?> </span>
				</div>
				<div class="text-center ">                                    
					<span class="fw-bolder"> 점검(정) <?=$row["mcmain"]?> </span>
				</div>
				<div class="text-center ">                                    
					<span class="fw-bolder"> 점검(부) <?=$row["mcsub"]?> </span>
				</div>
				<div class="text-center ">                                    
				   <span class="fw-bolder">
				   <img src=<?=$qrcode_tmp?> style="width:100%;height:100%;" >
				   </span>
				</div>
			</div>
		</div>					
	</div> 								
<?
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }	
?> 					
								
                </div>
            </div>
		</div>
	</div>
		
<!-- ajax 전송으로 DB 수정 -->
<? include "../formload.php"; ?>	
	
	<!-- Footer-->
<? include "../shop/footer.php" ?>  		

  
  
<script>

function choiceMC(num, mcmain, mcsub, mcno){
	var link ;
	link = 'https://8440.co.kr/qcoffice/laser.php?mcno=' + mcno;       

	popupCenter(link, '사무실 정비', 1200,900);		
}	

// 서버에 작업 기록
$(document).ready(function(){
	saveLogData('사무실 정비'); // 다른 페이지에 맞는 menuName을 전달
});
</script> 
</body>
</html>