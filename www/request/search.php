
 <!DOCTYPE HTML>
 <html>
 
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
 
  $scale = 1000;       // 한 페이지에 보여질 게시글 수
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

 <form name="board_form" method="post" action="search.php?mode=search&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>">  	

 <div class="container">
 <div class="d-flex">  
        <div id="list_search1" style="width:500px;">▷ <?= $total_row ?> 자료. &nbsp; &nbsp;검색어 : <?= $search ?> 
		<?php
    if($total_row==0)
	{     
   		   print "<button type='button' id='search_directinput' class='btn btn-dark btn-sm' > 직접입력 </button>"; 	 
           	}
     ?>
		
		</div>	 
</div> <!-- end of list_search3 -->
  
      <div class="table-respsonsive">
	   <table class="table table-bordered table-hover">
	     <thead class="table-primary">
            <th class="text-center" style="width:50px;" > 번호</th>
            <th class="text-center" style="width:200px;" >현장명</th>  
            <th class="text-center" style="width:100px;" >발주처</th>
            <th class="text-center" style="width:100px;" >재질</th>
          </thead>
       <tbody>

			<?php  
		  if ($page<=1)  
			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
		  else 
			$start_num=$total_row-($page-1) * $scale;
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
			  	  
				  include '../work/_row.php';
				  
    $materials = "";
    $materials1 = $material1 . " " . $material2;
    $materials2 = $material3 . " " . $material4;
    $materials3 = $material5 . " " . $material6;	

$materials	= trim($materials1) . trim($materials2) . trim($materials3) ; 
			  
			 ?>
			 
  	      <tr onclick="intoval('<?=$workplacename?>','<?=$worker?>'); return false;">
            <td class="text-center"><?= $start_num ?></td>
            <td class="text-start"><?=substr($workplacename,0,80)?> </td>  
            <td class="text-center"><?=substr($secondord,0,35)?> </td>
            <td class="text-center"><?=$materials?> </td>			
          </tr>				 
		  			 
			<?php
			$start_num--;
			 } 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
   
 ?>
	 
	  </tbody>
	  </table>
  
     </div>
    </div>
	</form>	
   
<script>

function intoval(name,worker)
	  {
		$("#displaysearch").hide();
			document.getElementById("outworkplace").value = name;
			document.getElementById("model").value = '쟘';
			document.getElementById("comment").value = worker + ' 소장,';			
	  }

    $("#search_directinput").on("click", function() {
    $("#displaysearch").hide();
    });	

</script>
  </body>
  </html>