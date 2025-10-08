<?php require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('session.php'));

$mcno=$_REQUEST["mcname"];
 
  // 첫 화면 표시 문구
 $title_message = '미래기업 고객만족 품질경영';        
 ?>
   
<?php include includePath('load_header.php') ?>
 
<title> <?=$title_message?> </title>  
 
</head> 

<body>

<?php include includePath("common/modal.php"); ?>
   
<?php require_once(includePath('myheader.php')); ?>   

<?php

 if(!isset($_SESSION["level"]) || $level>8) {
          /*   alert("관리자 승인이 필요합니다."); */
		 $_SESSION["url"]='https://8440.co.kr/qc/laser.php?mcno=' . $mcno ; 		  		 
		 sleep(1);
	     header("Location:" . $WebSite . "login/login_form.php"); 
         exit;
}  
							                	
require_once(includePath('lib/mydb.php'));
$pdo = db_connect(); 

// 배열로 장비점검리스트 불러옴
include includePath("load_DB.php");

// // 배열로 미점검 장비점검리스트 불러옴
// include "load_nocheck.php";

 ?>
 
<div class="container">  
	<div class="card mt-2 mb-2">  
	<div class="card-body">   
 <div class="d-flex mt-3 mb-1 justify-content-center">  			
				<img src="../img/qc-bg.jpg" style="width:100%;" >
 </div>
	<h5 class="fw-bolder mb-4"> 점검 장비 </h5>
	<div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
				
<?php

$todate=date("Y-m-d");   // 현재일자 변수지정   

$sql = "select * from mirae8440.mymc order by num";

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
	  $qrcode_tmp = $row["qrcode"] ?? '';
	  $qrcode = $qrcode_tmp;
	  $qrcode_arr[$counter] = $qrcode_tmp;
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
				   <img src=<?=$qrcode?> style="width:100%;height:100%;" >
				   </span>
				</div>
			</div>
		</div>					
	</div> 
<?php
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
function choiceMC(num, mcmain, mcsub, mcno) {
    var mcMap = {
        1: 'laser01',
        2: 'vcut01',
        3: 'bending01',
        4: 'shearing01',
        5: 'welder01',
        6: 'welder02',
        7: 'welder03',
        8: 'welder04',
        9: 'motor01',
        10: 'motor02',
        11: 'tapdrill01',
        12: 'comp01',
        13: 'comp02'
    };

    // 동작 환경에 따라 base URL 결정
    var baseUrl;
    if (location.hostname === 'localhost' || location.hostname === '127.0.0.1') {
        baseUrl = 'http://localhost:8000/qc/laser.php';
    } else {
        baseUrl = 'https://8440.co.kr/qc/laser.php';
    }

    var link = '';
    if (mcMap[num]) {
        link = baseUrl + '?mcno=' + mcMap[num] + '&mcname=' + mcMap[num];
    }

    if (num > 0 && link)
        popupCenter(link, '장비 정검', 1200, 900);
}

// 서버에 작업 기록
$(document).ready(function(){
    saveLogData('장비점검 화면'); // 다른 페이지에 맞는 menuName을 전달
});

</script> 
</body>
</html>