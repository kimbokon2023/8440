<!doctype html>

<?php
 session_start(); 
$img_name="http://5130.co.kr/img/screenorder.jpg";
  
$num=$_REQUEST["num"];
$upnum=$_REQUEST["upnum"];
 $sort=$_REQUEST["sort"];    //정렬 1번 내림차순, 2번 오름차순
 $receiver=$_REQUEST["receiver"];  //수신처
 $find=$_REQUEST["find"];      // 검색항목
 $page=$_REQUEST["page"];   //페이지번호

 require_once("../lib/mydb.php");
 $pdo = db_connect(); 
 try{
     $sql = "select * from chandj.egiorderlist where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC);
 	
			  $outdate=$row["outdate"];
			  $indate=$row["indate"];
			  $orderman=$row["orderman"];
			  $outworkplace=$row["outworkplace"];
			  $outputplace=$row["outputplace"];
			  $receiver=$row["receiver"];
			  $phone=$row["phone"];
			  $comment=$row["comment"];	 
	
  } catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
  }


if($outdate!="") {
    $week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
    $outdate = $outdate . $week[ date('w',  strtotime($outdate)  ) ] ;
}

$pagenum=1;
// 발주서 레코드 수량 파악해서 8개 이상이면 2페이지 이상 출력토록 만드는 서브루틴
     
   if($sort=='1')
	   		 $sql="select * from chandj.egimake  where upnum='$upnum' order by num asc";	 // 처음 오름차순   
     else
	 $sql="select * from chandj.egimake  where upnum='$upnum' order by num desc";	 // 처음 내림차순 
  
	 try{  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
	  $total_count=$stmh->rowCount();
//    print "<script> alert($total_count) </script>"; 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
  // 배열선언
              $text1=[];
			  $text2=[];
			  $memo=[];
			  $draw=[];  
		 	  $drawbottom1=[]; 
		 	  $drawbottom2=[];
  

  
	 try{  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $counter=0;
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) { 
			  $upnum=$row["upnum"];
if((int)$upnum==(int)$num)
      {	
			  $text1[$counter]=$row["text1"];
			  $text2[$counter]=$row["text2"];

			 $memo[$counter]=$row["memo"];  
			 $draw[$counter]=$row["draw"];  
			 $drawbottom1[$counter]=$row["drawbottom1"];  
			 $drawbottom2[$counter]=$row["drawbottom2"];   
  			$start_num--;
			$counter++;
			 }
	   }			 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
  
 $recnum=14;  //레코드수가 8개 넘으면 다음페이지로 출력
 $limit=ceil($total_count/$recnum);
 // print "<script> alert($limit) </script>"; 
?>


<html lang="ko">
  <head>
   
    <meta charset="utf-8">

 <link  rel="stylesheet" type="text/css" href="../css/common.css">
 <link  rel="stylesheet" type="text/css" media="print" href="../css/print2.css">
     <link rel="stylesheet" type="text/css" href="../css/screenlist.css">   
     <link rel="stylesheet" type="text/css" href="../css/egilist.css">   
      <!--  <link rel="stylesheet" type="text/css" href="../css/orderprint.css">  발주서 인쇄에 맞게 수정하기 위한 css -->
    <title>발주서 출력</title>
  </head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>  
<script src="../js/html2canvas.js"></script>    <!-- 스크린샷을 위한 자바스크립트 함수 불러오기 -->  
<script>
 
function partShot(number) {
        var d = new Date();
        var currentDate = ( d.getMonth() + 1 ) + "-" + d.getDate()  + "_" ;
        var currentTime = d.getHours()  + "_" + d.getMinutes() +"_" + d.getSeconds() ;
        var result = 'egi_cut' + currentDate + currentTime + '__' + number +'.jpg';		
	
//특정부분 스크린샷
html2canvas(document.getElementById("outlineprint"+number))
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

<?php
  for($j=0;$j<$limit;$j++) 
     {
  $pagenum=$j+1;  // 페이지번호 산출
?>
<div id="print">  
<div id="outlineprint<?=$pagenum?>">  
    <div class="img">      
    <div id="row1">  페이지번호 :   &nbsp; <?=$pagenum?> / &nbsp; <?=$limit?> </div>  <!-- end of row1 -->
	<div class="clear"> </div>
    <div id="row2">  
        <div id="col1">  <?=$outdate?>

	    </div>  <!-- end of row2 col1-->
        <div id="col2">   <?=$indate?>

	    </div>  <!-- end of row2 col2-->		
	</div>  <!-- end of row2 -->
		<div class="clear"> </div> 
    <div id="row3">  
        <div id="col1">   <?=$receiver?>

	    </div>  <!-- end of row3 col1-->
        <div id="col2">  <?=$outworkplace?>

	    </div>  <!-- end of row3 col2-->		
	</div>  <!-- end of row3 --> 
		<div class="clear"> </div> 
    <div id="row4">  
        <div id="col1">   <?=$phone?>

	    </div>  <!-- end of row4 col1-->
        <div id="col2">   <?=$outputplace?>

	    </div>  <!-- end of row4 col2-->		
	</div>  <!-- end of row4 --> 	
   <div id="row5">  	" <?=$comment?> "
		</div>  <!-- end of row5 --> 

<div id="containers" >	
	<div id="display_result" >	
	   <div id="ares1"> 번호 </div>
	   <div id="ares2"> 부호 </div>
	   <div id="ares3">  철재스라트 재질,  제작치수 폭(W) x 수량(매수) , 힌지사용여부  </div>
       <div class="clear"> </div>
	
 	<?php
	  $repeat=$total_count-$j*$recnum;
	  if($repeat>=$recnum)
		   $repeat=$recnum;	   
	  for($i=$j*$recnum;$i<=$j*$recnum+$repeat-1;$i++)
	     {
	?>
	   <div id="res1">  <?=$i+1?>  </div>
	   <div id="res2">  <?=$text1[$i]?> </div>
	   <div id="fres1"> <?=$text2[$i]?> </div>
       <div class="clear"> </div>
	    	<?php
	             }
 	?>    
		   
         </div>   <!-- end of display_result -->
	   </div> <!-- end of containers -->  
	   	   
    </div>
 </div>    <!-- end of outlineprint --> 
</div>    <!-- end of print -->   
	<?php
		print "<script> partShot($pagenum); </script>";  
	   // print "<script> cleardiv(); </script>"; 
		
		  }

?>
	<canvas id="canvas" width="1150" height="1600"style="border:1px solid #d3d3d3; display:none;"></canvas>	
 
  
</body>

</html>
	<?php

	// print "<script> self.close(); </script>";  // 화면 닫기
	
	?>

