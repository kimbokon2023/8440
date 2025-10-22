<!doctype html>

<?php
/**
 * 외주발주 출고증 인쇄 페이지
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// 세션 체크 (레벨 8 이하만 접근 가능)
if(!isset($_SESSION["level"]) || $_SESSION["level"]>8) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// 요청 변수 초기화 (?? '' 형태로 안전하게 처리)
$num = $_REQUEST["num"] ?? '';
$address = $_REQUEST["address"] ?? '';
$firstord = $_REQUEST["firstord"] ?? '';
$secondord = $_REQUEST["secondord"] ?? '';
$orderdate = $_REQUEST["outputdate"] ?? '';
$workplacename = $_REQUEST["workplacename"] ?? '';
$chargedman = $_REQUEST["chargedman"] ?? '';
$chargedmantel = $_REQUEST["chargedmantel"] ?? '';
$startday = $_REQUEST["startday"] ?? '';
$submemo = $_REQUEST["submemo"] ?? '';
$delitext = $_REQUEST["delitext"] ?? '';
$deadline = $_REQUEST["deadline"] ?? '';
$memo = $_REQUEST["memo"] ?? '';

// 납기일 요일 추가
if($deadline != "") {
    $week = array("(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)");
    $deadline = $deadline . $week[date('w', strtotime($deadline))];
}

// 배열 초기화
$text = array();
$item = array();
$spec = array();
$carsize = array();
$item_memo = array();
$textnum = array();
$textset = array();

// 요청 데이터 할당
$text = $_REQUEST["text"] ?? array();
$item = $_REQUEST["item"] ?? array();
$spec = $_REQUEST["spec"] ?? array();
$carsize = $_REQUEST["carsize"] ?? array();
$item_memo = $_REQUEST["item_memo"] ?? array();
$textnum = $_REQUEST["textnum"] ?? array();
$textset = $_REQUEST["textset"] ?? array();

$today = date("m/d", time());

// 환경별 기본 URL 설정
$baseUrl = getBaseUrl();
?>


<html lang="ko">
  <head>
   
  <meta charset="utf-8">
  
 <link  rel="stylesheet" type="text/css" href="<?=$baseUrl?>/css/common.css?v=1">
 <link  rel="stylesheet" type="text/css" media="print" href="<?=$baseUrl?>/css/print2.css?v=1">
 <link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/outorder/css/style.css?v=1">   
	 
   <!--  <link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/css/orderprint.css">  발주서 인쇄에 맞게 수정하기 위한 css -->
    <style>
        /* 환경별 배경 이미지 경로 설정 */
        .img {
            background-image: url('<?=$baseUrl?>/img/order_sheet1.jpg');
        }
    </style>
    <title> 발주서 출력 </title>
  </head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>  
<script src="<?=$baseUrl?>/js/html2canvas.js"></script>    <!-- 스크린샷을 위한 자바스크립트 함수 불러오기 -->  
<script>
// 환경별 기본 URL 설정 (PHP에서 전달)
var BASE_URL = '<?=$baseUrl?>';

(function() {
    'use strict';
    
    function partShot(number) {
        var tmp = $('#workplacename').val();		
        
        tmp = tmp.replace(',', '-');
        var d = new Date();
        var currentDate = '  ' + d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate();
        var currentTime = d.getHours() + "_" + d.getMinutes() + "_" + d.getSeconds();
        var result = tmp + currentDate + '.jpg';		
        // console.log('');
    
        //특정부분 스크린샷
        html2canvas(document.getElementById("outlineprint"))
        //id outlineprint 부분만 스크린샷
        .then(function (canvas) {
            //jpg 결과값
            drawImg(canvas.toDataURL('image/jpeg'));
            //이미지 저장
            saveAs(canvas.toDataURL(), result);
        }).catch(function (err) {
            console.log(err);
        });
    }
    
    function drawImg(imgData) {
        console.log(imgData);
        //imgData의 결과값을 console 로그로 보실 수 있습니다.
        return new Promise(function reslove() {
            //내가 결과 값을 그릴 canvas 부분 설정
            var canvas = document.getElementById('canvas');
            var ctx = canvas.getContext('2d');
            //canvas의 뿌려진 부분 초기화
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            var imageObj = new Image();
            imageObj.onload = function () {
                ctx.drawImage(imageObj, 10, 10);
                //canvas img를 그리겠다.
            };
            imageObj.src = imgData;
            //그릴 image데이터를 넣어준다.
            
        }, function reject() { });
    }
    
    function saveAs(uri, filename) {
        var link = document.createElement('a');
        if (typeof link.download === 'string') {
            link.href = uri;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        } else {
            window.open(uri);
        }
    }
    
    function cleardiv() {
        $('#outlineprint').empty();
    }
    
    function load_data() {
        $.ajax({
            url: BASE_URL + "/outorder/writeDB.php",
            type: "post",		
            data: $("#board_form").serialize(),
            dataType: "json",
            success: function(data) {
                console.log(data);
                if (opener && !opener.closed) {
                    opener.location.reload();
                }
                // window.close();			
            },
            error: function(jqxhr, status, error) {
                console.log(jqxhr, status, error);
            } 			      		
        });		
    }
    
    // 전역 스코프에 필요한 함수들을 노출
    window.partShot = partShot;
    window.load_data = load_data;
    window.cleardiv = cleardiv;
})();

