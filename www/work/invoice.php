<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));  

if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . $WebSite . "login/login_form.php"); 
    exit;
}

include getDocumentRoot() . '/load_header.php';
  
 $num=$_REQUEST["num"];
 $secondord=$_REQUEST["secondord"];
 $address=$_REQUEST["address"];
 $orderdate=$_REQUEST["outputdate"];
 $workplacename=$_REQUEST["workplacename"];
 $worker=$_REQUEST["worker"];
 $workertel=$_REQUEST["workertel"];
 
	$text=array();			
	$textnum1=array();	
	$textnum2=array();	
	$textnum3=array();		
	$textmemo=array();		
 
 $text=$_REQUEST["text"];
 $textnum1=$_REQUEST["textnum1"];
 $textnum2=$_REQUEST["textnum2"];
 $textnum3=$_REQUEST["textnum3"];
 $textmemo=$_REQUEST["textmemo"];
 
 
 $today = date("m/d", time());
 
 if($worker!=='') 
     $chargedman = $worker . " " .  $workertel;
   else
    $chargedman = '';
 

?>

 <link  rel="stylesheet" type="text/css" href="../css/common.css?v=<?php echo time(); ?>">
 <link  rel="stylesheet" type="text/css" media="print" href="../css/print2.css?v=<?php echo time(); ?>">
 <link  rel="stylesheet" type="text/css" href="../css/print_invoice.css?v=<?php echo time(); ?>">
	 
   <!--  <link rel="stylesheet" type="text/css" href="../css/orderprint.css">  발주서 인쇄에 맞게 수정하기 위한 css -->
    <title>쟘 출고증 인쇄</title>
  </head>
<script src="../js/html2canvas.js"></script>    <!-- 스크린샷을 위한 자바스크립트 함수 불러오기 -->  
<script>
 
function partShot(number) {
        var d = new Date();
        var currentDate = ( d.getMonth() + 1 ) + "-" + d.getDate()  + "_" ;
        var currentTime = d.getHours()  + "_" + d.getMinutes() +"_" + d.getSeconds() ;
        var result = 'invoice ' + currentDate + currentTime + '.jpg';		
	
        // 모든 이미지가 로드될 때까지 대기
        waitForImages().then(function() {
            //특정부분 스크린샷
            html2canvas(document.getElementById("outlineprint"), {
                allowTaint: true,
                useCORS: true,
                scale: 2, // 해상도 향상
                backgroundColor: '#ffffff',
                logging: false,
                imageTimeout: 15000 // 이미지 로딩 타임아웃 15초
            })
            //id outlineprint 부분만 스크린샷
            .then(function (canvas) {
                //jpg 결과값
                drawImg(canvas.toDataURL('image/jpeg'));
                //이미지 저장
                saveAs(canvas.toDataURL(), result);
            }).catch(function (err) {
                console.log(err);
            });
        });
}

// 모든 이미지가 로드될 때까지 대기하는 함수
function waitForImages() {
    return new Promise(function(resolve) {
        var images = document.querySelectorAll('#outlineprint img');
        var totalImages = images.length;
        var loadedImages = 0;
        
        if (totalImages === 0) {
            // 이미지가 없으면 바로 resolve
            resolve();
            return;
        }
        
        function imageLoaded() {
            loadedImages++;
            if (loadedImages === totalImages) {
                resolve();
            }
        }
        
        images.forEach(function(img) {
            if (img.complete) {
                imageLoaded();
            } else {
                img.addEventListener('load', imageLoaded);
                img.addEventListener('error', imageLoaded); // 에러가 나도 진행
            }
        });
        
        // 5초 후 타임아웃
        setTimeout(function() {
            if (loadedImages < totalImages) {
                console.log('이미지 로딩 타임아웃, 진행합니다.');
                resolve();
            }
        }, 5000);
    });
}

function drawImg(imgData) {
// console.log(imgData);
//imgData의 결과값을 console 로그롤 보실 수 있습니다.
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

}
</script>	 

<body>

<div id="print">  
<div id="outlineprint">  
    <div class="img">      
	<div class="clear"> </div>
    <div id="row1">   <?=$orderdate?> </div>
    <div class="clear"> </div>			
    <div id="row2">   <?=$workplacename?>  </div>    <!-- end of row2-->
    <div class="clear"> </div>		
	<div id="row3">  <?=$secondord?>  </div>  <!-- end of row3-->
    <div class="clear"> </div>		
	<div id="row4">  <?=$address?>  </div>  <!-- end of row3-->
	<div class="clear"> </div>	
    <div id="row4">  <?=$chargedman?>  </div>  <!-- end of row4-->	        
	<div class="clear"> </div>	
    <div id="row5"> 	
	
	<?php
	for($i=0; $i<=9; $i=$i+1)
	{
        if($textnum1[$i]>=1 or $textnum2[$i]>=1 or $textnum3[$i]>=1 or trim($text[$i])!==''  )
		{
			print '<div id="col1"> '.  $today . '</div>';
			print '<div id="col2"> '.  $text[$i]  . '</div>';			
			print '<div id="col3"> &nbsp;'. (empty(trim($textnum1[$i])) ? '-' : $textnum1[$i]) . '</div>';
			print '<div id="col4"> &nbsp;'. (empty(trim($textnum2[$i])) ? '-' : $textnum2[$i]) . '</div>';
			print '<div id="col5"> &nbsp;'. (empty(trim($textnum3[$i])) ? '-' : $textnum3[$i]) . '</div>';

			print '<div id="col6"> &nbsp;'.  $textmemo[$i]  . '</div>';
		}
	 print '<div class="clear"> </div> ';
	}
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
	   print "<script> 
	   // 페이지 로드 완료 후 이미지 로딩 대기 후 스크린샷
	   window.addEventListener('load', function() {
	       setTimeout(function() {
	           partShot($pagenum); 
	       }, 1000); // 1초 추가 대기
	   });
	   </script>"; 
	  
	?>
		<canvas id="canvas" width="1300" height="1840"style="border:1px solid #d3d3d3; display:none;"></canvas>	
</body>
<script>
$(document).ready(function () {
	showMsgModal(10) ; // 다운로드 후 ctrl+j 안내함
	setTimeout(function() {
		hideMsgModal();
	}, 2000);
});

</script>	
</html>
