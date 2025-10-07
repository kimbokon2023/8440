<?php
// 환경파일 읽어오기 (테이블명 작업 폴더 등)
include 'steelini.php';   
 
require_once("../lib/mydb.php");
$pdo = db_connect();

     try{
      $sql = "select * from mirae8440." . $tablename . " where id = ? ";
      $stmh = $pdo->prepare($sql); 

      $stmh->bindValue(1,$id,PDO::PARAM_STR); 
      $stmh->execute();
      $count = $stmh->rowCount();            
	  $row = $stmh->fetch(PDO::FETCH_ASSOC);  // $row 배열로 DB 정보를 불러온다.
    if($count<1){  
      print "검색결과가 없습니다.<br>";
     }else{
   
            include 'steelrowDB.php';            
					
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
 
// 발주서 분할하는 로직 불러오기 공통사용
include 'load_steel.php'; 
// echo nl2br($memo);   // textarea 개행문자로 배열저장하는 방법

$memoArr=explode( chr(13),$memo );  //  ascii 13 코드로 구분됨을 확인했다.

// 년도 월 출력
$title_date = substr($doneday,0,4) . "년" . substr($doneday,5,2) . "월";

$donedaystr =  substr($doneday,5,2) . "월 " . substr($doneday,8,2) . "일"  ;


?>

<!DOCTYPE HTML>
<html lang="ko">
<head>   
<meta charset="utf-8">  
<title>거래내역서 출력</title>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>  
<script src="../request/js/jspdf.min.js"></script>    <!-- pdf저장을 위한 자바스크립트 함수 불러오기 -->  
<script src="../common.js"></script> 

<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script> 
<link  rel="stylesheet" type="text/css" href="../css/common.css">
<link  rel="stylesheet" type="text/css" media="print" href="../css/print2.css">
<link  rel="stylesheet" type="text/css" href="./css/steelbill.css">    

</head>
  
<body>
<form  id="board_form" name="board_form" method="post" > 
	<input type=hidden id="imageURL" name="imageURL" >
	<br>
	&nbsp; &nbsp; <button type="button" id="closeBtn"  class="btn btn-outline-dark"> 창닫기 </button>	
	&nbsp; &nbsp; <button type="button" id="saveBtn"  class="btn btn-secondary"> PDF파일 저장 </button>	
</form>
<div id="print">  
<div id="outlineprint">  
	<div class="img">      
	
	<div class="clear"> </div>
    <div id="row1">   <?=$title_date?> </div>    
	<div class="clear"> </div>
    <div id="row2">   <?=$workplacename?> </div>
    <div class="clear"> </div>			
    <div id="row3">  <?=$totalamount_val?>  </div>   
    <div class="clear"> </div>		  
    <div id="row4">  </div>   
    <div class="clear"> </div>		        
	<?php
		
			for($i=0 ; $i < 14 ; $i++) 	 // 5개의 데이터 기준으로 찍고 하단에 totalamount 기록하기 위함
			{ 
			  if($item[$i]!=null) 
			  {				  
		  
				   $sumstr = $item[$i] . ' ' . $spec[$i] ;
				   print '<div id="row5"> ';
						print '<div id="col1"> '. $donedaystr . '</div>';			
						print '<div id="col2"> '. $sumstr . '</div>';			
						print '<div id="col3"> '.  $steelnum[$i]  . '</div>';			
						print '<div id="col4"> '. number_format($unitprice[$i]) . '</div>';	
						print '<div id="col5"> '. number_format($amount[$i]) . '</div>';	
						print '<div id="col6"> '. number_format($tax[$i]) . '</div>';	
						print '<div id="col7"> '.  number_format( (float)$amount[$i] + (float)$tax[$i] ) . '</div>';	
						if( strlen($comment[$i])>12)
						    print '<div id="col8" style="font-size:14px;"> '.  $comment[$i] . '</div>';	
						  else
							  print '<div id="col8"> '.  $comment[$i] . '</div>';	
				   print '</div> ' ;
			  }
			  else
			    {
				   print '<div id="row5"> ';
						print '<div id="col1"> &nbsp; </div>';				
				   print '</div> ' ;					
					
				}
			   
			}
	  ?>				
       
    <div class="clear"> </div>			
    <div id="row6">   </div>              
    <div class="clear"> </div>			
	
    <div id="row7">  
	   <div id="col1">  <?=number_format($amount_val)?>  </div>   
	   <div id="col2">  <?=number_format($tax_val)?>  </div>   
	   <div id="col3">  <?=number_format($totalamount_val)?>  </div>   
	</div>
	
    <div class="clear"> </div>		  				 		
	
	</div>  <!-- end of div img-->	
</div>    <!-- end of outlineprint --> 	
</div>    <!-- end of print --> 

<canvas id="canvas" width="1200" height="1750"style="border:1px solid #d3d3d3;display:none"> </canvas>	
</body>

<script>

function partShot() {
  
var d = new Date();
var currentDate = ( d.getMonth() + 1 ) + "-" + d.getDate()  + "_" ;
var currentTime = d.getHours()  + "_" + d.getMinutes() +"_" + d.getSeconds() ;
var result = 'estimate' + currentDate + currentTime + '.jpg';		
	
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
    url: 'imgupload.php',
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

// 숫자를 한글로 바꿔서 찍어주기 ...원정
var txt_id = '<?echo $totalamount_val?>';
console.log(txt_id);
$("#row3").text(num2han(txt_id)+'원정');

		
$("#closeBtn").click(function(){ 

		$.ajax({
			url: "delalljpg.php",
			type: "post",		
			data: $("#board_form").serialize(),			
			success : function( data ){
				console.log( data);
				window.close();					
			},
			  error : function( jqxhr , status , error ){
				   console.log( jqxhr , status , error );
			} 			      		
		   });	
	 
	});			
	
	$("#saveBtn").click(function(){  	 
        // $("#board_form").submit();
		popupCenter('pdf1.php?imageURL=' + $('#imageURL').val(), 'PDF파일보기/저장', 1200,800) ;		
	});			

	//윈도우 창을 닫을때 jpg 파일 삭제함  - 이것때문에 계속 오류가 발생한 것임... 창을 닫는 다는 것이 새로운 창을 띄운것과 같이 되므로...
	$(window).bind("beforeunload", function (e){	
		$.ajax({
			url: "delalljpg.php",
			type: "post",		
			data: $("#board_form").serialize(),			
			success : function( data ){
				console.log( data);
				window.close();					
			},
			  error : function( jqxhr , status , error ){
				   console.log( jqxhr , status , error );
			} 			      		
		   });
	});	
	
});


setTimeout(function() {
 partShot();
}, 500);



function num2han(num) {
  num = parseInt((num + '').replace(/[^0-9]/g, ''), 10) + '';  // 숫자/문자/돈 을 숫자만 있는 문자열로 변환
  if(num == '0')
    return '영';
  var number = ['영', '일', '이', '삼', '사', '오', '육', '칠', '팔', '구'];
  var unit = ['', '만', '억', '조'];
  var smallUnit = ['천', '백', '십', ''];
  var result = [];  //변환된 값을 저장할 배열
  var unitCnt = Math.ceil(num.length / 4);  //단위 갯수. 숫자 10000은 일단위와 만단위 2개이다.
  num = num.padStart(unitCnt * 4, '0')  //4자리 값이 되도록 0을 채운다
  var regexp = /[\w\W]{4}/g;  //4자리 단위로 숫자 분리
  var array = num.match(regexp);
  //낮은 자릿수에서 높은 자릿수 순으로 값을 만든다(그래야 자릿수 계산이 편하다)
  for(var i = array.length - 1, unitCnt = 0; i >= 0; i--, unitCnt++) {
    var hanValue = _makeHan(array[i]);  //한글로 변환된 숫자
    if(hanValue == '')  //값이 없을땐 해당 단위의 값이 모두 0이란 뜻. 
      continue;
    result.unshift(hanValue + unit[unitCnt]);  //unshift는 항상 배열의 앞에 넣는다.
  }
  //여기로 들어오는 값은 무조건 네자리이다. 1234 -> 일천이백삼십사
  function _makeHan(text) {
    var str = '';
    for(var i = 0; i < text.length; i++) {
      var num = text[i];
      if(num == '0')  //0은 읽지 않는다
        continue;
      str += number[num] + smallUnit[i];
    }
    return str;
  }
  return result.join('');
}

</script>	

</html>
