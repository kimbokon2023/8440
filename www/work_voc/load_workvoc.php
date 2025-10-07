
<div class="container">		

<?php         
 	 
// 접수중인 거 나타내기
$sqltemp="select * from mirae8440.voc where content<>''  and is_html='1' order by num desc ";
		
// 전체 레코드수를 파악한다.
 try{  	  
	  $stmhtmp = $pdo->query($sqltemp);            // 검색조건에 맞는글 stmh
	  $voc=$stmhtmp->rowCount();

if((int)$voc>0) {         					 	
								
?>  	 
<style>
	table.table-bordered.table-hover tbody tr:hover {
		background-color: lightgray;
	}
</style>
<div class="d-flex justify-content-center "> 	
<div class="card mt-3" style="width:70%;">    
<div class="card-header mt-1 mb-1 p-2  justify-content-center text-center">    
	<span class="fs-6 text-center text-danger fw-bold" > 미래소장 VOC 등록 (긴급 처리요함) </span>
</div>
 <div class="card-body">   
<div class="d-flex justify-content-center "> 
<table class="table table-bordered table-hover "> 
<thead class="table-primary" >
	<tr>
	  <th class="col  text-center " > 현장명  </th>
	  <th class="col  text-center " > 내용 </th>
	  <th class="col  text-center " > 미래소장 </th>
	  <th class="col  text-center " > 등록일 </th>
	  <th class="col  text-center " > 처리상황 </th>
	</tr>
</thead>
<tbody>		   

<?php  	 
	 while($row = $stmhtmp->fetch(PDO::FETCH_ASSOC)) {
		  $item_num=$row["num"];
		  $item_id=$row["id"];
		  $item_name=$row["name"];
		  $is_html=$row["is_html"];
		  $item_date=$row["regist_day"];
		  $item_date=substr($item_date, 0, 10);
		  $item_subject=iconv_substr($row["subject"],0,60,"utf-8");
		  $item_content=iconv_substr($row["content"],0,60,"utf-8");
 ?>
 
<tr onclick="toView('<?=$item_num?>')" >
   <td class=" p-2" > <?= $item_subject ?>  
			<?php		
				 if($num_ripple)
				 print "[<font color=red><b>$num_ripple</b></font>]";
			  ?>
					 </td>
   <td class="p-2" > 	<?= $item_content?> </td>
   <td class="text-center p-2" > 	<?= $item_name ?>  </td>
   <td class="text-center p-2" > 	<?= $item_date ?>  </td>
   <td class="text-center p-2" >  	
   <?php 
	  if($is_html=='1')
		    print '<span class="blinking" style="color:red" >' .  '접수 중' . '</span>' ; 
		  else
	        print "확인완료";	
	?>
	</td>  
</tr>     
	
<?php
	$start_numtmp--;
    }
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
  </div>
  </div>
  
  <script>	  
	function toView(num) {
		  popupCenter('../work_voc/view.php?num=' + num , '소장 VOC', 1400, 850); 		
	  }
  </script>