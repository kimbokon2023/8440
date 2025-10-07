<!doctype html>

<?php
 session_start(); 
  
 $num=$_REQUEST["num"];
 $address=$_REQUEST["address"];
 $orderdate=$_REQUEST["outputdate"];
 $workday=$_REQUEST["workday"];
 $workplacename=$_REQUEST["workplacename"];
 $chargedman=$_REQUEST["chargedman"];
 $chargedmantel=$_REQUEST["chargedmantel"];
 $secondord=$_REQUEST["secondord"];
 
	$text=array();			
	$textnum=array();	
	$textset=array();	
	$textmemo=array();	
 
 $text=$_REQUEST["text"];
 $textnum=$_REQUEST["textnum"];
 $textset=$_REQUEST["textset"];
 $textmemo=$_REQUEST["textmemo"];
 
 // $today = date("m/d", time());
 $today = date('m/d', strtotime($workday));
 
 $chargedman = $chargedman . " 님  " . $chargedmantel; 

?>


<html lang="ko">
  <head>
   
  <meta charset="utf-8">
  
 <link  rel="stylesheet" type="text/css" href="../css/common.css">
 <link  rel="stylesheet" type="text/css" media="print" href="../css/print2.css">
 <link rel="stylesheet" type="text/css" href="./css/print_invoice_sheet.css">   
	 
   <!--  <link rel="stylesheet" type="text/css" href="../css/orderprint.css">  발주서 인쇄에 맞게 수정하기 위한 css -->
    <title>본천장/조명천장 거래명세서 인쇄</title>
  </head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>  
<script src="../js/html2canvas.js"></script>    <!-- 스크린샷을 위한 자바스크립트 함수 불러오기 -->  
<script>
 
function partShot(number) {
	var workplace = '<?php echo $workplacename; ?>';
        var d = new Date();
        var currentDate = ( d.getMonth() + 1 ) + "-" + d.getDate()  + "_" ;
        var currentTime = d.getHours()  + "_" + d.getMinutes() +"_" + d.getSeconds() ;
        var result = '조명천장거래명세서(' + workplace +')' + currentDate + currentTime + '.jpg';		
	
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
    <div id="row3">   <?=$secondord?>  </div>    <!-- end of row2-->
    <div class="clear"> </div>			
        <div id="row4">  <?=$address?>  </div>  <!-- end of row3-->
	<div class="clear"> </div>	
        <div id="row4">  <?=$chargedman?>  </div>  <!-- end of row4-->	        
	<div class="clear"> </div>	
    <div id="row5"> 	
	
	<?php
	for($i=0; $i<=11;$i=$i+1)
	{
        if($textnum[$i]>=1)
		{
			print '<div id="col1"> '.  $today . '</div>';
			print '<div id="col2"> '.  $text[$i]  . '</div>';
			print '<div id="col3"> '.  $textset[$i]  . '</div>';
			print '<div id="col4"> '.  $textnum[$i]  . '</div>';
			print '<div id="col5"> '.  $textmemo[$i]  . '</div>';
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
	   print "<script> partShot($pagenum); </script>"; 
	  
	?>
		<canvas id="canvas" width="1300" height="1840"style="border:1px solid #d3d3d3; display:none;"></canvas>	
</body>
<script>
setTimeout(function() {
 // load_data();
}, 500);
</script>	

</html>
