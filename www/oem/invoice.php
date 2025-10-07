<!doctype html>

<?php
 session_start(); 
  
 $num=$_REQUEST["num"];
 $address=$_REQUEST["address"];
 $firstord=$_REQUEST["firstord"];
 $secondord=$_REQUEST["secondord"];
 $orderdate=$_REQUEST["outputdate"];
 $workplacename=$_REQUEST["workplacename"];
 $chargedman=$_REQUEST["chargedman"];
 $chargedmantel=$_REQUEST["chargedmantel"];
 $startday=$_REQUEST["startday"];
 
  $delitext=$_REQUEST["delitext"];
  $deadline=$_REQUEST["deadline"];  
  
   if($deadline!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $deadline = $deadline . $week[ date('w',  strtotime($deadline)  ) ] ;
}	
 
	$text=array();			
	$item=array();			
	$spec=array();			
	$carsize=array();			
	$item_memo=array();			
	$textnum=array();	
	$textset=array();	
 
 	$text=$_REQUEST["text"];			
	$item=$_REQUEST["item"];			
	$spec=$_REQUEST["spec"];			
	$carsize=$_REQUEST["carsize"];			
	$item_memo=$_REQUEST["item_memo"];			
	$textnum=$_REQUEST["textnum"];	
	$textset=$_REQUEST["textset"];	
 
 $today = date("m/d", time());
 
 // $chargedman = $chargedman . " 님  " . $chargedmantel;
 
?>


<html lang="ko">
  <head>
   
  <meta charset="utf-8">
  
 <link  rel="stylesheet" type="text/css" href="../css/common.css">
 <link  rel="stylesheet" type="text/css" media="print" href="../css/print2.css">
 <link rel="stylesheet" type="text/css" href="../css/outorder_invoice.css">   
	 
   <!--  <link rel="stylesheet" type="text/css" href="../css/orderprint.css">  발주서 인쇄에 맞게 수정하기 위한 css -->
    <title> 서한컴퍼니 발주서 인쇄</title>
  </head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>  
<script src="../js/html2canvas.js"></script>    <!-- 스크린샷을 위한 자바스크립트 함수 불러오기 -->  
<script>
 
function partShot(number) {
	    var tmp=$('#workplacename').val();		
		
		tmp= tmp.replace(',', '-');
        var d = new Date();
        var currentDate =  '  ' + d.getFullYear() + "-" + ( d.getMonth() + 1 ) + "-" + d.getDate() ;
        var currentTime = d.getHours()  + "_" + d.getMinutes() +"_" + d.getSeconds() ;
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
    <div id="row1">   <?=$firstord?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  발주일 :  <?=$startday?> </div>
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

			print '<div id="col1"> 1. 납    기 : '.  $deadline . '</div>  <div class="clear"> </div> ';
			print '<div id="col1"> 2. 납 품 처 :<b> '. $address . ' </b> </div>  <div class="clear"> </div> ';
			print '<div id="col1"> 3. 담 당 자 : '.  $chargedman . '  (tel) ' . $chargedmantel  . '</div>  <div class="clear"> </div> ';			
			print '<div id="col1"> 4. 운 반 비 : '.  $delitext . '</div>  <div class="clear"> </div> ';
			print '<div id="col1"> 5. 주의사항 : 박스에 현장명 표기요망 </div>  <div class="clear"> </div> ';

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
