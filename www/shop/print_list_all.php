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

 $ksql = "select * from chandj.output " . $common; 	


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
   				<div class="print1_new"> <?=$Transtodate?> 화물 출고 리스트 </div>
				  				<div class="clear"> </div>	

<?php
   
	 try{  
	  $stmh = $pdo->query($ksql);         // 전체 리스트
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


				<div class="print1_1"> 금일 </div>		
				<div class="print2"> <?=$receiver?> </div>				
				<div class="print3"> <?=substr($comment,0,90)?> </div>				
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


