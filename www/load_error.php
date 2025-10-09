<?php
require_once __DIR__ . '/bootstrap.php';
	 
$now = date("Y-m-d",time()) ;

$a= " where approve<>'처리완료' order by num desc ";  

$sql="select * from mirae8440.error " . $a; 		

// print $sql;   
	   
 try{  

	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();    
      $total_row=$temp1;			 
?>			 
			
<div class="card rounded-card mt-2 mb-1">
<div class="card-header  text-center ">   
  <h6>  <span class="text-center" > 부적합(불량) 결재 요청  </span> </h6>
</div>
<div class="card-body p-1 justify-content-center">	

  <div class="d-flex justify-content-center">
    <table class="table table-bordered table-hover">
    <thead class="table-danger">
      <tr>          
        <th class="text-center" scope="col" style="width:8%;" >보고자</th>
        <th class="text-center" scope="col" style="width:8%;" >유형</th>
        <th class="text-center" scope="col" style="width:10%;" >발생일</th>
        <th class="text-center" scope="col" style="width:22%;"  >현장명</th>
        <th class="text-center" scope="col" style="width:22%;">원인</th>
        <th class="text-center" scope="col" style="width:22%;">개선대책</th>
        <th class="text-center" scope="col" style="width:10%;" >진행</th>
      </tr>
    </thead>
    <tbody>		 		 
	
		<?php  
			  if ($page<=1)  
				$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
			  else 
				$start_num=$total_row-($page-1) * $scale;
	    
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	
	       include "./error/_row.php";	   		   
	    ?>
				   
		<tr onclick="popupCenter('./error/write_form.php?num=<?=$num?>&option=approval', '부적합 보고서 조회/결재', 1000, 960)" style="cursor:pointer;">
			<td class="text-center"><?=$reporter?></td>
			<td class="text-center"><?=iconv_substr($errortype,0,7,"utf-8")?></td>
			<td class="text-center"><?=$occur?></td>
			<td class="text-start"><?=$place?></td>
			<td class="text-start"><?=$content?></td>
			<td class="text-start"><?=$method?></td>
			<td class="text-center"><?=$approve?></td>
		</tr>

			<?php
			
			if($approve=='1차결재')
				array_push($approvalarr,'소현철');
			if($part=='지원파트' && $approve=='결재상신')
				array_push($approvalarr,'최장중');
			if($part=='제조파트' && $approve=='결재상신')
				array_push($approvalarr,'이경묵');
			
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
</div>
  