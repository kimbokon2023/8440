 <?php
	  
  require_once("../lib/mydb.php");
  $pdo = db_connect();	
   
 // 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	 

if($fromdate=="")
{
	$fromdate=substr(date("Y-m-d",time()),0,4) ;
	$fromdate=$fromdate . "-01-01";
}
if($todate=="")
{
	$todate=substr(date("Y-m-d",time()),0,4) . "-12-31" ;
	$Transtodate=strtotime($todate.'+1 days');
	$Transtodate=date("Y-m-d",$Transtodate);
}
    else
	{
	$Transtodate=strtotime($todate);
	$Transtodate=date("Y-m-d",$Transtodate);
	}

$common="   where  outdate between date('$fromdate') and date('$Transtodate') order by outdate ";
$find1="경동";
$find2="대신";
$con1 ="   where  (delivery like '%$find1%') and (outdate between date('$fromdate') and date('$Transtodate')) order by outdate ";
$con2 ="   where  (delivery like '%$find2%') and (outdate between date('$fromdate') and date('$Transtodate')) order by outdate ";
$a= $con1 . "desc ";  //경동화물/화물
$b= $con2 . "desc ";   //대신화물/화물

 $ksql = "select * from mirae8440.output " . $a; 	//경동				
 $dsql = "select * from mirae8440.output " . $b;  //대신			

$nowday=date("Y-m-d");   // 현재일자 변수지정   
   ?>
   <html lang="ko">
  <head>   
    <meta charset="utf-8">
 <link  rel="stylesheet" type="text/css" href="../css/common.css">
 <link  rel="stylesheet" type="text/css" href="../css/output.css">	
 <link  rel="stylesheet" type="text/css" media="print" href="../css/print.css">
 
    <title>화물 당일 출고 리스트</title>
  </head>
<body onload="pagePrintPreview();" >

<div id="print">  
    <div class="img">      
		<div class="menu">
   				<div class="print1"> <?=$Transtodate?> </div>
				  				<div class="clear"> </div>	

<?php
   
	 try{  
	  $stmh = $pdo->query($ksql);         // 경동화물 가는 것들
      $temp=$stmh->rowCount();  
	      
	  $total_row = $temp;     // 전체 글수	  		
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			  $num=$row["num"];
			  $outdate=$row["outdate"];
			  $indate=$row["indate"];
			  $orderman=$row["orderman"];
			  $outworkplace=$row["outworkplace"];
			  $outputplace=$row["outputplace"];
			  $receiver=$row["receiver"];
			  $phone=$row["phone"];
			  $comment=$row["comment"];	  
			  $root=$row["root"];	  
			  $steel=$row["steel"];	  
			  $motor=$row["motor"];	  
			  $delivery=$row["delivery"];	  
			 
			?>


				<div class="print1_1"> 경동 </div>		
				<div class="print2"> <?=$receiver?> </div>				
				<div class="print3"> <?=substr($comment,0,105)?> </div>				
				<div class="print4"> <?=substr($outputplace,0,90)?> </div>				
				<div class="print5"> <?=substr($phone,0,13)?> </div>				
				<div class="print6"> <?=$delivery?> </div>				
	
				<div class="clear"> </div>	
 <?php
	  }  // end of while
  }  // end of try
    catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
  }
 ?>  
 				<div class="print1_1"> </div>	<!--중간에 공백한칸 만들기 -->
				<div class="print2">  </div>				
				<div class="print3">  </div>				
				<div class="print4">  </div>				
				<div class="print5"> </div>				
				<div class="print6">  </div>				
	
				<div class="clear"> </div>	

 				<div class="print1_1"> </div>	<!--중간에 공백한칸 만들기 -->
				<div class="print2">  </div>				
				<div class="print3">  </div>				
				<div class="print4">  </div>				
				<div class="print5"> </div>				
				<div class="print6">  </div>				
	
				<div class="clear"> </div>					

 <?php
 /////////////////////////////////////////////   대신화물 처리부분   
	 try{  
	  $stmh = $pdo->query($dsql);         
      $temp=$stmh->rowCount();  
	      
	  $total_row = $temp;     		
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			  $num=$row["num"];
			  $outdate=$row["outdate"];
			  $indate=$row["indate"];
			  $orderman=$row["orderman"];
			  $outworkplace=$row["outworkplace"];
			  $outputplace=$row["outputplace"];
			  $receiver=$row["receiver"];
			  $phone=$row["phone"];
			  $comment=$row["comment"];	  
			  $root=$row["root"];	  
			  $steel=$row["steel"];	  
			  $motor=$row["motor"];	  
			  $delivery=$row["delivery"];	  
								  
		 
			?>


				<div class="print1_1"> 대신 </div>	
				<div class="print2"> <?=$receiver?> </div>				
				<div class="print3"> <?=substr($comment,0,105)?> </div>				
				<div class="print4"> <?=substr($outputplace,0,90)?> </div>				
				<div class="print5"> <?=substr($phone,0,13)?> </div>				
				<div class="print6"> <?=$delivery?> </div>				
	
				<div class="clear"> </div>	
 <?php
	  }  // end of while
  }  // end of try
    catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
  }
 ?>  
 
 
 
 
 
 
 
                     </div>  <!-- end of menu -->
    </div>
</div>
</body>


</html>


