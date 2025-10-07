<?php
session_start(); 
 
   if(isset($_REQUEST["num"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $num=$_REQUEST["num"];
  else
   $num="";
 
require_once("../lib/mydb.php");
$pdo = db_connect();

     try{
      $sql = "select * from mirae8440.request where num = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$num,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
    if($count<1){  
      print "검색결과가 없습니다.<br>";
     }else{

			$num=$row["num"];
			$outdate=$row["outdate"];			  

			$indate=$row["indate"];
			$outworkplace=$row["outworkplace"];

			$item=$row["item"];			  
			$spec=$row["spec"];
			$steelnum=$row["steelnum"];			  
			$company=$row["company"];
			$comment=$row["comment"];
			$supplier=$row["supplier"];
			$which=$row["which"];	 	  
			$model=$row["model"];	 			  
			$first_writer=$row["first_writer"];
			$update_log=$row["update_log"];		
            
			if((int)$steelnum>3)
                 $testnum = 3;
			   else
					$testnum = (int)$steelnum ;
				
			 if($indate!="0000-00-00") $indate = date("Y-m-d", strtotime( $indate) );
					else $indate="";	 
			 if($outdate!="0000-00-00") $outdate = date("Y-m-d", strtotime( $outdate) );
					else $outdate="";	 		
					
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
 

?>
<!DOCTYPE HTML>
<html lang="ko">
<head>
   
<meta charset="utf-8">
  
<link  rel="stylesheet" type="text/css" href="../css/common.css">
<link  rel="stylesheet" type="text/css" media="print" href="../css/print2.css">
<link rel="stylesheet" type="text/css" href="./css/input_inspection.css">    
 
<title>원자재 수입 검사서 </title>
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>  
<script src="./js/jspdf.min.js"></script>    <!-- pdf저장을 위한 자바스크립트 함수 불러오기 -->  
<script src="../common.js"></script> 

    <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script> 

<body>

<form  id="board_form" name="board_form" method="post"  > 
<input type=hidden id="imageURL" name="imageURL" >

<br>
&nbsp; &nbsp; <button type="button" id="saveBtn"  class="btn btn-secondary"> PDF파일 저장 </button>	
</form>
<div id="print">  
<div id="outlineprint">  
    <div class="img">      
	<div class="clear"> </div>
    <div id="row1">   <?=$num?> </div>    
	<div class="clear"> </div>
    <div id="row2">   <?=$item?> </div>
    <div class="clear"> </div>			
    <div id="row3"> </div> <!--  <img src='img/hansan_sign.png'>  </div>    <!-- end of row2-->
    <div class="clear"> </div>		
        <div id="row4">  	
	<?php
			print '<div id="col1"> '.  $indate . '</div>';
			print '<div id="col2"> '.  $outworkplace  . '</div>';
			print '<div id="col3"> '.  $steelnum  . '</div>';
			print '<div id="col4"> '.  $testnum  . '</div>';					
	  ?>	
	  </div>        
	<div class="clear"> </div>	
        <div id="row5">  	
	<?php
			print '<div id="col1"> '.  $indate . '</div>';
			print '<div id="col2"> '.   '</div>';
			print '<div id="col3"> '.   '</div>';
			print '<div id="col4"> '.   '</div>';					
	  ?>
	  </div>
   
	<div class="clear"> </div>	
        <div id="row6">  	
	<?php
			print '<div id="col1"> '.  '김영무'  . '</div>';
			print '<div id="col2"> '.  '의장면, 비닐상태, 긁힘여부 등' .  '</div>';		
	  ?>
	  </div>
   
	<div class="clear"> </div>	
        <div id="row7">  	
	<?php
			print '<div id="col1"> '.  $supplier  . '</div>';			
	  ?>
	  </div>



		</div>  <!-- end of row4-->	
        <div id="space1">     </div> 
	<div class="clear"> </div>	
    

	   

  </div>    <!-- end of outline --> 
  
  
  
</div>    <!-- end of print --> 

<canvas id="canvas" width="1300" height="1840"style="border:1px solid #d3d3d3;display:none"> </canvas>	
</body>

<script>

function partShot() {
  
var d = new Date();
var currentDate = ( d.getMonth() + 1 ) + "-" + d.getDate()  + "_" ;
var currentTime = d.getHours()  + "_" + d.getMinutes() +"_" + d.getSeconds() ;
var result = 'inspection' + currentDate + currentTime + '.jpg';		
	
//특정부분 스크린샷
html2canvas(document.getElementById("outlineprint"))
//id outlineprint 부분만 스크린샷
.then(function (canvas) {
//jpg 결과값
drawImg(canvas.toDataURL('image/jpeg'));

	const imgBase64 = canvas.toDataURL('image/jpeg', 'image/octet-stream');
	const decodImg = atob(imgBase64.split(',')[1]);
	// console.log(decodImg);

  let array = [];
  for (let i = 0; i < decodImg .length; i++) {
    array.push(decodImg .charCodeAt(i));
  }

  const file = new Blob([new Uint8Array(array)], {type: 'image/jpeg'});
  const fileName = 'canvas_img_' + new Date().getMilliseconds() + '.jpg';
  let formData = new FormData();
  formData.append('file', file, fileName);

  $.ajax({
    type: 'post',
    url: 'http://8440.co.kr/request/imgupload.php',
    cache: false,
    data: formData,
    processData: false,
    contentType: false,
    success: function (data) {
      // alert('Uploaded !!');
      console.log('Uploaded !');      
	  var data = data.replaceAll("\"", "");
	  data = data.replaceAll("\r", "");
	  data = data.replaceAll("\n", "");
	  data = data.replace(/ /g, '');
	  $('#imageURL').val(data);
	  
	  console.log(data);      
	  
    }
  });
});
 

}  // end of function
  
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
		// 서버를 활용해서 
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


$(document).ready(function(){	
		
	$("#saveBtn").click(function(){  	 
        // $("#board_form").submit();	   
		popupCenter('pdf1.php?imageURL=' + $('#imageURL').val(), 'PDF파일보기/저장', 1000,800) ;
		
	});	
		

	// 윈도우 창을 닫을때 jpg 파일 삭제함  - 이것때문에 계속 오류가 발생한 것임... 창을 닫는 다는 것이 새로운 창을 띄운것과 같이 되므로...
	$(window).bind("beforeunload", function (e){	
		 $('#row5').load('deljpg.php?imageURL=' + $('#imageURL').val());
	});	
	
});



			

setTimeout(function() {
 partShot();
}, 1000);
</script>	

</html>
