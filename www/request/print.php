<?php
 session_start(); 
$img_name="http://5130.co.kr/img/printorder.jpg";
$file_dir = '../uploads/output';
  
 $num=$_REQUEST["num"];
 $search=$_REQUEST["search"];  //검색어
 $find=$_REQUEST["find"];      // 검색항목
 $page=$_REQUEST["page"];   //페이지번호
 $process=$_REQUEST["process"];   // 진행현황
 // 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];
$separate_date=$_REQUEST["separate_date"];	 

 $year=$_REQUEST["year"];   // 년도 체크박스

 require_once("../lib/mydb.php"); 
 $pdo = db_connect(); 
 
 try{
     $sql = "select * from chandj.output where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC);
	 
			  $item_num=$row["num"];
			  $con_num=$row["con_num"];
			  $item_outdate=$row["outdate"];
			  $item_indate=$row["indate"];
			  $item_orderman=$row["orderman"];
			  $item_outworkplace=$row["outworkplace"];
			  $item_outputplace=$row["outputplace"];
			  $item_receiver=$row["receiver"];
			  $item_phone=$row["phone"];
			  $item_comment=$row["comment"];
			  $root=$row["root"];	  
			  $steel=$row["steel"];	  
			  $motor=$row["motor"];	 			  
			  $delivery=$row["delivery"];	
			  $regist_state=$row["regist_state"];	 	
     $image_name[0]   = $row["file_name_0"];
     $image_name[1]   = $row["file_name_1"];
     $image_name[2]   = $row["file_name_2"];
     $image_name[3]   = $row["file_name_3"];
     $image_name[4]   = $row["file_name_4"];
 
     $image_copied[0] = $row["file_copied_0"];
     $image_copied[1] = $row["file_copied_1"];
     $image_copied[2] = $row["file_copied_2"];
     $image_copied[3] = $row["file_copied_3"];
     $image_copied[4] = $row["file_copied_4"];
            
	     $img_name0 = $image_name[0];
	     $img_copied0 = "../uploads/output/".$image_copied[0]; 	
	     $img_name1 = $image_name[1];
	     $img_copied1 = "../uploads/output/".$image_copied[1]; 	
	     $img_name2 = $image_name[2];
	     $img_copied2 = "../uploads/output/".$image_copied[2]; 		
	     $img_name3 = $image_name[3];
	     $img_copied3 = "../uploads/output/".$image_copied[3]; 	
	     $img_name4 = $image_name[4];
	     $img_copied4 = "../uploads/output/".$image_copied[4]; 			  
			  
      } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
} 
	 
if($item_outdate!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $item_outdate = $item_outdate . $week[ date('w',  strtotime($item_outdate)  ) ] ;
}

?>


<html lang="ko"> 
  <head> 
   
    <meta charset="utf-8">

 <link  rel="stylesheet" type="text/css" href="../css/common.css">
 <link  rel="stylesheet" type="text/css" media="print" href="../css/print2.css">
     <link rel="stylesheet" type="text/css" href="../css/printorder.css">   
      <!--  <link rel="stylesheet" type="text/css" href="../css/orderprint.css">  발주서 인쇄에 맞게 수정하기 위한 css -->
    <title>발주서 출력</title>
  </head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>  
<script src="../js/html2canvas.js"></script>    <!-- 스크린샷을 위한 자바스크립트 함수 불러오기 -->  

<script>
 
function partShot() {
        var d = new Date();
        var currentDate = d.getFullYear() + "-" +( d.getMonth() + 1 ) + "-" + d.getDate()  + "_" ;
        var currentTime = d.getHours()  + "_" + d.getMinutes()  + "_" +d.getSeconds() ;
        var result = 'output' + currentDate + currentTime +'.jpg';		
	
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

</script>	
  
<body>

<div id="print">  
<div id="outlineprint">  
    <div class="img">      
    <div id="row1">  </div>  <!-- end of row1 -->
	<div class="clear"> </div>
    <div id="row2">  
        <div id="col1">  <?=$item_outdate?>

	    </div>  <!-- end of row2 col1-->
        <div id="col2">   <?=$item_indate?>

	    </div>  <!-- end of row2 col2-->		
	</div>  <!-- end of row2 -->
		<div class="clear"> </div> 
    <div id="row3">  
        <div id="col1">   <?=$item_receiver?>

	    </div>  <!-- end of row3 col1-->
        <div id="col2">  <?=$item_outworkplace?>

	    </div>  <!-- end of row3 col2-->		
	</div>  <!-- end of row3 --> 
		<div class="clear"> </div> 
    <div id="row4">  
        <div id="col1">   <?=$item_phone?>

	    </div>  <!-- end of row4 col1-->
        <div id="col2">   <?=$item_outputplace?>

	    </div>  <!-- end of row4 col2-->		
	</div>  <!-- end of row4 --> 	
   <div id="row5">  	<?=$delivery?> 
		</div>  <!-- end of row5 --> 
		<div class="clear"> </div> 
   <div id="row6">  	<?=$item_comment?> 
		</div>  <!-- end of row5 --> 		
		<div class="clear"> </div> 
		<?php
		if($img_name4!="") {
			print '<div id="row7">  <img src="' . $img_copied4 . '" > 
		</div> ';
		}
		?>
		
<div id="containers" >	
	<div id="display_result" >	
		   
         </div>   <!-- end of display_result -->
	   </div> <!-- end of containers -->  
	   	   
    </div>
 </div>    <!-- end of outlineprint --> 
</div>    <!-- end of print --> 
	<?php
		print "<script> partShot(); </script>";   // 화면 저장하기
		?>	
		  
	<canvas id="canvas" width="1150" height="1600"style="border:1px solid #d3d3d3; display:none;"></canvas>	
  
</body>

</html>