</script>	
  

<body>

<form name="board_form" id="board_form"  method="post" >  
 <input type="hidden" id="num" name="num" value="<?=$num?>"  >
</form>

<div id="print">  
<div id="outlineprint">  



    <div class="img">      
	<div class="clear"> </div>
    <div id="row1">
	<div id="col1">
				<?=$firstord?>
				</div>
	<div id="col2">				
			발주일 :  <?=$startday?> </div>
	 </div>
    <div class="clear"> </div>			
    <div id="row2">   <?=$secondord?>  </div>    <!-- end of row2-->
	
	    <input type="hidden" id="workplacename" name="workplacename" value="<?=$workplacename?>"  >
	
    <div class="clear"> </div>		
        <div id="row3"> <?=$workplacename?> </div>  <!-- end of row3-->
	<div class="clear"> </div>	
	<div class="clear"> </div>	
    <div id="row4"> 	
	
	<?php
	for($i=0; $i<=9; $i=$i+1)
	{
        if($textnum[$i]>=1)
		{
			print '<div id="col1"> '.  ($i+1) . '</div>';
			print '<div id="col2"> &nbsp;'.  $text[$i]  . '</div>';
			print '<div id="col3"> &nbsp;'.  $item[$i]  . '</div>';
			print '<div id="col4"> &nbsp;'.  $spec[$i]  . '</div>';
			print '<div id="col5"> &nbsp;'.  $textnum[$i]  . '</div>';
			print '<div id="col6"> &nbsp;'.  $textset[$i]  . '</div>';
			print '<div id="col7"> &nbsp;'.  $carsize[$i]  . '</div>';
			print '<div id="col8"> &nbsp;'.  $item_memo[$i]  . '</div>';			
		}
	 print '<div class="clear"> </div> ';
	}
	  ?>
	</div>

    <div id="row5"> 			
	<?php

			print '<div id="col1"> 1. 납    기 : '.  $deadline . "    " . $memo .'</div>  <div class="clear"> </div> ';
			print '<div id="col1"> 2. 납 품 처 :<b> '. $address . ' </b> </div>  <div class="clear"> </div> ';
			print '<div id="col1"> 3. 담 당 자 : '.  $chargedman . '  (tel) ' . $chargedmantel  . '</div>  <div class="clear"> </div> ';			
			print '<div id="col1"> 4. 운 반 비 : '.  $delitext . '</div>  <div class="clear"> </div> ';
			print '<div id="col1"> 5. 주의사항 : 박스에 현장명 표기요망 </div>  <div class="clear"> </div> ';
			print '<div id="col1"> </div>  <div class="clear"> </div> ';
			print '<div id="col1" style="font-size:35px;" > 특이사항 메모 : ' . $submemo . ' </div>  <div class="clear"> </div> ';

	  ?>
	  
 </div>

	</div>  <!-- end of row2 -->
		<div class="clear"> </div> 

<div id="containers" >	
	<div id="display_result" >	

       <div class="clear"> </div>
	
 	
		   
         </div>   <!-- end of display_result -->
	   </div> <!-- end of containers -->  

 </div>    <!-- end of outline -->
</div>    <!-- end of print -->
	<?php
	   print "<script> partShot($num); </script>";

	?>
		<canvas id="canvas" width="1300" height="1840"style="border:1px solid #d3d3d3; display:none;"></canvas>	
</body>
<script>
setTimeout(function() {
  load_data();
}, 1000);
</script>	

</html>
