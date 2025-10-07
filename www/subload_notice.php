
<style>
.blink_me {
  animation: blinker 3.5s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}

a { text-decoration:none !important }
a:hover { text-decoration:none !important }

</style>

 <?php
  require_once("../lib/mydb.php");
  $pdo = db_connect();	
	 
  $now = date("Y-m-d",time()) ;
  
		$a="   where noticecheck='y' order by num desc ";  
  
	   $sql="select * from mirae8440.notice " . $a; 					
	   
	 try{  

	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();    
      $total_row=$temp1;			 
			?>		
	<br>
	<br>
	 <div class="card-header"> 	
          	  <span style="margin-left:10px;color:red;"> 공지사항  </span> &nbsp;&nbsp;&nbsp;
     
    	      
			<?php  
			$color_arr = array();
			array_push($color_arr,"blue");
			array_push($color_arr,"brown");
			array_push($color_arr,"purple");
			array_push($color_arr,"black");
	        $counter = 0;
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			   
			  $num=$row["num"];

			  $subject=$row["subject"];
			  $content=$row["content"];
			 ?>
			     <a href="../notice/view.php?num=<?=$num?>&page=1" style="font-size:18px;color:<?=$color_arr[$counter]?>;font-weight:bold;">
			 	 
					<span class="blink_me" style="width:350px;" > <?=$subject?> </span>		
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;					
				
				  </a>
			<?php
            $counter++;
			 } 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
?>
     </div>

    </div> <!-- end of col2 -->
	</form>	   


  