 <?php session_start(); ?>
  
 <meta charset="utf-8">
 <script src="http://5130.co.kr/make/make.js"></script>
 <?php
 
 $num=$_REQUEST["num"];  // 번호만 불러오자
 $sort=$_REQUEST["sort"];  //
 $recallcal=$_REQUEST["recallcal"];  //
 $parentnum=$_REQUEST["parentnum"]; 	     // 리스트번호

 require_once("../lib/mydb.php"); 
  $pdo = db_connect();
  $sql="select * from chandj.make  where num='$num'";	 // 처음 오름차순

	 try{  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $row = $stmh->fetch(PDO::FETCH_ASSOC);
              $num=$row["num"];
              $upnum=$row["upnum"];
			  $text1=$row["text1"];
			  $text2=$row["text2"];
			  $text3=$row["text3"];
			  $text4=$row["text4"];
			  $text5=$row["text5"];
			 $ordercompany=$row["ordercompany"];  
			 $callname=$row["callname"];  
			 $cutwidth=$row["cutwidth"];  
			 $cutheight=$row["cutheight"];  
			 $number=$row["number"];  
			 $printside=$row["printside"];  
			 $direction=$row["direction"];  
			 $exititem=$row["exititem"];  
			 $intervalnum=$row["intervalnum"];  
			 $intervalnumsecond=$row["intervalnumsecond"];  
			 $memo=$row["memo"];  
			 $draw=$row["draw"];  
			 $drawbottom1=$row["drawbottom1"];  
			 $drawbottom2=$row["drawbottom2"];  
			 $drawbottom3=$row["drawbottom3"];  
			 $cover=$row["cover"];  
			 $exitinterval=$row["exitinterval"];  			 
			 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   
	 
   	   header("Location:http://5130.co.kr/make/write.php?num=$num&callname=$callname&cutwidth=$cutwidth&cutheight=$cutheight&ordercompany=$ordercompany&outworkplace=$ordercompany&number=$number&printside=$printside&direction=$direction&exititem=$exititem&intervalnum=$intervalnum&intervalnumsecond=$intervalnumsecond&memo=$memo&draw=$draw&drawbottom1=$drawbottom1&drawbottom2=$drawbottom2&drawbottom3=$drawbottom3&text2=$text2&sort=$sort&upnum=$upnum&recallcal=$recallcal&exitinterval=$exitinterval&cover=$cover&parentnum=$parentnum");    // 신규가입일때는 리스트로 이동
 ?>
