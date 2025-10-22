<?php require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';

// 요청 파라미터 초기화
$mcno = $_REQUEST["mcname"] ?? '';
 
  // 첫 화면 표시 문구
 $title_message = '미래기업 고객만족 품질경영';
 
 // 베이스 URL 설정 (로컬/서버 환경 자동 감지)
 $base_url = getBaseUrl();
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
include includePath("QC/load_DB.php");

// // 배열로 미점검 장비점검리스트 불러옴
// include "load_nocheck.php";

 ?>
 
<div class="container">  
	<div class="card mt-2 mb-2">  
	<div class="card-body">   
 <div class="d-flex mt-3 mb-1 justify-content-center">  			
				<img src="<?= $base_url ?>/img/qc-bg.jpg" style="width:100%;" alt="QC Background">
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
	  $num = $row["num"] ?? '';
	  $mcno = $row["mcno"] ?? '';
	  $mcname = $row["mcname"] ?? '';
	  $mcspec = $row["mcspec"] ?? '';
	  $mcmaker = $row["mcmaker"] ?? '';
	  $mcmain = $row["mcmain"] ?? '';
	  $mcsub = $row["mcsub"] ?? '';
	  $qrcode = $row["qrcode"] ?? '';
		
	  $num_arr[$counter] = $row["num"] ?? '';
	  $mcno_arr[$counter] = $row["mcno"] ?? '';
	  $mcname_arr[$counter] = $row["mcname"] ?? '';
	  $mcspec_arr[$counter] = $row["mcspec"] ?? '';
	  $mcmaker_arr[$counter] = $row["mcmaker"] ?? '';
	  $mcmain_arr[$counter] = $row["mcmain"] ?? '';
	  $mcsub_arr[$counter] = $row["mcsub"] ?? '';
	  $qrcode_tmp = $row["qrcode"] ?? '';
	  
	  // QR 코드 경로를 환경에 맞게 동적 생성
	  if (!empty($qrcode_tmp)) {
	      // 이미 전체 URL인 경우 그대로 사용
	      if (strpos($qrcode_tmp, 'http://') === 0 || strpos($qrcode_tmp, 'https://') === 0) {
	          $qrcode = $qrcode_tmp;
	      } else {
	          // 상대 경로인 경우 base URL 추가
	          $qrcode = $base_url . (strpos($qrcode_tmp, '/') === 0 ? $qrcode_tmp : '/' . $qrcode_tmp);
	      }
	  } else {
	      $qrcode = '';
	  }
	  
	  $qrcode_arr[$counter] = $qrcode;
	  // print $qrcode;
   
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
				   <?php if (!empty($qrcode)): ?>
				       <img src="<?= htmlspecialchars($qrcode) ?>" style="width:100%;height:100%;" alt="QR Code" onerror="this.style.display='none'">
				   <?php else: ?>
				       <div class="text-muted" style="padding: 20px;">QR 코드 없음</div>
				   <?php endif; ?>
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
<?php include includePath('formload.php'); ?>	
	
<!-- Footer-->
<?php include includePath('shop/footer.php'); ?>  					

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
        baseUrl = 'http://8440.local/qc/laser.php';
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