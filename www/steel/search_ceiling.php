 <?php
  if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
	 $search=$_REQUEST["search"];
	  
  if(isset($_REQUEST["readonly"]))   //목록표에 제목,이름 등 나오는 부분
	 $readonly=$_REQUEST["readonly"];
	  
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


					  $sql ="select * from mirae8440.ceiling where (num like '%$search%' ) or (workplacename like '%$search%' ) or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
					  $sql .="or (delicompany like '%$search%' ) or (hpi like '%$search%' ) or (firstord like '%$search%' ) or (secondord like '%$search%' ) or (worker like '%$search%' ) or (memo like '%$search%' ) " . $a;
					  
                      $sqlcon ="select * from mirae8440.ceiling where (num like '%$search%' ) or (workplacename like '%$search%' )  or (firstordman like '%$search%' )  or (secondordman like '%$search%' )  or (chargedman like '%$search%' ) ";
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
	
	 ▷ 총 <?= $total_row ?> 개 &nbsp; &nbsp;검색어 : <?= $search ?> 
			
   <?php
    if($total_row==0)
	{     
   		    print "&nbsp;  <button type='button' id='search_directinput' class='button button2' > 직접입력 </button>"; 	 
           	}
     ?>		
		<div class="table table-responsive align-items-center">
		  <table class="table table-hover table-border">
			<thead class="table-primary align-items-center">
			  <tr class="align-items-center">
				<th style="vertical-align: middle;" class="text-center"> 번호 </th>
				<th style="vertical-align: middle;" class="text-center"> 현장명 </th>
				<th style="vertical-align: middle;" class="text-center"> 발주처 </th>
				<th style="vertical-align: middle;" class="text-center"> 재질 </th>
			  </tr>
			</thead>
			<tbody>   	
			<?php  
		  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		  else 
			$start_num=$total_row-($page-1) * $scale;
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			  $numCeiling=$row["num"];
			  
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
			  
    $materials = "";
    $materials1 = $material1 . " " . $material2;
    $materials2 = $material3 . " " . $material4;
    $materials3 = $material5 . " " . $material6;	

$materials	= trim($materials1) . trim($materials2) . trim($materials3) ; 				  
			  
	
			 ?>		
			<tr onclick="intoval('<?=$workplacename?>','<?=$worker?>','<?=$numCeiling?>'); return false;">
			  <td ><?= $start_num ?></td>
			  <td ><?=substr($workplacename,0,80)?> </td>
			  <td ><?=substr($secondord,0,35)?> </td>
			  <td ><?=$materials?> </td>
			</tr>		
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
 
       </tbody>
	   </table>	   
   </div>
<div class="row row-cols-auto mt-3 mb-5 justify-content-center align-items-center"> 
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
<script>
function check_level()
	  {
		window.open("check_level.php?nick="+document.member_form.nick.value,"NICKcheck", "left=200,top=200,width=300,height=100, scrollbars=no, resizable=yes");
	  }
function intoval(name,worker,numCeiling)
	  {
		  
		  var readonly = '<?php echo $readonly; ?>';
		  
		  if(readonly !=='readonly')		  	  
			   popupCenter('record.php?num=' + numCeiling , '레이져 완료 기록', 500, 450);
		   
				$("#displaysearch").hide();
				document.getElementById("outworkplace").value = name;
				$("input[name=model]").val('천장');
				$("#ceilingcode").val(numCeiling);
				$("#displaySelect").val(numCeiling);
				// document.getElementById("comment").value = worker + ' 소장,';

			//	$("#searchtel").trigger("click");    // 제이쿼리 클릭 이벤트 발생   전화번호 클릭한 효과발생하기
		  
	  }

    $("#search_directinput").on("click", function() {
    $("#displaysearch").hide();
    });	

</script>
  