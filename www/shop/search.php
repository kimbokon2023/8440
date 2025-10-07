
 <?php
  if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	 $search=$_REQUEST["search"];
	  
  require_once("../lib/mydb.php");
  $pdo = db_connect();	
 // $find="firstord";	    //검색할때 고정시킬 부분 저장 ex) 전체/공사담당/건설사 등
 if(isset($_REQUEST["page"])) // $_REQUEST["page"]값이 없을 때에는 1로 지정 
 {
    $page=$_REQUEST["page"];  // 페이지 번호
 }
  else
  {
    $page=1;	 
  }
 
  $scale = 100;       // 한 페이지에 보여질 게시글 수
  $page_scale = 10;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     
 
$a="  order by num desc limit $first_num, $scale";  
$b="  order by num desc";


					  $sql ="select * from mirae8440.work where (workplacename like '%$search%' ) or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
					  $sql .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' ) " . $a;
					  
                      $sqlcon ="select * from mirae8440.work where (workplacename like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
					  $sqlcon .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' ) " . $b;
   
	 try{  
	  $allstmh = $pdo->query($sqlcon);         // 검색 조건에 맞는 쿼리 전체 개수
      $temp2=$allstmh->rowCount();  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();
	      
      $total_row = $temp2;     // 전체 글수	 
        					 
  	  $total_page = ceil($total_row / $scale); // 검색 전체 페이지 블록 수
	  $current_page = ceil($page/$page_scale); //현재 페이지 블록 위치계산			 
   //   print "$page&nbsp;$total_page&nbsp;$current_page&nbsp;$search&nbsp;$mode";			 
	?>
	
<div class="input-group p-2 mb-1">	
        <span class="input-group-text"> ▷ 총 <?= $total_row ?> 개의 자료 파일이 있습니다. &nbsp; &nbsp;검색어 : <?= $search ?>  </span>
			
   <?php
    if($total_row==0)
	{     
   		    print "&nbsp;  <button type='button' id='search_directinput' class='button button2' > 직접입력 </button>"; 	 
           	}
     ?>		
    </div>
     <div class="input-group p-1 mb-1">	 
         <span class="input-group-text align-items-center" style="width:50px;" >번호</span>
         <span class="input-group-text align-text-center" style="width:350px;" >현  장  명</span>     
         <span class="input-group-text text-center" style="width:250px;">발 주 처</span>
         <span class="input-group-text text-center" style="width:150px;">발주처담당</span>
         <span class="input-group-text text-center" style="width:150px;">미래설치소장</span>               
     </div>		 
      	 		 
			<?php  
		  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		  else 
			$start_num=$total_row-($page-1) * $scale;
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			  $num=$row["num"];
			  
	          $checkstep=$row["checkstep"];
			  $workplacename=$row["workplacename"];
			  $address=$row["address"];
			  $firstord=$row["firstord"];
			  $firstordman=$row["firstordman"];
			  $firstordmantel=$row["firstordmantel"];
			  $secondord=$row["secondord"];
			  $secondordman=$row["secondordman"];
			  $secondordmantel=$row["secondordmantel"];
			  $chargedman=$row["chargedman"];
			  $orderday=$row["orderday"];
			  $measureday=$row["measureday"];
			  $drawday=$row["drawday"];
			  $deadline=$row["deadline"];
			  $delicompany=$row["delicompany"];
			  
			  $workday=$row["workday"];
			  $startday=$row["startday"];
			  $testday=$row["testday"];
			  $worker=$row["worker"];
			  $endworkday=$row["endworkday"];
			  $material1=$row["material1"];
			  $material2=$row["material2"];
			  $material3=$row["material3"];
			  $material4=$row["material4"];
			  $material5=$row["material5"];
			  $material6=$row["material6"];
			  $widejamb=$row["widejamb"];
			  $normaljamb=$row["normaljamb"];
			  $smalljamb=$row["smalljamb"];
			  $memo=$row["memo"];
			  $regist_day=$row["regist_day"];
			  $update_day=$row["update_day"];
			  $demand=$row["demand"];
	
			 ?>		
             <div class="input-group p-1 mb-0" >	 			 
			 <span class="input-group-text align-items-center" style="width:50px;" ><?= $start_num ?></span>
			 <span class="input-group-text align-text-center" style="width:350px;" > <a href="#" onclick="javascript:intoval('<?=$workplacename?>','<?=$worker?>','<?=$secondord?>'); return false;" style="font-size=10px;" ><?= $workplacename ?>&nbsp;</a> </span>     
			 <span class="input-group-text text-center" style="width:250px;"><?=substr($secondord,0,30)?>&nbsp;	</span>
			 <span class="input-group-text text-center" style="width:150px;"><?=substr($secondordman,0,30)?>&nbsp; </span>
			 <span class="input-group-text text-center" style="width:150px;"><?=substr($worker,0,25)?>&nbsp;</span>      

             </div>			
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
 
      <div id="page_button">
	<div id="page_num">  
 <?php
      if($page!=1 && $page>$page_scale)
      {
        $prev_page = $page - $page_scale;    
        // 이전 페이지값은 해당 페이지 수에서 리스트에 표시될 페이지수 만큼 감소
        if($prev_page <= 0) 
            $prev_page = 1;  // 만약 감소한 값이 0보다 작거나 같으면 1로 고정
        print "<a href=search.php?page=$prev_page&search=$search&find=$find&list=1&process=$process&yearcheckbox=$yearcheckbox&year=$year>◀ </a>";
      }
    for($i=$start_page; $i<=$end_page && $i<= $total_page; $i++) 
      {        // [1][2][3] 페이지 번호 목록 출력
        if($page==$i) // 현재 위치한 페이지는 링크 출력을 하지 않도록 설정.
           print "<font color=red><b>[$i]</b></font>"; 
        else 
           print "<a href=search.php?page=$i&search=$search&find=$find&list=1&process=$process&yearcheckbox=$yearcheckbox&year=$year>[$i]</a>";
  }

      if($page<$total_page)
      {
        $next_page = $page + $page_scale;
        if($next_page > $total_page) 
            $next_page = $total_page;
        // netx_page 값이 전체 페이지수 보다 크면 맨 뒤 페이지로 이동시킴
        print "<a href=search.php?page=$next_page&search=$search&find=$find&list=1&process=$process&yearcheckbox=$yearcheckbox&year=$year> ▶</a><p>";
      }
 ?>			
        </div>
     </div>

     </div>

    </div> <!-- end of col2 -->
	</form>	
   </div> <!-- end of content -->
  </div> <!-- end of wrap -->
<script>
  	  function check_level()
			  {
				window.open("check_level.php?nick="+document.member_form.nick.value,"NICKcheck", "left=200,top=200,width=300,height=100, scrollbars=no, resizable=yes");
			  }
function intoval(name, worker, secondord)
	  {
		  	$("#displaysearch").hide();
			document.getElementById("outworkplace").value = name;
			document.getElementById("model").value = '쟘';
			document.getElementById("comment").value = '(' + secondord + ') ' + worker + ' 소장,';
			console.log(worker);
			//	$("#searchtel").trigger("click");    // 제이쿼리 클릭 이벤트 발생   전화번호 클릭한 효과발생하기
	  }

    $("#search_directinput").on("click", function() {
    $("#displaysearch").hide();
    });	

</script>
  </body>
  </html>