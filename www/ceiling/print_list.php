 <?php
	  
require_once("../lib/mydb.php");
$pdo = db_connect();	
   
 // 기간을 정하는 구간
 
$todate=date("Y-m-d");   // 현재일자 변수지정   

$common=" where  (date(endworkday)>=date(now()))  order by endworkday ";  // 출고예정일이 현재일보다 클때 조건

$sql = "select * from mirae8440.work " . $common; 							

$nowday=date("Y-m-d");   // 현재일자 변수지정   
$counter=1;
   ?>
   <html lang="ko">
  <head>   
    <meta charset="utf-8">
 <link  rel="stylesheet" type="text/css" href="../css/common.css">
 <link  rel="stylesheet" type="text/css" href="../css/work.css">	
 <link  rel="stylesheet" type="text/css" media="print" href="../css/print.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>  
<script src="../js/html2canvas.js"></script>    <!-- 스크린샷을 위한 자바스크립트 함수 불러오기 -->  


<script>
function partShot() {
        var d = new Date();
        var currentDate = ( d.getMonth() + 1 ) + "-" + d.getDate()  + "_" ;
        var currentTime = d.getHours()  + "_" + d.getMinutes() +"_" + d.getSeconds() ;
        var result = 'jambschedule' + currentDate + currentTime + '__' +'.jpg';		
	
//특정부분 스크린샷
html2canvas(document.getElementById("print_area"))
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
	 $('#print_area').empty();
}
</script>



    <title>출고예정 리스트</title>
  </head> 
<body >
<div id="print_area">
<div class="printlist_img">      
		<div id="print_list">
   				<div class="print1"> 출력일자 : <?=$nowday?> </div>
				<div class="clear"> </div>	
                <div class="up_space"> </div>	
				<div class="clear"> </div>	

<?php
   
	 try{  
	  $stmh = $pdo->query($sql);         // 경동화물 가는 것들
      $temp=$stmh->rowCount();  
	      
	  $total_row = $temp;     // 전체 글수	  		
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			  $checkstep=$row["checkstep"];
			  $workplacename=$row["workplacename"];
			  $address=$row["address"];
			  $firstord=$row["firstord"];
			  $firstordman=$row["firstordman"];
			  $firstordmantel=$row["firstordmantel"];
			  $secondord=$row["secondord"];
			  $secondordman=$row["secondordman"];
			  $secondordmantel=$row["secondordmantel"];
			  $chargedman=$row["chargedman"];
			  $orderday=$row["orderday"];
			  $measureday=$row["measureday"];
			  $drawday=$row["drawday"];
			  $deadline=$row["deadline"];
			  $workday=$row["workday"];
			  $worker=$row["worker"];
			  $endworkday=$row["endworkday"];
			  $material1=$row["material1"];
			  $material2=$row["material2"];
			  $material3=$row["material3"];
			  $material4=$row["material4"];
			  $material5=$row["material5"];
			  $material6=$row["material6"];
			  $widejamb=$row["widejamb"];
			  $normaljamb=$row["normaljamb"];
			  $smalljamb=$row["smalljamb"];
			  $memo=$row["memo"];
			  $regist_day=$row["regist_day"];
			  $update_day=$row["update_day"];
			  $demand=$row["demand"]; 
			  
 if($endworkday!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $endworkday = $endworkday . $week[ date('w',  strtotime($endworkday)  ) ] ;
}  			  
		
$sum_material=$material1 . $material2 . " " . $material3 . $material4 . " " . $material5 . $material6; 

	 $workitem="";
	 if($widejamb!="")
			$workitem="막판" . $widejamb . " "; 
	 if($normaljamb!="")
			$workitem .="막(無)" . $normaljamb . " "; 					
	 if($smalljamb!="")
			$workitem .="쪽쟘" . $smalljamb . " "; 												   
					
			?>


				<div class="print2"> <?=$counter?> </div>	
				<div class="print3"> <?=iconv_substr($endworkday,5,8, "utf-8")?> </div>				
				<div class="print4"> <?=iconv_substr($workplacename,0,20, "utf-8")?> </div>				
				<div class="print5"> <?=iconv_substr($worker,0,6, "utf-8")?> </div>				
				<div class="print6"> <?=iconv_substr($sum_material,0,40, "utf-8")?> </div>				
				<div class="print7"> <?=iconv_substr($workitem,0,20, "utf-8")?> </div>				
				<div class="clear"> </div>	 
 <?php
          $counter++;
	  }  // end of while
  }  // end of try
    catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
  }
 ?>    
                     </div>  <!-- end of print_list -->
    </div>
</div>	<!-- end of print_area -->
	<?php 
	   print "<script> partShot(); </script>"; 
	  
	?>
		<canvas id="canvas" width="1300" height="1840"style="border:1px solid #d3d3d3; display:none;"></canvas>		
</body>


</html>

