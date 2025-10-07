 
 <?php
	  
  require_once("../lib/mydb.php");
  $pdo = db_connect();	
  $con_num = $_POST["num"];

	 try{  
	 $sql = "select * from mirae8440.output where con_num =" . $con_num;
	 
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp=$stmh->rowCount();     
		 
?>
      <div class="aa">
		<div class="aa1"> 공사번호 </div>
		<div class="aa2"> 출고일자 </div>     <!-- 출고일자 -->
		<div class="aa3"> 접 수 일  </div>     <!-- 접수일 -->
		<div class="aa4"> 내역/코멘트  </div>     <!-- 접수일 -->

			</div>
					<br><br>
			<?php  
   
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			  $item_num=$row["num"];
			  $con_num=$row["con_num"];
			  $outdate=$row["outdate"];
			  $item_indate=$row["indate"];
			  $item_orderman=$row["orderman"];
			  $item_outworkplace=$row["outworkplace"];
			  $item_outputplace=$row["outputplace"];
			  $item_receiver=$row["receiver"];
			  $item_phone=$row["phone"];
			  $item_comment=$row["comment"];	  

			 if($outdate!="") {
				$week = array("(일)" , "(월)"  , "(화)" , "(수)" , "(목)" , "(금)" ,"(토)") ;
				$outdate = $outdate . $week[ date('w',  strtotime($outdate)  ) ] ;
			}  
			  
			 ?> 
				<div id="bb" > <a href="outputview.php?num=<?=$item_num?>" target="_blank" >
			    <div id="bb1">
				<b><?=$con_num ?></b> </div>			
			    <div id="bb2" style="color:<?=$font?>;"> <b> <?=substr($outdate,0,15)?></b> </div>
				<div id="bb3"> <b> <?=substr($item_indate,0,10)?></b></div>
				<div id="bb4"> <b> <?=substr($item_comment,0,100)?> </b> </div>				 </a> 
               <br><br>
				</div>
			<?php
			 } 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
 ?>
