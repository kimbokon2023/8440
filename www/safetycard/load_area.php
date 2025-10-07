 
 <?php
 
 	
ini_set('display_errors','0');  // 화면에 warning 없애기	
  
  $page=1;	 
  
  $scale = 20;       // 한 페이지에 보여질 게시글 수
  $page_scale = 20;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.
	 
  $now = date("Y-m-d",time()) ;
  
  $a="   where done is null order by num desc ";  	
 
  $sql="select * from mirae8440.myarealist " . $a; 					
	   
 try{  

	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();    
      $total_row=$temp1;			 
	  
	  
			?>		
	 

    <div class="card-body p-0 mb-0 mt-4 d-flex justify-content-center">
		  <h4 class=text-secondary>
			사무실 청소 미점검 List
		  </h4>
     </div>	
	 
	
 <div class="p-0 d-flex justify-content-center">
	     <span class="col-sm-1 text-center  " style="  background-color : #e8efd5; font-weight:bold;  border:1px solid #ccc; " >번호</span>
         <span class="col-sm-2 text-center  " style="  background-color : #e8efd5; font-weight:bold; border:1px solid #ccc; " >점검예정일</span>		 
         <span class="col-sm-2 text-center  " style="  background-color : #e8efd5; font-weight:bold;  border:1px solid #ccc; " >청소구역</span>         
         <span class="col-sm-1 text-center  " style="  background-color : #e8efd5; font-weight:bold;  border:1px solid #ccc; " >점검구분</span>
         <span class="col-sm-1 text-center  " style="  background-color : #e8efd5; font-weight:bold;  border:1px solid #ccc; " >담당(정)</span>
         <span class="col-sm-1 text-center  " style="  background-color : #e8efd5; font-weight:bold;  border:1px solid #ccc; " >담당(부)</span>         
     </div>			    	      
			<?php  
		  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		  else 
			$start_num=$total_row-($page-1) * $scale;
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {			   
	       include "rowDB.php";	   							   
				switch ($item){
				  case 'section1' :   
				  case 'section2' :   
				  case 'section3' :   
				  case 'section4' :   
				  case 'section5' :   
				  case 'section6' :   
				  case 'section7' :   
				    $itemstr = '내책상';
					break;
				  case 'comsection1' :   
				    $itemstr = '사무실공용통로';
					break;
				  case 'comsection2' :   
				    $itemstr = '사무실 가구 및 집기류';
					break;
				  case 'comsection3' :   
				    $itemstr = '사장실내부';
					break;
				}
					
		   
		   
		   
		   
		   
		   
		   ?>
		   
		<a href="../qcoffice/view.php?num=<?=$num?>" style="text-decoration:none;" onclick="popupCenter(this.href, '사무실 청소 점검', 1200, 700);return false;">			 
		 <div class="p-0 mb-0 d-flex justify-content-center">	 			     			       
	     <span class="col-sm-1 text-center  " style="   border:1px solid #ccc;  " ><?=$start_num?></span>
		 <span class="col-sm-2 text-center  " style="   border:1px solid #ccc;  " ><?=$checkdate?></span>
         <span class="col-sm-2 text-center  " style="   border:1px solid #ccc;  " ><?=$itemstr ?></span>         
         <span class="col-sm-1 text-center  " style="   border:1px solid #ccc;  " ><?=$term?></span>
         <span class="col-sm-1 text-center  " style="   border:1px solid #ccc;  " ><?=$writer ?></span>
         <span class="col-sm-1 text-center  " style="   border:1px solid #ccc;  " ><?=$writer2 ?></span>  
		  </div> 
			  </a>  
			<?php
			$start_num--;
			 } 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
   // 페이지 구분 블럭의 첫 페이지 수 계산 ($start_page)
      $start_page = ($current_page - 1) * $page_scale + 1;
   // 페이지 구분 블럭의 마지막 페이지 수 계산 ($end_page)
      $end_page = $start_page + $page_scale - 1;  
 ?>
  


  