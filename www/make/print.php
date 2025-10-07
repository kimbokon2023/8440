<!doctype html>
<?php
 session_start(); 
$img_name="http://8440.co.kr/img/screenorder.jpg";
  
$num=$_REQUEST["num"];
$upnum=$_REQUEST["upnum"];
 $sort=$_REQUEST["sort"];    //정렬 1번 내림차순, 2번 오름차순
 $find=$_REQUEST["find"];      // 검색항목
 $page=$_REQUEST["page"];   //페이지번호
 $process=$_REQUEST["process"];   // 진행현황
 $asprocess=$_REQUEST["asprocess"];   // as진행현황
 $m2=$_REQUEST["m2"];   // m2 면적

 require_once("../lib/mydb.php");
 $pdo = db_connect(); 
 try{
     $sql = "select * from mirae8440.orderlist where num=?";
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
	   		 $sql="select * from mirae8440.make  where upnum='$upnum' order by num asc";	 // 처음 오름차순   
     else
	 $sql="select * from mirae8440.make  where upnum='$upnum' order by num desc";	 // 처음 내림차순 

  
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
		 	  $drawbottom3=[];
  
   if($sort=='1')
	   		 $sql="select * from mirae8440.make  where upnum='$upnum' order by num asc";	 // 처음 오름차순   
     else
	 $sql="select * from mirae8440.make  where upnum='$upnum' order by num desc";	 // 처음 내림차순 
  
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
			 $drawbottom3[$counter]=$row["drawbottom3"];   
  			$start_num--;
			$counter++;
			 }
	   }			 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
  
 $recnum=8;  //레코드수가 8개 넘으면 다음페이지로 출력
 $limit=ceil($total_count/$recnum);
 // print "<script> alert($limit) </script>"; 
?>


<html lang="ko">
  <head>
   
    <meta charset="utf-8">

 <link  rel="stylesheet" type="text/css" href="../css/common.css">
 <link  rel="stylesheet" type="text/css" media="print" href="../css/print2.css">
     <link rel="stylesheet" type="text/css" href="../css/screenlist.css">   
      <!--  <link rel="stylesheet" type="text/css" href="../css/orderprint.css">  발주서 인쇄에 맞게 수정하기 위한 css -->
    <title>발주서 출력</title>
  </head>
  
  
<body>
<?php
  for($j=0;$j<$limit;$j++) 
     {
  $pagenum=$j+1;  // 페이지번호 산출
?>
<div id="print">  
<div id="outlineprint">  
    <div class="img">      
    <div id="row1">  페이지번호 :   &nbsp; <?=$pagenum?> / &nbsp; <?=$limit?> </div>  <!-- end of row1 -->
	<div class="clear"> </div>
    <div id="row2">  
        <div id="col1">  <?=$outdate?>

	    </div>  <!-- end of row2 col1-->
        <div id="col2">   <?=$indate?> &nbsp;&nbsp; (면적 : <?=$m2?>㎡)

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
	   <div id="ares3"> 스크린제작 치수 너비(W) x 높이(H) , 수량(EA) </div>
       <div class="clear"> </div>
	
 	<?php
	  $repeat=$total_count-$j*$recnum;
	  if($repeat>=8)
		   $repeat=8;	   
	  for($i=$j*$recnum;$i<=$j*$recnum+$repeat-1;$i++)
	     {
	?>
	   <div id="res1">  <?=$i+1?>  </div>
	   <div id="res2">  <?=$text1[$i]?> </div>
	   <div id="firstoutline">
	   <div id="fres1"> <?=$text2[$i]?> </div>
		   <div id="fres2"> <?=$memo[$i]?> </div> 
	   </div>	
	   <div id="outline">
	   <div id="res4"> <?=$draw[$i]?> </div>
	          <div class="clear"> </div>
		   <div id="res5"> <?=$drawbottom1[$i]?> </div>
           <div id="res7"> <?=$drawbottom3[$i]?> </div>		   		   
	       <div id="res6"> <?=$drawbottom2[$i]?> </div>		   
	   </div>
       <div class="clear"> </div>
	    	<?php
	             }
 	?>    
		   
         </div>   <!-- end of display_result -->
	   </div> <!-- end of containers -->  
	   	   
    </div>
 </div>    <!-- end of outline --> 
</div>    <!-- end of print --> 
	<?php

	  }
	?>
</body>

</html>


